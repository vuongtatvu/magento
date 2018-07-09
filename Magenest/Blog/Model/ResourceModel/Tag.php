<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magenest\Blog\Model\Config;
use Magento\Framework\Filter\FilterManager;
use Magento\Framework\Model\AbstractModel;

/**
 * Class Tag
 * @package Magenest\Blog\Model\ResourceModel
 */
class Tag extends AbstractDb
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var FilterManager
     */
    protected $filter;

    /**
     * @param Config        $config
     * @param FilterManager $filter
     * @param Context       $context
     * @param string        $connectionName
     */
    public function __construct(
        Config $config,
        FilterManager $filter,
        Context $context,
        $connectionName = null
    ) {
        $this->config = $config;
        $this->filter = $filter;
        parent::__construct($context, $connectionName);
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init('magenest_blog_tag', 'tag_id');
    }

    /**
     * @param AbstractModel $tag
     * @return $this
     */
    protected function _beforeSave(AbstractModel $tag)
    {
        /** @var \Magenest\Blog\Model\Tag $tag */

        if (!$tag->getData('url_key')) {
            $tag->setData('url_key', $this->filter->translitUrl($tag->getName()));
        }

        return parent::_beforeSave($tag);
    }
}
