<?php

declare(strict_types=1);

namespace Yireo\VsfConfigValidator\Application\Generic;

/**
 * Class StoreView
 * @package Yireo\VsfConfigValidator\Application\Generic
 */
class StoreView
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $code;

    /**
     * @var bool
     */
    private $isActive;

    /**
     * StoreView constructor.
     * @param int $id
     * @param string $code
     * @param bool $isActive
     */
    public function __construct(
        int $id,
        string $code,
        bool $isActive
    ) {
        $this->id = $id;
        $this->code = $code;
        $this->isActive = $isActive;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }
}
