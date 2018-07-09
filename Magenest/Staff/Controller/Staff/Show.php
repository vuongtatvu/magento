<?php
namespace Magenest\Staff\Controller\Staff;

use Magento\Framework\App\Action\Context;

class Show extends \Magento\Framework\App\Action\Action
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

        $search = $this->getRequest()->getParam('search');

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->create('Magento\Config\Model\ResourceModel\Config\Data\Collection');
        $connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        $values = $connection->fetchAll("select * from magenest_staff WHERE nick_name LIKE '%$search%' ");

        $message ='';
        foreach ($values as $items){
            $message .= " ".$items['nick_name'];
        }

        return $this->resultJsonFactory->create()->setData([
            'message' => $message
        ]);
    }
}
