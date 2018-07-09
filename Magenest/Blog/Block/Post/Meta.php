<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Block\Post;

use Magenest\Blog\Model\Config;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context;


/**
 * Class Meta
 * @package Magenest\Blog\Block\Post
 */
class Meta extends AbstractBlock
{
    protected $comment;

    /**
     * @var \Magenest\Blog\Model\Url
     */
    protected $url;

    /**
     * @var \Magenest\Blog\Model\AuthorFactory
     */
    protected $author;

    /**
     * Meta constructor.
     * @param Config $config
     * @param Registry $registry
     * @param Context $context
     * @param \Magenest\Blog\Model\CommentFactory $commentFactory
     * @param \Magenest\Blog\Model\AuthorFactory $authorFactory
     * @param \Magenest\Blog\Model\Url $url
     */
    public function __construct(
        Config $config,
        Registry $registry,
        Context $context,
        \Magenest\Blog\Model\CommentFactory $commentFactory,
        \Magenest\Blog\Model\AuthorFactory $authorFactory,
        \Magenest\Blog\Model\Url $url
    ) {
        $this->author = $authorFactory;
        $this->comment = $commentFactory;
        $this->url = $url;
        parent::__construct($config, $registry, $context);
    }

    /**
     * @return \Magenest\Blog\Model\Post
     */
    public function getPost()
    {
        if ($this->hasData('post')) {
            return $this->getData('post');
        }

        return $this->registry->registry('current_blog_post');
    }

    /**
     * @return \Magenest\Blog\Model\Category
     */
    public function getCategory()
    {

        return $this->registry->registry('current_blog_category');
    }

    /**
     * @return string
     */
    public function getCommentProvider()
    {

        return $this->config->getCommentProvider();
    }

    /**
     * check whether customer login
     */
    public function checkCustomerLogin()
    {
        $customerSession = \Magento\Framework\App\ObjectManager::getInstance()->create('Magento\Customer\Model\Session');
        if ($customerSession->isLoggedIn()) {
            return 1;
        }
        return 0;
    }

    /**
     * @return string
     */
    public function getCustomerName(){
        $customerSession = \Magento\Framework\App\ObjectManager::getInstance()->create('Magento\Customer\Model\Session');

        return $customerSession->getCustomer()->getName();
    }

    /**
     * @return string
     */
    public function getCustomerEmail(){
        $customerSession = \Magento\Framework\App\ObjectManager::getInstance()->create('Magento\Customer\Model\Session');

        return $customerSession->getCustomer()->getEmail();
    }

    /**
     * @return string
     */
    public function getDisqusShortname()
    {

        return $this->config->getDisqusShortname();
    }

    /**
     * @param string $date
     * @return string
     */
    public function toDateFormat($date)
    {

        return date($this->config->getDateFormat(), strtotime($date));
    }

    /**
     * @return bool
     */
    public function isAddThisEnabled()
    {

        return $this->config->isAddThisEnabled();
    }

    /**
     * @return string
     */
    public function postComment()
    {

        return $this->getUrl('blog/post/comment');
    }

    /**
     * @return string
     */
    public function getPathPage()
    {

        return $this->url->getPostUrl($this->getPost());
    }

    /**
     * @return array
     */
    public function getCommentData()
    {
        $data = $this->comment->create()->getCollection()->addFieldToFilter('post_id', $this->getPost()->getId())->getData();

        return $data;
    }

    public function getAuthor()
    {
        $model = $this->author->create()->load($this->getPost()->getAuthor()->getAuthorId());

        return $model;
    }
    public function getNumberVisits()
    {
        $model = $this->getPost()->getData('entity_id');
        $collections = $this->getPost()->getCollection()->addAttributeToSelect('*')->getItems();
        foreach ($collections as $collection) {
            if($model == $collection->getData('entity_id')){
                $number = $collection->getData('count_visits');
            }
        }
        return $number;
    }
}
