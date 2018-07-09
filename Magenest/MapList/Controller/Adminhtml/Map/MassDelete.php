<?php
/**
 * Created by PhpStorm.
 * User: hiennq
 * Date: 9/12/16
 * Time: 11:10
 */

namespace Magenest\MapList\Controller\Adminhtml\Map;

use Magenest\MapList\Controller\Adminhtml\Map;
use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Magenest\MapList\Model\MapFactory;
use Magenest\MapList\Model\MapLocationFactory;
use Magento\Framework\Registry;
use Psr\Log\LoggerInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;

class MassDelete extends Map
{
    protected $_filter;

    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        Registry $coreRegistry,
        \Magenest\MapList\Model\MapFactory $mapFactory,
        \Magenest\MapList\Model\MapLocationFactory $mapLocationFactory,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        LoggerInterface $logger,
        Filter $filter
    ) {
        $this->_filter = $filter;
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
        $collection = $this->_filter->getCollection($this->mapFactory->create()->getCollection());
        $deletedMap = 0;

        foreach ($collection->getItems() as $map) {
            $map->delete();
            $deletedMap++;
        }
        $this->messageManager->addSuccess(
            __('A total of %1 record(s) have been deleted.', $deletedMap)
        );

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('maplist/*/index');
    }
}
