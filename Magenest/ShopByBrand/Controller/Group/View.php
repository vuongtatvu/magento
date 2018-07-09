<?php
/**
 * Created by PhpStorm.
 * User: duccanh
 * Date: 02/11/2016
 * Time: 16:04
 */
namespace Magenest\ShopByBrand\Controller\Group;

/**
 * Class View
 *
 * @package Magenest\ShopByBrand\Controller\Group
 */
class View extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Magenest\ShopByBrand\Model\GroupFactory
     */
    protected $_group;

    /**
     * @param \Magento\Framework\App\Action\Context      $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magenest\ShopByBrand\Model\GroupFactory $groupFactory
    ) {
        $this->_group = $groupFactory;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * Execute
     *
     * @return void
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('group_id');
        if (!$id) {
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath("*/*/view");
        }
        $brand=$this->_group->create()->load($id);
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set($brand->getName());
        return $resultPage;
    }
}
