<?php
namespace Magenest\StockStatus\Controller\Product;

class Status extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;

    protected $productRepository;
    protected $dataObjectHelper;
    protected $productFactory;
    protected $_storeManager;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultPageFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        \Magento\Catalog\Model\ProductFactory $product,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->productRepository = $productRepository;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->productFactory = $product;
        $this->_storeManager = $storeManager;
    }

    public function execute()
    {
        if ($this->getRequest()->isAjax()) {


            $color = $this->getRequest()->getParam('color');
            $id = $this->getRequest()->getParam('id');

            $product = $this->getChildProduct($color, $id);

            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $scopeConfig = $objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface');

            $display_CSS_onLisProduct = $scopeConfig->getValue('stockstore/hellopage/display_stock_status_on_product_list_page', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $ruleQty = $scopeConfig->getValue('stockstore/hellopage/activate_rules', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $defaultCSS = $scopeConfig->getValue('stockstore/hellopage/hide_default_stockstatus', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

            if ($display_CSS_onLisProduct == 1) {
                $customStockStatusId = $optionText = '';
                $stockRegistry = $objectManager->get('Magento\CatalogInventory\Api\StockRegistryInterface');


                $stockitem = $stockRegistry->getStockItem(
                    $product->getId(),
                    $product->getStore()->getWebsiteId()
                );
                $qty = $stockitem->getQty();
                if ($product->getTypeId() != 'configurable') {
                    if ($ruleQty == 1) {
                        $customStockStatusQtyRuleId = $product->getData('custom_stock_status_qty_rule');
                        if (!empty($customStockStatusQtyRuleId)) {
                            /**
                             * @var $quantityRuleFactory \Magenest\StockStatus\Model\QuantityRuleFactory
                             */
                            $quantityRuleFactory = $objectManager->get('Magenest\StockStatus\Model\QuantityRuleFactory');
                            $collection = $quantityRuleFactory->create()->getCollection();
                            $collection->addFieldToSelect('*');
                            $items = $collection->addFieldToFilter('status_id', ['eq' => $customStockStatusQtyRuleId])->getItems();
                            foreach ($items as $item) {
                                $data = $item->getData();
                                if (($qty >= $data['qty_from']) && ($qty <= $data['qty_to'])) {
                                    $customStockStatusId = $optionId = $data['rule'];
                                    $attributeId = $product->getResource()->getAttribute('custom_stock_status');
                                    if ($attributeId->usesSource()) {
                                        $optionText = $attributeId->getSource()->getOptionText($optionId);
                                    }
                                }
                            }
                        }
                    }


                    if (empty($optionText)) {
                        $customStockStatusId = $product->getData('custom_stock_status');
                        $attributeId = $product->getResource()->getAttribute('custom_stock_status');
                        if ($attributeId->usesSource()) {
                            $optionText = $attributeId->getSource()->getOptionText($customStockStatusId);
                        }
                    }
                    if (empty($optionText) && $defaultCSS == 0) {
                        $attributeId = $product->getResource()->getAttribute('custom_stock_status');
                        if ($attributeId->usesSource()) {
                            $data = $attributeId->getSource()->getAttribute()->getData();
                            $customStockStatusId = $optionDefaultId = $data['default_value'];
                            $optionText = $attributeId->getSource()->getOptionText($optionDefaultId);
                        }
                    }

                    /**
                     * @var  $managerIconFactory \Magenest\StockStatus\Model\ManagerIconFactory
                     */
                    $managerIconFactory = $objectManager->get('Magenest\StockStatus\Model\ManagerIconFactory');
                    $managerIconCollection = $managerIconFactory->create()->getCollection();
                    $results = array();
                    $pathImage = '';
                    foreach ($managerIconCollection as $item) {
                        $data = $item->getData();
                        if ($data['stockstatus_id'] == $customStockStatusId) {
                            /**
                             * @var $imageHelper \Magenest\StockStatus\Helper\Image
                             */
                            $imageHelper = $objectManager->get('Magenest\StockStatus\Helper\Image');
                            $pathImage = $imageHelper->getStatusIconUrl($data['stockstatus_id'], $data['path_image']);
                        }
                        $results[] = $item->getData();
                    }

                    $numberleft = $product->getData('notice_number_left');
                    if ($numberleft >= $qty) {
                        $product['qty'] = $qty;
                    }
                    if (isset($optionText) && $optionText != null) {
                        $product['status'] = $optionText;
                        $product['image'] = $pathImage;
                    }
                    else{
                        $product['status'] = null;
                    }
                }
            }


            return $this->resultPageFactory->create()->setData([
                'type' => $product['type_id'],
                'price' => $product['price'],
                'sku' => $product['sku'],
                'qty' => $product['qty'],
                'status' => $product['status'],
                'numberleft' => $product->getData('notice_number_left'),
                'image' => $product['image']
            ]);
        }
    }


    public function getChildProduct($color, $id)
    {
        $childObj = $this->getAllConfigChildProductIds($id);
        foreach ($childObj as $child) {
            if ($child->getAttributeText('color') == $color) {
                return $child;
            }
        }
    }

    public function getAllConfigChildProductIds($id)
    {
        $product = array();
        if (is_numeric($id)) {
            $product = $this->productRepository->getById($id);
        } else {
            return;
        }

        if (!isset($product)) {
            return;
        }

        if ($product->getTypeId() != \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE) {
            return [];
        }

        $storeId = $this->_storeManager->getStore()->getId();

        $productTypeInstance = $product->getTypeInstance();
        $productTypeInstance->setStoreFilter($storeId, $product);
        $usedProducts = $productTypeInstance->getUsedProducts($product);
        $childrenList = [];

        foreach ($usedProducts as $child) {
            $attributes = [];
            $isSaleable = $child->isSaleable();

            //getting in-stock product
            if ($isSaleable) {
                foreach ($child->getAttributes() as $attribute) {
                    $attrCode = $attribute->getAttributeCode();
                    $value = $child->getDataUsingMethod($attrCode) ?: $child->getData($attrCode);
                    if (null !== $value && $attrCode != 'entity_id') {
                        $attributes[$attrCode] = $value;
                    }
                }

                $attributes['store_id'] = $child->getStoreId();
                $attributes['id'] = $child->getId();
                /** @var \Magento\Catalog\Api\Data\ProductInterface $productDataObject */
                $productDataObject = $this->productFactory->create();
                $this->dataObjectHelper->populateWithArray(
                    $productDataObject,
                    $attributes,
                    '\Magento\Catalog\Api\Data\ProductInterface'
                );
                $childrenList[] = $productDataObject;
            }
        }

        $childData = array();
        foreach ($childrenList as $child) {
            $childData[] = $child;
        }

        return $childData;
    }
}