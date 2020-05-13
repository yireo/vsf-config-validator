<?php
declare(strict_types=1);

namespace Yireo\VsfConfigValidator\Application;

use Yireo\VsfConfigValidator\ApplicationInterface;
use Mage;

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
        return $this->attributeCodes;
    }
}
