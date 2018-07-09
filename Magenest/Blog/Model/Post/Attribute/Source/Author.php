<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Magenest\Blog\Model\Post\Attribute\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Eav\Model\Entity\Attribute\Source\SourceInterface;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\User\Model\ResourceModel\User\CollectionFactory as UserCollectionFactory;
use Magenest\Blog\Model\AuthorFactory;

/**
 * Class Author
 * @package Magenest\Blog\Model\Post\Attribute\Source
 */
class Author extends AbstractSource implements SourceInterface, OptionSourceInterface
{
    /**
     * @var UserCollectionFactory
     */
    protected $userCollectionFactory;

    /**
     * @var AuthorFactory
     */
    protected $author;

    /**
     * Author constructor.
     * @param AuthorFactory $authorFactory
     * @param UserCollectionFactory $userCollectionFactory
     */
    public function __construct(
        AuthorFactory $authorFactory,
        UserCollectionFactory $userCollectionFactory
    ) {
        $this->author = $authorFactory;
        $this->userCollectionFactory = $userCollectionFactory;
    }

    /**
     * Retrieve option array
     *
     * @return string[]
     */
    public function getOptionArray()
    {
        $result = [];
        /** @var \Magenest\Blog\Model\Author $user */
        foreach ($this->author->create()->getCollection() as $user) {
            $result[$user->getAuthorId()] = $user->getDisplayName();
        }

        return $result;
    }

    /**
     * Retrieve option array with empty value
     *
     * @return string[]
     */
    public function getAllOptions()
    {
        $result = [];
        foreach ($this->getOptionArray() as $index => $value) {
            $result[] = ['value' => $index, 'label' => $value];
        }

        return $result;
    }
}
