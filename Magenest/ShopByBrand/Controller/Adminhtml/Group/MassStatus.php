<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Magenest_Blog extension
 * NOTICE OF LICENSE
 *
 * @category Magenest
 * @package  Magenest_Blog
 * @author   <CanhND>-duccanhdhbkhn@gmail.com
 */
namespace Magenest\ShopByBrand\Controller\Adminhtml\Group;

use Magenest\ShopByBrand\Controller\Adminhtml\Brand;
use Magenest\ShopByBrand\Controller\Adminhtml\Group;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class MassStatus
 *
 * @package Magenest\ShopByBrand\Controller\Adminhtml\Brand
 */
class MassStatus extends Group
{
    /**
     * execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $brandCollection = $this->_groupBrandFactory->create()->getCollection();
        $collections     = $this->_filter->getCollection($brandCollection);
        $status          = (int) $this->getRequest()->getParam('status');
        $totals          = 0;
        $resultRedirect = $this->resultRedirectFactory->create();
        try {
            foreach ($collections as $item) {
                //                $error=false;
                //                /*
                //                    * @var \Magenest\ShopByBrand\Model\Brand $item
                //                 */
                //                $brands = $this->brandFactory->create()->getCollection()->addFieldToFilter('groups',$item->getId());
                //                if($brands->getSize()>0){
                //                    foreach ($brands as $brand){
                //                        $this->messageManager->addError("Can't change status of group ".$item->getName().",please check all brands in this group");
                //                        $error = true;
                //                    }
                //                }
                //                if($error==true)continue;

                $item->setStatus($status)->save();
                /*Refresh cache*/
                $types = array('layout','block_html','collections','reflection');
                foreach ($types as $type) {
                    $this->_cacheTypeList->cleanType($type);
                }
                foreach ($this->_cacheFrontendPool as $cacheFrontend) {
                    $cacheFrontend->getBackend()->clean();
                }
                $totals++;
                $this->messageManager->addSuccess(__('A total of %1 record(s) have been updated.', $totals));
            }
            //            if($error==true) {
            //                return $resultRedirect->setPath('*/*/');
            //            }
        } catch (LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
        return $resultRedirect->setPath('*/*');
    }
}
