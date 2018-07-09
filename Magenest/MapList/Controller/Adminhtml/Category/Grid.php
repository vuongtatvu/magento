<?php
/**
 * Created by PhpStorm.
 * User: hiennq
 * Date: 10/14/16
 * Time: 08:16
 */

namespace Magenest\MapList\Controller\Adminhtml\Category;

use Magenest\MapList\Controller\Adminhtml\Category;
use Magento\Backend\App\Action;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Psr\Log\LoggerInterface;

class Grid extends Category
{
    protected $resultRawFactory;
    protected $layoutFactory;

    public function __construct(
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        Action\Context $context,
        PageFactory $resultPageFactory,
        Registry $coreRegistry,
        \Magenest\MapList\Model\CategoryFactory $categoryFactory,
        LoggerInterface $logger
    ) {
        $this->layoutFactory = $layoutFactory;
        $this->resultRawFactory = $resultRawFactory;
        parent::__construct($context, $resultPageFactory, $coreRegistry, $categoryFactory, $logger);
    }

    /**
     * @return \Magento\Framework\Controller\Result\Raw|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $resultRaw = $this->resultRawFactory->create();

        return $resultRaw->setContents(
            $this->layoutFactory->create()->createBlock(
                'Magenest\MapList\Block\Adminhtml\Category\Edit\Tab\AddLocation\Grid',
                'location.tab.list'
            )->toHtml()
        );
    }
}
