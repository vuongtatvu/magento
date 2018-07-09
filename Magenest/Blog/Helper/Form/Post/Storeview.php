<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Helper\Form\Post;

/**
 * Class Storeview
 * @package Magenest\Blog\Helper\Form\Post
 */
class Storeview extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var array
     */
    private $storeTrees = [];

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Storeview constructor.
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Helper\Context $context
    ) {
        $this->storeManager = $storeManager;
        $this->context      = $context;

        parent::__construct($context);
    }

    /**
     * @param $post
     * @param $container
     * @return string
     */
    public function getField($post, $container)
    {

        return '
        <div>
            <div data-role="spinner" data-component="'.$container.'.'.$container.'"
                class="admin__form-loading-mask">
                <div class="spinner">
                    <span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span>
                </div>
            </div>
            <div data-bind="scope: \''.$container.'.'.$container.'\'" class="entry-edit form-inline '.$container.'">
                <!-- ko template: getTemplate() --><!-- /ko -->
            </div>
        <script type="text/x-magento-init">
            {
                ".'.$container.'": '.json_encode($this->getJsLayout($post, $container)).'
            }
        </script>
        </div>';
    }

    /**
     * @param $post
     * @param $container
     * @return array
     */
    public function getJsLayout($post, $container)
    {

        return [
            "Magento_Ui/js/core/app" => [
                "types" => [
                    "dataSource" => [
                        "component" => "Magento_Ui/js/form/provider"
                    ],
                    "container" => [
                        "extends" => $container
                    ],
                    "select" => [
                        "extends" => $container
                    ],
                    "multiselect" => [
                        "extends" => $container
                    ],
                    "form.select" => [
                        "extends" => "select"
                    ],
                    "fieldset" => [
                        "component" => "Magento_Ui/js/form/components/fieldset",
                        "extends"   => $container,
                    ],
                    "html_content" => [
                        "component" => "Magento_Ui/js/form/components/html",
                        "extends"   => $container,
                    ],
                    "form.multiselect" => [
                        "extends"   => 'multiselect',
                    ],
                    $container => [
                        "component" => "Magento_Ui/js/form/form",
                        "provider"  => $container.".storeview_data_source",
                        "deps"      => $container.".storeview_data_source",
                        "namespace" => $container
                    ]
                ],
                "components" => [
                    $container => [
                        "children" => [
                            $container => [
                                "type"     => $container,
                                "name"     => $container,
                                "children" => [
                                    'storeview-details' => [
                                        "children" => [
                                            "container_storeview_ids" => [
                                                "type"     => "container",
                                                "name"     => "container_storeview_ids",
                                                "children" => [
                                                    "storeview_ids" => $this->getStoreviewField($post),
                                                ],
                                                "dataScope" => "",
                                                "config"    => [
                                                    "component"     => "Magento_Ui/js/form/components/group",
                                                    "label"         => __(""),
                                                    "breakLine"     => false,
                                                    "formElement"   => "container",
                                                    "componentType" => "container",
                                                    "scopeLabel"    => __("[GLOBAL]"),
                                                    "sortOrder"     => 0
                                                ]
                                            ]
                                        ],
                                        'config' => [
                                            'collapsible'   => false,
                                            'componentType' => 'fieldset',
                                            'label'         => '',
                                            'sortOrder'     => 0,
                                        ],
                                        'name'      => 'storeview-details',
                                        'dataScope' => 'data.storeview',
                                        'type'      => 'fieldset',
                                    ],
                                ]
                            ],
                            "storeview_data_source" => [
                                "type"      => "dataSource",
                                "name"      => "storeview_data_source",
                                "dataScope" => $container,
                                "config"    => [
                                    "data" => [
                                        "post" => $post->getData()
                                    ],
                                    "params" => [
                                        "namespace" => $container
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * @param \Magenest\Blog\Model\Post $post
     * @return array
     */
    public function getStoreviewField($post)
    {

        return [
            "type"      => "form.select",
            "name"      => "storeview_ids",
            "dataScope" => "storeview_ids",
            "config"    => [
                "code"             => "storeview_ids",
                "component"        => "Magenest_Blog/js/form/components/post-edit-storeview",
                "template"         => "ui/form/field",
                "dataType"         => "text",
                "formElement"      => "select",
                "componentType"    => "field",
                "label"            => "",
                "source"           => 'storeview-details',
                "scopeLabel"       => '[GLOBAL]',
                "sortOrder"        => 90,
                "globalScope"      => true,
                "filterOptions"    => true,
                "chipsEnabled"     => true,
                "disableLabel"     => true,
                "levelsVisibility" => "1",
                "elementTmpl"      => "ui/grid/filters/elements/ui-select",
                "options"          => $this->getTree($post->getStoreIds()),
                "value"            => array_map('intval', $post->getStoreIds()), // var type is important here
                'visible'          => 1,
                "listens"          => [],
                "config"           => [
                    "dataScope" => "storeview_ids",
                    "sortOrder" => 10
                ]
            ]
        ];
    }

    /**
     * Retrieve store tree
     *
     * @param array $storeIds
     * @return array
     */
    protected function getTree($storeIds)
    {
        $filter = implode(',', $storeIds);
        if (isset($this->storeTrees[$filter])) {
            return $this->storeTrees[$filter];
        }

        $data = [];
        $stores = $this->storeManager->getStores(true);
        foreach ($stores as $store) {
            if (in_array($store->getId(), $storeIds)) {
                $data[$store->getId()] = $this->getStoreOptions($store);
            } elseif ($filter == 0) {
                $data[$store->getId()] = $this->getStoreOptions($store);
            }
        }

        $this->storeTrees[$filter] = $data;

        return $this->storeTrees[$filter];
    }

    /**
     * @param \Magento\Store\Api\Data\StoreInterface $store
     * @return array
     */
    public function getStoreOptions($store)
    {
        if ($store->getId() == 0) {
            $option = [
                'is_active' => 1,
                'label'     => __('All Store Views'),
                'value'     => 0,
            ];
        } else {
            $option = [
                'value'     => (int)$store->getId(),
                'is_active' => $store->isActive(),
                'label'     => $store->getName()
            ];
        }

        return $option;
    }

    /**
     * @return bool
     */
    public function isMultiStore()
    {

        return count($this->getTree([0])) > 2;
    }
}
