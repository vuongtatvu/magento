<?php
namespace Magenest\ShopByBrand\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Store\Model\Store;
use Magenest\ShopByBrand\Model\Config\Router;

class InstallData implements InstallDataInterface
{
    /**
     * EAV setup factory
     *
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @var \Magento\Eav\Model\Entity\Attribute
     */
    protected $catalogAttribute;

    /**
     * @var \Magento\Store\Model\Store
     */
    protected $store;

    /**
     * Init
     *
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        \Magento\Eav\Model\Entity\Attribute $catalogAttribute,
        Store $store
    )
    {
        $this->catalogAttribute = $catalogAttribute;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->store = $store;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /**
         * @var EavSetup $eavSetup
         */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        $eavSetup->removeAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'brand_related'
        );
        /**
         * Add attributes to the eav/attribute
         */
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'brand_related',
            [
                'group' => 'Brand Product',
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'label' => 'Show Related Product',
                'input' => 'boolean',
                'class' => '',
                'source' => '',
                'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => 'simple,configurable,virtual,bundle,downloadable'
            ]
        );

        $eavSetup->removeAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'brand_id'
        );
        $data = array(
            'group' => 'Brand Product',
            'type' => 'varchar',
            'input' => 'select',
            'default' => 1,
            'label' => 'Product Brand',
            'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
            'frontend' => '',
            'source' => 'Magenest\ShopByBrand\Model\ListBrand',
            'visible' => 1,
            'required' => 0,
            'user_defined' => 1,
            'used_for_price_rules' => 1,
            'position' => 2,
            'unique' => 0,
            'sort_order' => 100,
            'is_global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_STORE,
            'is_required' => 0,
            'is_configurable' => 1,
            'is_searchable' => 0,
            'is_visible_in_advanced_search' => 0,
            'is_comparable' => 0,
            'is_filterable' => 1,
            'is_filterable_in_search' => 1,
            'is_used_for_promo_rules' => 1,
            'is_html_allowed_on_front' => 0,
            'is_visible_on_front' => 1,
            'used_in_product_listing' => 1,
            'used_for_sort_by' => 0,
        );
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'brand_id',
            $data
        );
        $brandIds = $this->catalogAttribute->loadByCode('catalog_product', 'brand_id');
        $brandIds->addData($data)->save();


        $eavSetup->removeAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'manufacturer'
        );

        $dataMenu = array(
            'group' => 'Brand Product',
            'type' => 'varchar',
            'input' => 'select',
            'default' => 1,
            'label' => 'Manufacturer',
            'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
            'frontend' => '',
            'source' => 'Magenest\ShopByBrand\Model\ListBrand',
            'visible' => 1,
            'required' => 0,
            'user_defined' => 1,
            'used_for_price_rules' => 1,
            'position' => 2,
            'unique' => 0,
            'sort_order' => 100,
            'is_global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_STORE,
            'is_required' => 0,
            'is_configurable' => 1,
            'is_searchable' => 0,
            'is_visible_in_advanced_search' => 0,
            'is_comparable' => 0,
            'is_filterable' => 0,
            'is_filterable_in_search' => 1,
            'is_used_for_promo_rules' => 1,
            'is_html_allowed_on_front' => 0,
            'is_visible_on_front' => 1,
            'used_in_product_listing' => 1,
            'used_for_sort_by' => 0,
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'manufacturer',
            $dataMenu
        );
        $menufacturer = $this->catalogAttribute->loadByCode('catalog_product', 'manufacturer');
        $menufacturer->addData($dataMenu)->save();
    }
}
