<?php

use CryptLib\Core\BaseConverter;

/**
 * Let's generate some random strings!  For this, we'll use the 
 * CryptLib\Random\Factory class to build a random number generator to suit
 * our needs here.
 */

//We first load the bootstrap file so we have access to the library
require_once dirname(dirname(__DIR__)) . '/lib/CryptLib/bootstrap.php';

//Now, let's get a random number factory
$factory = new CryptLib\Random\Factory;

/**
 * Now, since we want a low strength random number, let's get a low strength 
 * generator from the factory.
 *
 * If we wanted stronger random numbers, we could change this to medium or high
 * but both use significantly more resources to generate, so let's just stick 
 * with low for the purposes of this example:
 */
$generator = $factory->getLowStrengthGenerator();

/**
 * We can now start generating our random strings.  The generator by default
 * outputs full-byte strings (character 0 - 255), so it's not safe to display
 * them directly.  Instead, let's convert them to hex to show the string.
 */
$number = $generator->generate(8);

printf("\nHere's our first random string: %s\n", bin2hex($number));

/**
 * We can also base64 encode it to display the string
 */
$number = $generator->generate(8);

printf("\nHere's a base64 encoded random string: %s\n", base64_encode($number));

/**
 * We also can UUEncode the string to display it
 */
$number = $generator->generate(8);

printf("\n Here's a UUEncoded random string: %s\n", convert_uuencode($number));

/**
 * Now, let's define a string of allowable characters to use for token
 * generation (this can be for one-time-use passwords, CRSF tokens, etc)
 */
$characters = '0123456789abcdefghijklmnopqrstuvwxyz' .
              'ABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%&;<>?';

/**
 * After that, we can generate the number.  Remember that each character is
 * 0 - 256 (base 256).  So if we're going to convert to base 72 such as with
 * the above characters, it's going to take approximately 4/3 the space (8 bits
 * per character for base 256 and about 6 bits per character for base 72).  So
 * to generate a 16 character token, we need to generate 12 random bytes
 */

$number = $generator->generate(12);

$converted = BaseConverter::convertFromBinary($number, $characters);

/**
 * Now, since we want to be sure we use 16 characters, we need to pad it out
 * since we really are dealing with a number which could be less than 16 chars.
 */

$converted = str_pad($converted, 16, '0', STR_PAD_LEFT);

printf("\n Here's our token: %s\n", $converted);
