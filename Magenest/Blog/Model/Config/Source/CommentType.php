<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Model\Config\Source;

/**
 * Class CommentType
 * @package Magenest\Blog\Model\Config\Source
 */
class CommentType
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $result = [
            [
                'label' => __('Disable comments'),
                'value' => '',
            ],
            [
                'label' => 'Disqus',
                'value' => 'disqus'
            ],
            [
                'label' => 'Facebook',
                'value' => 'facebook'
            ],
            [
                'label' => 'In Site',
                'value' => 'insite'
            ]
        ];

        return $result;
    }

    /**
     * @return array
     */
    public function getAllOptions()
    {

        return $this->toOptionArray();
    }
}
