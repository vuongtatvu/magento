<?php
/**
 * Created by PhpStorm.
 * User: thien
 * Date: 28/08/2017
 * Time: 13:26
 */

namespace Magenest\ShopByBrand\Controller\Adminhtml\Group;

use Magenest\ShopByBrand\Controller\Adminhtml\Group;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Delete
 *
 * @package Magenest\ShopByBrand\Controller\Adminhtml\Brand
 */
class Delete extends Group
{
    /**
     * @return \Magento\Framework\Controller\Result\Redirect|\Magento\Framework\View\Result\Page
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('group_id');
        $error=false;
        /*
            * @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect
         */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            $model = $this->_objectManager->create('Magenest\ShopByBrand\Model\Group');
            $model->load($id);
            if ($id != $model->getId()) {
                throw new LocalizedException(__('Wrong label rule.'));
            }

            $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($model->getData());
            try {
                $this->deleteUrlRewrite($id);
                //check do this group have anybrand
                $brands = $this->brandFactory->create()->getCollection();
                if ($brands->getSize()>0) {
                    foreach ($brands as $brand) {
                        $group=$brand->getGroups();
                        $group=explode(',', $group);
                        if (in_array($id, $group)) {
                            $this->messageManager->addError("Can't delete this group, please check all brands in this group");
                            $error = true;
                        }
                    }
                }
                if ($error==true) {
                    return $resultRedirect->setPath('*/*/edit', ['group_id' => $model->getId(), '_current' => true]);
                }

                $model->delete();
                /*Refresh cache*/
                $types = array('layout','block_html','collections','reflection');
                foreach ($types as $type) {
                    $this->_cacheTypeList->cleanType($type);
                }
                foreach ($this->_cacheFrontendPool as $cacheFrontend) {
                    $cacheFrontend->getBackend()->clean();
                }
                /*end delete cache*/
                $this->messageManager->addSuccess(__('The Group has been deleted.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['group_id' => $model->getId(), '_current' => true]);
                }

                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($id);
                return $resultRedirect->setPath('*/*/edit', ['group_id' => $this->getRequest()->getParam('brand_id')]);
            }
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
