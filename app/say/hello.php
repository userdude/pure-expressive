<?php

declare(strict_types=1);

namespace App\Say;

interface Hello {
    public function __invoke();
}

return fn(): string => 'Hello!';
