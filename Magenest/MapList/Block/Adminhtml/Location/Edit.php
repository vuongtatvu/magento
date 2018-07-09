<?php

namespace Magenest\MapList\Block\Adminhtml\Location;

use Magento\Backend\Block\Widget\Context;
use Magento\Backend\Block\Widget\Form\Container as FormContainer;
use Magento\Framework\Registry;

class Edit extends FormContainer
{
    protected $_coreRegistry;

    public function __construct(
        Context $context,
        Registry $registry,
        array $data
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    protected function _construct()
    {
        $map = $this->_coreRegistry->registry('maplist_location_edit');
        $this->_objectId = 'id';
        $this->_blockGroup = 'Magenest_MapList';
        $this->_controller = 'adminhtml_location';
        parent::_construct();

        $this->buttonList->update('save', 'label', __('Save'));
    }

    protected function _prepareLayout()
    {
        $this->buttonList->add(
            'save-and-continue',
            [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => [
                            'event' => 'saveAndContinueEdit',
                            'target' => '#edit_form'
                        ]
                    ]
                ]
            ],
            -100
        );

        return parent::_prepareLayout();
    }

    public function getHeaderText()
    {
        $map = $this->coreRegistry->registry('maplist_location_edit');
        if ($map->getId()) {
            return __("Edit Location", $this->escapeHtml($map->getData('name')));
        }

        return __('New Location');
    }

    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl(
            'maplist/*/save',
            [
                '_current' => true,
                'back' => 'edit',
                'active_tab' => '{{tab_id}}'
            ]
        );
    }
}
