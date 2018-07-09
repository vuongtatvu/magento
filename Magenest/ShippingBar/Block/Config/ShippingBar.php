<?php

namespace Magenest\ShippingBar\Block\Config;

class ShippingBar extends \Magento\Config\Block\System\Config\Form\Field
{

    const XML_PATH_SHIPPINGBAR_SHIPPINGTYPE = 'shippingstore/shippingpage/shippingtype';
    const XML_PATH_SHIPPINGBAR_SHIPPINGGOAL = 'shippingstore/shippingpage/shipping_goal';
    const XML_PATH_SHIPPINGBAR_FONTFAMILY = 'shippingstore/design/font_family';
    const XML_PATH_SHIPPINGBAR_FONTSIZE = 'shippingstore/design/font_size';
    const XML_PATH_SHIPPINGBAR_FONTWEIGHT = 'shippingstore/design/font_weight';
    const XML_PATH_SHIPPINGBAR_FONTCOLOR = 'shippingstore/design/font_color';
    const XML_PATH_SHIPPINGBAR_BACKGROUNDCOLOR = 'shippingstore/design/background_color';
    const XML_PATH_SHIPPINGBAR_TEXTALIGN = 'shippingstore/design/text_align';
    const XML_PATH_SHIPPINGBAR_ALOWCLOSE = 'shippingstore/design/alow_close';
    const XML_PATH_SHIPPINGBAR_AUTOHIDE = 'shippingstore/design/auto_hide';
    const XML_PATH_SHIPPINGBAR_DELAY = 'shippingstore/design/page_delay';
    const XML_PATH_SHIPPINGBAR_DISPLAYTYPE = 'shippingstore/design/display_type';


    protected $_template = 'Magenest_ShippingBar::shipping_bar.phtml';
    protected $scopeConfig;

    /**
     * Template path
     *
     * @var string
     *
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        // \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        array $data = []
    )
    {
        $this->scopeConfig = $context->getScopeConfig();
        parent::__construct($context, $data);
    }


    /**
     * Render fieldset html
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */

    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $columns = $this->getRequest()->getParam('website') || $this->getRequest()->getParam('store') ? 5 : 4;
        return $this->_decorateRowHtml($element, "<td colspan='{$columns}'>" . $this->toHtml() . '</td>');
    }

    //  Type of Shipping
    public function getType()
    {

        return $type = $this->scopeConfig->getValue(
            self::XML_PATH_SHIPPINGBAR_SHIPPINGTYPE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

    }

    //  Value of Shipping
    public function getValue()
    {
        return $value = $this->scopeConfig->getValue(
            self::XML_PATH_SHIPPINGBAR_SHIPPINGGOAL,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    //Display with delay after page load, seconds
    public function getDelay()
    {
        return $delay = $this->scopeConfig->getValue(
            self::XML_PATH_SHIPPINGBAR_DELAY,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    //Get Display Type:
    public function getDisplayType()
    {
        return $displaytype = $this->scopeConfig->getValue(
            self::XML_PATH_SHIPPINGBAR_DISPLAYTYPE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    //Get alow-to-close
    public function getAlowClose()
    {
        return $alowClose = $this->scopeConfig->getValue(
            self::XML_PATH_SHIPPINGBAR_ALOWCLOSE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    //Get alow-to-close
    public function getAutoHide()
    {
        return $autoHide = $this->scopeConfig->getValue(
            self::XML_PATH_SHIPPINGBAR_AUTOHIDE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }


    public function getFontFamily()
    {
        return $fontFamily = $this->scopeConfig->getValue(
            self::XML_PATH_SHIPPINGBAR_FONTFAMILY,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    //Get font-size
    public function getFontSize()
    {
        return $fontSize = $this->scopeConfig->getValue(
            self::XML_PATH_SHIPPINGBAR_FONTSIZE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    //Get font-weight
    public function getFontWeight()
    {
        return $fontWeigth = $this->scopeConfig->getValue(
            self::XML_PATH_SHIPPINGBAR_FONTWEIGHT,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    //Get text-align
    public function getTextAlign()
    {
        return $textAlign = $this->scopeConfig->getValue(
            self::XML_PATH_SHIPPINGBAR_TEXTALIGN,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    //Get font-color
    public function getFontColor()
    {
        return $fontColor = $this->scopeConfig->getValue(
            self::XML_PATH_SHIPPINGBAR_FONTCOLOR,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    //Get background-color
    public function getBackgroundColor()
    {
        return $backgroundColor = $this->scopeConfig->getValue(
            self::XML_PATH_SHIPPINGBAR_BACKGROUNDCOLOR,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

}