<?php

namespace Magenest\PartTime\Controller\Part;

class Edit extends \Magento\Framework\App\Action\Action
{

    public function __construct(
        \Magento\Framework\App\Action\Context $context
    )
    {
        parent::__construct($context);
    }

    public function execute()
    {

        if ($_POST) {

            $member_id = $this->getRequest()->getParam('member_id');

            $name = $this->getRequest()->getParam('name');
            $address = $this->getRequest()->getParam('address');
            $phone = $this->getRequest()->getParam('phone');
            $created_time = $this->getRequest()->getParam('created_time');
            $updated_time = $this->getRequest()->getParam('updated_time');

            $tmpPhone = "";

            for ($i = 0; $i < strlen($phone); $i++) {
                if (($phone[$i] >= '0' && $phone[$i] <= '9') || $phone[$i] == '+') {
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

            $this->messageManager->addSuccess(__('You edit member success.'));

            return $this->_redirect('parttime/part/time');

        }
        return $this->_redirect('parttime/part/time');
    }
}