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
namespace Magenest\ShopByBrand\Controller\Adminhtml\Brand;

use Magenest\ShopByBrand\Controller\Adminhtml\Brand;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Controller\ResultFactory;

/**
 * Class MassDelete
 *
 * @package Magenest\ShopByBrand\Controller\Adminhtml\Label
 */
class MassDelete extends Brand
{
    /**
     * execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $brandCollection = $this->_objectManager
            ->create('Magenest\ShopByBrand\Model\ResourceModel\Brand\Collection');
        $collections     = $this->_filter->getCollection($brandCollection);
        $totals          = 0;
        try {
            foreach ($collections as $item) {
                /*
                    * @var \Magenest\ShopbyBrand\Model\Brand $item
                 */
                $this->deleteUrlRewrite($item->getId());
                $item->delete();
                $totals++;
            }

            $this->messageManager->addSuccess(__('A total of %1 record(s) have been deteled.', $totals));
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
            $this->_getSession()->addException($e, __('Something went wrong while delete the brand(s).'));
        }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @param $id
     */
    public function deleteUrlRewrite($id)
    {
        $collection = $this->_urlRewrite->create()->getCollection()
            ->addFieldToFilter('entity_type', 'brand')
            ->addFieldToFilter('entity_id', $id);
        foreach ($collection as $model) {
            $model->delete();
        }
    }
}
