<?php
/**
 * Created by PhpStorm.
 * User: heomep
 * Date: 19/09/2016
 * Time: 13:51
 */

namespace Magenest\MapList\Block\Adminhtml\Category;

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
        $category = $this->_coreRegistry->registry('maplist_category_edit');
        $this->_objectId = 'id';
        $this->_blockGroup = 'Magenest_MapList';
        $this->_controller = 'adminhtml_category';
        parent::_construct();

        $this->buttonList->update('save', 'label', __('Save'));
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
    }

    public function getHeaderText()
    {
        $category = $this->_coreRegistry->registry('maplist_category_edit');
        if ($category->getId()) {
            return __("Edit Tag", $this->escapeHtml($category->getData('name')));
        }

        return __('New Tag');
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
