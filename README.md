# TYPO3 Extension `jw_forms`

[![Packagist][packagist-logo-stable]][extension-packagist-url]
[![Latest Stable Version][extension-build-shield]][extension-ter-url]
[![Total Downloads][extension-downloads-badge]][extension-packagist-url]
[![Monthly Downloads][extension-monthly-downloads]][extension-packagist-url]
[![TYPO3 13.4][TYPO3-shield]][TYPO3-13-url]

![Build Status](https://github.com/jweiland-net/jw_forms/workflows/CI/badge.svg)

With this extension you can provide a very simple list of files like PDFs
for download. It comes with a plugin to list and search the files.

## Feature

* Provide file for download
* Provide URL target for download
* Assign tags to form record
* You can search files by title, category title and tags

## 2 Usage

### 2.1 Installation

#### Installation using Composer

The recommended way to install the extension is using Composer.

Run the following command within your Composer based TYPO3 project:

```
composer require jweiland/jw-forms
```

#### Installation as extension from TYPO3 Extension Repository (TER)

Download and install `jw_forms` with the extension manager module.

### 2.2 Minimal setup

1) Install the extension
2) Check, if the form table was created
3) Create a new storage folder
4) Create a new form record on that storage folder
5) Assign title
6) Assign file or URL
7) Attach form plugin to a page
8) Insert static template of jw_forms extension
9) Update TypoScript storagePid constant to storage folder

## 3 Support

Free Support is available via [GitHub Issue Tracker](https://github.com/jweiland-net/jw_forms/issues).

For commercial support, please contact us at [support@jweiland.net](support@jweiland.net).

<!-- MARKDOWN LINKS & IMAGES -->

[extension-build-shield]: https://poser.pugx.org/jweiland/jw-forms/v/stable.svg?style=for-the-badge

[extension-downloads-badge]: https://poser.pugx.org/jweiland/jw-forms/d/total.svg?style=for-the-badge

[extension-monthly-downloads]: https://poser.pugx.org/jweiland/jw-forms/d/monthly?style=for-the-badge

[extension-ter-url]: https://extensions.typo3.org/extension/daycarecenters/

[extension-packagist-url]: https://packagist.org/packages/jweiland/jw-forms/

[packagist-logo-stable]: https://img.shields.io/badge/--grey.svg?style=for-the-badge&logo=packagist&logoColor=white

[TYPO3-13-url]: https://get.typo3.org/version/13

[TYPO3-shield]: https://img.shields.io/badge/TYPO3-13.4-green.svg?style=for-the-badge&logo=typo3
