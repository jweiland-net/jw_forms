<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/jw_forms.
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\JwForms\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Class Form
 */
class Form extends AbstractEntity
{
    /**
     * @var string
     *
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $title = '';

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    protected $file;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category>
     */
    protected $categories;

    /**
     * @var string
     */
    protected $urlToFile = '';

    /**
     * @var string
     */
    protected $tags = '';

    /**
     * Constructor of this class.
     */
    public function __construct()
    {
        $this->initStorageObjects();
    }

    /**
     * Initializes all \TYPO3\CMS\Extbase\Persistence\ObjectStorage properties.
     */
    protected function initStorageObjects(): void
    {
        $this->file = new ObjectStorage();
        $this->categories = new ObjectStorage();
    }

    /**
     * @return string $title
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Form
     */
    public function setTitle(string $title): Form
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return ObjectStorage
     */
    public function getFile(): ObjectStorage
    {
        return $this->file;
    }

    /**
     * @param ObjectStorage $file
     * @return Form
     */
    public function setFile(ObjectStorage $file): Form
    {
        $this->file = $file;
        return $this;
    }

    /**
     * @return ObjectStorage $categories
     */
    public function getCategories(): ObjectStorage
    {
        return $this->categories;
    }

    /**
     * @param ObjectStorage $categories
     * @return Form
     */
    public function setCategories(ObjectStorage $categories): Form
    {
        $this->categories = $categories;
        return $this;
    }

    /**
     * @return string $urlToFile
     */
    public function getUrlToFile(): string
    {
        return $this->urlToFile;
    }

    /**
     * @param string $urlToFile
     * @return Form
     */
    public function setUrlToFile(string $urlToFile): Form
    {
        $this->urlToFile = $urlToFile;
        return $this;
    }

    /**
     * @return string $tags
     */
    public function getTags(): string
    {
        return $this->tags;
    }

    /**
     * @param string $tags
     * @return Form
     */
    public function setTags(string $tags): Form
    {
        $this->tags = $tags;
        return $this;
    }
}
