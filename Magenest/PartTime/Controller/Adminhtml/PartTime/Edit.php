<?php
namespace Magenest\PartTime\Controller\Adminhtml\PartTime;

use    Magento\Backend\App\Action\Context;
use    Magento\Framework\View\Result\PageFactory;

class Edit extends \Magento\Backend\App\Action
{
    protected $resultPageFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();


        if ($_POST) {



            $member_id = $this->getRequest()->getParam('member_id');

            $name = $this->getRequest()->getParam('name');
            $address = $this->getRequest()->getParam('address');
            $phone = $this->getRequest()->getParam('phone');
            $created_time = $this->getRequest()->getParam('created_time');
            $updated_time = $this->getRequest()->getParam('updated_time');

            $tmpPhone = "";

            for($i = 0; $i<strlen($phone);$i++){
                if(($phone[$i]>='0' && $phone[$i]<='9')|| $phone[$i] =='+'){
                    $tmpPhone .= $phone[$i];
                }
            }
            
            $phone = $tmpPhone;


            $parttimeCollection = $this->_objectManager->create('Magenest\PartTime\Model\PartTime')->load($member_id);

            $parttimeCollection->setName($name);
            $parttimeCollection->setAddress($address);
            $parttimeCollection->setPhone($phone);
            $parttimeCollection->setCreatedTime($created_time);
            $parttimeCollection->setUpdatedTime($updated_time);
            $parttimeCollection->save();
            $this->messageManager->addSuccess(__('You edit movie success.'));



            $this->_redirect('parttime/parttime');


        }
        return $resultPage;

    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenest_PartTime::parttime');
    }
}