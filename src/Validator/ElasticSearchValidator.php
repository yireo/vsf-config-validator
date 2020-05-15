<?php
declare(strict_types=1);

namespace Yireo\VsfConfigValidator\Validator;

use Yireo\VsfConfigValidator\ApplicationInterface;
use Yireo\VsfConfigValidator\VsfConfiguration;

/**
 * Class ElasticSearchValidator
 * @package Yireo\VsfConfigValidator\Validator
 */
class ElasticSearchValidator implements ValidatorInterface
{
    /**
     * @inheritDoc
     */
    public function validate(ApplicationInterface $application, VsfConfiguration $vsf): bool
    {
        $elasticSearchData = $vsf->getDataFromPath('elasticsearch');
        if (!empty($elasticSearchData['host']) && !empty($elasticSearchData['index'])) {
            $url = $elasticSearchData['host'].'/'.$elasticSearchData['index'].'/_search';
            $data = $this->getDataFromRemoteUrl($url);

            if (empty($data)) {
                echo "WARNING: ElasticSearch request with URL `".$url." failed`\n";
            }

        }

        return true;
    }

    /**
     * @param string $url
     * @return array
     */
    private function getDataFromRemoteUrl(string $url): array
    {
        $body = file_get_contents($url);
        $data = json_decode($body, true);
        return $data;
    }
}
