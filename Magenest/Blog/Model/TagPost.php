<?php

namespace Magenest\Blog\Model;

/**
 * Class TagPost
 * @package Magenest\Blog\Model
 * @method int getTagId()
 * @method int getPostId()
 * @method int getStoreId()
 */
class TagPost extends \Magento\Framework\Model\AbstractModel
{
    /**
     * TagPost constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource =
        null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection =
        null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    public function _construct()
    {

        $this->_init('Magenest\Blog\Model\ResourceModel\TagPost');

    }
}