<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Block\Adminhtml\Post;

/**
 * Class Edit
 * @package Magenest\Blog\Block\Adminhtml\Post
 */
class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_objectId = 'entity_id';
        $this->_controller = 'adminhtml_post';
        $this->_blockGroup = 'Magenest_Blog';
        $this->buttonList->remove('save');
        $this->buttonList->remove('reset');
        $this->addButton(
            '_reset',
            ['label' => __('Reset'), 'onclick' => 'setLocation(window.location.href)', 'class' => 'reset'],
            -1
        );
        $this->getToolbar()->addChild(
            'save-split-button',
            'Magento\Backend\Block\Widget\Button\SplitButton',
            [
                'id'           => 'save-split-button',
                'label'        => __('Save'),
                'class_name'   => 'Magento\Backend\Block\Widget\Button\SplitButton',
                'button_class' => 'widget-button-update',
                'options'      => [
                    [
                        'id'             => 'save-button',
                        'label'          => __('Save'),
                        'default'        => true,
                        'data_attribute' => [
                            'mage-init' => [
                                'button' => [
                                    'event'  => 'saveAndContinueEdit',
                                    'target' => '#edit_form'
                                ]
                            ]
                        ]
                    ],
                    [
                        'id'             => 'save-continue-button',
                        'label'          => __('Save & Close'),
                        'data_attribute' => [
                            'mage-init' => [
                                'button' => [
                                    'event'  => 'save',
                                    'target' => '#edit_form'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        );
    }
}
