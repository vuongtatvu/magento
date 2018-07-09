<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Magenest_ShopByBrand extension
 * NOTICE OF LICENSE
 *
 * @category Magenest
 * @package  Magenest_ShopByBrand
 * @author   CanhND <duccanhdhbkhn@gmail.com>
 */
namespace Magenest\ShopByBrand\Controller\Adminhtml\Brand;

use Magenest\ShopByBrand\Controller\Adminhtml\Brand;
use Magento\Framework\Exception\LocalizedException;
use Magenest\ShopByBrand\Model\Config\Router;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class Save
 *
 * @package Magenest\ShopByBrand\Controller\Adminhtml\Brand
 */
class Save extends Brand
{

    /**
     * @return \Magento\Framework\Controller\Result\Redirect|\Magento\Framework\View\Result\Page
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $data         = $this->getRequest()->getPostValue();
        $selectedProduct=json_decode($data['brand_products']);
        $data['summary']=count(json_decode(json_encode($selectedProduct), true));
        $data['name'] = $data['brand_name'];
        $data['url_key'] = $data['url_key'].".html";
        $logo         = $this->getRequest()->getFiles('logo');
        $banner       = $this->getRequest()->getFiles('banner');

        /*
            * @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect
        */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            /*
                * @var \Magenest\ShopByBrand\Model\Brand $model
            */
            $model = $this->_brandFactory->create();
            //get all brand name
            $listBrand = $this->_brandFactory->create()->getCollection();
            $nameData = strtolower($data['brand_name']);
            foreach ($listBrand as $brand) {
                $namedb=strtolower($brand->getName());
                $brandNames[$namedb] =$brand->getId();
            }
            //Checking disable status
            if ($data['status']==\Magenest\ShopByBrand\Model\Brand::STATUS_DISABLE) {
                $data['featured']=\Magenest\ShopByBrand\Model\Brand::FEATURE_DISABLE;
            }
            //check duplicate title
            if (isset($data['brand_id'])) {
                $brand = $this->_brandFactory->create()->load($data['brand_id']);
                if (strtolower($brand->getName())!=$nameData) {
                    if (isset($brandNames[$nameData])) {
                        $this->messageManager->addError("Duplicate brand title");
                        return $resultRedirect->setPath('*/*/edit', ['brand_id'=>$data['brand_id']]);
                    }
                }
                $item=$model->getCollection()->addUrlFilter($data['brand_id'], $data['url_key'])
                    ->getData();
            } else {
                if (isset($brandNames[$nameData])) {
                    $this->messageManager->addError('This title is exist');
                    return $resultRedirect->setPath('*/*/new');
                }
                $item=$model->getCollection()->addUrlFilter(null, $data['url_key'])
                    ->getData();
            }
            if ($item) {
                $this->messageManager->addErrorMessage("Duple url_key ");
                return $resultRedirect->setPath('*/*/index');
            }

            if (!empty($data['brand_id'])) {
                $model->load($data['brand_id']);
                if ($data['brand_id'] != $model->getId()) {
                    throw new LocalizedException(__('Wrong label rule.'));
                }
            }

            if (isset($logo) && $logo['name'] != '') {
                try {
                    $uploader        = $this->_objectManager->get('Magenest\ShopByBrand\Model\Theme\Upload');
                    $backgroundModel = $this->_objectManager->get('Magenest\ShopByBrand\Model\Theme\Logo');
                    $data['logo']    = $uploader->uploadFileAndGetName('logo', $backgroundModel->getBaseDir(), $data);
                    if ($data['logo']=='error') {
                        $this->messageManager->addErrorMessage("Your photos couldn't be uploaded. Photos should be saved as JPG, PNG, GIF ");
                    }
                } catch (LocalizedException $e) {
                    throw new LocalizedException(__('Wrong Upload Logo.'));
                }
            } else {
                if (!empty($data['logo']['delete']) && !empty($data['logo']['value'])) {
                    $uploader = $this->_objectManager->get('Magenest\ShopByBrand\Model\Theme\Upload');
                    $uploader->deleteFile($data['logo']['value']);
                    $data['logo'] = "";
                } elseif (isset($data['brand_id'])) {
                    $data['logo'] = $model->getData('logo');
                } else {
                    $data['logo'] = "";
                }
            }

