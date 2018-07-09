<?php
namespace Magenest\StockStatus\Helper;
use \Magento\Framework\App\Filesystem\DirectoryList;
class Image extends \Magento\Framework\App\Helper\AbstractHelper{
    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $_uploaderFactory;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $_fileSystem;
    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $_fileUploaderFactory;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var \Magento\Framework\Filesystem\Io\File
     */
    protected $_ioFile;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Magento\Framework\Filesystem\Io\File $ioFile,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ){
        $this->_fileSystem = $filesystem;
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->_ioFile = $ioFile;
        $this->_storeManager = $storeManager;
        parent::__construct($context);
    }
    public function uploadImage($optionId,$file){
        $path = $this->_fileSystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath('magenest/stockstatus');
        $this->_ioFile->checkAndCreateFolder($path);
        try{
            /** @var $uploader \Magento\MediaStorage\Model\File\Uploader */
            $fileId = "magenest_icon[$optionId]";
            $uploader = $this->_fileUploaderFactory->create(['fileId' => $fileId]);
            $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
            $uploader->setAllowRenameFiles(true);
            $result = $uploader->save($path,$file.'jpg');
            return $result['file'];
        }catch (\Exception $e){
            if($e->getCode() != \Magento\MediaStorage\Model\File\Uploader::TMP_NAME_EMPTY){
                $this->_logger->critical($e);
            }
            return $this;
        }
    }
    public function getStatusIconUrl($optionId,$file){
        $path = $this->_fileSystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath('magenest/stockstatus');
        $name = $file;
        if($this->_ioFile->fileExists($path.'/'.$name)){
            $path = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
            return $path.'magenest/stockstatus/'.$name;
        }else{
            return '';
        }
    }
}