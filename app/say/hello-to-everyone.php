<?php

declare(strict_types=1);

namespace App\Say;

interface HelloToEveryone {
    public function __invoke(string ...$names): string;
}

return fn(array $names = []): string => sprintf(
    'Hello %s and everyone else!',
    ucwords(implode(', ', count($names) ? $names : ['world']))
);
