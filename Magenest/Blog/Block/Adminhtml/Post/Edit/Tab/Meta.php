<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Block\Adminhtml\Post\Edit\Tab;

use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;

/**
 * Class Meta
 * @package Magenest\Blog\Block\Adminhtml\Post\Edit\Tab
 */
class Meta extends \Magento\Backend\Block\Widget\Form
{
    /**
     * @var FormFactory
     */
    protected $formFactory;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @param FormFactory                           $formFactory
     * @param Registry                              $registry
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param array                                 $data
     */
    public function __construct(
        FormFactory $formFactory,
        Registry $registry,
        \Magento\Backend\Block\Widget\Context $context,
        array $data = []
    ) {
        $this->formFactory = $formFactory;
        $this->registry    = $registry;
        $this->context     = $context;

        parent::__construct($context, $data);
    }

    /**
     * @return $this
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        $form = $this->formFactory->create();
        $this->setForm($form);
        /** @var \Magenest\Blog\Model\Post $post */
        $post = $this->registry->registry('current_model');
        $fieldset = $form->addFieldset('edit_fieldset', [
            'class' => 'blog__post-fieldset'
        ]);
        $fieldset->addField('meta_title', 'text', [
            'label' => __('Meta Title'),
            'name'  => 'post[meta_title]',
            'value' => $post->getMetaTitle(),
        ]);
        $fieldset->addField('meta_description', 'textarea', [
            'label' => __('Meta Description'),
            'name'  => 'post[meta_description]',
            'value' => $post->getMetaDescription(),
        ]);
        $fieldset->addField('meta_keywords', 'textarea', [
            'label' => __('Meta Keywords'),
            'name'  => 'post[meta_keywords]',
            'value' => $post->getMetaKeywords(),
        ]);
        $fieldset->addField('url_key', 'text', [
            'label' => __('URL Key'),
            'name'  => 'post[url_key]',
            'value' => $post->getUrlKey(),
        ]);

        return parent::_prepareForm();
    }
}
