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
namespace Magenest\ShopByBrand\Controller\Adminhtml\Group;

use Magenest\ShopByBrand\Controller\Adminhtml\Group;
use Magento\Framework\Exception\LocalizedException;
use Magenest\ShopByBrand\Model\Config\Router;

/**
 * Class Save
 *
 * @package Magenest\ShopByBrand\Controller\Adminhtml\Group
 */
class Save extends Group
{

    /**
     * @return \Magento\Framework\Controller\Result\Redirect|\Magento\Framework\View\Result\Page
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $error=false;

        /*
            * @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect
        */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            /*
            * @var \Magenest\ShopByBrand\Model\Group $model
            */
            $model = $this->_groupBrandFactory->create();
            if (isset($data['group_id'])) {
                $item = $model->getCollection()->addUrlFilter($data['group_id'], $data['url_key'])
                    ->getData();
            } else {
                $item = $model->getCollection()->addUrlFilter(null, $data['url_key'])
                    ->getData();
            }
            if ($item) {
                $this->messageManager->addErrorMessage("Duple url_key ");
                return $resultRedirect->setPath('*/*/index');
            }

            if (!empty($data['group_id'])) {
                $model->load($data['group_id']);
                if ($data['group_id'] != $model->getId()) {
                    throw new LocalizedException(__('Wrong label rule.'));
                }
            }
            //            if($data['status']==2&&isset($data['group_id'])){
            //                $brands = $this->brandFactory->create()->getCollection()->addFieldToFilter('groups',$data["group_id"]);
            //                if($brands->getSize()>0){
            //                    foreach ($brands as $brand){
            //                        $this->messageManager->addError("Can't change status this group,please check all brands in this group");
            //                        $error = true;
            //
            //                    }
            //                    return $resultRedirect->setPath('*/*/edit', ['group_id' => $model->getId(), '_current' => true]);
            //                }
            //            }
            if ($error==true) {
                return $resultRedirect->setPath('*/*/edit', ['group_id' => $model->getId(), '_current' => true]);
            }

            $model->setData($data);
            $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($model->getData());


            try {
                $model->save();

                $this->addUrlRewrite($data, $model->getId());
                $this->messageManager->addSuccess(__('Group has been saved.'));
                /*Refresh cache*/
                $types = array('layout', 'block_html', 'collections', 'reflection');
                foreach ($types as $type) {
                    $this->_cacheTypeList->cleanType($type);
                }
                foreach ($this->_cacheFrontendPool as $cacheFrontend) {
                    $cacheFrontend->getBackend()->clean();
                }
                /*end delete cache*/
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['group_id' => $model->getId(), '_current' => true]);
                }

                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError($e, __('Something went wrong while saving the brand.'));
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($data);
                return $resultRedirect->setPath('*/*/edit', ['group_id' => $this->getRequest()->getParam('group_id')]);
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

        $allStore = $this->_storeManage->getStores($withDefault = false);

        foreach ($allStore as $store) {
            $allStoreId[] = $store->getStoreId();
        }

        return $allStoreId;
    }

    /**
     * @param $data
     * @param $id
     */
    public function addUrlRewrite($data, $id)
    {
        $allStoreId = $this->getAllStoreId();
        
        $model  = $this->_urlRewrite->create();
        $router = Router::ROUTER_GROUP;
        if (!empty($data['group_id'])) {
            $collection = $model->getCollection()
                ->addFieldToFilter('entity_type', 'group')
                ->addFieldToFilter('entity_id', $id);
            foreach ($collection as $model) {
                $model->delete();
            }
            $page = [];
            $page['url_rewrite_id'] = null;
            $page['entity_type']    = 'group';
            $page['entity_id']      = $id;
            $page['request_path']   = $router.'/'.$data['url_key'];
            $page['target_path']    = 'shopbybrand/group/view/group_id/'.$id;

            $model  = $this->_urlRewrite->create();
            
            foreach ($allStoreId as $id) {
                    $page['store_id']       = $id;
                    $model->setData($page);
                    $model->save();
            }
        } else {
            $page = [];
            $page['url_rewrite_id'] = null;
            $page['entity_type']    = 'group';
            $page['entity_id']      = $id;
            $page['request_path']   = $router.'/'.$data['url_key'];
            $page['target_path']    = 'shopbybrand/group/view/group_id/'.$id;

            foreach ($allStoreId as $id) {
                $page['store_id']       = $id;
                $model->setData($page);
                $model->save();
            }
        }
    }
}
