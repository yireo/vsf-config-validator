<?php
declare(strict_types=1);

namespace Yireo\VsfConfigValidator;

use Yireo\VsfConfigValidator\Application\Generic\StoreView;

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

    /**
     * @return StoreView[]
     */
    public function getStoreViews(): array;
}
