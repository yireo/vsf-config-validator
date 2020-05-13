<?php
declare(strict_types=1);

namespace Yireo\VsfConfigValidator\Application;

use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory;
use Magento\Framework\App\Bootstrap;
use Magento\Framework\App\Http;
use Yireo\VsfConfigValidator\ApplicationInterface;

/**
 * Class Magento2
 */
class Magento2 implements ApplicationInterface
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
     * @var $objectManager
     */
    private $objectManager;

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
        require_once __DIR__ . '/app/bootstrap.php';

        $bootstrap = Bootstrap::create(BP, $_SERVER);
        /** @var Http $app */
        $app = $bootstrap->createApplication(Http::class);
        $bootstrap->run($app);
        $this->objectManager = $bootstrap->getObjectManager();
    }

    /**
     * @return array
     */
    public function getAttributeCodes(): array
    {
        if ($this->attributeCodes !== null) {
            return $this->attributeCodes;
        }

        $collectionFactory = $this->objectManager->get(
            CollectionFactory::class
        );

        $attributes = $collectionFactory->create();
        foreach ($attributes as $attribute) {
            $this->attributeCodes[] = $attribute->getCode();
        }

        return $attributes;
    }
}
