<?php

declare(strict_types=1);

namespace App\DataTransfert;

use Symfony\Component\Validator\Constraints as Assert;

final class QueryRecord
{
    public function __construct(
        #[Assert\NotBlank()]
        public ?string $code = null,
        public bool $recursive = false
    ) {
    }
}
