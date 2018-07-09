<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Block\Adminhtml\Author\Edit;

/**
 * Class Form
 * @package Magenest\Blog\Block\Adminhtml\Author\Edit
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var
     */
    protected $_prepareForm;

    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $wysiwygConfig;

    /**
     * Form constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        array $data = []
    ) {
        $this->wysiwygConfig = $wysiwygConfig;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /* @var $model \Magenest\Blog\Model\Version */
        $model = $this->_coreRegistry->registry('author');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post', 'enctype' => 'multipart/form-data']]
        );
        $this->setForm($form);
        $form->setUseContainer(true);
        $form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('')]);

        if ($model->getId()) {
            $fieldset->addField(
                'author_id',
                'hidden',
                [
                    'name' =>'author_id'
                ]
            );
        }
        $fieldset->addField(
            'display_name',
            'text',
            [
                'name' => 'display_name',
                'label' => __('Display Name'),
                'title' => __('Display Name'),
                'required' => true,
            ]
        );
        $fieldset->addField(
            'image',
            'image',
            [
                'name' => 'image',
                'label' => __('Avatar'),
                'title' => __('Avatar'),
                'required' => true,
            ]
        );
        $editorConfig = $this->wysiwygConfig->getConfig(['tab_id' => $this->getId()]);
        $fieldset->addField('information', 'editor', [
            'name'    => 'information',
            'wysiwyg' => true,
            'style'   => 'height:35em',
            'label' => __('Information'),
            'title' => __('Information'),
            'config'  => $editorConfig,
        ]);

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
