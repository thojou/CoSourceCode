# ILIAS "CoSourceCode" Plugin

![Static Badge](https://img.shields.io/badge/PHP_Version-%3E%3D7.4-blue)
[![License](https://img.shields.io/github/license/thojou/CoSourceCode)](./LICENSE)

The **CoSourceCode** plugin introduces a new ILIAS page component designed for displaying source code files with syntax highlighting.
Unlike the SourceCode PageComponent included in the ILIAS Core, this plugin offers support for a wider range of programming
languages and allows users to choose from various modern themes for displaying content.

The plugin utilizes [highlight.php](https://github.com/scrivo/highlight.php), a PHP adaptation of the widely popular JavaScript library, [highlight.js](https://highlightjs.org/).
This enables it to provide robust syntax highlighting capabilities and enhances the overall user
experience when working with source code within ILIAS.

## Requirements
* PHP >= 7.4
* ILIAS >= 8.0

## Installation

In Order to install the plugin, clone the repository into the ILIAS Customizing directory for the related Plugin slot.

```bash
cd <ILIAS_ROOT>
mkdir -p Customizing/global/plugins/Services/COPage/PageComponent
cd Customizing/global/plugins/Services/COPage/PageComponent
git clone https://github.com/thojou/CoSourceCode.git CoSourceCode
```

After cloning the repository, you need to install the plugin dependencies utilizing the popular PHP package manager [composer](https://getcomposer.org/).

```bash
cd CoSourceCode
composer install --no-dev
```

After installing the dependencies, you can activate the plugin in the ILIAS administration interface.

## Configuration

The plugin can be configured in the ILIAS administration interface. The following configuration options are available:

* **Default Theme**: The default theme to use for displaying source code files. The default value is `atom-one-dark`.
* **Default Language**: The default language to use for displaying source code files. The default value is `php`.
* **Active Themes**: A list of themes that are available for selection when displaying source code files. The default value is `atom-one-dark, atom-one-light`.
* **Active Languages**: A list of languages that are available for selection when displaying source code files. The default value is `bash, c, c++, css, go, html, js, java, php, python, ruby, vb, xml`.

When configuring the plugin, you can choose from a wide range of themes and languages. You can customize the available options to your liking.
