<?php
/**
 * Created by PhpStorm.
 * User: heomep
 * Date: 24/09/2016
 * Time: 14:10
 */

namespace Magenest\MapList\Controller\Adminhtml\Map;

use Magento\Backend\App\Action;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Framework\Controller\ResultFactory;
use Magenest\MapList\Model\MapFactory;
use Magento\Framework\Exception\LocalizedException;

class MassStatus extends Action
{
    protected $_filter;

    protected $_mapFactory;

    public function __construct(
        Filter $filter,
        MapFactory $mapFactory,
        Action\Context $context
    ) {
        $this->_filter = $filter;
        $this->_mapFactory = $mapFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $status = (int)$this->getRequest()->getParam('is_active');
        $collection = $this->_filter->getCollection($this->_mapFactory->create()->getCollection());
        $total = 0;

        try {
            foreach ($collection as $item) {
                $item->setData('is_active', $status)->save();
                $total++;
            }

            $this->messageManager->addSuccess(__('A total of %1 record(s) have been updated.', $total));
        } catch (LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('maplist/*/index');
    }
}
