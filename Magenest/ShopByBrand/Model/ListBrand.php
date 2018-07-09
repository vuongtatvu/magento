<?php
/**
 * Created by PhpStorm.
 * User: duccanh
 * Date: 01/06/2016
 * Time: 09:34
 */
namespace Magenest\ShopByBrand\Model;

/**
 * Class Listbrand
 *
 * @package Magenest\ShopByBrand\Model
 */
class ListBrand extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * @var Brand
     */
    protected $_brand;

    /**
     *
     * @param \Magenest\ShopByBrand\Model\Brand $brand
     */
    public function __construct(
        \Magenest\ShopByBrand\Model\Brand $brand
    ) {
        $this->_brand = $brand;
    }

    /**
     * Get Gift Card available templates
     *
     * @return array
     */
    public function getAvailableTemplate()
    {
        $brands    = $this->_brand->getCollection()
            ->addFieldToFilter('status', '1');
        $listBrand = array();
        foreach ($brands as $brand) {
            $listBrand[] = array(
                            'label' => $brand->getName(),
                            'value' => $brand->getId(),
                           );
        }

        return $listBrand;
    }

    /**
     * Get model option as array
     *
     * @return array
     */
    public function getAllOptions($withEmpty = true)
    {
        $options = $this->getAvailableTemplate();

        if ($withEmpty) {
            array_unshift(
                $options,
                array(
                 'value' => '0',
                 'label' => '-- Please Select --',
                )
            );
        }

        return $options;
    }
}
