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
 * @author   <CanhND>-duccanhdhbkhn@gmail.com
 */
namespace Magenest\ShopByBrand\Controller\Adminhtml\Group;

use Magenest\ShopByBrand\Controller\Adminhtml\Group;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Controller\ResultFactory;
use function print_r;

/**
 * Class MassDelete
 *
 * @package Magenest\ShopByBrand\Controller\Adminhtml\Label
 */
class MassDelete extends Group
{
    /**
     * execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $brandCollection = $this->_objectManager
            ->create('Magenest\ShopByBrand\Model\ResourceModel\Group\Collection');
        $collections     = $this->_filter->getCollection($brandCollection);
        $totals          = 0;
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        try {
            $brands = $this->brandFactory->create()->getCollection();
            foreach ($collections as $item) {
                $error=false;
                /*
                    * @var \Magenest\ShopbyBrand\Model\Brand $item
                 */
                $this->deleteUrlRewrite($item->getId());
                //check do this group have anybrand
                if ($brands->getSize()>0) {
                    foreach ($brands as $brand) {
                        $group=$brand->getGroups();
                        $group=explode(',', $group);
                        if (in_array($item->getId(), $group)) {
                            $this->messageManager->addError("Can't delete group ".$item->getName().",please check all brands in this group");
                            $error = true;
                        }
                    }
                }
                if ($error==true) {
                    continue;
                }
                $item->delete();
                $totals++;
                $this->messageManager->addSuccess(__('A total of %1 record(s) have been deteled.', $totals));
            }
            if ($error==true) {
                return $resultRedirect->setPath('*/*/');
            }

            /*Refresh cache*/
            $types = array('layout','block_html','collections','reflection');
            foreach ($types as $type) {
                $this->_cacheTypeList->cleanType($type);
            }
            foreach ($this->_cacheFrontendPool as $cacheFrontend) {
                $cacheFrontend->getBackend()->clean();
            }
            /*end delete cache*/
        } catch (LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->_getSession()->addException($e, __('Something went wrong while delete the group(s).'));
        }


        return $resultRedirect->setPath('*/*/');
    }
    /**
     * @param $id
     */
    public function deleteUrlRewrite($id)
    {
        $collection = $this->_urlRewrite->create()->getCollection()
            ->addFieldToFilter('entity_type', 'group')
            ->addFieldToFilter('entity_id', $id);
        foreach ($collection as $model) {
            $model->delete();
        }
    }
}
