<?php

namespace Magenest\PartTime\Controller\Part;

use Magento\Framework\App\Action\Context;

class    Info extends \Magento\Framework\App\Action\Action
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

            $member_id = $this->getRequest()->getParam('member_id');

            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

            $member = $objectManager->create('Magenest\PartTime\Model\PartTime')->load($member_id)->getData();

        }
        return $this->resultJsonFactory->create()->setData([
            'member_id' => $member['member_id'],
            'name' => $member['name'],
            'address' => $member['address'],
            'phone' => $member['phone'],
            'created_time' => $member['created_time'],
            'updated_time' => $member['updated_time']
        ]);
    }
    
}