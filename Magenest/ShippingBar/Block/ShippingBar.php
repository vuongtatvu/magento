<?php

namespace Magenest\ShippingBar\Block;

class ShippingBar extends \Magento\Framework\View\Element\Template
{
    protected $_cart;
    protected $_checkoutSession;
    const XML_PATH_SHIPPINGBAR_ENABLED = 'shippingstore/shippingpage/enabled';
    const XML_PATH_SHIPPINGBAR_SHIPPINGTYPE = 'shippingstore/shippingpage/shippingtype';
    const XML_PATH_SHIPPINGBAR_SHIPPINGGOAL = 'shippingstore/shippingpage/shipping_goal';
    const XML_PATH_SHIPPINGBAR_CARTEMPTY = 'shippingstore/content/cart_empty';
    const XML_PATH_SHIPPINGBAR_CARTNOTEMPTY = 'shippingstore/content/cart_not_empty';
    const XML_PATH_SHIPPINGBAR_CARTGOAL = 'shippingstore/content/cart_goal';
    const XML_PATH_SHIPPINGBAR_DELAY = 'shippingstore/design/page_delay';
    const XML_PATH_SHIPPINGBAR_DISPLAYTYPE = 'shippingstore/design/display_type';
    const XML_PATH_SHIPPINGBAR_PAGEDISPLAY = 'shippingstore/wheretodisplay/page_display';
    const XML_PATH_SHIPPINGBAR_FONTFAMILY = 'shippingstore/design/font_family';
    const XML_PATH_SHIPPINGBAR_FONTSIZE = 'shippingstore/design/font_size';
    const XML_PATH_SHIPPINGBAR_FONTWEIGHT = 'shippingstore/design/font_weight';
    const XML_PATH_SHIPPINGBAR_FONTCOLOR = 'shippingstore/design/font_color';
    const XML_PATH_SHIPPINGBAR_BACKGROUNDCOLOR = 'shippingstore/design/background_color';
    const XML_PATH_SHIPPINGBAR_TEXTALIGN = 'shippingstore/design/text_align';

    const XML_PATH_SHIPPINGBAR_ALOWCLOSE = 'shippingstore/design/alow_close';
    const XML_PATH_SHIPPINGBAR_AUTOHIDE = 'shippingstore/design/auto_hide';

    const XML_PATH_SHIPPINGBAR_ENABLEDDATE = 'shippingstore/schedule_date/enabled';
    const XML_PATH_SHIPPINGBAR_STARTDATE = 'shippingstore/schedule_date/start_date';
    const XML_PATH_SHIPPINGBAR_ENDDATE = 'shippingstore/schedule_date/end_date';

    const XML_PATH_SHIPPINGBAR_ENABLEDDAY = 'shippingstore/schedule_day/enabled';
    const XML_PATH_SHIPPINGBAR_STARTTIME = 'shippingstore/schedule_day/start_time';
    const XML_PATH_SHIPPINGBAR_ENDTIME = 'shippingstore/schedule_day/end_time';

