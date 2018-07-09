<?php
/**
 * Created by PhpStorm.
 * User: duccanh
 * Date: 01/11/2016
 * Time: 16:49
 */
namespace Magenest\ShopByBrand\Ui\Component\Listing\Columns;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class SideBar
 *
 * @package Magenest\ShopByBrand\Ui\Component\Listing\Columns
 */
class SideBar extends Column
{
    /**
     *  Status
     */
    const STATUS_ENABLED  = 1;
    const STATUS_DISABLED = 2;

    /**
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory           $uiComponentFactory
     * @param array                                                        $components
     * @param array                                                        $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param  array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (!empty($item['show_in_sidebar'])) {
                    $item['show_in_sidebar'] = strip_tags($this->getOptionGrid($item['show_in_sidebar']), ENT_IGNORE);
                }
            }
        }

        return $dataSource;
    }


    /**
     * @return array
     */
    public static function getOptionArray()
    {
        return [
            self::STATUS_ENABLED  => __('Enable'),
            self::STATUS_DISABLED => __('Disable'),
        ];
    }


    /**
     * @param $optionId
     * @return string
     */
    public function getOptionGrid($optionId)
    {
        $options = self::getOptionArray();
        if ($optionId == self::STATUS_ENABLED) {
            $html = '<span class="grid-severity-notice"><span>'.$options[$optionId].'</span>'.'</span>';
        } else {
            $html = '<span class="grid-severity-critical"><span>'.$options[$optionId].'</span></span>';
        }

        return $html;
    }
}
