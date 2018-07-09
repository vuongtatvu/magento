<?php
/**
 * Created by PhpStorm.
 * User: hiennq
 * Date: 9/24/16
 * Time: 14:36
 */

namespace Magenest\MapList\Block\Adminhtml\Location\Edit\Tab\AddProduct;

use Magenest\MapList\Model\LocationFactory;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Grid\Extended as GridExtended;
use Magento\Backend\Helper\Data as BackendHelper;

class Grid extends GridExtended
{
    protected $_defaultLimit = 0;
    protected $_locationFactory;
    protected $_coreRegistry = null;
    protected $_productFactory;

    public function __construct(
        Context $context,
        BackendHelper $backendHelper,
        LocationFactory $locationFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        array $data = []
    ) {
        $this->_productFactory = $productFactory;
        $this->_locationFactory = $locationFactory;
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $backendHelper, $data);
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setId('maplistProductGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('asc');
        //$this->setFilterVisibility(false);
        //$this->setPagerVisibility(false);
        $this->setUseAjax(true);
        //$this->_logger->addDebug("Test");
        //var_dump($this->_productFactory->create()->getCollection()->getData());die();
    }

    public function getCategory()
    {
        $location = $this->_coreRegistry->registry('location');

        return $location;
    }

    protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in category flag
        if ($column->getId() == 'in_location') {
            $productIds = $this->_getSelectedProducts();
            if (empty($productIds)) {
                $productIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', ['in' => $productIds]);
            } elseif (!empty($productIds)) {
                $this->getCollection()->addFieldToFilter('entity_id', ['nin' => $productIds]);
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }

        return $this;
    }

    protected function _prepareCollection()
    {
//        if ($this->getCategory()->getId()) {
//            $this->setDefaultFilter(['in_location' => 1]);
//        }
        $collection = $this->_productFactory->create()->getCollection()->addAttributeToSelect(
            'name'
        )->addAttributeToSelect(
            'sku'
        )->addAttributeToSelect(
            'price'
        )->joinField(
            'position',
            'catalog_category_product',
            'position',
            'product_id=entity_id',
            'category_id=' . (int)$this->getRequest()->getParam('id', 0),
            'left'
        );
        $storeId = (int)$this->getRequest()->getParam('store', 0);
        if ($storeId > 0) {
            $collection->addStoreFilter($storeId);
        }
        $this->setCollection($collection);

//        if ($this->getCategory()->getProductsReadonly()) {
//            $productIds = $this->_getSelectedProducts();
//            if (empty($productIds)) {
//                $productIds = 0;
//            }
//            $this->getCollection()->addFieldToFilter('entity_id', ['in' => $productIds]);
//        }

        return parent::_prepareCollection();
    }


    protected function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    protected function _prepareFilterButtons()
    {
        parent::_prepareFilterButtons();
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', ['_current' => true]);
    }

//    protected function _prepareCollection()
//    {
//        $this->setCollection($this->_productFactory->create()->getCollection()
//            ->joinAttribute(
//                'price',
//                'catalog_product/price',
//                'entity_id',
//                null,
//                'left')
//            ->joinAttribute(
//                'name',
//                'catalog_product/name',
//                'entity_id',
//                null,
//                'left')
//        );
//
//        return parent::_prepareCollection();
//    }

    protected function _prepareColumns()
    {
        $this
            ->addColumn(
                'in_location',
                [
                    'type' => 'checkbox',
                    'name' => 'in_map',
                    'values' => $this->_getSelectedProducts(),
                    'index' => 'entity_id',
                    'header_css_class' => 'col-select col-massaction',
                    'column_css_class' => 'col-select col-massaction',
                    'filter' => false,
                ]
            )
            ->addColumn(
                'entity_id',
                [
                    'header' => __('ID'),
                    'index' => 'entity_id',
                    //'sortable' => false,
                ]
            )
            ->addColumn(
                'product_name',
                [
                    'header' => __('Name'),
                    'index' => 'name',
                    //'sortable' => false,
                ]
            )
            ->addColumn(
                'product_sku',
                [
                    'header' => __('Sku'),
                    'index' => 'sku',
                    //'sortable' => false,
                ]
            )
            ->addColumn(
                'product_price',
                [
                    'header' => __('Price'),
                    'index' => 'price',
                    //'sortable' => false,
                ]
            );
//            ->addColumn(
//                'short_description',
//                [
//                    'header' => __('Description'),
//                    'index' => 'short_description',
//                    'sortable' => false,
//                ]
//            )
//            ->addColumn(
//                'action',
//                [
//                    'header' => __('Action'),
//                    'type' => 'action',
//                    'getter' => 'getId',
//                    'actions' => [
//                        [
//                            'caption' => __('Edit'),
//                            'url' => [
//                                'base' => 'catalog/product/edit',
//                            ],
//                            'field' => 'id',
//                        ],
//                    ],
//                    'sortable' => false,
//                ]
//            );

        // todo: render status bar on grid

        return parent::_prepareColumns();
    }


    private function _getSelectedProducts()
    {
//        $productId = $this->getRequest()->getPost('selected_products');
//        $idlist = [];
////        $idlist[] =3;
////        $idlist[] =4;
//        $productId = json_decode($productId);
//        //var_dump(serialize($idlist));
//        if(is_array($productId)){
//            foreach ($productId as $id){
//                //$this->_logger->addDebug(($id));
//                array_push($idlist,$id);
//            }
//        }
//        foreach ($productId as $id){
//            $this->_logger->addDebug($id);
//        }
        //$this->_logger->addDebug(serialize($productId));
//
//        foreach ($productId as $id){
//            $this->_logger->addDebug($id);
//        }
        //$this->_logger->addDebug($productId);
        $data = $this->_coreRegistry->registry('maplist_location_selected_product');
        $productId = [];
        if (!$data) {
            return $productId;
        }
        foreach ($data as $value) {
            $productId[] = $value['product_id'];
        }

        //var_dump($idlist);
        return $productId;
    }
}
