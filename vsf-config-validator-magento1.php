<?php

declare(strict_types=1);

$mOption = getopt("m:");
$cOption = getopt("c:");

if (empty($mOption) || empty($cOption)) {
    Help::show();
}

$magentoDirectory = array_shift($mOption);
$jsonFile = array_shift($cOption);
if (empty($jsonFile) || empty($magentoDirectory)) {
    Help::show();
}

if (!is_dir($magentoDirectory) || !is_file($magentoDirectory . '/app/etc/local.xml')) {
    die('Directory "' . $magentoDirectory . '" is not a Magento directory');
}

if (!is_file($jsonFile) || !preg_match('/\.json$/', $jsonFile)) {
    die('JSON file "' . $jsonFile . '" is not a valid file');
}


/**
 * Class Help
 */
class Help
{
    public static function show()
    {
        echo 'Usage: php ' . basename(__FILE__) . ' [OPTIONS]' . PHP_EOL;
        echo 'Options: ' . PHP_EOL;
        echo ' -m MAGENTO_DIRECTORY' . PHP_EOL;
        echo ' -c VSF_CONFIG_JSON' . PHP_EOL;
        exit;
    }
}

/**
 * Class Magento
 */
class Magento
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

/**
 * Class VsfConfiguration
 */
class VsfConfiguration
{
    /**
     * @var string
     */
    private $jsonFile;

    /**
     * @var
     */
    private $data;

    /**
     * VsfConfiguration constructor.
     * @param string $jsonFile
     */
    public function __construct(
        string $jsonFile
    ) {
        $this->jsonFile = $jsonFile;
        $this->init();
    }

    /**
     * Initialize
     */
    public function init()
    {
        $data = json_decode(file_get_contents($this->jsonFile), true);
        if (!$data) {
            throw new InvalidArgumentException('Unable to read from JSON file "' . $this->jsonFile . '"');
        }

        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param string $path
     * @return array
     */
    public function getDataFromPath(string $path): array
    {
        $pathParts = explode('/', $path);
        $data = $this->data;
        foreach ($pathParts as $pathPart) {
            if (isset($data[$pathPart])) {
                $data = $data[$pathPart];
                continue;
            }

            throw new InvalidArgumentException("Path '$path' does not exist in configuration");
        }

        return $data;
    }
}

class Validator
{
    const ELASTICSEARCH_ATTRIBUTES = [
        '_score'
    ];

    const SKIPPED_ATTRIBUTES = [
        'attribute_set_id',
        'category',
        'color_options',
        'configurable_options',
        'configurable_children',
        'custom_attributes',
        'errors',
        'final_price',
        'final_price_incl_tax',
        'final_price_tax',
        'id',
        'is_configured',
        'links',
        'minimal_regular_price',
        'max_price',
        'max_regular_price',
        'options',
        'original_price',
        'original_price_incl_tax',
        'parentSku',
        'price_incl_tax',
        'priceInclTax',
        'priceInclTax',
        'price_tax',
        'priceTax',
        'product_links',
        'product_option',
        'qty',
        'regular_price',
        'size_options',
        'slug',
        'special_price',
        'special_price_incl_tax',
        'specialPriceInclTax',
        'special_price_tax',
        'specialPriceTax',
        'stock',
        'tier_prices',
        'type_id',
    ];

    /**
     * @var Magento
     */
    private $magento;

    /**
     * @var VsfConfiguration
     */
    private $vsf;

    /**
     * Validator constructor.
     * @param Magento $magento
     * @param VsfConfiguration $vsf
     */
    public function __construct(Magento $magento, VsfConfiguration $vsf)
    {
        $this->magento = $magento;
        $this->vsf = $vsf;
    }

    /**
     * @param string $path
     */
    public function checkForConfigValuesInMagentoAttributes(string $path)
    {
        $vsfAttributes = $this->vsf->getDataFromPath($path);
        foreach ($vsfAttributes as $vsfAttribute) {
            $vsfAttribute = preg_replace('/^\*/', '', $vsfAttribute);
            $vsfAttribute = preg_replace('/^\./', '', $vsfAttribute);

            if (in_array($vsfAttribute, self::ELASTICSEARCH_ATTRIBUTES)) {
                continue;
            }

            if (in_array($vsfAttribute, self::SKIPPED_ATTRIBUTES)) {
                continue;
            }

            if (preg_match('/\./', $vsfAttribute)) {
                continue;
            } // @todo: Not implemented yet or not even needed?

            if (!in_array($vsfAttribute, $this->magento->getAttributeCodes())) {
                echo "ERROR: Config value '$path/$vsfAttribute' is not defined in Magento\n";
            }
        }
    }

    /**
     * @param string $path
     */
    public function checkForMagentoAttributesInConfig(string $path)
    {
        $vsfAttributes = $this->vsf->getDataFromPath($path);
        foreach ($this->magento->getAttributeCodes() as $magentoAttributeCode) {
            if (!in_array($magentoAttributeCode, $vsfAttributes)) {
                echo "WARNING: Magento attribute '$magentoAttributeCode' is not defined in configuration '$path'\n";
            }
        }
    }


}

$magento = new Magento($magentoDirectory);
$configuration = new VsfConfiguration($jsonFile);
$validator = new Validator($magento, $configuration);

$validator->checkForConfigValuesInMagentoAttributes('entities/productList/includeFields');
$validator->checkForMagentoAttributesInConfig('entities/productList/includeFields');
$validator->checkForConfigValuesInMagentoAttributes('entities/productList/excludeFields');
$validator->checkForMagentoAttributesInConfig('entities/productList/excludeFields');
$validator->checkForConfigValuesInMagentoAttributes('entities/productListWithChildren/includeFields');
$validator->checkForMagentoAttributesInConfig('entities/productListWithChildren/includeFields');
$validator->checkForConfigValuesInMagentoAttributes('entities/productListWithChildren/excludeFields');
$validator->checkForMagentoAttributesInConfig('entities/productListWithChildren/excludeFields');
$validator->checkForConfigValuesInMagentoAttributes('entities/product/standardSystemFields');
$validator->checkForMagentoAttributesInConfig('entities/product/standardSystemFields');
$validator->checkForConfigValuesInMagentoAttributes('entities/product/excludeFields');
$validator->checkForMagentoAttributesInConfig('entities/product/excludeFields');

# End
