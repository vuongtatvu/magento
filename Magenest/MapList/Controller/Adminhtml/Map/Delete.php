<?php
/**
 * Created by PhpStorm.
 * User: hiennq
 * Date: 9/12/16
 * Time: 11:09
 */

namespace Magenest\MapList\Controller\Adminhtml\Map;

use Magenest\MapList\Controller\Adminhtml\Map;
use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry;
use Psr\Log\LoggerInterface;

class Delete extends Map
{
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        Registry $coreRegistry,
        \Magenest\MapList\Model\MapFactory $mapFactory,
        \Magenest\MapList\Model\MapLocationFactory $mapLocationFactory,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        LoggerInterface $logger
    ) {
        parent::__construct(
            $context,
            $resultPageFactory,
            $coreRegistry,
            $mapFactory,
            $mapLocationFactory,
            $resultRawFactory,
            $layoutFactory,
            $logger
        );
    }

    public function execute()
    {
        $map_id = $this->getRequest()->getParam('id');
        $model = $this->mapFactory->create()->load($map_id);
        $resultRedirect = $this->resultRedirectFactory->create();

        try {
            $model->delete();
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
            $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($model->getData());

            return $resultRedirect->setPath('*/*/edit', ['id' => $map_id]);
        }

        return $resultRedirect->setPath('*/*/index');
    }
}
