<?php

declare(strict_types=1);

namespace App\DataTransfert;

final class QueryRecord
{
    public function __construct(
        public ?string $code = null,
        public bool $recursive = false
    ) {
    }
}
