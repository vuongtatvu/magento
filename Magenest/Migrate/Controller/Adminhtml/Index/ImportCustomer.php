<?php
namespace Magenest\Migrate\Controller\Adminhtml\Index;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\Escaper;
use Magento\Framework\Mail\Template\TransportBuilder;
use Symfony\Component\Config\Definition\Exception\Exception;

class ImportCustomer extends \Magento\Backend\App\Action
{

    protected $resultPageFactory;
    protected $csv;

    /**
     * @var \Magento\Framework\Module\Dir\Reader
     */
    protected $moduleDirReader;

    protected $inlineTranslation;
    protected $escaper;
    protected $transportBuilder;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\File\Csv $csv,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Module\Dir\Reader $moduleDirReader,
        StateInterface $inlineTranslation,
        Escaper $escaper,
        TransportBuilder $transportBuilder
    )
    {
        $this->csv = $csv;
        $this->resultPageFactory = $resultPageFactory;
        $this->moduleDirReader = $moduleDirReader;
        $this->inlineTranslation = $inlineTranslation;
        $this->escaper = $escaper;
        $this->transportBuilder = $transportBuilder;

        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultPage = $this->resultPageFactory->create();

        $resultPage->getConfig()->getTitle()->prepend(__('Mirgate Import Customer'));
        if (isset($_FILES)) {
            if (isset($_FILES['customer']) && $_FILES['customer']['tmp_name'] != '') {
                $this->import($_FILES['customer']);
            } else {
                $this->messageManager->addErrorMessage(__('Not File CSV.'));
            }
        }
        $resultRedirect->setPath('*/*/index');
        return $resultRedirect;
    }

    public function import($file)
    {
        if (!isset($file['tmp_name']))
            throw new \Magento\Framework\Exception\LocalizedException(__('Invalid file upload attempt.'));

        $csvData = $this->csv->getData($file['tmp_name']);

        array_splice($csvData, 0, 1);
        array_walk($csvData, array($this, 'saveCustomer'));
    }

    public function saveCustomer($key, $value)
    {
        $customerCollection = $this->_objectManager->create('\Magento\Customer\Model\ResourceModel\Customer\Collection')->addAttributeToSelect('*')->addAttributeToFilter('email', $key[1])->load();
        $arrCustomer = $customerCollection->getData();

        if ($arrCustomer == null) {
            $email = $key[1];
            $customer = $this->_objectManager->create('Magento\Customer\Model\Customer');
            $customer->setEmail($email);
            $customer->setFirstname($key[3] ? $key[3] : 'none');
            $customer->setLastname($key[4] ? $key[4] : 'none');
            $customer->save();
        } else {
            $customercr = $arrCustomer[0];
            $customer = $this->_objectManager->create('Magento\Customer\Model\Customer')->load($customercr['entity_id']);
            $customer->setFirstname($key[3] ? $key[3] : 'none');
            $customer->setLastname($key[4] ? $key[4] : 'none');
            $customer->save();
        }

        $customerCollection = $this->_objectManager->create('\Magento\Customer\Model\ResourceModel\Customer\Collection')->addAttributeToSelect('*')->addAttributeToFilter('email', $key[1])->load();
        $arrCustomer = $customerCollection->getData();
        $customercr = $arrCustomer[0];
        $customerFlat = $this->_objectManager->create('Magenest\Migrate\Model\CustomerFlat')->load($customercr['entity_id']);
        $customerFlat->setBillingFirstname($key[5]);
        $customerFlat->setBillingLastname($key[6]);
        $customerFlat->setBillingCompany($key[7]);
        $customerFlat->setBillingStreet($key[8]);
        $customerFlat->setBillingCity($key[10]);
        $customerFlat->setBillingPostcode($key[11]);
        $customerFlat->setBillingCountryId($key[12]);
        $customerFlat->setBillingTelephone($key[15]);
        $customerFlat->save();

    }

    public function getValue()
    {
        $filePath = $this->moduleDirReader->getModuleDir('etc', 'Magenest_Migrate') . '/wishlist.csv';
        $parsedArray = $csvData = $this->csv->getData($filePath);
        return $parsedArray;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenest_Migrate::migrate');
    }

}