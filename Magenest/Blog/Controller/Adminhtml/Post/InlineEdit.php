<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Controller\Adminhtml\Post;

use Magenest\Blog\Controller\Adminhtml\Post;

/**
 * Class InlineEdit
 * @package Magenest\Blog\Controller\Adminhtml\Post
 */
class InlineEdit extends Post
{
    /**
     * @return $this
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];
        $postItems = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->getParam('isAjax') && count($postItems))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error'    => true,
            ]);
        }

        foreach (array_keys($postItems) as $postId) {
            /** @var \Magento\Cms\Model\Page $page */
            $post = $this->postFactory->create()->load($postId);
            try {
                $data = $postItems[$postId];
                $post->addData($data)
                    ->save();

            } catch (\Exception $e) {
                $messages[] = __('Something went wrong while saving the post.');
                $error = true;
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error'    => $error
        ]);
    }
}
