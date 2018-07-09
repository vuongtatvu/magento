<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Block\Html;

use Magento\Framework\App\ObjectManager;

/**
 * Class Pager
 * @package Magenest\Blog\Block\Html
 */
class Pager extends \Magento\Theme\Block\Html\Pager
{
    /**
     * @var \Magenest\Blog\Model\UrlInterface
     */
    protected $entity;

    /**
     * Retrieve page URL by defined parameters
     *
     * @param array $params
     * @return string
     */
    public function getPagerUrl($params = [])
    {
        $urlParams = [];
        $urlParams['_current'] = false;
        $urlParams['_escape']  = true;
        $urlParams['_query']   = $params;
        $path = $this->getUrl($this->getPath(), $urlParams);
        if ($this->getEntity()) {
            $path = $this->getEntity()->getUrl($urlParams);
        } elseif ($this->getRequest()->getControllerName() == 'search') {
            $url = ObjectManager::getInstance()->get('Magenest\Blog\Model\Url');
            $urlParams['_current'] = true;
            $path = $url->getSearchUrl($urlParams);
        }

        return $path;
    }

    /**
     * @return string
     */
    protected function getPath()
    {
        $config = ObjectManager::getInstance()->get('Magenest\Blog\Model\Config');

        return $config->getBaseRoute();
    }

    /**
     * @return \Magenest\Blog\Model\UrlInterface|null
     */
    public function getEntity()
    {

        return $this->entity;
    }

    /**
     * @param \Magenest\Blog\Model\UrlInterface $entity
     * @return $this
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }
}
