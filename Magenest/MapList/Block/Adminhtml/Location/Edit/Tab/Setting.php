<?php
/**
 * Created by PhpStorm.
 * User: heomep
 * Date: 16/09/2016
 * Time: 10:14
 */

namespace Magenest\MapList\Block\Adminhtml\Location\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magenest\MapList\Model\Status;
use \Magento\Directory\Model\Config\Source\Country;


class Setting extends Generic implements TabInterface
{
    protected $_wysiwygConfig;
    protected $_status;
    protected $categoryFactory;
    protected $_productFactory;
    protected $_country;
    protected $regionColFactory;

    public function __construct(
        \Magento\Catalog\Model\ProductFactory $productFactory,
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Status $status,
        Country $country,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        \Magenest\MapList\Model\CategoryFactory $categoryFactory,
        \Magento\Directory\Model\RegionFactory $regionColFactory,
        array $data
    ) {
        $this->_productFactory = $productFactory;
        $this->categoryFactory = $categoryFactory;
        $this->_status = $status;
        $this->_country = $country;
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->regionColFactory     = $regionColFactory;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('maplist_location_edit');
        $data = $model->getData();
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('location_');

        $fieldset = $form->addFieldset(
            'setting_fieldset',
            [
                'legend' => __('General Settings'),
                'class' => 'fieldset-wide'
            ]
        );

        $fieldset->addType('map', '\Magenest\MapList\Block\Adminhtml\Template\GoogleMap');
        $fieldset->addType('customgallery','\Magenest\MapList\Block\Adminhtml\Template\Gallery');
        $fieldset->addType('customicon','\Magenest\MapList\Block\Adminhtml\Template\Icon');

        $countries = $this->_country->toOptionArray(false, 'US');



        if ($model->getId()) {
            $fieldset->addField(
                'location_id',
                'hidden',
                ['name' => 'location_id']
            );
        }


        $fieldset->addField(
            'title',
            'text',
            [
                'name' => 'title',
                'label' => __('Name Location'),
                'title' => __('Name'),
                'required' => true
            ]
        );

        $fieldset->addField(
            'short_description',
            'textarea',
            [
                'name' => 'short_description',
                'label' => __('Short Description'),
                'title' => __('short_description'),
            ]
        );

        $fieldset->addField(
            'description',
            'editor',
            [
                'name' => 'description',
                'label' => __('Description'),
                'title' => __('Description'),
                'style' => 'height:20em',
                'required' => false,
                'config' => $this->_wysiwygConfig->getConfig(),
            ]
        );

//        $fieldset->addField(
//            'location_product',
//            'multiselect',
//            [
//                'name' => 'location_product[]',
//                'label' => __('Available Products'),
//                'title' => __('Available Products'),
//                'required' => false,
//                'values' => $this->_getAllProduct(),
//                'disabled' => false,
//            ]
//        );


        $fieldset->addField(
            'gallery_image',
            'customgallery',
            [
                'name' => 'gallery_image',
                'label' => __('Gallery Image'),
                'title' => __('Gallery Image'),
                'required' => false,
                'note' => 'Allow image type: jpg, gif, jpeg, png',
            ]
        );

       $fieldset->addField(
            'country',
            'select',
            [
                'name' => 'country',
                'label' => __('Country'),
                'title' => __('Country'),
                'values' => $countries,
                'required' => false,
            ]
        );
        $fieldset->addField(
            'state_province',
            'select',
            [
                'name' => 'state_province',
                'label' => __('State/Province'),
                'title' => __('State/Province'),
                'required' => false,
                'style' => 'width: 50%;',
                'values' => $this->_getStateProvince()
            ]
        );

        $fieldset->addField(
            'city',
            'text',
            [
                'name' => 'city',
                'label' => __('City'),
                'title' => __('City')
            ]
        );

        $fieldset->addField(
            'zip',
            'text',
            [
                'name' => 'zip',
                'label' => __('Zip'),
                'title' => __('Zip')
            ]
        );

        $fieldset->addField(
            'address',
            'text',
            [
                'name' => 'address',
                'label' => __('Address'),
                'title' => __('address')
            ]
        );

        $fieldset->addField(
            'website',
            'text',
            [
                'name' => 'website',
                'label' => __('Website'),
                'title' => __('website'),
                'class' => 'validate-url',
                'note' => __('Input your Website with http/https'),
            ]
        );

        $fieldset->addField(
            'email',
            'text',
            [
                'name' => 'email',
                'label' => __('Email'),
                'title' => __('email')
            ]
        );

        $fieldset->addField(
            'phone_number',
            'text',
            [
                'name' => 'phone_number',
                'label' => __('Phone Number'),
                'title' => __('phone_number')
            ]
        );

        $fieldset->addField(
            'location_categories',
            'multiselect',
            [
                'name' => 'location_categories[]',
                'label' => __('Tags'),
                'title' => __('Tags'),
                'required' => false,
                'values' => $this->_getAllCategories(),
                'disabled' => false,
                'style' => 'width: 100%;height: auto'
            ]
        );

        $fieldset->addField(
            'latitude',
            'text',
            [
                'name' => 'latitude',
                'label' => __('Latitude'),
                'title' => __('latitude'),
                'required' => true,
            ]
        );

        $fieldset->addField(
            'longitude',
            'text',
            [
                'name' => 'longitude',
                'label' => __('Longitude'),
                'title' => __('longitude'),
                'required' => true,
            ]
        );

        $fieldset->addField(
            'map',
            'map',
            [
                'name' => 'map',
                'label' => __('Map'),
                'title' => __('Map')
            ]
        );

        $fieldset->addField(
            'small_image',
            'customicon',
            [
                'name' => 'small_image',
                'label' => __('Location Icon'),
                'title' => __('Location Icon'),
                'required' => false,
                'note' => 'Allow image type: jpg, gif, jpeg, png',
            ]
        );

        $fieldset->addField(
            'is_active',
            'select',
            [
                'label' => __('Status'),
                'title' => __('Status'),
                'name' => 'is_active',
                'required' => true,
                'options' => ['1' => __('Enable'), '0' => __('Disable')]
            ]
        );

        if (!isset($data['is_active'])) {
            $data['is_active'] = 1;
        }


        $form->setValues($data);
//        $form->setValues($model->getData());
        $this->setForm($form);


        return parent::_prepareForm();
    }

