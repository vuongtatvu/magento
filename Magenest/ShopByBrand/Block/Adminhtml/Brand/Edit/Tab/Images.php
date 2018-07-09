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
 * @author   CanhND <duccanhdhbkhn@gmail.com>
 */
namespace Magenest\ShopByBrand\Block\Adminhtml\Brand\Edit\Tab;

use Magento\Backend\Block\Widget\Form;
use Magento\Backend\Block\Widget\Form\Generic as FormGeneric;

/**
 * Class Images
 *
 * @package Magenest\ShopByBrand\Block\Adminhtml\Brand\Edit\Tab
 */
class Images extends FormGeneric
{


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

        $form->setHtmlIdPrefix('images_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Brand Images')]);

        $fieldset->addType('logo', 'Magenest\ShopByBrand\Block\Adminhtml\Brand\Helper\Logo');
        $fieldset->addType('banner', 'Magenest\ShopByBrand\Block\Adminhtml\Brand\Helper\Banner');

        $fieldset->addField(
            'logo',
            'logo',
            [
                'name'     => 'logo',
                'label'    => __('Logo'),
                'title'    => __('Logo'),
                'required' => false,
                'note' => 'Allow image type: jpg, jpeg, gif, png (Optimal icon min size is 100 x 100 px)',
            ]
        );

        $fieldset->addField(
            'banner',
            'banner',
            [
                'name'     => 'banner',
                'label'    => __('Banner'),
                'title'    => __('Banner'),
                'required' => false,
                'note' => 'Allow image type: jpg, jpeg, gif, png (Optimal size is 1360 x 318)',
            ]
        );

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
