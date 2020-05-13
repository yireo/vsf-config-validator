<?php

declare(strict_types=1);

use Yireo\VsfConfigValidator\Application\Magento1;
use Yireo\VsfConfigValidator\Application\Magento2;
use Yireo\VsfConfigValidator\Help;
use Yireo\VsfConfigValidator\Validator;
use Yireo\VsfConfigValidator\VsfConfiguration;

require_once __DIR__.'/src/Help.php';
require_once __DIR__.'/src/Validator.php';
require_once __DIR__.'/src/VsfConfiguration.php';
require_once __DIR__.'/src/ApplicationInterface.php';

$pOption = getopt("p:");
$dOption = getopt("d:");
$cOption = getopt("c:");

if (empty($pOption) || empty($dOption) || empty($cOption)) {
    Help::show();
}

$platform = array_shift($pOption);
$directory = array_shift($dOption);
$jsonFile = array_shift($cOption);
if (empty($jsonFile) || empty($directory)) {
    Help::show();
}

if (!in_array($platform, ['magento1', 'magento2'])) {
    die('Unknown platform');
}

if (!is_dir($directory)) {
    die('Directory "' . $directory . '" does not exist');
}

if ($platform === 'magento1' && !is_file($directory . '/app/etc/local.xml')) {
    die('Directory "' . $directory . '" is not a Magento 1 directory');
}

if ($platform === 'magento2' && !is_file($directory . '/app/etc/env.php')) {
    die('Directory "' . $directory . '" is not a Magento 2 directory');
}

if (!is_file($jsonFile) || !preg_match('/\.json$/', $jsonFile)) {
    die('JSON file "' . $jsonFile . '" is not a valid file');
}



if ($platform === 'magento1') {
    require_once __DIR__.'/src/Application/Magento1.php';
    $application = new Magento1($directory);
}

if ($platform === 'magento2') {
    require_once __DIR__.'/src/Application/Magento2.php';
    $application = new Magento2($directory);
}

$configuration = new VsfConfiguration($jsonFile);
$validator = new Validator($application, $configuration);

$validator->checkForConfigValuesInMagentoAttributes('entities/productList/includeFields');
$validator->checkForMagentoAttributesInConfig('entities/productList/includeFields');
$validator->checkForConfigValuesInMagentoAttributes('entities/productList/excludeFields');
$validator->checkForMagentoAttributesInConfig('entities/productList/excludeFields');
$validator->checkForConfigValuesInMagentoAttributes('entities/productListWithChildren/includeFields');
$validator->checkForMagentoAttributesInConfig('entities/productListWithChildren/includeFields');
$validator->checkForConfigValuesInMagentoAttributes('entities/productListWithChildren/excludeFields');
$validator->checkForMagentoAttributesInConfig('entities/productListWithChildren/excludeFields');
$validator->checkForConfigValuesInMagentoAttributes('entities/product/standardSystemFields');
$validator->checkForMagentoAttributesInConfig('entities/product/standardSystemFields');
$validator->checkForConfigValuesInMagentoAttributes('entities/product/excludeFields');
$validator->checkForMagentoAttributesInConfig('entities/product/excludeFields');

# End
