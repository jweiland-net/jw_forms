<?php
namespace JWeiland\JwForms\Domain\Model;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Class Form
 *
 * @package JWeiland\JwForms\Domain\Model
 */
class Form extends AbstractEntity
{
    /**
     * Title
     *
     * @var string
     *
     * @validate NotEmpty
     */
    protected $title = '';

    /**
     * File
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    protected $file;

    /**
     * Categories
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category>
     */
    protected $categories;

    /**
     * URL to file
     *
     * @var string
     */
    protected $urlToFile = '';

    /**
     * Tags
     *
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
    protected function initStorageObjects()
    {
        $this->file = new ObjectStorage();
        $this->categories = new ObjectStorage();
    }

    /**
     * Returns the title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title
     *
     * @param string $title
     *
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Returns the file
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage $file
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Sets the file
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $file
     *
     * @return void
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * Returns the categories
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage $categories
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Sets the categories
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $categories
     *
     * @return void
     */
    public function setCategories(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $categories)
    {
        $this->categories = $categories;
    }

    /**
     * Returns the urlToFile
     *
     * @return string $urlToFile
     */
    public function getUrlToFile()
    {
        return $this->urlToFile;
    }

    /**
     * Sets the urlToFile
     *
     * @param string $urlToFile
     *
     * @return void
     */
    public function setUrlToFile($urlToFile)
    {
        $this->urlToFile = $urlToFile;
    }

    /**
     * Returns the tags
     *
     * @return string $tags
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Sets the tags
     *
     * @param string $tags
     *
     * @return void
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }
}
