<?php
/**
 * Created by PhpStorm.
 * User: thien
 * Date: 15/09/2017
 * Time: 09:10
 */

namespace Magenest\ShopByBrand\Controller\Adminhtml\Import;

use Magenest\ShopByBrand\Controller\Adminhtml\Brand;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Class Save
 *
 * @package Magenest\Pin\Controller\Adminhtml\Upload
 */
class Save extends Brand
{
    /**
     * @return mixed
     */
    public function execute()
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('pin/code/index', ['_current' => false]);
        //upload file
        $uploader       = $this->uploaderFactory->create(['fileId' => 'import_file']);
        $uploadDir      = $this->varDirectory->getAbsolutePath('importexport/');
        $result         = $uploader->save($uploadDir);
        $uploadedFile   = $result['path'] . $result['file'];
        if (strpos($result['file'], '.xml') === true) {
            try {
                $xml    = simplexml_load_file($uploadedFile);
                $table  = $xml->Worksheet->Table;
                $head   = $this->getHead($xml->Worksheet->Table);
                $data   = $this->getData($table, $head);
                $i=0;
                foreach ($data as $record) {
                    $status=$this->saveBrand($record);
                    if ($status) {
                        $i++;
                    }
                    $this->messageManager->addSuccess(__('A total of %1 record(s) have been saved', $i));
                }
            } catch (\Exception $exception) {
                $this->messageManager->addError('This file xml is not supported. Please try again.');
                return $resultRedirect->setPath('*/*/index', ['_current' => true]);
            }
        } else {
            try {
                //get csv adapter
                $csvAdapter = \Magento\ImportExport\Model\Import\Adapter::findAdapterFor(
                    $uploadedFile,
                    $this->filesystem->getDirectoryWrite(DirectoryList::ROOT),
                    ','
                );
                $i=0;
                foreach ($csvAdapter as $rowNum => $record) {
                    $status=$this->saveBrand($record);
                    if ($status) {
                        $i++;
                    }
                }
                $this->messageManager->addSuccess(__('A total of %1 record(s) have been saved', $i));
            } catch (\Exception $e) {
                $this->messageManager->addError('This file csv is not supported. Please try again.');
                return $resultRedirect->setPath('*/*/index', ['_current' => true]);
            }
        }
        return $resultRedirect->setPath('*/brand/', ['_current' => true]);
    }

    /**
     * @param $table
     * @param $head
     * @return array
     */
    protected function getData($table, $head)
    {
        $result=array();
        for ($i=0; $i<count($table->Brand); $i++) {
            $data=$table->Brand[$i]->Cell;
            $temp=array();
            for ($index=0; $index<count($data); $index++) {
                $temp[$head[$index]]=(string)$data[$index]->Data;
            }
            array_push($result, $temp);
        }
        return $result;
    }

    /**
     * @param $table
     * @return array
     */
    protected function getHead($table)
    {
        $head=$table->Head->Cell;
        $result=array();
        foreach ($head as $column) {
            $x=(string)$column->Data;
            array_push($result, $x);
        }
        return $result;
    }

    /**
     * @param $data
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function saveBrand($data)
    {
        /*
            * @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect
        */
        $resultRedirect = $this->resultRedirectFactory->create();
        try {
            $dataBrand = [
                'name'          => $data['Brand Name'],
                'slogan'        => $data['Slogan'],
                'sort_order'    => $data['Sort Order'],
                'description'   => $data['Description'],
                'meta_keywords' => $data['Meta Keywords'],
                'meta_description' => $data['Meta Description'],
                'groups'        => $data['Groups'],
                'featured'      => $data['Featured Brand'],
                'status'        => $data['Status'],
                'store_ids'     => explode(",", $data['Store']),
                'logo'          => $data['Logo'],
                'banner'        => $data['Banner']
            ];
            /*Check group or store view*/
            $dataBrand['store_ids']=$this->checkStore($dataBrand['store_ids']);
            if (!count($dataBrand['store_ids'])) {
                $dataBrand['store_ids']=['0'];
                $this->messageManager->addWarning('We not found store view of brand '.$data['Brand Name'].'. So brand will show all store, then you can edit it');
            }

            $listGroup = '';
            $arrayGroup = explode(",", $data['Groups']);
            for ($i = 0; $i < count($arrayGroup); $i++) {
                if ($i == 0) {
                    $listGroup = $data['Groups'][$i];
                } else {
                    $listGroup = $listGroup.','.$arrayGroup[$i];
                }
            }
            $dataBrand['groups'] = $listGroup;
        } catch (\Exception $e) {
            $this->messageManager->addWarning('Some columns information of brand not found');
        }

        /*check field required*/
        try {
            $dataBrand['page_title']=$data['Page Title'];
            $dataBrand['url_key']=$data['Url'];
        } catch (\Exception $e) {
            //            $this->messageManager->addError($e->getMessage());
            $this->messageManager->addError("We not find column Page Title or Url in file data ");
            return false;
        }

        if ($data) {
            /*
                * @var \Magenest\ShopByBrand\Model\Brand $model
            */
            $model = $this->_brandFactory->create();
            $item=$model->getCollection()->addUrlFilter(null, $data['Url'])
                ->getData();
            if ($item) {
                $dataBrand['brand_id']  = $item[0]['brand_id'];
                $data['brand_id']       = $item[0]['brand_id'];
            }

            if (!empty($data['brand_id'])) {
                $model->load($data['brand_id']);
                if ($data['brand_id'] != $model->getId()) {
                    throw new \Magento\Framework\Exception\LocalizedException(__('Wrong label rule.'));
                }
            }
            try {
                if (isset($data['Product']) && $data['Product']!='') {
                    $products       = explode(",", $data['Product']);
                    $productTemp    = array();
                    foreach ($products as $productId) {
                        $productTemp[$productId]='0';
                    }
                    $model->setPostedProducts($products);
                    $brandCategories = [];
                    $brandProducts   = [];
                    $summary         = 0;
                    foreach ($productTemp as $productId => $position) {
                        $product = $this->_productFactory->create()->load($productId);
                        $tempProduct=$this->_productFactory->create()->getCollection()->addFieldToFilter('entity_id', $productId)->getData();
                        if (count($tempProduct)) {
                            $summary++;
                            $brandProducts[$productId]='0';
                            $cats = $product->getCategoryIds();
                            $brandCategories = array_merge($cats, $brandCategories);
                        }
                    }
                    $dataBrand['brand_products'] = json_encode($brandProducts);
                    $dataBrand['summary']        = $summary;
                    $categoryMerge               = array_unique($brandCategories);
                    $dataBrand['categories']     = json_encode($categoryMerge);
                }
            } catch (\Exception $e) {
                $this->messageManager->addWarning('something errors while get product');
            }

            $model->setData($dataBrand);
            $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($model->getData());
            try {
                $model->save();
                $this->addUrlRewrite($dataBrand, $model->getId());

                /*Refresh cache*/
                $types = array('layout','block_html','collections','reflection');
                foreach ($types as $type) {
                    $this->_cacheTypeList->cleanType($type);
                }
                foreach ($this->_cacheFrontendPool as $cacheFrontend) {
                    $cacheFrontend->getBackend()->clean();
                }
                /*End delete cache*/
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData(false);
                return true;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError($e, __('Something went wrong while saving the brand.'));
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($data);
            }
        }
    }
    public function checkStore($array_store)
    {
        $all_store=$this->getAllStoreId();
        $store=[];
        foreach ($array_store as $index => $value) {
            if (in_array($value, $all_store)) {
                $store[]=$value;
            }
        }
        return $store;
    }
    /**
     * @param $data
     * @param $id
     */
    public function addUrlRewrite($data, $id)
    {

        $model  = $this->_urlRewrite->create();
        $rewritePage = $this->_scoreConfig->getValue('shopbybrand/page/url');
        if (!empty($data['brand_id'])) {
            $collection = $model->getCollection()
                ->addFieldToFilter('entity_type', 'brand')
                ->addFieldToFilter('entity_id', $id);
            foreach ($collection as $model) {
                $model->delete();
            }
            $page = [];
            $page['url_rewrite_id'] = null;
            $page['entity_type']    = 'brand';
            $page['entity_id']      = $id;
            $page['request_path']   = $rewritePage.'/'.$data['url_key'];
            $page['target_path']    = 'shopbybrand/brand/view/brand_id/'.$id.'/';
            $model  = $this->_urlRewrite->create();
            $arrayStore=$data['store_ids'];
            if ($arrayStore[0] == 0) {
                $allIds = $this->getAllStoreId();

                foreach ($allIds as $id) {
                    $page['store_id']       = $id;
                    $model->setData($page);
                    $model->save();
                }
            } else {
                foreach ($arrayStore as $id) {
                    $page['store_id']       = $id;
                    $model->setData($page);
                    $model->save();
                }
            }
        } else {
            $page = [];
            $page['url_rewrite_id'] = null;
            $page['entity_type']    = 'brand';
            $page['entity_id']      = $id;
            $page['request_path']   = $rewritePage.'/'.$data['url_key'];
            $page['target_path']    = 'shopbybrand/brand/view/brand_id/'.$id.'/';
            $arrayStore=$data['store_ids'];
            if ($arrayStore[0] == 0) {
                $allIds = $this->getAllStoreId();

                foreach ($allIds as $id) {
                    $page['store_id']       = $id;
                    $model->setData($page);
                    $model->save();
                }
            } else {
                foreach ($arrayStore as $id) {
                    $page['store_id']       = $id;
                    $model->setData($page);
                    $model->save();
                }
            }
        }
    }
    /**
     * Get all store id
     *
     * @return array
     */
    public function getAllStoreId()
    {
        $allStoreId = array();

        $allStore = $this->_storeManagement->getStores($withDefault = false);

        foreach ($allStore as $store) {
            $allStoreId[] = $store->getStoreId();
        }

        return $allStoreId;
    }
    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return true;
    }
}
