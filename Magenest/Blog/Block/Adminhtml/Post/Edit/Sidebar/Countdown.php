<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Block\Adminhtml\Post\Edit\Sidebar;

use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magento\Backend\Block\Widget\Context;



/**
 * Class Publish
 * @package Magenest\Blog\Block\Adminhtml\Post\Edit\Sidebar
 */
class Countdown extends \Magento\Backend\Block\Widget\Form
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
     * @param Status      $status
     * @param FormFactory $formFactory
     * @param Registry    $registry
     * @param Context     $context
     * @param array       $data
     */
    public function __construct(
        FormFactory $formFactory,
        Registry $registry,
        Context $context,
        array $data = []
    ) {
        $this->formFactory = $formFactory;
        $this->registry = $registry;
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
        $fieldset = $form->addFieldset('countdown_fieldset', [
            'class'  => 'blog__post-fieldset',
            'legend' => __('Countdown for post'),
        ]);
        $fieldset->addField('start_countdown', 'date', [
            'label'       => __('Date start'),
            'name'        => 'post[start_countdown]',
            'value'       => $post->getStartCountdown(),
            'date_format' => 'd MMM y',
            'time_format' => 'h:mm a',
        ]);
        $fieldset->addField('end_countdown', 'date', [
            'label'       => __('Date end'),
            'name'        => 'post[end_countdown]',
            'value'       => $post->getEndCountdown(),
            'date_format' => 'd MMM y',
            'time_format' => 'h:mm a',
        ]);

        return parent::_prepareForm();
    }
}
