<?php
declare(strict_types=1);

namespace Database\Adapters;

use Configuration\Attribute\Configuration;
use Configuration\Attribute\Value;

#[Configuration]
class AdapterConfig
{
    #[Value("dataSource.adapter.type")]
    private string $adapterType;

    public function getAdapterType(): string
    {
        return mb_strtolower($this->adapterType);
    }
}
