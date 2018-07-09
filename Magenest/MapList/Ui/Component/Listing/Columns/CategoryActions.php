<?php
/**
 * Created by PhpStorm.
 * User: heomep
 * Date: 19/09/2016
 * Time: 13:48
 */

namespace Magenest\MapList\Ui\Component\Listing\Columns;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

class CategoryActions extends Column
{
    protected $urlBuilder;

    public function __construct(
        UrlInterface $urlBuilder,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components,
        array $data
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $id = $this->context->getFilterParam('category_id');
            foreach ($dataSource['data']['items'] as &$item) {
                $item[$this->getData('name')]['view'] = [
                    'href' => $this->urlBuilder->getUrl(
                        'maplist/category/view',
                        [
                            'id' => $item['category_id'],
                            'store' => $id
                        ]
                    ),
                    'label' => __('View'),
                    'hidden' => true,
                ];
                $item[$this->getData('name')]['edit'] = [
                    'href' => $this->urlBuilder->getUrl(
                        'maplist/category/edit',
                        [
                            'id' => $item['category_id'],
                            'store' => $id
                        ]
                    ),
                    'label' => __('Edit'),
                    'hidden' => false,
                ];
                $item[$this->getData('name')]['delete'] = [
                    'href' => $this->urlBuilder->getUrl(
                        'maplist/category/delete',
                        [
                            'id' => $item['category_id'],
                            'store' => $id
                        ]
                    ),
                    'label' => __('Delete'),
                    'hidden' => false,
                ];
            }
        }

        return $dataSource;
    }
}
