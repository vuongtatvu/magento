<?php

namespace Magenest\ShippingBar\Plugin;

class PriceShipping
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;
    protected $_checkOut;
    protected $_card;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magenest\ShippingBar\Block\CheckOut $checkOut,
        \Magento\Checkout\Model\Cart $cart

    )
    {
        $this->_cart = $cart;
        $this->_checkOut = $checkOut;
        $this->objectManager = $objectManager;
    }

    public function aroundCollectRates(
        \Magento\Shipping\Model\Shipping $subject,
        \Closure $closure,
        \Magento\Quote\Model\Quote\Address\RateRequest $request
    )
    {
        $closure($request);
        $result = $subject->getResult();
        $oldRates = $result->getAllRates();
        $oldPrices = $this->_getPrices($oldRates);
        $newRates = [];

        $grandTotal = $this->_cart->getQuote()->getGrandTotal();
        $totalQuantity = $this->_cart->getQuote()->getItemsQty();
        $goal = $this->_checkOut->getValue();
        $type = $this->_checkOut->getType();
        $idSession = $this->_checkOut-> getCustomerSession();
        $idSelect = $this->_checkOut-> getSelectGroupCustomer();
        $goalleft1 = $goal - $grandTotal;
        $goalleft2 = $goal - $totalQuantity;

        //fix price when free ship
        if($idSession == $idSelect && $type == 1 && $goalleft1 <=0 or $idSession == $idSelect && $type == 2 && $goalleft2 <=0) {
            foreach ($oldRates as $rate) {
                $rate->setPrice(0);
                $newRates[] = $rate;
            }
            $result->reset();
            foreach ($newRates as $rate) {
                $rate->setOldPrice($oldPrices[$rate->getMethod()]);
                $rate->setPrice(max(0, $rate->getPrice()));
                $result->append($rate);
            }
            return $subject;
        }else{
            foreach ($newRates as $rate) {
                $rate->setOldPrice($oldPrices[$rate->getMethod()]);
                $rate->setPrice(max(0, $rate->getPrice()));
                $result->append($rate);
            }
            return $subject;
        }

    }

    /**
     * Get All Rates Prices
     *
     * @param $rates
     * @return array
     */
    protected function _getPrices($rates)
    {
        $prices = [];
        foreach ($rates as $rate) {
            $prices[$rate->getMethod()] = $rate->getPrice();
        }
        return $prices;
    }


}