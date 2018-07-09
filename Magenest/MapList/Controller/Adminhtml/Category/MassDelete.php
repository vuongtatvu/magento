<?php
/**
 * Created by PhpStorm.
 * User: heomep
 * Date: 19/09/2016
 * Time: 13:51
 */

namespace Magenest\MapList\Controller\Adminhtml\Category;

use Magenest\MapList\Controller\Adminhtml\Category;
use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Magenest\MapList\Model\CategoryFactory;
use Magento\Framework\Registry;
use Psr\Log\LoggerInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;

class MassDelete extends Category
{
    protected $_filter;

    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        Registry $coreRegistry,
        \Magenest\MapList\Model\CategoryFactory $categoryFactory,
        LoggerInterface $logger,
        Filter $filter
    ) {
        $this->_filter = $filter;
        parent::__construct($context, $resultPageFactory, $coreRegistry, $categoryFactory, $logger);
    }

    public function execute()
    {
        $collection = $this->_filter->getCollection($this->categoryFactory->create()->getCollection());
        $deletedCategory = 0;

        foreach ($collection->getItems() as $category) {
            $category->delete();
            $deletedCategory++;
        }
        $this->messageManager->addSuccess(
            __('A total of %1 record(s) have been deleted.', $deletedCategory)
        );

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('maplist/*/index');
    }
}
