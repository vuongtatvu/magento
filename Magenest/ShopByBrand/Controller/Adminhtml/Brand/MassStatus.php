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
namespace Magenest\ShopByBrand\Controller\Adminhtml\Brand;

use Magenest\ShopByBrand\Controller\Adminhtml\Brand;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class MassStatus
 *
 * @package Magenest\ShopByBrand\Controller\Adminhtml\Brand
 */
class MassStatus extends Brand
{
    /**
     * execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $brandCollection = $this->_brandFactory->create()->getCollection();
        $collections     = $this->_filter->getCollection($brandCollection);
        $status          = (int) $this->getRequest()->getParam('status');
        $totals          = 0;

        try {
            foreach ($collections as $item) {
                if ($status==\Magenest\ShopByBrand\Model\Brand::STATUS_DISABLE) {
                    $item->setFeatured(\Magenest\ShopByBrand\Model\Brand::FEATURE_DISABLE);
                }
                /*
                    * @var \Magenest\ShopByBrand\Model\Brand $item
                 */
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
            }

            $this->messageManager->addSuccess(__('A total of %1 record(s) have been updated.', $totals));
        } catch (LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*');
    }
}
