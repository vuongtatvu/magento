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
 * Class Tags
 * @package Magenest\Blog\Block\Adminhtml\Post\Edit\Sidebar
 */
class Tags extends Form
{
    /**
     * @var string
     */
    protected $_template = 'post/edit/sidebar/tags.phtml';

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
     * @return \Magenest\Blog\Model\ResourceModel\Tag\Collection
     */
    public function getTags()
    {
        /** @var \Magenest\Blog\Model\Post $post */
        $post = $this->registry->registry('current_model');

        return $post->getTags();
    }
}
