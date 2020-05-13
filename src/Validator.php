<?php
declare(strict_types=1);

namespace Yireo\VsfConfigValidator;

/**
 * Class Validator
 * @package Yireo\VsfConfigValidator
 */
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
     * @var ApplicationInterface
     */
    private $application;

    /**
     * @var VsfConfiguration
     */
    private $vsf;

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
                echo "WARNING: Magento attribute '$magentoAttributeCode' is not defined in configuration '$path'\n";
            }
        }
    }
}
