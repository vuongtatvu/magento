<?php
namespace Magenest\Migrate\Controller\Adminhtml\Index;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Quote\Api\CartManagementInterface;

class ImportOrder extends \Magento\Backend\App\Action
{

    protected $resultPageFactory;
    protected $csv;

    /**
     * @var \Magento\Framework\Module\Dir\Reader
     */
    protected $moduleDirReader;

    protected $_storeManager;
    protected $cartRepositoryInterface;
    protected $cartManagementInterface;
    protected $customerFactory;
    protected $customerRepository;
    protected $quote;
    protected $quoteManagement;
    protected $_order;
    protected $_productRepository;


    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\File\Csv $csv,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Module\Dir\Reader $moduleDirReader,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Quote\Model\QuoteFactory $quote,
        \Magento\Quote\Model\QuoteManagement $quoteManagement,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Sales\Model\Order $order,
        \Magento\Quote\Api\CartRepositoryInterface $cartRepositoryInterface,
        \Magento\Quote\Api\CartManagementInterface $cartManagementInterface,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
    )
    {
        $this->csv = $csv;
        $this->resultPageFactory = $resultPageFactory;
        $this->moduleDirReader = $moduleDirReader;
        $this->_storeManager = $storeManager;
        $this->quote = $quote;
        $this->quoteManagement = $quoteManagement;
        $this->customerFactory = $customerFactory;
        $this->customerRepository = $customerRepository;
        $this->_order = $order;
        $this->cartRepositoryInterface = $cartRepositoryInterface;
        $this->cartManagementInterface = $cartManagementInterface;
        $this->_productRepository = $productRepository;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultPage = $this->resultPageFactory->create();

        $resultPage->getConfig()->getTitle()->prepend(__('Mirgate Import Order'));
        if (isset($_FILES)) {
            if (isset($_FILES['order']) && $_FILES['order']['tmp_name'] != '') {
                $this->import($_FILES['order']);
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

        $csvDatas = $this->csv->getData($file['tmp_name']);
        $idtmp = 0;

        $productCollection = $this->_objectManager->create('\Magento\Catalog\Model\ResourceModel\Product\Collection');

        for ($i = 1; $i < count($csvDatas); $i++) {
            if ($idtmp == $csvDatas[$i][0]) {
                continue;
            }
            $idtmp = $csvDatas[$i][0];
            $collection = $productCollection->addAttributeToFilter('woocommerce_product_id', $csvDatas[$i][30])->getFirstItem();
            $items = array([$collection->getEntityId(), $collection->getSku(), $csvDatas[$i][32], $csvDatas[$i][34], $csvDatas[$i][35], $csvDatas[$i][36]]);
            for ($j = $i + 1; $j < count($csvDatas); $j++) {
                if ($idtmp == $csvDatas[$j][0]) {
                    $collection = $productCollection->addAttributeToSelect('*')->addAttributeToFilter('woocommerce_product_id', $csvDatas[$j][30])->getFirstItem();
                    $prd = [$collection->getEntityId(), $collection->getSku(), $csvDatas[$i][32], $csvDatas[$i][34], $csvDatas[$i][35], $csvDatas[$i][36]];
                    array_push($items, $prd);
                } else {
                    break;
                }
            }

            switch ($csvDatas[$i][5]) {
                case "wc-processing":
                    $status = 'processing';
                    $state = 'processing';
                    break;
                case "wc-on-hold":
                    $status = 'holed';
                    $state = 'holed';
                    break;
                case "wc-completed":
                    $status = 'complete';
                    $state = 'complete';
                    break;
                case "wc-cancelled":
                    $status = 'canceled';
                    $state = 'canceled';
                    break;
                case "wc-refunded":
                    $status = 'complete';
                    $state = 'complete';
                    break;
                case "wc-failed":
                    $status = 'fraud';
                    $state = 'pending_payment';
                    break;
                default :
                    $status = 'pending';
                    $state = 'new';
                    break;
            }

            if ($csvDatas[$i][7] == "Credit Card") {
                $paymentMethod = 'braintree';
            } elseif ($csvDatas[$i][7] == "PayPal" || $csvDatas[$i][7] == "PayPal Express") {
                $paymentMethod = 'paypal_express';
            } else {
                $paymentMethod = 'checkmo';
            }

            $colectionCustomer = $this->customerFactory->create();
            $colectionCustomer->setWebsiteId(2);
            $colectionCustomer->loadByEmail($csvDatas[$i][9]);// load customet by email address
            if (!$colectionCustomer->getEntityId()) {
                $customer = array(null, 0, $csvDatas[$i][9], $csvDatas[$i][10], $csvDatas[$i][11]);
            } else {
                $customer = array($colectionCustomer->getEntityId(), $colectionCustomer->getGroupId(),
                    $colectionCustomer->getEmail(), $colectionCustomer->getFirstname(),
                    $colectionCustomer->getLastname());
            }

            $resource = $this->_objectManager->create('Magento\Config\Model\ResourceModel\Config\Data\Collection');
            $connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);


            $values = $connection->fetchAll("SELECT * FROM quote ORDER BY entity_id DESC LIMIT 1");
            $reservedOrderId = $values[0]['reserved_order_id'] + 1;

//        create quote
            $sql = "INSERT INTO quote (entity_id, store_id, created_at,grand_total, base_grand_total, 
            customer_id, customer_group_id, customer_email, customer_firstname, customer_lastname, 
            reserved_order_id, subtotal, base_subtotal, subtotal_with_discount,base_subtotal_with_discount) 
            VALUES (null,2,'" . $csvDatas[$i][2] . "'," . $csvDatas[$i][8] . "," . $csvDatas[$i][8] . "," .
                $customer[0] . "," . $customer[1] . ",'" . $customer[2] . "','" . $customer[3] . "','" .
                $customer[4] . "','" . $reservedOrderId . "'," .
                ($csvDatas[$i][8] + $csvDatas[$i][52] - $csvDatas[$i][47]) . "," .
                ($csvDatas[$i][8] + $csvDatas[$i][52] - $csvDatas[$i][47]) . "," .
                abs($csvDatas[$i][52]) . "," . abs($csvDatas[$i][52]) . ")";

            $connection->query($sql);

            $values = $connection->fetchAll("SELECT * FROM quote ORDER BY entity_id DESC LIMIT 1");
            $quoteId = $values[0]['entity_id'];

//        create quote_address shipping

            $sql = "INSERT INTO quote_address(address_id, quote_id, 
            customer_id, address_type, email, firstname, lastname, company,
             street, city, postcode, country_id, telephone, same_as_billing, 
             shipping_method, shipping_description,  subtotal,base_subtotal,shipping_amount, 
             base_shipping_amount, discount_amount, base_discount_amount, grand_total,base_grand_total) 
              VALUES (null," . $quoteId . "," . $customer[0] . ",'shipping','" . $customer[2] . "','" . $customer[3] . "','" . $customer[4] . "','" .
                $csvDatas[$i][23] . "','" . $csvDatas[$i][24] . "','" . $csvDatas[$i][26] . "'," . $csvDatas[$i][27] . ",'" .
                $csvDatas[$i][28] . "'," . $csvDatas[$i][20] . ",1,'" . $csvDatas[$i][46] . "','" .
                $csvDatas[$i][46] . "'," . ($csvDatas[$i][8] + $csvDatas[$i][52] - $csvDatas[$i][47]) . "," .
                ($csvDatas[$i][8] + $csvDatas[$i][52] - $csvDatas[$i][47]) . "," . $csvDatas[$i][47] . "," .
                $csvDatas[$i][47] . "," . abs($csvDatas[$i][52]) . "," . abs($csvDatas[$i][52]) . "," .
                $csvDatas[$i][8] . "," . $csvDatas[$i][8] . ")";


            $connection->query($sql);

            //        create quote_address billing
            $sql = "INSERT INTO quote_address(address_id, quote_id,
            customer_id, email, firstname, lastname, company, street, city, 
            country_id, telephone,address_type) VALUES (null," . $quoteId . "," .
                $customer[0] . ",'" . $customer[2] . "','" . $customer[3] . "','" . $customer[4] . "','" . $csvDatas[$i][12] . "','" . $csvDatas[$i][13] . "','" .
                $csvDatas[$i][15] . "','" . $csvDatas[$i][17] . "'," . $csvDatas[$i][20] . "," . "'billing')";

            $connection->query($sql);

            $values = $connection->fetchAll("SELECT * FROM quote_address ORDER BY address_id DESC LIMIT 2");
            $addressId = array($values[0]['address_id'], $values[1]['address_id']);

//        create quote_item product
            foreach ($items as $item) {

                $sql = "INSERT INTO quote_item(item_id, quote_id,
                product_id, store_id,sku, name , qty, price, base_price,
                row_total, base_row_total,product_type) VALUES (null," . $quoteId . "," .
                    $item[0] . ",2,'" . $item[1] . "','" . $item[2] . "'," . $item[3] . "," .
                    $item[4] . "," . $item[4] . "," . $item[5] . "," . $item[5] . ",'simple')";

                $connection->query($sql);
            }

//        create quote_payment
            $connection->query("INSERT INTO quote_payment(payment_id, quote_id, method) 
            VALUES (null," . $quoteId . ",'" . $paymentMethod . "')");

//        create quote_shippng_rate

            $sql = "INSERT INTO quote_shipping_rate(rate_id, address_id, 
            carrier, carrier_title, code, method, price, method_title) 
            VALUES (null ," . $addressId[0] . ",'" . $csvDatas[$i][46] . "','" . $csvDatas[$i][46] . "','" . $csvDatas[$i][46] . "','" .
                $csvDatas[$i][46] . "'," . $csvDatas[$i][47] . ",'" . $csvDatas[$i][46] . "')";

            $connection->query($sql);

//        create sales_order

            $sql = "INSERT INTO sales_order(entity_id, state, status , 
            shipping_description, store_id, customer_id, base_discount_amount, base_grand_total,
             base_shipping_amount, base_subtotal,base_total_refunded, 
             discount_amount, grand_total, shipping_amount, subtotal,total_refunded, 
              billing_address_id, shipping_address_id,customer_group_id,
              increment_id, customer_email, customer_firstname, customer_lastname, shipping_method) 
              VALUES (null ,'" . $state . "','" . $status . "','" . $csvDatas[$i][46] . "',2," . $customer[0] .
                "," . abs($csvDatas[$i][52]) . "," . $csvDatas[$i][8] . " ," . $csvDatas[$i][47] . "," .
                ($csvDatas[$i][8] + $csvDatas[$i][52] - $csvDatas[$i][47]) . "," . $csvDatas[$i][59] . "," .
                abs($csvDatas[$i][52]) . "," . $csvDatas[$i][8] . "," . $csvDatas[$i][47] . "," .
                ($csvDatas[$i][8] + $csvDatas[$i][52] - $csvDatas[$i][47]) . "," . $csvDatas[$i][59] . "," .
                $addressId[0] . "," . $addressId[1] . "," . $customer[1] . ",'" . $reservedOrderId . "','" . $customer[2] . "','" .
                $customer[3] . "','" . $customer[4] . "','" . $csvDatas[$i][46] . "')";

            $connection->query($sql);

            $values = $connection->fetchAll("SELECT * FROM sales_order ORDER BY entity_id DESC LIMIT 1");
            $orderId = $values[0]['entity_id'];

//        create sales_order_address shipping

            $sql = "INSERT INTO sales_order_address(entity_id, parent_id, 
            quote_address_id, customer_id, postcode, lastname, street, 
            city, email, telephone, country_id, firstname,company) 
            VALUES (null ," . $orderId . "," . $addressId[1] . "," . $customer[0] . "," . $csvDatas[$i][27] . ",'" .
                $customer[4] . "','" . $csvDatas[$i][24] . "','" . $csvDatas[$i][26] . "','" . $customer[2] . "','" . $csvDatas[$i][20] . "','" .
                $csvDatas[$i][28] . "','" . $customer[3] . "','" . $csvDatas[$i][23] . "')";

            $connection->query($sql);

//        create sales_order_address billing

            $sql = "INSERT INTO sales_order_address(entity_id, parent_id, 
            quote_address_id, customer_id, postcode, lastname, street, 
            city, email, telephone, country_id, firstname,company) 
            VALUES (null ," . $orderId . "," . $addressId[0] . "," . $customer[0] . ",'" . $csvDatas[$i][16] . "','" .
                $customer[4] . "','" . $csvDatas[$i][13] . "','" . $csvDatas[$i][15] . "','" . $customer[2] . "','" . $csvDatas[$i][20] . "','" .
                $csvDatas[$i][17] . "','" . $customer[3] . "','" . $csvDatas[$i][12] . "')";

            $connection->query($sql);

//        create sales_order_grid

            $sql = "INSERT INTO sales_order_grid(entity_id, status,
            store_id, store_name, customer_id, base_grand_total,grand_total,
            increment_id, shipping_name, billing_name, billing_address, shipping_address,
            customer_email, customer_group, subtotal, shipping_and_handling,
            customer_name, payment_method, total_refunded)
            VALUES (
            " . $orderId . ",' " . $status . "', 2, 'JB Cookie Cutters jbcookiecutter' , " . $customer[0] . ", " .
                $csvDatas[$i][8] . ", " . $csvDatas[$i][8] . ", '" . $reservedOrderId . "', '" .
                $csvDatas[$i][21] . ' ' . $csvDatas[$i][22] . "', '" .
                $csvDatas[$i][10] . ' ' . $csvDatas[$i][11] . "',' " .
                $csvDatas[$i][24] . "', '" . $csvDatas[$i][13] . "', '" . $customer[2] . "', " . $customer[1] . ", " .
                ($csvDatas[$i][8] + $csvDatas[$i][52] - $csvDatas[$i][47]) . ", '" .
                $csvDatas[$i][47] . "', '" . $csvDatas[$i][21] . ' ' . $csvDatas[$i][22] . "', '" .
                $paymentMethod . "', " . $csvDatas[$i][59] . ")";


            $connection->query($sql);

//        create sales_order_payment

            $sql = "INSERT INTO sales_order_payment(entity_id, parent_id,
            amount_refunded,base_shipping_amount, shipping_amount, base_amount_ordered,
            base_amount_refunded, amount_ordered,method)
            VALUES (null , " . $orderId . ", " . $csvDatas[$i][59] . ", " . $csvDatas[$i][47] . ", " . $csvDatas[$i][47] . ", " .
                $csvDatas[$i][59] . ", " . $csvDatas[$i][59] . ", " . $csvDatas[$i][59] . ", '" . $paymentMethod . "')";

            echo $sql;
            $connection->query($sql);


//        create sales_order_item product
            foreach ($items as $item) {

                $sql = "INSERT INTO sales_order_item(item_id, order_id,
                store_id, product_id, sku, name, price, base_price, discount_amount,
                base_discount_amount, amount_refunded,base_amount_refunded, row_total, base_row_total)
                 VALUES (null , " . $orderId . ", 2, " . $item[0] . ", '" . $item[1] . "',' " . $item[2] . "', " .
                    $item[4] . ", " . $item[4] . ", " . abs($csvDatas[$i][52]) . ", " . abs($csvDatas[$i][52]) . ", " .
                    $csvDatas[$i][59] . ", " . $csvDatas[$i][59] . ", " . $item[5] . ", " . $item[5] . ")";

                $connection->query($sql);
            }

        }

    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenest_Migrate::migrate');
    }


}

