<?php

declare(strict_types=1);

namespace Service\Make;

use function Format\pascal;
use function Format\sf;
use function Format\template;
use function Service\path;
use function Service\ns;

interface Service {
    public const TEMPLATE = '
<?php

declare(strict_types=1);

namespace {{ namespace }};

use App\Context;
{{ use }}
/** @var Context $context */
{{ functions }}
interface {{ interface }} {
    // TODO: Synchronize service definition arguments with __invoke()
    public function __invoke({{ arguments }});
}
{{ services }}
return {{ definition }}
';
    
    public const FULL_DEFINITION = '
function() use(&$context) {
    // TODO: Implement {{ name }} service
    \Service\implement(\'{{ name }}\');
};
';
    
    public const SHORT_DEFINITION = '
fn({{ arguments }}) => \Service\implement(\'{{ name }}\'); // TODO: Implement {{ name }} service
';
    
    public function __invoke(string $name, bool $useFullDefinition = true): string;
}

return function(string $name, bool $useFullDefinition = true): string {
    if (!file_exists($path = path($name))) {
        $directory = dirname($path);
        
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        
        $definition = trim($useFullDefinition ? Service::FULL_DEFINITION : Service::SHORT_DEFINITION);
        
        $rendered = template(Service::TEMPLATE, [
            'namespace' => ns(sf('app/%s', $name), 1),
            'use' => '',
            'functions' => '',
            'interface' => pascal(basename($name)),
            'arguments' => '',
            'services' => '',
            'definition' => template($definition, [
                'arguments' => '',
                'name' => $name,
            ]),
        ]);
        
        if (file_put_contents($path, trim($rendered).PHP_EOL)) {
            return sf('Success! %s service file generated.', trim($path, '/'));
        }
        
        throw new \RuntimeException(sf('Failed! Did not create service %s file at %s!', $name, trim($path, '/')));
    }
    
    throw new \RuntimeException(sf('Service %s already exists at path %s.', $name, trim($path, '/')));
};
