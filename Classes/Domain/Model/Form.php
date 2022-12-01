<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/jw-forms.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\JwForms\Domain\Model;

use TYPO3\CMS\Extbase\Annotation\Validate;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Domain model for form records
 */
class Form extends AbstractEntity
{
    /**
     * @var string
     *
     * @Validate("NotEmpty")
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

    public function __construct()
    {
        $this->initStorageObjects();
    }

    protected function initStorageObjects(): void
    {
        $this->file = new ObjectStorage();
        $this->categories = new ObjectStorage();
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): Form
    {
        $this->title = $title;
        return $this;
    }

    public function getFile(): ?ObjectStorage
    {
        return $this->file;
    }

    public function setFile(ObjectStorage $file): Form
    {
        $this->file = $file;
        return $this;
    }

    public function getCategories(): ?ObjectStorage
    {
        return $this->categories;
    }

    public function setCategories(ObjectStorage $categories): Form
    {
        $this->categories = $categories;
        return $this;
    }

    public function getUrlToFile(): string
    {
        return $this->urlToFile;
    }

    public function setUrlToFile(string $urlToFile): Form
    {
        $this->urlToFile = $urlToFile;
        return $this;
    }

    public function getTags(): string
    {
        return $this->tags;
    }

    public function setTags(string $tags): Form
    {
        $this->tags = $tags;
        return $this;
    }
}
