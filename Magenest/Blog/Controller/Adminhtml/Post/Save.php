<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Magenest\Blog\Controller\Adminhtml\Post;

use Magenest\Blog\Controller\Adminhtml\Post;
use Magenest\Blog\Model\PostFactory;
use Magento\Framework\Registry;
use Magento\Backend\App\Action\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Backend\Helper\Js as JsHelper;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

/**
 * Class Save
 * @package Magenest\Blog\Controller\Adminhtml\Post
 */
class Save extends Post
{

    protected $_tagPostFactory;

    /**
     * Save constructor.
     * @param PostFactory $authorFactory
     * @param StoreManagerInterface $storeManager
     * @param JsonFactory $jsonFactory
     * @param JsHelper $jsHelper
     * @param Registry $registry
     * @param TimezoneInterface $localeDate
     * @param Context $context
     */
    public function __construct(
        \Magenest\Blog\Model\TagPostFactory $tagPostFactory,
        PostFactory $authorFactory,
        StoreManagerInterface $storeManager,
        JsonFactory $jsonFactory,
        JsHelper $jsHelper,
        Registry $registry,
        TimezoneInterface $localeDate,
        Context $context)
    {
        $this->_tagPostFactory = $tagPostFactory;
        parent::__construct($authorFactory, $storeManager, $jsonFactory, $jsHelper, $registry, $localeDate, $context);
    }

    /**
     * @return $this|\Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data = $this->getRequest()->getParams()) {
            $model = $this->initModel();
            $data = $this->filterPostData($data);
            $model->addData($data);
            //save Tags
            $tags = $this->_tagPostFactory->create()->getCollection()->addFieldToFilter('post_id', $model->getId());
            foreach ($tags as $item) {
                $tag = \Magento\Framework\App\ObjectManager::getInstance()
                    ->create('Magenest\Blog\Model\Tag')->load($item->getTagId());
                if (in_array("tag_names", $data)) {
                    if (in_array($tag->getName(), $data['tag_names']))
                        continue;
                }

                $tag->delete()->save();
            }

            try {
                if (isset($data['preview']) && $data['preview']) {
                    $revision = $model->saveAsRevision();

                    //@todo: Improve
                    $url = $this->storeManager->getStore()->getBaseUrl();
                    $url .= 'blog/post/view/id/' . $revision->getId() . '/';
                    return $resultRedirect->setUrl($url);
                } else {
                    $model->save();
                }

                $this->messageManager->addSuccess(__('Post was successfully saved'));
                $this->context->getSession()->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
                }
                return $this->context->getResultRedirectFactory()->create()->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
            }
        } else {
            $resultRedirect->setPath('*/*/');
            $this->messageManager->addError('No data to save.');

            return $resultRedirect;
        }
    }

    /**
     * @param array $data
     * @return array
     */
    protected function filterPostData($data)
    {
        $result = $data['post'];

        if (!isset($result['is_pinned'])) {
            $result['is_pinned'] = false;
        }

        if (isset($result['store_ids'])) {
            $result['store_ids'] = explode(',', $result['store_ids']);
        }

        if (isset($result['created_at'])) {
            $formatter = new \IntlDateFormatter(
                $this->context->getLocaleResolver()->getLocale(),
                \IntlDateFormatter::MEDIUM,
                \IntlDateFormatter::SHORT,
                null,
                null,
                'MMM. d, y h:mm a'
            );
            $result['created_at'] = $formatter->parse($result['created_at']);
        }

        if (isset($result['start_countdown'])) {
            $dateStart = date_create($result['start_countdown']);
            $result['start_countdown'] = date_format($dateStart, "Y-m-d H:i:s");
        }

        if (!isset($result['end_countdown'])) {
            $dateStart = date_create($result['end_countdown']);
            $result['end_countdown'] = date_format($dateStart, "Y-m-d H:i:s");
        }

        if (!isset($data['count_visits'])){
            $result['count_visits']=0;
        }

        if (isset($data['featured_image'])
            && is_array($data['featured_image'])
            && isset($data['featured_image']['delete'])
        ) {
            $result['featured_image'] = '';
        }

        if (isset($data['links'])) {
            $links = is_array($data['links']) ? $data['links'] : [];
            foreach (['relatedproducts' => 'product_ids'] as $type => $alias) {
                if (isset($links[$type])) {
                    $result[$alias] = array_keys($this->jsHelper->decodeGridSerializedInput($links[$type]));
                }
            }
        }
        return $result;
    }
}
