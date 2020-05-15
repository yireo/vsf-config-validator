<?php
declare(strict_types=1);

namespace Yireo\VsfConfigValidator;

use Yireo\VsfConfigValidator\Validator\AttributeValidator;
use Yireo\VsfConfigValidator\Validator\ElasticSearchValidator;
use Yireo\VsfConfigValidator\Validator\StoreViewValidator;
use Yireo\VsfConfigValidator\Validator\ValidatorInterface;

/**
 * Class Validator
 * @package Yireo\VsfConfigValidator
 */
class Validator implements ValidatorInterface
{
    /**
     * @var string[]
     */
    private $subValidatorClasses = [
        AttributeValidator::class,
        ElasticSearchValidator::class,
        StoreViewValidator::class
    ];

    /**
     * @inheritDoc
     */
    public function validate(ApplicationInterface $application, VsfConfiguration $vsf): bool
    {
        $subValidatorClasses = array_merge($this->subValidatorClasses, $application->getValidatorClasses());

        foreach ($subValidatorClasses as $subValidatorClass) {
            /** @var ValidatorInterface $subValidator */
            $subValidator = new $subValidatorClass();
            $subValidator->validate($application, $vsf);
        }

        return true;
    }
}
