<?php
/**
 * Created by PhpStorm.
 * User: duccanh
 * Date: 23/05/2016
 * Time: 01:17
 */
namespace Magenest\ShopByBrand\Model\Theme;

use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Class Upload
 *
 * @package Magenest\ShopByBrand\Model\Theme
 */
class Upload
{
    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $uploaderFactory;

    /**
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory
     * @param \Magento\Framework\Filesystem                    $filesystem
     */
    public function __construct(UploaderFactory $uploaderFactory, Filesystem $filesystem)
    {
        $this->uploaderFactory = $uploaderFactory;
        $this->_filesystem     = $filesystem;
    }

    /**
     * @param $input
     * @param $destinationFolder
     * @param $data
     * @return string
     * @throws \Exception
     */
    public function uploadFileAndGetName($input, $destinationFolder, $data)
    {
        try {
            if (isset($data[$input]['delete'])) {
                return '';
            } else {
                $uploader = $this->uploaderFactory->create(['fileId' => $input]);
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(true);
                $uploader->setAllowCreateFolders(true);

                $result = $uploader->save($destinationFolder);
                return $result['file'];
            }
        } catch (\Exception $e) {
            return 'error';
        }

        return '';
    }

    public function getUploadFile($input)
    {
        return $uploadFile = $this->uploaderFactory->create(['fileId' => $input]);
    }

    /**
     * @param $file
     */
    public function deleteFile($file)
    {
        $path = $this->_filesystem->getDirectoryRead(
            DirectoryList::MEDIA
        );
        if ($path->isFile("shopbybrand/brand/image/".$file)) {
            $this->_filesystem->getDirectoryWrite(
                DirectoryList::MEDIA
            )->delete("shopbybrand/brand/image/".$file);
        }
    }
}
