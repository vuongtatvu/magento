<?php
namespace Magenest\Staff\Controller\Staff;

use Magento\Framework\App\Action\Context;

class Handle extends \Magento\Framework\App\Action\Action
{
    protected $resultJsonFactory;

    public function __construct(
        Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    )
    {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
    }

    public function execute()
    {

        $customerId = $this->getRequest()->getParam('customerId');
        $type = $this->getRequest()->getParam('type');
        $nickname = $this->getRequest()->getParam('nickname');
        $message = "true";

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $customer = $objectManager->create('\Magento\Customer\Model\Customer')->load($customerId);
        $customer->setStaffType($type);
        $customer->save();

        $staffCollection = $objectManager->create('\Magenest\Staff\Model\ResourceModel\Staff\Collection');
        $staffcc = $staffCollection->addFieldToFilter('customer_id', $customerId)->load();

        $a = $staffcc->getData();

        if ($a != null) {
            $staffId = $a[0]['id'];

            if ($staffId != null) {
                $staff = $objectManager->create('Magenest\Staff\Model\Staff')->load($staffId);
                $staff->setNickName($nickname);
                $staff->setType($type);
                $staff->save();
            }
        } else {
            $message = "false";
        }

        return $this->resultJsonFactory->create()->setData([
            'message' => $message
        ]);
    }
}
