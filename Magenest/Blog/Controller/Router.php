<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Controller;

use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\RouterInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Event\ManagerInterface as EventManagerInterface;
use Magenest\Blog\Model\Url;

/**
 * Class Router
 * @package Magenest\Blog\Controller
 */
class Router implements RouterInterface
{
    /**
     * @var ActionFactory
     */
    protected $actionFactory;

    /**
     * @var EventManagerInterface
     */
    protected $eventManager;

    /**
     * @var Url
     */
    protected $url;

    /**
     * @param Url                   $url
     * @param ActionFactory         $actionFactory
     * @param EventManagerInterface $eventManager
     */
    public function __construct(
        Url $url,
        ActionFactory $actionFactory,
        EventManagerInterface $eventManager
    ) {
        $this->url = $url;
        $this->actionFactory = $actionFactory;
        $this->eventManager = $eventManager;
    }

    /**
     * {@inheritdoc}
     */
    public function match(RequestInterface $request)
    {
        /** @var \Magento\Framework\App\Request\Http $request */
        $identifier = trim($request->getPathInfo(), '/');
        $this->eventManager->dispatch('core_controller_router_match_before', [
            'router'    => $this,
            'condition' => new DataObject(['identifier' => $identifier, 'continue' => true])
        ]);
        $pathInfo = $request->getPathInfo();
        $result = $this->url->match($pathInfo);
        if ($result) {
            $params = $result->getParams();
            $request
                ->setModuleName($result->getModuleName())
                ->setControllerName($result->getControllerName())
                ->setActionName($result->getActionName())
                ->setParams($params);

            return $this->actionFactory->create(
                'Magento\Framework\App\Action\Forward',
                ['request' => $request]
            );
        }

        return false;
    }
}
