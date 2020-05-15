<?php
declare(strict_types=1);

namespace Yireo\VsfConfigValidator\Validator;

use InvalidArgumentException;
use Yireo\VsfConfigValidator\ApplicationInterface;
use Yireo\VsfConfigValidator\VsfConfiguration;

/**
 * Class StoreViewValidator
 * @package Yireo\VsfConfigValidator\Validator
 */
class StoreViewValidator implements ValidatorInterface
{
    /**
     * @inheritDoc
     */
    public function validate(ApplicationInterface $application, VsfConfiguration $vsf): bool
    {
        $this->validateWhetherStoreViewsAreMapped($application, $vsf);
        $this->validateWhetherStoreViewDetailsAreValid($application, $vsf);

        return true;
    }

    /**
     * @param ApplicationInterface $application
     * @param VsfConfiguration $vsf
     * @return bool
     */
    private function validateWhetherStoreViewsAreMapped(ApplicationInterface $application, VsfConfiguration $vsf): bool
    {
        $storeViews = $application->getStoreViews();
        $configPath = 'storeViews/mapStoreUrlsFor';

        try {
            $mappedStoreUrls = $vsf->getDataFromPath($configPath);
        } catch(InvalidArgumentException $exception) {
            $mappedStoreUrls = [];
        }

        if (empty($mappedStoreUrls) && !empty($storeViews)) {
            echo "WARNING: There are StoreViews in Magento while the VSF config '$configPath' is empty\n";
        }

        foreach ($storeViews as $storeView) {
            if (!in_array($storeView->getCode(), $mappedStoreUrls)) {
                echo "WARNING: Magento StoreView '".$storeView->getCode()."' is not defined in VSF config '$configPath'\n";
            }

            $match = false;
            foreach ($mappedStoreUrls as $mappedStoreUrl) {
                if ($mappedStoreUrl === $storeView->getCode()) {
                    $match = true;
                }
            }

            if ($match === false) {
                echo "WARNING: Mapped StoreView '".$mappedStoreUrl."' in VSF config '$configPath' does not exist in Magento\n";
            }
        }

        return true;
    }

    /**
     * @param ApplicationInterface $application
     * @param VsfConfiguration $vsf
     * @return bool
     */
    private function validateWhetherStoreViewDetailsAreValid(ApplicationInterface $application, VsfConfiguration $vsf): bool
    {
        $configPath = 'storeViews/mapStoreUrlsFor';

        try {
            $mappedStoreUrls = $vsf->getDataFromPath($configPath);
        } catch(InvalidArgumentException $exception) {
            return true;
        }

        foreach($mappedStoreUrls as $mappedStoreUrl) {
            $configPath = 'storeViews/'.$mappedStoreUrl;
            try {
                $storeViewDetails = $vsf->getDataFromPath($configPath);
            } catch(InvalidArgumentException $exception) {
                echo "WARNING: VSF config path '$configPath' does not exist\n";
                return false;
            }

            if ($storeViewDetails['storeCode'] !== $mappedStoreUrl) {
                echo "WARNING: Shouldn't the VSF config '$configPath' have a storeCode '$mappedStoreUrl'\n";
            }
        }

        return true;
    }
}
