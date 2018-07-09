<?php
/**
 * Created by PhpStorm.
 * User: hiennq
 * Date: 11/7/16
 * Time: 09:48
 */

namespace Magenest\MapList\Controller\Map;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Listmap extends Action
{
    protected $_resultPageFactory;

    public function __construct(
        Context $context,
        PageFactory $pageFactory
    ) {
        $this->_resultPageFactory = $pageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $this->_view->loadLayout();
        if ($block = $this->_view->getLayout()->getBlock('maplist_map_listmap')) {
            $block->setRefererUrl($this->_redirect->getRefererUrl());
        }
        $this->_view->getPage()->getConfig()->getTitle()->set(__('Store Locator'));
        $this->_view->renderLayout();
    }
}