            if (isset($banner) && $banner['name'] != '') {
                try {
                    $uploader        = $this->_objectManager->get('Magenest\ShopByBrand\Model\Theme\Upload');
                    $backgroundModel = $this->_objectManager->get('Magenest\ShopByBrand\Model\Theme\Logo');
                    $data['banner']  = $uploader->uploadFileAndGetName('banner', $backgroundModel->getBaseDir(), $data);
                    if ($data['banner']=='error') {
                        $this->messageManager->addErrorMessage("Your photos couldn't be uploaded. Photos should be saved as JPG, PNG, GIF ");
                    }
                } catch (LocalizedException $e) {
                    throw new LocalizedException(__('Wrong Upload Banner.'));
                }
            } else {
                if (!empty($data['banner']['delete']) && !empty($data['banner']['value'])) {
                    $uploader = $this->_objectManager->get('Magenest\ShopByBrand\Model\Theme\Upload');
                    $uploader->deleteFile($data['banner']['value']);
                    $data['banner'] = "";
                } elseif (isset($data['brand_id'])) {
                    $data['banner'] = $model->getData('banner');
                } else {
                    $data['banner'] = "";
                }
            }

            if (isset($data['brand_products'])
                && is_string($data['brand_products'])
            ) {
                $products = json_decode($data['brand_products'], true);

                $model->setPostedProducts($products);

                $brandCategories = [];

                foreach ($products as $productId => $position) {
                    $product = $this->_productFactory->create()->load($productId);

                    $cats = $product->getCategoryIds();

                    $brandCategories = array_merge($cats, $brandCategories);
                }

                $categoryMerge = array_unique($brandCategories);

                $data['categories'] = json_encode($categoryMerge);
            }
            $listGroup = '';
            if (isset($data['groups'])) {
                for ($i = 0; $i < count($data['groups']); $i++) {
                    if ($i == 0) {
                        $listGroup = $data['groups'][$i];
                    } else {
                        $listGroup = $listGroup . ',' . $data['groups'][$i];
                    }
                }
                $data['groups'] = $listGroup;
            }
                $model->setData($data);
            $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($model->getData());


            try {
                $model->save();

                $this->addUrlRewrite($data, $model->getId());
                $this->messageManager->addSuccess(__('Brand has been saved.'));
                /*Refresh cache*/
                $types = array('layout','block_html');
                foreach ($types as $type) {
                    $this->_cacheTypeList->cleanType($type);
                }
                foreach ($this->_cacheFrontendPool as $cacheFrontend) {
                    $cacheFrontend->getBackend()->clean();
                }
                /*End delete cache*/
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['brand_id' => $model->getId(), '_current' => true]);
                }

                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError($e, __('Something went wrong while saving the brand.'));
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($data);
                return $resultRedirect->setPath('*/*/edit', ['brand_id' => $this->getRequest()->getParam('brand_id')]);
            }
        }
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
            if ($data['store_ids'][0] == 0) {
                $allIds = $this->getAllStoreId();
                
                foreach ($allIds as $id) {
                    $page['store_id']       = $id;
                    $model->setData($page);
                    $model->save();
                }
            } else {
                foreach ($data['store_ids'] as $id) {
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

            if ($data['store_ids'][0] == 0) {
                $allIds = $this->getAllStoreId();

                foreach ($allIds as $id) {
                    $page['store_id']       = $id;
                    $model->setData($page);
                    $model->save();
                }
            } else {
                foreach ($data['store_ids'] as $id) {
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
}
