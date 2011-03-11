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

var_dump(BaseConverter::convertFromBinary(chr(1).chr(1), '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz./'));
