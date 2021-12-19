<?php

declare(strict_types=1);

namespace App\Make\Service;

use App\Context;

/** @var Context $context */

interface HelloWorld {
    /**
     * Generate Hello world example service.
     */
    public function __invoke();
}

$make = $context->service('make/service');

return fn() => $make('say/hello-world', (object) [
    '@description' => 'Hello world! example service.',
    'service' => "fn(): string => 'Hello world!';",
]);
