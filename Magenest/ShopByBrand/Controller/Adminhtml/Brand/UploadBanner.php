<?php
/**
 * Copyright © 2018 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magenest\ShopByBrand\Controller\Adminhtml\Brand;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Backend\App\Action;

class UploadBanner extends \Magento\Backend\App\Action
{
    protected $_fileUploaderFactory;
    protected $resultJsonFactory;
    protected $_filesystem;

    public function __construct(
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\Filesystem $filesystem,
        Action\Context $context
    )
    {
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_filesystem = $filesystem;
        parent::__construct($context);
    }

    public function execute()
    {
        if ($this->getRequest()->getParam('id')) {
            $id = $this->getRequest()->getParam('id');
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $brand = $objectManager->create('Magenest\ShopByBrand\Model\Brand')->load($id);
            $brand->setBanner('');
            $brand->save();
            return;
        }
    }
}