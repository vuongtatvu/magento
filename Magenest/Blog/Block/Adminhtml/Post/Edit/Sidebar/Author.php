<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Block\Adminhtml\Post\Edit\Sidebar;

use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magenest\Blog\Model\Post\Attribute\Source\Author as AuthorSource;
use Magento\Backend\Block\Widget\Context;
use Magento\Backend\Block\Widget\Form;

/**
 * Class Author
 * @package Magenest\Blog\Block\Adminhtml\Post\Edit\Sidebar
 */
class Author extends Form
{
    /**
     * @var AuthorSource
     */
    protected $authorSource;

    /**
     * @var FormFactory
     */
    protected $formFactory;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @param AuthorSource $authorSource
     * @param FormFactory  $formFactory
     * @param Registry     $registry
     * @param Context      $context
     */
    public function __construct(
        AuthorSource $authorSource,
        FormFactory $formFactory,
        Registry $registry,
        Context $context
    ) {
        $this->authorSource = $authorSource;
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
        /** @var \Magenest\Blog\Model\Post $post */
        $post = $this->registry->registry('current_model');
        $form = $this->formFactory->create();
        $this->setForm($form);
        $fieldset = $form->addFieldset('tags_fieldset', [
            'class'  => 'blog__post-fieldset',
            'legend' => __('Author'),
        ]);
        $fieldset->addField('author_id', 'select', [
            'name'   => 'post[author_id]',
            'value'  => $post->getAuthorId(),
            'values' => $this->authorSource->toOptionArray()
        ]);

        return parent::_prepareForm();
    }
}
