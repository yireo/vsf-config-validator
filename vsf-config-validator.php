<?php

declare(strict_types=1);

use Yireo\VsfConfigValidator\Cli;
use Yireo\VsfConfigValidator\Application\Magento1;
use Yireo\VsfConfigValidator\Application\Magento2;
use Yireo\VsfConfigValidator\Validator;
use Yireo\VsfConfigValidator\VsfConfiguration;

$autoloader = __DIR__.'/vendor/autoload.php';
if (!file_exists($autoloader)) {
    die("Make sure to run `composer install` first\n");
}

require_once $autoloader;

$cli = new Cli;

$platform = $cli->getOption('p');
$directory = $cli->getOption('d');
$jsonFile = $cli->getOption('c');
if (empty($platform) || empty($jsonFile) || empty($directory)) {
    echo "ERROR: Empty options specified\n";
    $cli->show();
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
$validator->validate();

# End
