# TYPO3 Extension `jw_forms`

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
