<?php
namespace Magenest\Avatar\Block\Account\Dashboard;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Webapi\Exception;

class Info extends \Magento\Framework\View\Element\Template
{
    protected $currentCustomer;
    protected $helperView;
    protected $customerFactory;

    public function __construct(
        Template\Context $context,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer,
        \Magento\Customer\Helper\View $helperView,
        array $data = [])
    {
        $this->customerFactory = $customerFactory;
        $this->currentCustomer = $currentCustomer;
        $this->helperView = $helperView;
        parent::__construct($context, $data);
    }

    public function getCustomer()
    {
        try {
            return $this->currentCustomer->getCustomer();
        } catch (NoSuchEntityException $entityException) {
            return null;
        }
    }

    public function getImage()
    {
        try {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $storeManager = $objectManager->get(\Magento\Store\Model\StoreManagerInterface::class);
            $mediaUrl = $storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
            $customer = $this->currentCustomer->getCustomer();
            $img = $customer->getCustomAttribute('image');
            if ($img) {
                $image_value = $customer->getCustomAttribute('image')->getValue();
                return $mediaUrl . 'customer' . $image_value;
            }
            return null;
        } catch (Exception $ex) {
            return NULL;
        }
    }

}