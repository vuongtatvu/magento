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
 * @author   Chienbigstar <chienbigstar@gmail.com>
 */

namespace Magenest\ShopByBrand\Block\Brand\View;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Page\Config;

/**
 * Class Info
 *
 * @package Magenest\ShopByBrand\Block\Brand\View
 */
class Info extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magenest\ShopByBrand\Model\Brand
     */
    protected $_brand;

    /**
     * Info constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magenest\ShopByBrand\Model\Brand                $brand
     * @param array                                            $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magenest\ShopByBrand\Model\Brand $brand,
        array $data = []
    ) {
        $this->_brand      = $brand;
        parent::__construct($context, $data);
    }

    /**
     * @return array
     */
    public function getBrandInfo()
    {
        $id          = $this->getRequest()->getParam('brand_id');
        $baseUrl = $this->_urlBuilder->getBaseUrl(['_type' => UrlInterface::URL_TYPE_MEDIA]);
        $brand   = $this->_brand->load($id)->getData();

        $data = [
            'name'      => $brand['name'],
            'url_key'   => $brand['url_key'],
            'slogan'    => $brand['slogan'],
            'banner'    => $baseUrl."shopbybrand/brand/image".$brand['banner'],
            'logo'      => $baseUrl."shopbybrand/brand/image".$brand['logo'],
            'description'=> $brand['description'],
            'alt'       => $brand['name'],
            'type'      => '1',
            'page_title' => $brand['page_title'],
            'meta_keywords' => $brand['meta_keywords'],
            'meta_description' => $brand['meta_description']
        ];
        $ch = curl_init($data['logo']);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($retcode==200) {
        } else {
            $data['logo'] =$this->getViewFileUrl('Magento_Catalog::images/product/placeholder/thumbnail.jpg');
        }
        return $data;
    }
    /**
     * Return file image
     */
    public function getUrlImage($name)
    {
        return $this->getViewFileUrl('Magenest_ShopByBrand::images/'.$name);
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl();
    }

    /**
     * @return mixed
     */
    public function getBrandRewrite()
    {
        return $this->_scopeConfig->getValue('shopbybrand/page/url');
    }

    /**
     * @return string
     */
    public function getLinkShare()
    {
        $baseUrl = $this->getBaseUrl();
        $link = $this->getBrandInfo();
        $brandRewrite = $this->getBrandRewrite();
        $linkShare = $baseUrl.$brandRewrite.'/'.$link['url_key'];
        return $linkShare;
    }

    /**
     * @return $this
     */
    public function _prepareLayout()
    {
        $brand = $this->getBrandInfo();
        
        $page_title = $brand['page_title'];
        $meta_keywords = $brand['meta_keywords'];
        $meta_description = $brand['meta_description'];
        
        if ($page_title) {
            $this->pageConfig->getTitle()->set(__($page_title));
        }

        if ($meta_keywords) {
            $this->pageConfig->setKeywords($meta_keywords);
        }
        
        if ($meta_description) {
            $this->pageConfig->setDescription($meta_description);
        }

        return parent::_prepareLayout();
    }


    /**
     * @param $title
     * @param $linkShare
     * @param $summary
     * @param $imageurl
     *
     * @return string
     */
    public function linkShareFace($title, $linkShare, $summary, $imageurl)
    {
        $facebook_share_link ="https://www.facebook.com/sharer.php?s=100&amp;p[title]=" . $title . "&amp;p[url]=" . $linkShare . "&amp;p[summary]=" . $summary . "&amp;p[images][0]=" . $imageurl ;

        return $facebook_share_link;
    }

    /**
     * @param $linkShare
     * @param $twitter_summary
     *
     * @return string
     */
    public function linkShareTwitter($linkShare, $twitter_summary)
    {
        $twitter_share_link = "https://twitter.com/share?url=" . $linkShare . "&amp;text=" . $twitter_summary ;

        return $twitter_share_link;
    }

    /**
     * @param $linkShare
     * @param $title
     *
     * @return string
     */
    public function linkShareGoogle($linkShare, $title)
    {
        $google_share_link = "https://plus.google.com/share?url=" . $linkShare . '&amp;title=' . $title ;

        return $google_share_link;
    }

    /**
     * @param $linkShare
     * @param $title
     * @param $summary
     *
     * @return string
     */
    public function linkSharePinterest($linkShare, $image, $summary)
    {
        $pinterest_share_link = "https://www.pinterest.com/pin/create/button/?url=".$linkShare."&media=".$image."&description=".$summary;

        return $pinterest_share_link;
    }
}
