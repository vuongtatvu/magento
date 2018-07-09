<?php

namespace Magenest\Vu\Controller\Vendor;
class Checkemail extends \Magento\Framework\App\Action\Action
{
    /**
     *    Index    action
     *
     * @return    $this
     */

    /**    @var    \Magento\Framework\View\Result\PageFactory */
    protected $resultPageFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory

    )
    {

        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    public function execute()
    {


        $email = $this->getRequest()->getParam('email');


        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $CustomerModel = $objectManager->create('Magento\Customer\Model\Customer');
        $CustomerModel->setWebsiteId(1);
        $CustomerModel->loadByEmail($email);

        if (($CustomerModel->getId()) > 0) {
            echo 'email exist';
        } else
            echo 'email ok';


    }
}