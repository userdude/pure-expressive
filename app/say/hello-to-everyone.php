<?php

declare(strict_types=1);

namespace App\Say;

interface HelloToEveryone {
    public function __invoke();
}

$sayHello = $context->service('say/hello');

return fn(string ...$names): array => array_map($sayHello, $names);
