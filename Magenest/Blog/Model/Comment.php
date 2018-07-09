<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Model;

/**
 * Class Comment
 * @package Magenest\Blog\Model
 * @method int getCommentId()
 * @method string getName()
 * @method string getComment()
 * @method string getPostId()
 * @method string getEmail()
 * @method string getCreatedAt()
 * @method string getStoreId()
 */
class Comment extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Version constructor.
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

        $this->_init('Magenest\Blog\Model\ResourceModel\Comment');
    }
}
