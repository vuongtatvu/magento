<?php
/**
 * Created by PhpStorm.
 * User: heomep
 * Date: 19/09/2016
 * Time: 13:50
 */

namespace Magenest\MapList\Controller\Adminhtml\Category;

use Magenest\MapList\Controller\Adminhtml\Category;
use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry;
use Psr\Log\LoggerInterface;

class Delete extends Category
{
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        Registry $coreRegistry,
        \Magenest\MapList\Model\CategoryFactory $categoryFactory,
        LoggerInterface $logger
    ) {
        parent::__construct($context, $resultPageFactory, $coreRegistry, $categoryFactory, $logger);
    }

    public function execute()
    {
        $category_id = $this->getRequest()->getParam('id');
        $model = $this->categoryFactory->create()->load($category_id);
        $resultRedirect = $this->resultRedirectFactory->create();

        try {
            $model->delete();
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
            $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($model->getData());

            return $resultRedirect->setPath('*/*/edit', ['id' => $category_id]);
        }

        return $resultRedirect->setPath('*/*/index');
    }
}
