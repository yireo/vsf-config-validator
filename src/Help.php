<?php
declare(strict_types=1);

namespace Yireo\VsfConfigValidator;

/**
 * Class Help
 */
class Help
{
    public static function show()
    {
        echo 'Usage: php ' . basename(__FILE__) . ' [OPTIONS]' . PHP_EOL;
        echo 'Options: ' . PHP_EOL;
        echo ' -m MAGENTO_DIRECTORY' . PHP_EOL;
        echo ' -c VSF_CONFIG_JSON' . PHP_EOL;
        exit;
    }
}
