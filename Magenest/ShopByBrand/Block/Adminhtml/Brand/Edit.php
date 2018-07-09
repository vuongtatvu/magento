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
namespace Magenest\ShopByBrand\Block\Adminhtml\Brand;

use Magento\Backend\Block\Widget\Form\Container;
use Magento\Backend\Block\Widget\Context;

/**
 * Class Edit
 *
 * @package Magenest\ShopByBrand\Block\Adminhtml\Brand
 */
class Edit extends Container
{

    /**
     * @param Context $context
     * @param array   $data
     */
    public function __construct(
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Initialize blog post edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId   = 'brand_id';
        $this->_blockGroup = 'Magenest_ShopByBrand';
        $this->_controller = 'adminhtml_brand';

        $this->buttonList->update('save', 'label', __('Save Information'));
        $this->buttonList->add(
            'saveandcontinue',
            [
             'label'          => __('Save and Continue Edit'),
             'class'          => 'save',
             'data_attribute' => [
                                  'mage-init' => [
                                                  'button' => [
                                                               'event'  => 'saveAndContinueEdit',
                                                               'target' => '#edit_form',
                                                              ],
                                                 ],
                                 ],
            ],
            -100
        );

        parent::_construct();
    }

    /**
     * Getter of url for "Save and Continue" button
     * tab_id will be replaced by desired by JS later
     *
     * @return string
     */
    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('shopbybrand/*/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '{{tab_id}}']);
    }
}
