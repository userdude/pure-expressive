<?php

declare(strict_types=1);

namespace App\Foo;

interface Bar {
    public function __invoke();
}

return fn() => 'Baz!!!';
