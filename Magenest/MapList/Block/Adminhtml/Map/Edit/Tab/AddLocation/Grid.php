<?php
/**
 * Created by PhpStorm.
 * User: hiennq
 * Date: 9/24/16
 * Time: 14:36
 */

namespace Magenest\MapList\Block\Adminhtml\Map\Edit\Tab\AddLocation;

use Magenest\MapList\Model\LocationFactory;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Grid\Extended as GridExtended;
use Magento\Backend\Helper\Data as BackendHelper;

class Grid extends GridExtended
{
    protected $_defaultLimit = 0;
    protected $_locationFactory;
    protected $_coreRegistry;

    public function __construct(
        Context $context,
        BackendHelper $backendHelper,
        LocationFactory $locationFactory,
        \Magento\Framework\Registry $coreRegistry,
        array $data = []
    ) {
        $this->_locationFactory = $locationFactory;
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $backendHelper, $data);
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setId('maplistMapGrid');
        $this->setDefaultSort('location_id');
        $this->setDefaultDir('asc');
//        $this->setFilterVisibility(false);
//        $this->setPagerVisibility(false);
        $this->setUseAjax(true);
    }

    protected function _prepareLayout()
    {
//        $this->setChild(
//            'add_button',
//            $this->getLayout()->createBlock(\Magento\Backend\Block\Widget\Button::class)->setData(
//                [
//                    'label' => __('Add'),
//                    'onclick' => 'addLocation()',
//                    'class' => 'task action-secondary',
//                ]
//            )->setDataAttribute(
//                [
//                    'action' => 'grid-filter-apply'
//                ]
//            )
//        );
        return parent::_prepareLayout();
    }

    protected function _prepareFilterButtons()
    {
        parent::_prepareFilterButtons();
//        $this->setChild(
//            'add_button',
//            $this->getLayout()->createBlock(\Magento\Backend\Block\Widget\Button::class)->setData(
//                [
//                    'label' => __('Add Location'),
//                    'onclick' => 'addLocation()',
//                    'class' => 'task action-secondary',
//                ]
//            )->setDataAttribute(
//                [
//                    'action' => 'grid-filter-apply'
//                ]
//            )
//        );
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', ['_current' => true]);
    }

    protected function _prepareCollection()
    {
        $this->setCollection($this->_locationFactory->create()->getCollection());

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_map',
            [
                'type' => 'checkbox',
                'name' => 'in_map',
                'values' => $this->_getSelectedProducts(),
                'index' => 'location_id',
                'header_css_class' => 'col-select col-massaction',
                'column_css_class' => 'col-select col-massaction',
                'filter' => false,
            ]
        )
            ->addColumn(
                'location_id',
                [
                    'header' => __('ID'),
                    'index' => 'location_id',
                    //'sortable' => false,
                ]
            )
            ->addColumn(
                'location_title',
                [
                    'header' => __('Title'),
                    'index' => 'title',
                    //'sortable' => false,
                ]
            )
            ->addColumn(
                'location_short_description',
                [
                    'header' => __('Description'),
                    'index' => 'short_description',
                    //'sortable' => false,
                ]
            )
            ->addColumn(
                'location_image',
                [
                    'header' => __('Map'),
                    'index' => 'image',
                    'renderer' => 'Magenest\MapList\Block\Adminhtml\Map\Edit\Tab\AddLocation\Renderer\Image',
                    'sortable' => false,
                    'filter' => false,
                ]
            )
            ->addColumn(
                'action',
                [
                    'header' => __('Action'),
                    'type' => 'action',
                    'getter' => 'getId',
                    'actions' => [
                        [
                            'caption' => __('Edit'),
                            'url' => [
                                'base' => 'maplist/location/edit',
                            ],
                            'field' => 'id',
                        ],
                    ],
                    'sortable' => false,
                    'filter' => false,
                ]
            );

        // todo: render status bar on grid

        return parent::_prepareColumns();
    }

//    public function getMainButtonsHtml()
//    {
//        $html = '';
//        if ($this->getColumnSet()->isFilterVisible()) {
//            $html .= $this->getAddFilterButtonHtml();
//        }
//        return $html.parent::getMainButtonsHtml();
//    }
//
//    public function getAddFilterButtonHtml()
//    {
//        return $this->getChildHtml('add_button');
//    }

    private function _getSelectedProducts()
    {
        $locationData = $this->_coreRegistry->registry('maplist_location_data');
        $locationId = [];
        if (!$locationData) {
            return $locationId;
        }

        foreach ($locationData as $locationValue) {
            $locationId[] = $locationValue['location_id'];
        }

        return $locationId;
    }
}
