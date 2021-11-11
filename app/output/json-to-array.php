<?php

declare(strict_types=1);

namespace App\Output;

interface JsonToArray {
    public function __invoke(...$input): string;
}

return fn(array $input): array => $input;
