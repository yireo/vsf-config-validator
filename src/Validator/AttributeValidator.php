<?php
declare(strict_types=1);

namespace Yireo\VsfConfigValidator\Validator;

use Yireo\VsfConfigValidator\ApplicationInterface;
use Yireo\VsfConfigValidator\VsfConfiguration;

/**
 * Class AttributeValidator
 * @package Yireo\VsfConfigValidator\Validator
 */
class AttributeValidator implements ValidatorInterface
{
    const ELASTICSEARCH_ATTRIBUTES = [
        '_score'
    ];

    const SKIPPED_ATTRIBUTES = [
        'attribute_set_id',
        'attributes_metadata',
        'category',
        'category_ids',
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
        'sgn',
        'size_options',
        'sku',
        'slug',
        'special_price',
        'special_price_incl_tax',
        'specialPriceInclTax',
        'special_price_tax',
        'specialPriceTax',
        'stock',
        'tax_class_id',
        'tier_prices',
        'type_id',
    ];

    /**
     * @var ApplicationInterface
     */
    private $application;

    /**
     * @var VsfConfiguration
     */
    private $vsf;

    /**
     * Validate
     * @param ApplicationInterface $application
     * @param VsfConfiguration $vsf
     * @return bool
     */
    public function validate(ApplicationInterface $application, VsfConfiguration $vsf): bool
    {
        $this->application = $application;
        $this->vsf = $vsf;

        $this->checkForConfigValuesInMagentoAttributes('entities/productList/includeFields');
        $this->checkForMagentoAttributesInConfig('entities/productList/includeFields');
        $this->checkForConfigValuesInMagentoAttributes('entities/productList/excludeFields');
        $this->checkForMagentoAttributesInConfig('entities/productList/excludeFields');
        $this->checkForConfigValuesInMagentoAttributes('entities/productListWithChildren/includeFields');
        $this->checkForMagentoAttributesInConfig('entities/productListWithChildren/includeFields');
        $this->checkForConfigValuesInMagentoAttributes('entities/productListWithChildren/excludeFields');
        $this->checkForMagentoAttributesInConfig('entities/productListWithChildren/excludeFields');
        $this->checkForConfigValuesInMagentoAttributes('entities/product/standardSystemFields');
        $this->checkForMagentoAttributesInConfig('entities/product/standardSystemFields');
        $this->checkForConfigValuesInMagentoAttributes('entities/product/excludeFields');
        $this->checkForMagentoAttributesInConfig('entities/product/excludeFields');
        return true;
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
                continue; // @todo: Not implemented yet or not even needed?
            }

            if (!in_array($vsfAttribute, $this->application->getAttributeCodes())) {
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
        foreach ($this->application->getAttributeCodes() as $magentoAttributeCode) {
            if (!in_array($magentoAttributeCode, $vsfAttributes)) {
                echo "NOTICE: Magento attribute '$magentoAttributeCode' is not defined in configuration '$path'\n";
            }
        }
    }
}
