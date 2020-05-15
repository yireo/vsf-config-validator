<?php
declare(strict_types=1);

namespace Yireo\VsfConfigValidator;

use Yireo\VsfConfigValidator\Validator\Attribute;
use Yireo\VsfConfigValidator\Validator\ValidatorInterface;

/**
 * Class Validator
 * @package Yireo\VsfConfigValidator
 */
class Validator implements ValidatorInterface
{
    /**
     * @var ApplicationInterface
     */
    private $application;

    /**
     * @var VsfConfiguration
     */
    private $vsf;

    /**
     * @var string[]
     */
    private $subValidatorClasses = [
        Attribute::class
    ];

    /**
     * Validator constructor.
     * @param ApplicationInterface $application
     * @param VsfConfiguration $vsf
     */
    public function __construct(ApplicationInterface $application, VsfConfiguration $vsf)
    {
        $this->application = $application;
        $this->vsf = $vsf;
    }

    /**
     * @inheritDoc
     */
    public function validate(): bool
    {
        foreach ($this->subValidatorClasses as $subValidatorClass) {
            /** @var ValidatorInterface $subValidator */
            $subValidator = new $subValidatorClass($this->application, $this->vsf);
            $subValidator->validate();
        }

        return true;
    }
}
