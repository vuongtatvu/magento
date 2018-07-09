<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Magenest_ShopByBrand extension
 * NOTICE OF LICENSE
 *
 * @category Magenest
 * @package  Magenest_ShopByBrand
 * @author   ChienLV <chienlv@gmail.com>
 */
namespace Magenest\ShopByBrand\Block\Adminhtml\Brand\Edit\Tab;

use Magento\Backend\Block\Widget\Form;
use Magento\Backend\Block\Widget\Form\Generic as FormGeneric;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Store\Model\System\Store as SystemStore;

/**
 * Class BrandPage
 *
 * @package Magenest\ShopByBrand\Block\Adminhtml\Brand\Edit\Tab
 */
class BrandPage extends FormGeneric
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;


    /**
     * BrandPage constructor.
     *
     * @param Context                           $context
     * @param Registry                          $registry
     * @param FormFactory                       $formFactory
     * @param \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig
     * @param SystemStore                       $systemStore
     * @param array                             $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        \Magento\Store\Model\System\Store $systemStore,
        array $data = []
    ) {
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->_systemStore   = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }


    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('shopbybrand');

        /*
            * @var \Magento\Framework\Data\Form $form
         */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Brand Information')]);

        $fieldset->addField(
            'page_title',
            'text',
            [
             'name'     => 'page_title',
             'label'    => __('Page Title'),
             'title'    => __('Page Title'),
             'required' => false,
            ]
        );
        $fieldset->addField(
            'url_key',
            'text',
            [
             'name'     => 'url_key',
             'label'    => __('URL key'),
             'title'    => __('URL key'),
             'required' => true,
            ]
        );
        $fieldset->addField(
            'slogan',
            'text',
            [
                'name'     => 'slogan',
                'label'    => __('Slogan'),
                'title'    => __('Slogan'),
                'required' => false,
            ]
        );
        $fieldset->addField(
            'description',
            'editor',
            [
             'name'     => 'description',
             'label'    => __('Brand description'),
             'title'    => __('Brand description'),
             'style'    => 'height:20em',
             'required' => false,
             'config'   => $this->_wysiwygConfig->getConfig(),
            ]
        );
        $fieldset->addField(
            'meta_keywords',
            'textarea',
            [
             'label' => __('Meta keywords'),
             'title' => __('Meta keywords'),
             'name'  => 'meta_keywords',
             'cols'  => 20,
             'rows'  => 5,
             'wrap'  => 'soft',
            ]
        );
        $fieldset->addField(
            'meta_description',
            'textarea',
            [
             'label' => __('Meta description'),
             'title' => __('Meta description'),
             'name'  => 'meta_description',
             'cols'  => 20,
             'rows'  => 5,
             'wrap'  => 'soft',
            ]
        );

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