    const XML_PATH_SHIPPINGBAR_ENABLEDWEEK = 'shippingstore/schedule_week/enabled';
    const XML_PATH_SHIPPINGBAR_DAYOFWEEK = 'shippingstore/schedule_week/day_of_week';
    const XML_PATH_SHIPPINGBAR_SHIPPINGGROUP = 'shippingstore/shippingpage/shipping_group';


    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlInterface;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
//       \Magento\Framework\UrlInterface $urlInterface,
//       \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Customer\Model\Session $customerSession,
        array $data = array()
    )
    {
        $this->_cart = $cart;
        $this->_checkoutSession = $checkoutSession;
        $this->urlInterface = $context->getUrlBuilder();
        $this->scopeConfig = $context->getScopeConfig();
        $this->customerSession = $customerSession;
        parent::__construct($context, $data);
    }
    //get id group customer in setting
    public function  getSelectGroupCustomer(){
        return $this->scopeConfig->getValue(
            self::XML_PATH_SHIPPINGBAR_SHIPPINGGROUP,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
    //get id group customer current after login
    public  function  getCustomerSession(){
        return $this->customerSession->getCustomerGroupId();
    }


    public function getCart()
    {
        return $this->_cart;
    }

    public function getCheckoutSession()
    {
        return $this->_checkoutSession;
    }

    //Content when cart is empty
    public function getCartEmpty()
    {
        $isDisplay = $this->scopeConfig->getValue(self::XML_PATH_SHIPPINGBAR_PAGEDISPLAY, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $isEnabled = $this->scopeConfig->getValue(self::XML_PATH_SHIPPINGBAR_ENABLED, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if ($isEnabled && (strpos($isDisplay,'4') > 0 || $isDisplay == '1')) {
            $cartEmpty = $this->scopeConfig->getValue(
                self::XML_PATH_SHIPPINGBAR_CARTEMPTY,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
            return $cartEmpty;
        } else {
            return null;
        }
    }

    //Content when cart is not empty
    public function getCartNotEmpty()
    {
        $isDisplay = $this->scopeConfig->getValue(self::XML_PATH_SHIPPINGBAR_PAGEDISPLAY, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $isEnabled = $this->scopeConfig->getValue(self::XML_PATH_SHIPPINGBAR_ENABLED, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if ($isEnabled && (strpos($isDisplay,'4') > 0 || $isDisplay == '1')) {
            $cartNotEmpty = $this->scopeConfig->getValue(
                self::XML_PATH_SHIPPINGBAR_CARTNOTEMPTY,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
            return $cartNotEmpty;
        } else {
            return null;
        }
    }

    //Content when goal is reached
    public function getCartGoal()
    {
        $isDisplay = $this->scopeConfig->getValue(self::XML_PATH_SHIPPINGBAR_PAGEDISPLAY, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $isEnabled = $this->scopeConfig->getValue(self::XML_PATH_SHIPPINGBAR_ENABLED, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if ($isEnabled && (strpos($isDisplay,'4') > 0 || $isDisplay == '1')) {
            $cartGoal = $this->scopeConfig->getValue(
                self::XML_PATH_SHIPPINGBAR_CARTGOAL,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
            return $cartGoal;
        } else {
            return null;
        }
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

    // Get Type of Shipping
    public function getType()
    {
        $isEnabled = $this->scopeConfig->getValue(self::XML_PATH_SHIPPINGBAR_ENABLED, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if ($isEnabled) {
            $type = $this->scopeConfig->getValue(self::XML_PATH_SHIPPINGBAR_SHIPPINGTYPE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            return $type;
        } else {
            return null;
        }

    }

    // Get value of Shipping
    public function getValue()
    {
        $isEnabled = $this->scopeConfig->getValue(self::XML_PATH_SHIPPINGBAR_ENABLED, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if ($isEnabled) {
            $buttonText = $this->scopeConfig->getValue(self::XML_PATH_SHIPPINGBAR_SHIPPINGGOAL, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            return $buttonText;
        } else {
            return null;
        }

    }

    //Get enable
    public function getEnable()
    {
        return $enable = $this->scopeConfig->getValue(
            self::XML_PATH_SHIPPINGBAR_ENABLED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getPageDisplay()
    {
        $isDisplay = $this->scopeConfig->getValue(self::XML_PATH_SHIPPINGBAR_PAGEDISPLAY, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $isEnabled = $this->scopeConfig->getValue(self::XML_PATH_SHIPPINGBAR_ENABLED, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if ($isEnabled && (strpos($isDisplay,'4') > 0 || $isDisplay == '1')) {
            return true;
        } else
            return false;

    }

    //Get font-family
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


    // Get start date
    public function getStartDate()
    {
        $isEnabledDate = $this->scopeConfig->getValue(self::XML_PATH_SHIPPINGBAR_ENABLEDDATE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if ($isEnabledDate) {
            $startDate = $this->scopeConfig->getValue(self::XML_PATH_SHIPPINGBAR_STARTDATE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            return $startDate;
        } else {
            return null;
        }

    }

    // Get end date
    public function getEndDate()
    {
        $isEnabledDate = $this->scopeConfig->getValue(self::XML_PATH_SHIPPINGBAR_ENABLEDDATE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if ($isEnabledDate) {
            $endDate = $this->scopeConfig->getValue(self::XML_PATH_SHIPPINGBAR_ENDDATE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            return $endDate;
        } else {
            return null;
        }

    }
    // Get start time
    public function getStarTime()
    {
        $isEnabledDay = $this->scopeConfig->getValue(self::XML_PATH_SHIPPINGBAR_ENABLEDDAY, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if ($isEnabledDay) {
            $startTime = $this->scopeConfig->getValue(self::XML_PATH_SHIPPINGBAR_STARTTIME, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            return $startTime;
        } else {
            return null;
        }

    }

    // Get end time
    public function getEndTime()
    {
        $isEnabledDay = $this->scopeConfig->getValue(self::XML_PATH_SHIPPINGBAR_ENABLEDDAY, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if ($isEnabledDay) {
            $endTime = $this->scopeConfig->getValue(self::XML_PATH_SHIPPINGBAR_ENDTIME, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            return $endTime;
        } else {
            return null;
        }

    }

    //Get enable date
    public function getEnableDate()
    {

        return $enabledDate = $this->scopeConfig->getValue(
            self::XML_PATH_SHIPPINGBAR_ENABLEDDATE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    // Get day of week
    public function getDayOfWeek()
    {
        $isEnabledWeek = $this->scopeConfig->getValue(self::XML_PATH_SHIPPINGBAR_ENABLEDWEEK, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if ($isEnabledWeek) {
            $dayOfWeek = $this->scopeConfig->getValue(self::XML_PATH_SHIPPINGBAR_DAYOFWEEK, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            return $dayOfWeek;
        } else {
            return null;
        }

    }

}
