<?php

namespace Magenest\Vu\Controller\Index;

use Magento\Framework\App\Action\Context;

class Show extends \Magento\Framework\App\Action\Action
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

            $customer_id = $this->getRequest()->getParam('customer_id');
            
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

            $vendor = $objectManager->create('Magenest\Vu\Model\Vendor')->load('customer_id',$customer_id);

        }
        return $this->resultJsonFactory->create()->setData([
            'email' => $vendor['email'],
            'firstname' => $vendor['first_name'],
            'lastname' => $vendor['last_name']
        ]);
    }
    
}