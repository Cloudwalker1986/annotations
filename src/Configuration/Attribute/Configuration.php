<?php
declare(strict_types=1);

namespace Configuration\Attribute;

use Attribute;
use InvalidArgumentException;
use Utils\Collection;
use Utils\Map;
use function PHPUnit\Framework\exactly;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_CLASS)]
class Configuration
{
    private array $config;

    public function __construct()
    {
        if (!defined('APPLICATION_CONFIG')) {
            throw new InvalidArgumentException('Application configuration is not defined');
        }

        $this->config = yaml_parse_file(APPLICATION_CONFIG);
    }

    public function getValueByPath(Value $value): string|int|float|array|Map|Collection|null|bool
    {
        return $this->parse($value->getPath());
    }

    private function parse(string $path): string|int|float|array|Map|Collection|null|bool
    {
        $value = null;
        $pathLevelList = explode('.', $path);
        $max = count($pathLevelList) - 1;
        $iterator = $this->config;
        for ($i = 0; $i <= $max; $i++) {
            if (isset($iterator[$pathLevelList[$i]])) {
                if (is_array($iterator[$pathLevelList[$i]])) {
                    $iterator = $iterator[$pathLevelList[$i]];
                    $value = $iterator;
                } else {
                    $value = $iterator[$pathLevelList[$i]];
                }
            } else {
                $value = null;
            }
        }
        return $value;
    }
}
