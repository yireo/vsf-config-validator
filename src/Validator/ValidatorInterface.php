<?php
declare(strict_types=1);

namespace Yireo\VsfConfigValidator\Validator;

use Yireo\VsfConfigValidator\ApplicationInterface;
use Yireo\VsfConfigValidator\VsfConfiguration;

/**
 * Interface ValidatorInterface
 * @package Yireo\VsfConfigValidator\Validator
 */
interface ValidatorInterface
{
    /**
     * Validate
     * @param ApplicationInterface $application
     * @param VsfConfiguration $vsf
     * @return bool
     */
    public function validate(ApplicationInterface $application, VsfConfiguration $vsf): bool;
}
