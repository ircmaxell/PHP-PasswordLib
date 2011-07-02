Library Dependencies:
--------------------
The only dependency PHP-CryptLib has to use as a library is the PHP version.  It is made to be completely indepedent of extensions, implementing functionality natively where possible.

 - PHP >= 5.3.3

Build Dependencies:
------------------

These dependencies are necessary to build the project for your environment (including running unit tests, packaging and code-quality checks)

**Pear Dependencies**

 - PDepend Channel (pear.pdepend.org)
   - pdepend/PHP_Depend >= 0.10.0

 - Phing Channel (pear.phing.info)
   - phing/Phing >= 2.4.0

 - PHPMD Channel (pear.phpmd.org)
   - phpmd/PHP_MD >= 1.1.0


 - PHPUnit Channel (pear.phpunit.de)
   - phpunit/PHPUnit >=3.5.0
   - phpunit/PHP_CodeBrowser >= 1.0.0
   - phpunit/phpcpd >= 1.3.0
   - phpunit/phploc >= 1.6.0

 - PHP-Tools Channel (pear.php-tools.net)
   - pat/vfsStream >= 0.8.0

 - Default Pear Channel
   - pear/PHP_CodeSniffer >= 1.3.0
   - pear/PHP_UML >= 1.5.0

**PHP Dependencies**

 - PHP >= 5.3.2
   - `php.ini` Settings:
     - `phar.readonly = Off`

 - PHP Extensions
   - XDebug
   - MCrypt
   - Hash (usually enabled)
   - Phar
   - Zip (For Packaging)
   - BZ2 (For Packaging)
   - XSL (For Documentation)

