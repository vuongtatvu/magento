<?php

namespace Magenest\WeddingEvent\Controller\WeddingEvent;

use Magento\Framework\App\Action\Context;
class    Show extends \Magento\Framework\App\Action\Action
{
    /**
     *    Index    action
     *
     * @return    $this
     */

    /**    @var    \Magento\Framework\View\Result\PageFactory */

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

        if ($this->getRequest()->isAjax()) {

            $brideemail = $this->getRequest()->getParam('brideEmail');
            $groomemail = $this->getRequest()->getParam('groomEmail');
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

            $customer = $objectManager->create('Magenest\WeddingEvent\Model\ResourceModel\WeddingEvent\Collection')->load();
            $wedding = $customer->getData();

            foreach ($wedding as $event) {
                $a = $event['bride_email'];
                $b = $event['groom_email'];
                if ($a == $brideemail && $b == $groomemail) {
                    return $this->resultJsonFactory->create()->setData([
                    'event' => $event
                    ]);
                    
                }
            }
            return $this->resultJsonFactory->create()->setData([
            'message' => "Not Found"
            ]);
        }
    }
}