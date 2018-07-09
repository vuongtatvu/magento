<?php
/**
 * Created by PhpStorm.
 * User: keysnt
 * Date: 24/01/2018
 * Time: 10:08
 */
namespace Magenest\StockStatus\Block\Adminhtml\ManagerIcon;

use \Magento\Framework\View\Element\Template;
use \Magento\Eav\Model\Config;

class Icons extends Template{
    /**
     * @var Config
     */
    protected $_eavConfig;
    /**
     * @var \Magenest\StockStatus\Model\ManagerIconFactory
     */
    protected $managerIconFactory;
    /**
     * @var \Magenest\StockStatus\Helper\Image
     */
    protected $imageHelper;
    /**
     * Icons constructor.
     * @param Template\Context $context
     * @param Config $eavConfig
     * @param \Magenest\StockStatus\Model\ManagerIconFactory $managerIconFactory
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        \Magento\Eav\Model\Config $eavConfig,
        \Magenest\StockStatus\Model\ManagerIconFactory $managerIconFactory,
        \Magenest\StockStatus\Helper\Image $imageHelper,
        array $data = []
    ){
        $this->imageHelper = $imageHelper;
        $this->managerIconFactory = $managerIconFactory;
        $this->_eavConfig = $eavConfig;
        parent::__construct($context, $data);
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCustomStockStatus(){
        $string = 'custom_stock_status';
        $attribute = $this->_eavConfig->getAttribute('catalog_product', $string);
        $options = $attribute->getSource()->getAllOptions();
        $managerIconCollections = $this->managerIconFactory->create()->getCollection();
        $results = array();
        foreach ($managerIconCollections as $item){
            $results[] = $item->getData();
        }
        $data = array();
        foreach ($options as $option){
            if(!empty($results) && !empty($option)){
                foreach ($results as $collection){
                    if($option['value'] == $collection['stockstatus_id']){
                        $option['path'] = $collection['path_image'];
                        $option['entity_id'] = $collection['entity_id'];
                    }
                }
            }else{
                $option['path'] = '';
            }
            $data[] = $option;
        }

        return $data;
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
    public function getIconImage($optionId,$file){
        return $this->imageHelper->getStatusIconUrl($optionId,$file);
    }
}