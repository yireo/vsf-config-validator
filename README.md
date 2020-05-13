# Vue Storefront 1 config validator for Magento 1
A simple PHP CLI tool to check whether your Vue Storefront PWA configuration matches the structure of your Magento 1 site.

## Usage
Copy the PHP script to your system and run it:

    php ./vsf-config-validator-magento1.php -m MAGENTO_DIRECTORY -c VSF_CONFIG_JSON

The `MAGENTO_DIRECTORY` should be pointing to the root of your Magento 1 instance. The `VSF_CONFIG_JSON` should be referring to the JSON file in your VSF `config` folder, for example `config/local.json`.

## Errors vs warnings
Errors are given for things that you probably really want to fix. Warnings are a question that need to be answered per instance. For instance, for Magento attributes that are included from the configuration path `entities/productList/excludeFields`, a warning is given. However, implementing all Magento attributes in this configuration path is probably a bad idea anyway.

Probably errors are most useful, so you can pipe this command to only show errors:

    php ./vsf-config-validator-magento1.php -m MAGENTO_DIRECTORY -c VSF_CONFIG_JSON | grep ERROR:

## Current features
- Scanning for attributes that are defined in the VSF configuration but are actually not in Magento 1
- Scanning for attributes that are defined in Magento 1 but not present in the VSF configuration

## Todo
- Is it bad to add non-existing Magento attributes to `excludeFields`?
- Scan for StoreViews defined in Magento 1, but not defined in the VSF configuration
- Scan for category options `includeFields`
- Scan for attribute options `includeFields`

