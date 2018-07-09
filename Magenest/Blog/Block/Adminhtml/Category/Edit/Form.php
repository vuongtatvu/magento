<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Block\Adminhtml\Category\Edit;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Data\FormFactory;

/**
 * Class Form
 * @package Magenest\Blog\Block\Adminhtml\Category\Edit
 */
class Form extends \Magento\Backend\Block\Widget\Form
{
    /**
     * @var FormFactory
     */
    protected $formFactory;

    /**
     * {@inheritdoc}
     * @param FormFactory $formFactory
     * @param Context     $context
     */
    public function __construct(
        FormFactory $formFactory,
        Context $context
    ) {
        $this->formFactory = $formFactory;
        parent::__construct($context);
    }

    /**
     * @return $this
     */
    protected function _prepareForm()
    {
        $form = $this->formFactory->create()->setData([
            'id'      => 'edit_form',
            'action'  => $this->getUrl('*/*/save', ['id' => $this->getRequest()->getParam('id')]),
            'method'  => 'post',
            'enctype' => 'multipart/form-data',
        ]);

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
