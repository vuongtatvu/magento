<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Block\Adminhtml\Post\Edit\Sidebar;

use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magento\Backend\Block\Widget\Form;
use Magento\Backend\Block\Widget\Context;

/**
 * Class Image
 * @package Magenest\Blog\Block\Adminhtml\Post\Edit\Sidebar
 */
class Image extends Form
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
     * @param FormFactory $formFactory
     * @param Registry    $registry
     * @param Context     $context
     */
    public function __construct(
        FormFactory $formFactory,
        Registry $registry,
        Context $context
    ) {
        $this->formFactory = $formFactory;
        $this->registry = $registry;
        parent::__construct($context);
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
        $fieldset = $form->addFieldset('image_fieldset', [
            'class'  => 'blog__post-fieldset',
            'legend' => __('Featured Image'),
        ]);
        $fieldset->addField('featured_image', 'image', [
            'required' => false,
            'name'     => 'featured_image',
            'value'    => $post->getFeaturedImageUrl(),
        ]);

        return parent::_prepareForm();
    }
}

