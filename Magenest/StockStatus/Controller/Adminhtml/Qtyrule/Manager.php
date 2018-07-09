<?php
namespace Magenest\StockStatus\Controller\Adminhtml\Qtyrule;

class Manager extends \Magento\Framework\App\Action\Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var \Magenest\StockStatus\Model\QuantityRuleFactory
     */
    protected $quantityRuleFactory;

    /**
     * Manager constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magenest\StockStatus\Model\QuantityRuleFactory $quantityRuleFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magenest\StockStatus\Model\QuantityRuleFactory $quantityRuleFactory
    )
    {
        $this->quantityRuleFactory = $quantityRuleFactory;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        try {
            $request = $this->getRequest()->getParams();
            $rules = $request['rule'];
            /**
             * @var $quantityRule \Magenest\StockStatus\Model\ResourceModel\QuantityRule
             */

            $array = array();

            if (!empty($rules)) {
                for ($j = 0; $j < count($rules['from']); $j++) {
                    $array[$j] = array(
                        'entity_id' => $rules['entity_id'][$j],
                        'qty_from' => $rules['from'][$j],
                        'qty_to' => $rules['to'][$j],
                        'rule' => $rules['customStockStatus'][$j],
                        'status_id' => $rules['customeStockStatusQtyRule'][$j]
                    );
                }
            }

            for ($j = 0; $j < count($array); $j++) {

                if ($array[$j]['qty_from'] == "" || $array[$j]['qty_to'] == ""
                    || $array[$j]['rule'] == "" || $array[$j]['status_id'] == ""
                ) {
                    $this->messageManager->addError(__('Input no\'t null'));
                    return $resultRedirect->setPath('*/');
                }

                if ($array[$j]['qty_from'] > $array[$j]['qty_to']) {
                    $this->messageManager->addError(__('Quantity From don\'t bigger Quantity To'));
                    return $resultRedirect->setPath('*/');
                }

                if ($array[$j]['qty_from'] < 0 || $array[$j]['qty_to'] < 0) {
                    $this->messageManager->addError(__('Quantity must bigger 0'));
                    return $resultRedirect->setPath('*/');
                }
                
            }

            for ($j = 0; $j < count($array) - 1; $j++) {
                for ($z = $j + 1; $z < count($array); $z++) {
                    if ($array[$j]['status_id'] === $array[$z]['status_id']) {
                        if ($array[$j]['rule'] === $array[$z]['rule'] ||
                            $array[$j]['qty_to'] === $array[$z]['qty_to'] ||
                            $array[$j]['qty_from'] === $array[$z]['qty_from']
                        ) {
                            $this->messageManager->addError(__('Error and required to create new others rules'));
                            return $resultRedirect->setPath('*/');
                        } else {
                            if (($array[$j]['qty_from'] <= $array[$z]['qty_to'] &&
                                    $array[$j]['qty_to'] >= $array[$z]['qty_to']) ||
                                ($array[$j]['qty_from'] <= $array[$z]['qty_from'] &&
                                    $array[$j]['qty_to'] >= $array[$z]['qty_from']) ||
                                ($array[$j]['qty_from'] >= $array[$z]['qty_to'] &&
                                    $array[$j]['qty_to'] <= $array[$z]['qty_to']) ||
                                ($array[$j]['qty_from'] >= $array[$z]['qty_from'] &&
                                    $array[$j]['qty_to'] <= $array[$z]['qty_from'])
                            ) {
                                $this->messageManager->addError(__('Error and required to create new others rules'));
                                return $resultRedirect->setPath('*/');
                            }
                        }
                    }
                }
            }


            $quantityRuleCollection = $this->quantityRuleFactory->create();

            $collection = $quantityRuleCollection->getCollection();

            $results = array();
            foreach ($collection as $item) {
                $results[] = $item->getData();
            }
//            $connection = $quantityRule->getConnection();
            foreach ($results as $result) {
                $flag = false;
                foreach ($array as $a) {
                    if ($a['entity_id'] == $result['entity_id']) {
                        $flag = true;
                        break;
                    }
                }
                if (!$flag) {
                    $this->_objectManager->create('Magenest\StockStatus\Model\QuantityRule')->load($result['entity_id'])->delete();
                }
            }
            //$arrColum = array('qty_from','qty_to','rule','status_id');
            foreach ($array as $arr) {
                $arrData = array('qty_from' => $arr['qty_from'], 'qty_to' => $arr['qty_to'], 'rule' => $arr['rule'], 'status_id' => $arr['status_id']);
                if ($arr['entity_id'] != 0) {
                    $qtyRule = $quantityRuleCollection->load($arr['entity_id']);
                    if (!empty($qtyRule)) {
                        $qtyRule->setQtyFrom($arr['qty_from']);
                        $qtyRule->setQtyTo($arr['qty_to']);
                        $qtyRule->setRule($arr['rule']);
                        $qtyRule->setStatusId($arr['status_id']);
                        $qtyRule->save();
                    }
                } else {
                    $quantityRuleCollection->setData($arrData);
                    $quantityRuleCollection->save();
                }
            }
            $this->messageManager->addSuccessMessage(__('You have updated your data'));
            return $resultRedirect->setPath('*/');
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('We can\'t submit your request, Please try again.'));
            $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
            return $resultRedirect->setPath('*/');
        }

    }

    public function _isAllowed(){
        return $this->_authorization->isAllowed('Magenest_StockStatus::magenest');
    }
}