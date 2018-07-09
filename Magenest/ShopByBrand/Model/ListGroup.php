<?php
/**
 * Created by PhpStorm.
 * User: duccanh
 * Date: 02/11/2016
 * Time: 11:28
 */
namespace Magenest\ShopByBrand\Model;

use Psr\Log\LoggerInterface;

/**
 * Class ListGroup
 *
 * @package Magenest\ShopByBrand\Model
 */

class ListGroup extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * @var Group
     */
    protected $_group;
    protected $_logger;
    /**
     *
     * @param \Magenest\ShopByBrand\Model\Brand $brand
     */
    public function __construct(
        \Magenest\ShopByBrand\Model\Group $group,
        LoggerInterface $logger
    ) {
        $this->_group = $group;
        $this->_logger=$logger;
    }

    /**
     * Get Gift Card available templates
     *
     * @return array
     */
    public function getAvailableTemplate()
    {
        $groups    = $this->_group->getCollection()
            ->addFieldToFilter('status', '1');
        $listGroup = array();
        foreach ($groups as $group) {
            $listGroup[] = array(
                'label' => $group->getName(),
                'value' => $group->getGroupId(),
            );
        }
        return $listGroup;
    }

    /**
     * Get model option as array
     *
     * @return array
     */
    public function getAllOptions($empty = false)
    {
        $options = $this->getAvailableTemplate();

        if ($empty) {
            array_unshift(
                $options,
                array(
                    'value' => '0',
                    'label' => 'All Group Views',
                )
            );
        }

        return $options;
    }
}
