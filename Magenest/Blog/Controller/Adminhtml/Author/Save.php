<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magenest\Blog\Controller\Adminhtml\Author;

use Magento\Backend\App\Action;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\MediaStorage\Model\File\Uploader;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;

/**
 * Class Save
 * @package Magenest\Blog\Controller\Adminhtml\Author
 */
class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \Magenest\Blog\Model\AuthorFactory
     */
    protected $author;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;

    /**
     * @var Filesystem
     */
    protected $_filesystem;

    /**
     * @var UploaderFactory
     */
    protected $_fileUploaderFactory;

    /**
     * Save constructor.
     * @param Action\Context $context
     * @param \Magenest\Blog\Model\AuthorFactory $authorFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param Filesystem $filesystem
     * @param UploaderFactory $fileUploaderFactory
     */
    public function __construct(
        Action\Context $context,
        \Magenest\Blog\Model\AuthorFactory $authorFactory,
        \Psr\Log\LoggerInterface $logger,
        Filesystem $filesystem,
        UploaderFactory $fileUploaderFactory
    ) {
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->_filesystem = $filesystem;
        $this->_logger = $logger;
        $this->author = $authorFactory;
        parent::__construct($context);
    }

    /**
     * save action
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            //save data icon
            if (!isset($data['image'])) {
                $data['image'] = [];
            }
            /** @var  \Magento\Framework\App\RequestInterface $file */
            $file = $this->_request->getFiles('image', true);
            $image = $this->saveImage($file, $data['image'], $name = 'image');
            if (is_array($data['image']) && !empty($data['image'])) {
                $avatar['value'] =  $data['image']['value'];
            }
            if ($image == 'deleted' || $image == '') {
                $avatar['value'] = null;
            } else {
                $avatar['value'] = $image;
            }

            $array = [
                'display_name' => $data['display_name'],
                'information' => $data['information'],
                'image' => $avatar['value'],
            ];
            $model = $this->_objectManager->create('Magenest\Blog\Model\Author');
            if (isset($data['author_id']) && !empty($data['author_id'])) {
                $model->load($data['author_id']);
            }

            $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData();

            try {
                $model->addData($array);
                $model->save();
                $this->messageManager->addSuccessMessage(__('Author template has been saved.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e, __('Something went wrong while saving the author.'));
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($data);
                return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
            }
        }
        return $resultRedirect->setPath('*/*/');
    }


    /**
     * @param $value
     * @param $data
     * @return string
     */
    public function saveImage($value, $data, $name)
    {
        if (!empty($value['name']) || !empty($data)) {
            /** Deleted file */
            if (!empty($data['delete']) && !empty($data['value'])) {
                $path = $this->_filesystem->getDirectoryRead(
                    DirectoryList::MEDIA
                );
                if ($path->isFile($data['value'])) {
                    $this->_filesystem->getDirectoryWrite(
                        DirectoryList::MEDIA
                    )->delete($data['value']);
                }
                if (empty($value['name'])) {
                    return 'deleted';
                }
            }
            if (empty($value['name']) && !empty($data)) {
                return $data['value'];
            }
            $path = $this->_filesystem->getDirectoryRead(
                DirectoryList::MEDIA
            )->getAbsolutePath(
                'blog/avatar/'
            );
            try {
                /** @var $uploader \Magento\MediaStorage\Model\File\Uploader */
                $uploader = $this->_fileUploaderFactory->create(['fileId' => $name]);
                $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                $uploader->setAllowRenameFiles(false);
                $result = $uploader->save($path);
                if (is_array($result) && !empty($result['name'])) {
                    return 'blog/avatar/'.$result['name'];
                }
            } catch (\Exception $e) {
                if ($e->getCode() != Uploader::TMP_NAME_EMPTY) {
                    $this->_logger->critical($e);
                }
                $this->_logger->critical($e);
            }
        }

        return '';
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {

        return $this->_authorization->isAllowed('Magenest_Blog::blog_author');
    }
}
