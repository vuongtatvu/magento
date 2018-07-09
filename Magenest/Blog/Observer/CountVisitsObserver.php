<?php

namespace Magenest\Blog\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\ObjectManager;


class CountVisitsObserver implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $count = $observer->getData('post');
        /** @var \Magenest\Blog\Model\Post $postModel */
        $post = ObjectManager::getInstance()->create(\Magenest\Blog\Model\Post::class);
        $collections = $post->getCollection()->addAttributeToSelect('*')->getItems();
        $curId = $count ->getData('entity_id');
        foreach ($collections as $collection) {
            $postId = $collection->getData('entity_id');
            if($curId == $postId){
                $number = $collection->getData('count_visits')+1;
                $count->setData('count_visits',$number)->save();
                $collection->setData('count_visits',$number)->save();
            }
        }

    }
}