<?php

namespace Magenest\Cybergame\Controller\Product;

class Checkmail extends \Magento\Framework\App\Action\Action
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
        \Magento\Framework\Controller\Result\JsonFactory $resultPageFactory

    )
    {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    public function execute()
    {

        if ($this->getRequest()->isAjax()) {

            $email = $this->getRequest()->getParam('email');

            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            
            $customerCollection = $objectManager->create('\Magento\Customer\Model\ResourceModel\Customer\Collection');
            $vendor = $customerCollection->addAttributeToSelect('*')->addAttributeToFilter('email', $email)->load();

            if ($vendor->getData() != null) {
                $message = "Account was exist in our system. You are buying hour for this acocunt";
                $yes = 0;
            } else {
                $message = "We will create new account for you. Default password = 1. You should change the password after login";
                $yes = 1;
            }

            return $this->resultPageFactory->create()->setData([
                'message' => $message,
                'yes'=>$yes
            ]);
        }

    }
}