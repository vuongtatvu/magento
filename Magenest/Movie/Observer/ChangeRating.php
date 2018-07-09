<?php

namespace Magenest\Movie\Observer;

use    Magento\Framework\Event\ObserverInterface;

class   ChangeRating implements ObserverInterface
{


    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        //getChange lay ten cua array truyen vao
        $rating = $observer->getChange();

        $rating->setRating('0');

        $rating->save();

    }
}