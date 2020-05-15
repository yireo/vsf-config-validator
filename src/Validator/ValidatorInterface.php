<?php
declare(strict_types=1);

namespace Yireo\VsfConfigValidator\Validator;

/**
 * Interface ValidatorInterface
 * @package Yireo\VsfConfigValidator\Validator
 */
interface ValidatorInterface
{
    /**
     * Validate
     * @return bool
     */
    public function validate(): bool;
}
