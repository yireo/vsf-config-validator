<?php

declare(strict_types=1);

namespace Yireo\VsfConfigValidator;

/**
 * Class Cli
 */
class Cli
{
    /**
     * @param string $optionCode
     * @return string
     */
    public function getOption(string $optionCode): string
    {
        $options = getopt( "p:d:c:");
        return (string)$options[$optionCode];
    }

    /**
     * Show the available options
     */
    public function show()
    {
        echo 'Usage: php vsf-config-validator [OPTIONS]' . PHP_EOL;
        echo 'Options: ' . PHP_EOL;
        echo ' -p magento1|magento2' . PHP_EOL;
        echo ' -d MAGENTO_DIRECTORY' . PHP_EOL;
        echo ' -c VSF_CONFIG_JSON' . PHP_EOL;
        exit;
    }
}
