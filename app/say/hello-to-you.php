<?php

declare(strict_types=1);

namespace App\Say;

interface HelloToYou {
    public function __invoke(string $you): string;
}

return fn(string $you): string => sprintf('Hello to you, %s!', $you);
