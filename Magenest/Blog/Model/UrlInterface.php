<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Model;

/**
 * Interface UrlInterface
 * @package Magenest\Blog\Model
 */
interface UrlInterface
{
    /**
     * @param array $urlParams
     * @return string
     */
    public function getUrl($urlParams = []);

}