<?php
/**
 * Created by PhpStorm.
 * User: duccanh
 * Date: 08/11/2016
 * Time: 08:31
 */
namespace Magenest\ShopByBrand\Block\Adminhtml\Dashboard;

/**
 * Class Index
 *
 * @package Magenest\ShopByBrand\Block\Adminhtml\Dashboard
 */
class Index extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var string
     */
    protected $_template = 'dashboard/index.phtml';

    /**
     * @var \Magenest\ShopByBrand\Model\BrandFactory
     */
    protected $_brandFactory;

    /**
     * Index constructor.
     *
     * @param \Magento\Backend\Block\Template\Context  $context
     * @param \Magento\Framework\Registry              $registry
     * @param \Magento\Framework\Data\FormFactory      $formFactory
     * @param \Magenest\ShopByBrand\Model\BrandFactory $brandFactory
     * @param array                                    $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magenest\ShopByBrand\Model\BrandFactory $brandFactory,
        array $data = []
    ) {
    
        $this->_brandFactory    = $brandFactory;
        parent::__construct($context, $registry, $formFactory, $data);
    }


    /**
     * Get Brand Data
     *
     * @return array
     */
    public function getBrandData()
    {
        return $this->_brandFactory->create()->getCollection()->getData();
    }

    /**
     * Get Data Order Brand
     *
     * @return array js
     */
    public function getBrandOrder()
    {
        $orderData = [];
        $datas = $this->getBrandData();

        foreach ($datas as $data) {
            $orderData[] = [$data['name'], (float)$data['order_total']];
        }

        return $this->getArrayJs($orderData);
    }

    /**
     * Get Data Qty Brand
     *
     * @return array js
     */
    public function getBrandQty()
    {
        $qtyData = [];
        $datas = $this->getBrandData();

        foreach ($datas as $data) {
            $qtyData[] = [$data['name'], (int)$data['summary']];
        }

        return $this->getArrayJs($qtyData);
    }

    /**
     * Convert ToString
     *
     * @param  $string
     * @return mixed
     */
    public function getArrayJs($string)
    {
        $data = json_encode($string);

        $convert1 = str_replace("\"", "\'", $data);

        $convert2 = str_replace("\\", "", $convert1);

        return $convert2;
    }
}
