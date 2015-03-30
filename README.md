NAME
----
	phpapi: The PHP Documentation Creator.
    This is the fork of the Peej's Quick & Dirty PHPDoc Clone.

VERSION
-------
	3.0

DESCRIPTION
-----------
    phpapi is a Javadoc style comment parser for PHP, written with an emphasis on speed and simplicity. It is designed to be as close a clone to Javadoc as possible.
    phpapi - это парсер комментариев в стиле Javadoc для PHP, написан с акцентом на скорость и простоту и как можно ближе к клону Javadoc.

REQUIREMENTS
------------
	PHP 5.3+ is required.

ABOUT
-----
	phpapi uses the PHP tokenizer extension, this means that it lets PHP do the parsing of your source code. phpAPI just takes the tokens PHP parses out and turns them into API documentation.
    phpapi использует PHP-расширение tokenizer, анализирует токены PHP и транслирует их в документацию по API.

FEATURES
--------
	* Fast running speed, uses PHP tokenizer function to take advantage of PHPs
      internal parsing functionality.
	* Parsing of any valid PHP file, with multiple classes and functions in the same
      file.
	* Simple output template layer, allowing easy changing of the output format by
      copying and editing of a few simple PHP template files.
	* Simple to install and use, instant results.
	* Will generates API documentation bare bones without Javadoc comments present.
	* Works with both OO and procedural code, also documents global variables and
      constants.
	* Minimal changes to Sun's Javadoc specification.
	* Javadoc extensions compatible with other PHPDoc programs.
    * Original template system for output.

INSTALLATION
------------
	Unzip the archive somewhere, edit the default config file and then run phpapi.php
    with your CLI version of PHP.

    Распакуйте архив, отредактируйте конфигурационный файл и запустите phpapi.php.

FILES
-----
	phpapi.ini - Default ini file
	README.md  - This file
	classes/*.php - Classes used by phpapi
	doclets/standard/*.php - Standard HTML doclet
    formatters/*.php - Formatters

USAGE
-----
    # ./phpapi.php (if your config file is phpapi.ini)
    or
	# ./phpapi.php config.ini
    or
    # ./phpapi.php config.ini > result.txt (To save output mesages)

	To create a config file for your project, copy, rename the phpini.ini file and
    edit it to your needs, it's fully commented.

    Для созданияя конфигурационного файла вашего проекта, скопируйте, переименуйте
    phpapi.ini и отредактируйте его в соответствие с имеющемися комментариями.

CONFIGURATION
-------------
    phpapi supports a number of configuration directives:
    phpapi поддерживает следующие директивы:

	* source  - The directory to look for files in, if not used the phpAPI will look
                in the current directory (the directory it is run from).
    * destination - The directory to place generated documentation in. If the given
                    path is relative to it will be relative to "source".
    * files   - Names of files to parse. This can be a single filename, or a comma
                separated list of filenames. Wildcards are allowed.
    * ignore  - Names of files or directories to ignore. This can be a single
                filename, or a comma separated list of files and direcories.
                Wildcards are NOT allowed.
	* subdirs = on|off - If you do not want phpAPI to look in each sub directory for
                         files set this option to "off".
    * doclet  - Select the doclet to use for generating output.
	* quiet   = on|off   - Quiet mode suppresses all output other than warnings and
                           errors.
	* verbose = on|off - Verbose mode outputs additional messages during execution.
	* default_package - If the code you are parsing does not use package tags or not
                        all elements have package tags, use this setting to place
                        unbound elements into a particular package.
	* overview - Specifies the name of a HTML file containing text for the overview
                 documentation to be placed on the overview page. The path is
                 relative to "source" unless an absolute path is given.
	* globals   = on|off - Parse out global variables.
	* constants = on|off - Parse out constants.
	* private   = on|off - Parse out private class members.
	* protected = on|off - Parse out protected class members.
	* public    = on|off - Parse out public class members.
	* windowtitle - Specifies the title to be placed in the HTML page.
	* doctitle    - Specifies the title to be placed near the top of the overview
                    summary file.
	* header - Specifies the header text to be placed at the top of each output file.
               The header will be placed to the right of the upper navigation bar.
	* footer - Specifies the footer text to be placed at the bottom of each output
               file. The footer will be placed to the right of the lower navigation
               bar.
	* tree   - Create a class tree.

DOC COMMENTS
------------
	A full description of the format of doc comments can be found on the Sun Javadoc
    web site (http://java.sun.com/j2se/javadoc/). Doc comments look like this:

	/**
	 * This is the typical format of a simple documentation comment
	 * that spans two lines.
	 * 
	 * @param string $var Description
	 */

TAGS
----
	phpapi supports the following tags:
    phpapi поддерживает следующие теги:

	@abstract    - defining a class or method as abstract.
	@access type - the access type of the field or method.
	@author name - author name, email, web...
	@deprecated  - deprecated
	@final       - defining a class or method as final.
	{@link package.class#member label}
	{@linkplain package.class#member label}
	@package name - places an item into a specific package and is valid within any doc
                    comment of a top level item.
	@param type name description - description of method or function parameters.
	@return type description     - description of the return value.
	@see package.class#member    - link to corresponding information.
	@since   - since-text.
	@static  - defining a class, method or member variable as static.
	@var type name description - variable description.
	@version - version description.

	Some Javadoc tags are not relevant to PHP and so are ignored, others are added or
    slightly changed due to PHPs loose typing.

COPYRIGHT AND LICENSE
---------------------
	This program is a fork of the PHPDoctor: The PHP Documentation Creator v2.0.5
    Copyright (C) 2005 Paul James <paul@peej.co.uk>
    Copyright (C) 2015 Victor Nabatov <greenray.spb@gmail.com>

	This program is free software; you can redistribute it and/or modify it	under the
    terms of the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported
    License <http://creativecommons.org/licenses/by-nc-sa/3.0/>

	This program is distributed in the hope that it will be useful, but WITHOUT ANY
    WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
    PARTICULAR PURPOSE.

    Эта программа является свободной; вы можете распространять и/или модифицировать ее
    в соответствии с условиями Creative Commons Attribution-NonCommercial-ShareAlike
    3.0 Unported License <http://creativecommons.org/licenses/by-nc-sa/3.0/>

    Эта программа распространяется в надежде что она будет полезной, но БЕЗ КАКИХ-ЛИБО
    ГАРАНТИЙ; даже без подразумеваемых гарантий КОММЕРЧЕСКОЙ ЦЕННОСТИ или ПРИГОДНОСТИ
    ДЛЯ КОНКРЕТНОЙ ЦЕЛИ.
