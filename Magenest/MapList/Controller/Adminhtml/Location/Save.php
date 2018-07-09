<?php
/**
 * Created by PhpStorm.
 * User: heomep
 * Date: 17/09/2016
 * Time: 14:46
 */

namespace Magenest\MapList\Controller\Adminhtml\Location;

use Magenest\MapList\Controller\Adminhtml\Location;
use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\MediaStorage\Model\File\Uploader;
use Psr\Log\LoggerInterface;

class Save extends Location
{
    /**
     * @var \Magenest\MapList\Model\LocationFactory
     */
    protected $_locationFactory;
    protected $_fileUploaderFactory;
    protected $_adapterFactory;
    protected $_filesystem;
    protected $locationCategoryFactory;
    protected $locationProductFactory;
    protected $locationGalleryCollection;
    protected $imageUploader;

    /**
     * @param Action\Context $context
     */

    public function __construct(
        Action\Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        \Magenest\MapList\Model\LocationFactory $locationFactory,
        \Magento\Catalog\Model\ImageUploader $imageUploader,
        \Magenest\MapList\Model\LocationCategoryFactory $locationCategoryFactory,
        LoggerInterface $logger,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Magento\Framework\Image\AdapterFactory $adapterFactory,
        \Magenest\MapList\Model\LocationProductFactory $locationProductFactory,
        \Magenest\MapList\Model\ResourceModel\LocationGallery\CollectionFactory $locationCollectionFactory)
    {
        $this->_adapterFactory = $adapterFactory;
        $this->_filesystem = $filesystem;
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->_locationFactory = $locationFactory;
        $this->locationCategoryFactory = $locationCategoryFactory;
        $this->locationProductFactory = $locationProductFactory;
        $this->locationGalleryCollection = $locationCollectionFactory;
        $this->imageUploader = $imageUploader;
        parent::__construct($context, $coreRegistry, $resultPageFactory, $locationFactory, $logger);
    }
    /**
     * Dispatch request
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */


    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $params = $this->getRequest()->getParams();
//        $this->logger->addDebug(print_r($_POST, true));
        $productList = json_decode($params['product_list']);
        $model = $this->_locationFactory->create();
        $resultRedirect = $this->resultRedirectFactory->create();
        $collectionGallery = $this->locationGalleryCollection->create();

        if (isset($params['location_id'])) {
            $model->load($params['location_id']);
        }

        try {
            $i = 0;
            $dataGallery = [];
            foreach ($collectionGallery as $locationGallery) {
                if ($locationGallery != null) {
                    if($locationGallery->getData('type_image')==2) {
                        $dataGallery[$i] = $locationGallery->getData('name_image');
                        $locationGallery->delete();
                        $i++;
                    }

                    if($locationGallery->getData('type_image')==1) {
                        $dataIcon= $locationGallery->getData('name_image');
                        $locationGallery->delete();
                    }
                }
            }
            if (isset($dataGallery[0])) {
                foreach($dataGallery as $data) {
                    try {
                        $this->imageUploader->moveFileFromTmp($data);
                    } catch (\Exception $e) {
                        $this->messageManager->addError($e->getMessage());
                    };
                }
                $gallerys = implode(' ; ', $dataGallery);
                if ($model->getGallery() != null) {
                    $preImage = $model->getData('gallery');
                    $curImage = $preImage . ' ; ' . $gallerys;
                    $model->setData('gallery', $curImage);
                } else {
                    $model->setData('gallery', $gallerys);
                }
            }

            if (isset($dataIcon)) {
                $icon = $dataIcon;
                    $model->setData('small_image', $icon);
                try {
                    $this->imageUploader->moveFileFromTmp($icon);
                } catch (\Exception $e) {
                    $this->messageManager->addError($e->getMessage());
                };
            }
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }

        try {
            $model->addData($params)->save();

            try {
                $this->saveCategoryLocation(
                    $model->getId(),
                    isset($params['location_categories']) ? $params['location_categories'] : []
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }

            try {
                $this->saveProductLocation($model->getId(), $productList);
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }

            $this->messageManager->addSuccess(__('Location successfully saved.'));
            $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData(false);
            if ($this->getRequest()->getParam('back')) {
                return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
            }

            return $resultRedirect->setPath('*/*/');
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addError($e, __('Something went wrong while saving the methods.'));
            $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
            $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($params);

            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }

        return $resultRedirect->setPath('*/*/');
    }

    private function saveCategoryLocation($locationId, $newCategoryArrayData)
    {
        $oldCategoryData = $this->locationCategoryFactory->create()->getCollection()
            ->addFieldToFilter('location_id', $locationId)
            ->getData();
        $oldCategoryArrayData = [];
        foreach ($oldCategoryData as $category) {
            $oldCategoryArrayData[] = $category['category_id'];
        }
        $listCategoryIdWillAdd = array_diff($newCategoryArrayData, $oldCategoryArrayData);
        $listCategoryIdWillRemove = array_diff($oldCategoryArrayData, $newCategoryArrayData);

        foreach ($listCategoryIdWillAdd as $categoryId) {
            $locationCategoryModel = $this->locationCategoryFactory->create();
            try {
                $locationCategoryModel->addData([
                    'location_id' => $locationId,
                    'category_id' => $categoryId
                ])->save();
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        try {
            //location_category_id
            $locationCategoryIdListToDelete = $this->locationCategoryFactory->create()->getCollection()
                ->addFieldToFilter('location_id', $locationId)
                ->addFieldToFilter('category_id', $listCategoryIdWillRemove)
                ->getData();
        } catch (\Exception $e) {
            $locationCategoryIdListToDelete = [];
        }
        foreach ($locationCategoryIdListToDelete as $categoryInfo) {
            try {
                $this->locationCategoryFactory->create()
                    ->load($categoryInfo['location_category_id'])
                    ->delete();
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
    }

    private function saveProductLocation($locationId, $productList)
    {
        $currentLocationProductData = $this->locationProductFactory->create()->getCollection()
            ->addFieldToFilter('location_id', $locationId)->getData();
        //format arr location product Id => product Id
        $paserdCurrentProductData = [];
        try {
            foreach ($currentLocationProductData as $locationProductData) {
                $paserdCurrentProductData[] = $locationProductData['product_id'];
            }
        } catch (\Exception $e) {
        }
        //var_dump($paserdCurrentProductData);
        $removeListId = array_diff($paserdCurrentProductData, $productList);
        $addListId = array_diff($productList, $paserdCurrentProductData);

        foreach ($addListId as $product) {
            if (!!$product) {
                try {
                    $this->locationProductFactory->create()
                        ->addData(['location_id' => $locationId, 'product_id' => $product])
                        ->save();
                } catch (\Exception $e) {
                }
            }
        }

        try {
            $locationProductWillRemove = $this->locationProductFactory->create()
                ->getCollection()
                ->addFieldToFilter('product_id', $removeListId)
                ->getData();
            if (!!$locationProductWillRemove) {
                foreach ($locationProductWillRemove as $value) {
                    try {
                        $this->locationProductFactory->create()
                            ->load($value['location_product_id'])
                            ->delete();
                    } catch (\Exception $e) {
                    }
                }
            }
        } catch (\Exception $e) {
        }
    }
}
