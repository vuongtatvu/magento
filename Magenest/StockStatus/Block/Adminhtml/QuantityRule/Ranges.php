<?php
namespace Magenest\StockStatus\Block\Adminhtml\QuantityRule;

use \Magento\Framework\View\Element\Template;
use \Magento\Eav\Model\Config;

class Ranges extends Template {
    /**
     * @var Config
     */
    protected $_eavConfig;
    /**
     * @var \Magenest\StockStatus\Model\QuantityRuleFactory
     */
    protected $quantityRuleFactory;

    /**
     * Ranges constructor.
     * @param Template\Context $context
     * @param Config $eavConfig
     * @param \Magenest\StockStatus\Model\QuantityRuleFactory $quantityRuleFactory
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        \Magento\Eav\Model\Config $eavConfig,
        \Magenest\StockStatus\Model\QuantityRuleFactory $quantityRuleFactory,
        array $data = []
    ){
        $this->quantityRuleFactory = $quantityRuleFactory;
        $this->_eavConfig = $eavConfig;
        parent::__construct($context, $data);
    }

    /**
     * @param string $string
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCustomStockStatus($string = 'custom_stock_status'){
        $attribute = $this->_eavConfig->getAttribute('catalog_product', $string);
        $options = $attribute->getSource()->getAllOptions();
        return $options;
    }

    /**
     * @return mixed
     */
    public function getBaseUrl(){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->create('\Magento\Store\Model\StoreManagerInterface');
        $baseURL = $storeManager->getStore()->getBaseUrl();
        return $baseURL;
    }

    public function getQtyRule(){
        $quantityRuleCollection = $this->quantityRuleFactory->create();
        $collection = $quantityRuleCollection->getCollection();
        $results = array();
        foreach ($collection as $item){
            $results[] = $item->getData();
        }
        $arr = array();
        foreach ($results as $result){
            $result['rule'] = $this->getAttributeByOptionId($result['rule']);
            $result['status_id'] = $this->getAttributeByOptionId($result['status_id']);
            $arr[] = $result;
        }
        return $arr;
    }

    public function getAttributeByOptionId($optionId){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $Tbl = $connection->getTableName('eav_attribute_option_value');
        $sql = $connection->select()->from($Tbl)->where('option_id = ? AND store_id = 1', $optionId);
        $result = $connection->fetchRow($sql);
        return $result['value'];
    }
    public function getLandingsUrl(){
        return $this->getUrl('magenestmovie');
    }
}