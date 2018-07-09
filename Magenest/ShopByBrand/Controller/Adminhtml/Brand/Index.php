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
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;

/**
 * Class Index
 *
 * @package Magenest\ShopByBrand\Controller\Adminhtml\Brand
 */
class Index extends Brand
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Magenest_ShopByBrand::brand');
        $resultPage->addBreadcrumb(__('ShopByBrand'), __('ShopByBrand'));
        $resultPage->addBreadcrumb(__('Manage Brand'), __('Manage Brand'));
        $resultPage->getConfig()->getTitle()->prepend(__('Shop By Brand'));
        return $resultPage;
    }
}
