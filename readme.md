#### NAME / НАЗВАНИЕ

phpapi: The PHP Documentation Creator.
This is the fork of the Peej's Quick & Dirty PHPDoc Clone.

#### VERSION / ВЕРСИЯ

5.0

#### DESCRIPTION / ОПИСАНИЕ

phpapi is a Javadoc style comment parser for PHP, written with an emphasis on speed and simplicity.
phpapi - это парсер комментариев в стиле Javadoc для PHP, написан с акцентом на скорость и простоту.

#### REQUIREMENTS / ТРЕБОВАНИЯ

PHP 5.4+ is required.

#### ABOUT / О ПРОГРАММЕ

phpapi uses the PHP tokenizer extension, parses the tokens and translates them into API documentation.
phpapi использует PHP-расширение tokenizer, анализирует токены PHP и транслирует их в документацию по API.

#### FEATURES / ВОЗМОЖНОСТИ

- Fast speed.
- Uses PHP tokenizer to take advantage of PHPs internal parsing functionality.
- Parsing of any valid PHP file, with multiple classes and functions in the same file.
- Simple to install and use, instant results.
- Documents global constants.
- Original template system for output.
- Высокая скорость.
- Использует PHP tokenizer для облегчения внутреннего разбора функциональности.
- Парсит любой корректный PHP-файл, с несколькими классами и функциями в одном файле.
- Простой в установке и использовании, мгновенный результат.
- Документирует глобальные константы.
- Оригинальная система шаблонов для вывода.

#### FILES / ФАЙЛЫ

    phpapi.php            - main executable
    phpapi.ini            - Default config file
    README.md             - This file
    system/classes/       - Classes used by phpapi
    system/frames/        - HTML doclet for output with frames
    system/plain/         - HTML doclet for output without frames
    system/markdown/      - Markdown parser
    system/taglets/       - Doc Comment tags parsers
    docs/                 - some docs and licenses
    locales/              - Translation files
    resources/            - Illustrations, javascript and so on
    templates/frames/     - Templates for output with frames
    templates/plain/      - Templates for output without frames
    api/                  - Developer documentation

#### INSTALLATION / УСТАНОВКА

Unzip the archive somewhere, edit the config file and then run phpapi.php.
Распакуйте архив, отредактируйте конфигурационный файл и запустите phpapi.php.

#### USAGE / ИСПОЛЬЗОВАНИЕ

    ./phpapi.php (if your config file is phpapi.ini)
    ./phpapi.php config.ini (if your config file is config.ini)
    ./phpapi.php config.ini > result.txt (To save output mesages)

To create a config file for your project, copy, rename the phpini.ini to project.ini file and edit it to your needs, it's fully commented.
Для созданияя конфигурационного файла вашего проекта, скопируйте, переименуйте phpapi.ini в project.ini и отредактируйте его в соответствие с имеющемися комментариями.

#### CONFIGURATION / КОНФИГУРАЦИЯ

phpapi supports a number of configuration directives:
phpapi поддерживает следующие директивы:

    source           - The directory to look for files in,
    destination      - The directory to place generated documentation in.
    files            - Names or wildcards of files to parse.
    ignore             Names of files or directories to ignore. Wildcards are NOT allowed.
    subDirs = on|off - To look in each sub directory for files.
    generator        - Documentation generator.
    formatter        - Documentation formatter.
    doclet           - The doclet to use for generating output.
    verbose = on|off - Verbose mode outputs additional messages during execution.
    defaultPackage   - It for elements without @package tag.
    overview         - The name of file containing overview text to be placed on the overview page.
    windowtitle      - The title to be placed in the HTML page.
    doctitle         - The title to be placed near the top of the overview summary file.
    header           - The header text to be placed at the top of each output file.

#### DOC COMMENTS

A full description of the format of doc comments can be found on the Sun Javadoc web site [javadoc](http://java.sun.com/j2se/javadoc/) .
Doc comments for phpAPI are look like this:

/** <br />
 \* This is the typical format of a simple documentation comment<br />
 \* in two lines.<br />
 \* <br />
 \* __@param__ string $var Description<br />
 \*/

Class fields may be commented as:

/** Comment.<br />
 \* __@var__ integer<br />
 \*/

or

/** __@var__ integer Comment */

#### TAGS / ТЭГИ

phpapi supports the following tags:
phpapi поддерживает следующие теги:

    @abstract                    - defining a class or method as abstract
    @access type                 - the access type of the field or method
    @author name                 - author name, email, web...
    @deprecated                  - deprecated element
    @final                       - defining a class or method as final
    { @link package.class#member label }      - link to element
    { @linkplain package.class#member label } - plain link to element
    @overview                    - detailed description of a package
    @package name                - package name
    @param type name description - description of method or function parameters
    @private                     - element is private
    @program                     - The title of the application, script or libruary
    @protected                   - element is protected
    @public                      - element is public
    @return type description     - return value
    @see package.class#member    - link to corresponding information
    @since                       - since-text
    @static                      - defining a class, method or member variable as static
    @throws                      - exeptions
    @var type name description   - variable description
    @version                     - version number

#### COPYRIGHT AND LICENSE

This program is a fork of the PHPDoctor: The PHP Documentation Creator v2.0.5

Copyright (C) 2005 Paul James <paul@peej.co.uk>
Copyright (C) 2016 Victor Nabatov <greenray.spb@gmail.com>

This program is free software.
You can redistribute it and/or modify it under the terms of the [Creative Commons Attribution-ShareAlike 4.0 International License](https://creativecommons.org/licenses/by-sa/4.0/legalcode) .
This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

Эта программа является свободной.
Вы можете распространять и/или модифицировать ее в соответствии c условиями [Creative Commons Attribution-ShareAlike 4.0 International License](https://creativecommons.org/licenses/by-sa/4.0/legalcode) .
Эта программа распространяется в надежде что она будет полезной, но БЕЗ КАКИХ-ЛИБО ГАРАНТИЙ;
даже без подразумеваемых гарантий КОММЕРЧЕСКОЙ ЦЕННОСТИ или ПРИГОДНОСТИ ДЛЯ КОНКРЕТНОЙ ЦЕЛИ.
