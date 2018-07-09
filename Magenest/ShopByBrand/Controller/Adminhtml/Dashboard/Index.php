<?php
/**
 * Created by PhpStorm.
 * User: duccanh
 * Date: 08/11/2016
 * Time: 09:02
 */
namespace Magenest\ShopByBrand\Controller\Adminhtml\Dashboard;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;

/**
 * Class Index
 *
 * @package Magenest\ShopByBrand\Controller\Adminhtml\Dashboard
 */
class Index extends Action
{
    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->getConfig()->getTitle()->prepend(__('Dashboard'));
        return $resultPage;
    }
}
