<?php
namespace Magenest\Ajax\Controller\Ajax;

use Magento\Framework\App\Action\Context;

class Handle extends \Magento\Framework\App\Action\Action
{
    protected $resultJsonFactory;

    public function __construct(
        Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory //can co cai nay de ajax doc duoc response
    )
    {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
    }

    public function execute()
    {
       // $x = $this->getRequest()->getParams();//day la data ajax gui di

//        $email = $this->getRequest()->getParams('email');
//
//        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
//
//        $customer = $objectManager->create('\Magento\Customer\Model\ResourceModel\Customer\Collection')->addAttributeToSelect($email)->load();
//
//        foreach ($customer as $items) {
//            foreach ($email as $mail) {
//                if ($mail === $items->getEmail()) {
//                    $name = $items->getFirstname() . ' ' . $items->getLastname();
//                }
//            }
//        }
//        return $this->resultJsonFactory->create()->setData([
//            'message' => $name
//        ]);


        if ($this->getRequest()->isAjax()) {

            $email = $this->getRequest()->getParam('email');

            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

            $customer = $objectManager->create('\Magento\Customer\Model\ResourceModel\Customer\Collection')->getItemsByColumnValue('email', $email);

            foreach ($customer as $items) {
                echo 'Name: ' . $items->getFirstname() . ' ' . $items->getLastname() . '<br>';
            }

        }
    }
}
