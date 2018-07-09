<?php

namespace Magenest\StockStatus\Block\Product;
//use Magento\Backend\Block\Template\Context;
//use Magento\Framework\Registry;

class Status extends \Magento\Framework\View\Element\Template
{
    const XML_PATH_STOCKSTATUS_ENABLED = 'stockstore/statuspage/enabled';
    const XML_PATH_STOCKSTATUS_BUTTONTEXT = 'stockstore/statuspage/button_text';
    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlInterface;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;
    protected $productFactory;

    protected $dataObjectHelper;
    protected $productRepository;

    /**
     * @param \Magento\Framework\UrlInterface $urlInterface
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\UrlInterface $urlInterface,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Catalog\Model\ProductFactory $product,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper
    )
    {
        parent::__construct($context);
        $this->urlInterface = $urlInterface;
        $this->scopeConfig = $scopeConfig;
        $this->productFactory = $product;
        $this->productRepository = $productRepository;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    public function getProductId()
    {
        $data = $this->_request->getParam('id');
        return $data;
    }

    public function getValue()
    {
        $isEnabled = $this->scopeConfig->getValue(self::XML_PATH_STOCKSTATUS_ENABLED, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if ($isEnabled) {
            $buttonText = $this->scopeConfig->getValue(self::XML_PATH_STOCKSTATUS_BUTTONTEXT, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            return $buttonText;
        } else
            return null;

    }


    public function getType()
    {
        $id = $this->getProductId();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $stockRegistry = $objectManager->get('Magento\CatalogInventory\Api\StockRegistryInterface');

        $product = $this->productFactory->create()->load($id);

        $stockitem = $stockRegistry->getStockItem(
            $product->getId(),
            $product->getStore()->getWebsiteId()
        );
        $productArray = [];
        $productArray['type'] = $product->getTypeId();
        $productArray['price'] = $product->getPrice();
        $productArray['sku'] = $product->getSku();
        $productArray['qty'] = $stockitem->getQty();
        $productArray['status'] = $product->getData('custom_stock_status');
        $productArray['numberleft'] = $product->getData('notice_number_left');
        return $productArray;
    }

    public function getChildProduct($color){
        $id = $this->getProductId();
        $childObj = $this->getAllConfigChildProductIds($id);
        foreach ($childObj as $child){
           if($child->getAttributeText('color')==$color){
               $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
               $stockRegistry = $objectManager->get('Magento\CatalogInventory\Api\StockRegistryInterface');

               $product = $this->productFactory->create()->load($child->getId());

               $stockitem = $stockRegistry->getStockItem(
                   $product->getId(),
                   $product->getStore()->getWebsiteId()
               );

               $productArray = [];
               $productArray['type'] = $child->getTypeId();
               $productArray['price'] = $child->getPrice();
               $productArray['sku'] = $child->getSku();
               $productArray['qty'] = $stockitem->getQty();
               $productArray['status'] = $child->getData('custom_stock_status');
               $productArray['numberleft'] = $child->getData('notice_number_left');
               return $productArray;
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