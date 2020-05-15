<?php
declare(strict_types=1);

namespace Yireo\VsfConfigValidator;

use InvalidArgumentException;

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
     *
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
     *
     * @throws InvalidArgumentException
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
     * @throws InvalidArgumentException
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
