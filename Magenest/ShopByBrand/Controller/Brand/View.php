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
namespace Magenest\ShopByBrand\Controller\Brand;

/**
 * Class View
 *
 * @package Magenest\ShopByBrand\Controller\Brand
 */
class View extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Magenest\ShopByBrand\Model\Brand
     */
    protected $brand;

    /**
     * @param \Magento\Framework\App\Action\Context      $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magenest\ShopByBrand\Model\Brand $brand
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->brand=$brand;
        parent::__construct($context);
    }

    /**
     * @return $this|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('brand_id');
        if (!$id) {
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath("*/*/index");
        }
        $brand=$this->brand->load($id);
        $resultPage = $this->resultPageFactory->create();
        if ($brand->getPageTitle()) {
            $resultPage->getConfig()->getTitle()->set($brand->getPageTitle());
        }

        return $resultPage;
    }
}
