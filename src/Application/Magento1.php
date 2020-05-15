<?php

declare(strict_types=1);

namespace Yireo\VsfConfigValidator\Application;

use Mage;
use Mage_Core_Model_Store;
use Yireo\VsfConfigValidator\Application\Generic\StoreView;
use Yireo\VsfConfigValidator\ApplicationInterface;
use Yireo\VsfConfigValidator\Application\Magento1\VsBridgeValidator;

/**
 * Class Magento1
 */
class Magento1 implements ApplicationInterface
{
    /**
     * @var string
     */
    private $directory;

    /**
     * @var array
     */
    private $attributeCodes;

    /**
     * Magento constructor.
     * @param string $directory
     */
    public function __construct(string $directory)
    {
        $this->directory = $directory;
        $this->init();
    }

    /**
     * Initialize
     */
    public function init()
    {
        define('MAGENTO_ROOT', realpath($this->directory));
        require_once $this->directory . '/app/bootstrap.php';
        require_once $this->directory . '/app/Mage.php';
        Mage::app();
    }

    /**
     * @inheritDoc
     */
    public function getValidatorClasses(): array
    {
        return [
            VsBridgeValidator::class
        ];
    }

    /**
     * @return array
     */
    public function getAttributeCodes(): array
    {
        if ($this->attributeCodes !== null) {
            return $this->attributeCodes;
        }

        $attributeCollection = Mage::getModel('eav/entity_attribute')->getCollection();
        $attributeCodes = [];
        foreach ($attributeCollection as $attribute) {
            $attributeCodes[] = $attribute->getAttributeCode();
        }

        $this->attributeCodes = $attributeCodes;
        sort($this->attributeCodes);

        return $this->attributeCodes;
    }

    /**
     * @inheritDoc
     */
    public function getStoreViews(): array
    {
        $return = [];
        $storeViews = Mage::getmodel('core/store')->getCollection();
        foreach ($storeViews as $storeView) {
            /** @var Mage_Core_Model_Store $storeView */
            $return[] = new StoreView(
                (int)$storeView->getId(),
                (string)$storeView->getCode(),
                (bool)$storeView->getIsActive()
            );
        }

        return $return;
    }
}
