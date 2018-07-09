<?php

namespace Magenest\MapList\Controller\Adminhtml\Location;

use Magento\Framework\Controller\ResultFactory;

/**
 * Agorae Adminhtml Category Image Upload Controller
 */
class UploadImage extends \Magento\Backend\App\Action
{
    /**
     * Image uploader
     *
     * @var \Magento\Catalog\Model\ImageUploader
     */
    protected $imageUploader;

    /**
     * Uploader factory
     *
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    private $uploaderFactory;

    /**
     * Media directory object (writable).
     *
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    protected $mediaDirectory;

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Core file storage database
     *
     * @var \Magento\MediaStorage\Helper\File\Storage\Database
     */
    protected $coreFileStorageDatabase;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    protected $locationGallery;

    protected $locationGalleryCollection;

    protected $locationCollection;

    /**
     * Upload constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Catalog\Model\ImageUploader $imageUploader
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Catalog\Model\ImageUploader $imageUploader,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\MediaStorage\Helper\File\Storage\Database $coreFileStorageDatabase,
        \Psr\Log\LoggerInterface $logger,
        \Magenest\MapList\Model\LocationGalleryFactory $locationFactory,
        \Magenest\MapList\Model\ResourceModel\LocationGallery\CollectionFactory $locationCollectionFactory,
        \Magenest\MapList\Model\ResourceModel\Location\CollectionFactory $collectionFactory
    )
    {
        parent::__construct($context);
        $this->imageUploader = $imageUploader;
        $this->uploaderFactory = $uploaderFactory;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        $this->storeManager = $storeManager;
        $this->coreFileStorageDatabase = $coreFileStorageDatabase;
        $this->logger = $logger;
        $this->locationGallery = $locationFactory;
        $this->locationGalleryCollection = $locationCollectionFactory;
        $this->locationCollection = $collectionFactory;
    }

    /**
     * Check admin permissions for this controller
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenest_MapList::list_location');
    }

    /**
     * Upload file controller action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $locationGallery = $this->locationGallery->create();
        $imageDelete = $this->getRequest()->getParam('nameDeleteBeforeSave');
        if (!isset($imageDelete)) {
            $x = 1;
            try {
                $result = $this->imageUploader->saveFileToTmpDir('gallery_0[-1]');
                $x = $this->_getSession();
                $result['cookie'] = [
                    'name' => $this->_getSession()->getName(),
                    'value' => $this->_getSession()->getSessionId(),
                    'lifetime' => $this->_getSession()->getCookieLifetime(),
                    'path' => $this->_getSession()->getCookiePath(),
                    'domain' => $this->_getSession()->getCookieDomain(),
                ];
            } catch (\Exception $e) {
                $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
            }

            $nameImage = $result['name'];
            $data = [
                'type_image' => 2,
                'name_image' => $nameImage
            ];
            $locationGallery->addData($data)->save();
            return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
        } else {
            $galleryCollection = $this->locationGalleryCollection->create();
            foreach ($galleryCollection as $gallery) {
                if ($gallery->getNameImage() == $imageDelete) {
                    $gallery->delete();
                };
            }
            $locationCollection = $this->locationCollection->create();
            foreach ($locationCollection as $location){
                $strNameGallery = $location->getGallery();
                $arrNameGallery = explode(' ; ', $strNameGallery);
                foreach($arrNameGallery as $key => $nameImage){
                    if($nameImage == $imageDelete){
                        unset($arrNameGallery[$key]);
                    }
                }
                $gallerys = implode(' ; ', $arrNameGallery);
                $location->setData('gallery',$gallerys)->save();
            }
            return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData([
                'message' => 'done'
            ]);
        }

    }
}