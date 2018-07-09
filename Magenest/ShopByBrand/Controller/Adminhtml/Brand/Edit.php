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

/**
 * Class Edit
 *
 * @package Magenest\ShopByBrand\Controller\Adminhtml\Brand
 */
class Edit extends Brand
{
    /**
     * @return $this|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $id    = $this->getRequest()->getParam('brand_id');
        $a= $this->getRequest();
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

        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        $url = $model->getUrlKey();
        if(strpos($url, ".html")){
            $url = substr($url,0,strlen($url)-5);
        }
        $model->setUrlKey($url);

        $this->_coreRegistry->register('shopbybrand', $model);
        $this->_coreRegistry->register('category', 1);
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(!$model->getId() ? __('New Rules Label') : __('Edit \'%1\' Rule', $model->getName()));

        return $resultPage;
    }
}
