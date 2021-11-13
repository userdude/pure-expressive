<?php

declare(strict_types=1);

namespace Service\Make;

use function Format\pascal;
use function Format\sf;
use function Format\template;
use function Service\path;
use function Service\ns;

interface Service {
    public function __invoke(string $name): string;
}

return function(string $name): string {
    if (!file_exists($path = path($name))) {
        $directory = dirname($path);
        
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        
        $template = '
<?php

declare(strict_types=1);

namespace {{ namespace }};

use App\Context;

/** @var Context $context */

interface {{ interface }} {
    // TODO: Synchronize service definition arguments with __invoke()
    public function __invoke();
}

return function() use(&$context) {
    // TODO: Implement {{ name }} service
    \Service\implement(\'{{ name }}\');
};
';
        
        $rendered = template($template, [
            'namespace' => ns(sf('app/%s', $name), 1),
            'interface' => pascal(basename($name)),
            'name' => $name,
        ]);
        
        if (file_put_contents($path, trim($rendered).PHP_EOL)) {
            return sf('Success! %s service file generated.', trim($path, '/'));
        }
        
        throw new \DomainException(sf('Failed! Did not create service %s file at %s!', $name, trim($path, '/')));
    }
    
    throw new \RuntimeException(sf('Service %s already exists at path %s.', $name, trim($path, '/')));
};
