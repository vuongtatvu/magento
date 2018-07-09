<?php
/**
 * Created by Magenest
 * User: Luu Thanh Thuy
 * Date: 08/10/2016
 * Time: 02:50
 */

namespace Magenest\MapList\Block;

class Link extends \Magento\Framework\View\Element\Html\Link
{
    protected $mapFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magenest\MapList\Model\MapFactory $mapFactory,
        array $data
    ) {
        $this->mapFactory = $mapFactory;
        parent::__construct($context, $data);
    }

    public function getMapList()
    {
        $collection = $this->mapFactory->create()->getCollection()
            ->getData();// ->addFieldToFilter();
        return $collection;
    }
}
