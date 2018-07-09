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

/**
 * Class Grid
 *
 * @package Magenest\ShopByBrand\Controller\Adminhtml\Brand
 */
class Grid extends Brand
{
    /**
     * @return \Magento\Framework\Controller\Result\Raw|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $id    = $this->getRequest()->getParam('brand_id');
        $model = $this->_objectManager->create('Magenest\ShopByBrand\Model\Brand');

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This rule no longer exists.'));
                /*
                    * \Magento\Backend\Model\View\Result\Redirect $resultRedirect
                 */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->_coreRegistry->register('shopbybrand', $model);
        $this->_coreRegistry->register('category', 1);
        $resultRaw = $this->resultRawFactory->create();
        return  $resultRaw->setContents(
            $this->layoutFactory->create()->createBlock(
                'Magenest\ShopByBrand\Block\Adminhtml\Brand\Edit\Tab\Products',
                'brand.tab.products'
            )->toHtml()
        );
    }
}
