# Vue Storefront 1 config validator for Magento 1 and Magento 2
A simple PHP CLI tool to check whether your Vue Storefront PWA configuration matches the structure of your Magento site.

## Usage
Clone this repository to your system:

    git clone git@github.com:yireo/vsf-config-validator.git

And run the CLI tool:

    cd vsf-config-validator/
    composer install
    php ./vsf-config-validator.php -p magento1 -d MAGENTO_DIRECTORY -c VSF_PWA_CONFIG_JSON

The flag `-p` should either be `magento1` or `magento2`.

The `MAGENTO_DIRECTORY` should be pointing to the root of your Magento instance. The `VSF_PWA_CONFIG_JSON` should be referring to the JSON file in your VSF PWA `config` folder, for example `config/local.json`.

## Errors vs warnings
Errors are given for things that you probably really want to fix. Warnings are a question that need to be answered per instance. For instance, for Magento attributes that are included from the configuration path `entities/productList/excludeFields`, a warning is given. However, implementing all Magento attributes in this configuration path is probably a bad idea anyway.

Probably errors are most useful, so you can pipe this command to only show errors:

    php ./vsf-config-validator.php -p magento1 -d MAGENTO_DIRECTORY -c VSF_PWA_CONFIG_JSON | grep ERROR:

## Current features
- Scanning for attributes that are defined in the VSF configuration but are actually not in Magento
- Scanning for attributes that are defined in Magento but not present in the VSF configuration
- Simple check for validity of ElasticSearch URL
- Validation of StoreViews

## Todo
- Support for API JSON file
- Match ElasticSearch indices
- Is it bad to add non-existing Magento attributes to `excludeFields`?
- Check why some attributes need to be skipped like `attributes_metadata`
- Scan for category options `includeFields`
- Scan for attribute options `includeFields`
- Add validator for API-specific sections
    - vsbridge section for M1 (secret, user)
    - Whitelist / AllowedHosts
