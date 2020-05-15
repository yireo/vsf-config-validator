<?php
declare(strict_types=1);

namespace Yireo\VsfConfigValidator;

/**
 * Interface ApplicationInterface
 */
interface ApplicationInterface
{
    /**
     * @return string[]
     */
    public function getValidatorClasses(): array;

    /**
     * @return string[]
     */
    public function getAttributeCodes(): array;
}
