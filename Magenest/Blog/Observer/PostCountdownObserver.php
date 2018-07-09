<?php

namespace Magenest\Blog\Observer;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Event\ObserverInterface;

class PostCountdownObserver implements ObserverInterface
{
    protected $datetime;

    public function __construct(
        \Magento\Framework\Stdlib\DateTime\DateTime $datetime
    )
    {
        $this->datetime = $datetime;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magenest\Blog\Model\Post $postModel */
        $postModel = ObjectManager::getInstance()->create(\Magenest\Blog\Model\Post::class);
        $collections = $postModel->getCollection()->addAttributeToSelect('*')->getItems();
        $curtime = $this->datetime->gmtDate();
        foreach ($collections as $collection) {
            $dateEnd = $collection->getData('end_countdown');
            $countdownTime = (strtotime($dateEnd) - strtotime($curtime));
            if($dateEnd != 0 ) {
                if ($countdownTime <= 0) {
                    $collection->delete()->save();
                }
            }
        }
    }
}
