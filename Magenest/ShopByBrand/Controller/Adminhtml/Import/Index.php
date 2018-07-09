<?php
/**
 * Created by PhpStorm.
 * User: thien
 * Date: 15/09/2017
 * Time: 08:27
 */

namespace Magenest\ShopByBrand\Controller\Adminhtml\Import;

use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{

    /**
     * Dispatch request
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    /**
     * @var PageFactory
     */
    protected $_pageFactory;

    /**
     * Index constructor.
     *
     * @param Action\Context $context
     * @param PageFactory    $pageFactory
     */
    public function __construct(
        Action\Context $context,
        PageFactory $pageFactory
    ) {
    
        parent::__construct($context);
        $this->_pageFactory=$pageFactory;
    }

    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        // TODO: Implement execute() method.
        return $this->_pageFactory->create();
    }
}
