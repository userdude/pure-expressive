<?php

declare(strict_types=1);

namespace App\Say;

interface HelloToAnyone {
    public function __invoke(string $name): string;
}

return fn(string $name): string => sprintf('Hello %s!', $name ?: 'anyone');