    public function getTabLabel()
    {
        return __('Location Settings');
    }

    public function getTabTitle()
    {
        return __('Location Settings');
    }

    public function canShowTab()
    {
        return true;
    }

    public function isHidden()
    {
        return false;
    }

    private function _getAllCategories()
    {
        // Get our collection
        $existingCategories = $this->categoryFactory->create()->getCollection()->getData();
        $categoryList = [];
        foreach ($existingCategories as $category) {
            $categoryList[] = [
                'value' => $category['category_id'],
                'label' => $category['title']
            ];
        }

        return $categoryList;
    }

    private function _getAllProduct()
    {
        // Get our collection
        $existingProduct = $this->_productFactory->create()->getCollection()->getData();
        $productList = [];
        foreach ($existingProduct as $product) {
            $productList[] = [
                'value' => $product['entity_id'],
                'label' => $product['sku']
            ];
        }

        return $productList;
    }

    private function _getStateProvince()
    {
        // Get our collection

        $model = $this->_coreRegistry->registry('maplist_location_edit');
        $data = $model->getData();
        $regionData = [];
        if ($data) {
            $regions = $this->regionColFactory->create()->getCollection()->addFieldToFilter('country_id', $data['country']);
            foreach ($regions as $region) {
                $regionData[] = [
                    'value' => $region['code'],
                    'label' => $region['name']
                ];
            }
        }

        return $regionData;
    }

}
