<?php
declare(strict_types=1);

namespace Yireo\VsfConfigValidator\Application\Magento1;

use Yireo\VsfConfigValidator\ApplicationInterface;
use Yireo\VsfConfigValidator\Validator\ValidatorInterface;
use Yireo\VsfConfigValidator\VsfConfiguration;

/**
 * Class VsBridgeValidator
 * @package Yireo\VsfConfigValidator\Application\Magento1
 */
class VsBridgeValidator implements ValidatorInterface
{
    /**
     * @inheritDoc
     */
    public function validate(ApplicationInterface $application, VsfConfiguration $vsf): bool
    {
        return true;
    }
}
