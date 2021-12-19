<?php

declare(strict_types=1);

namespace Service\Make;

use function Format\pascal;
use function Format\sausage;
use function Format\sf;
use function Format\template;
use function Input\chomp;
use function Service\links;
use function Service\path;
use function Service\ns;

interface Service {
    public function __invoke(string $name): string;
}

$format = fn(?string $contents = null): string => $contents ? trim($contents) : '';
$formatSingle = fn(?string $contents = null): string => $contents ? PHP_EOL.trim($contents) : '';
$formatDouble = fn(?string $contents = null): string => $contents ? PHP_EOL.PHP_EOL.trim($contents) : '';

return function(string $name, object $options = null) use($format, $formatSingle, $formatDouble): string {
    if (!file_exists($path = path($name))) {
        $directory = dirname($path);
        
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        
        $config = [
            'uses' => $formatSingle,
            'uses_functions' => $formatDouble,
            'heading' => $formatDouble,
            '@description' => fn(?string $contents = null) => $contents
                ? $contents
                : '@todo Add service description for {{ name }}.',
            '@params' => $formatDouble,
            '@returns' => $formatDouble,
            'services' => $formatDouble,
            'service' => fn(?string $content = null) => trim($content ?? '
function({{ arguments }}) use({{ imports }}){{ response }} {
    \Service\implement(\'{{ name }}\');
};
'),
            'arguments' => $format,
            'response' => $format,
            'imports' => fn() => '&$context',
        ];
        
        $config += [ // Guarantee these are set.
            'namespace' => fn() => ns(sf('app/%s', $name), 1),
            'interface' => fn() => pascal(basename($name)),
            'name' => fn() => links('sausage', $name),
        ];
        
        $rendered = template(chomp((array) $options ?? [], $config, true), '
<?php

declare(strict_types=1);

namespace {{ namespace }};

use App\Context;{{ uses }}{{ uses_functions }}

/** @var Context $context */{{ heading }}

interface {{ interface }} {
    /**
     * {{ @description }}{{ @params }}{{ @returns }}
     */
    public function __invoke({{ arguments }}){{ response }};
}{{ services }}

return {{ service }}
');
        
        if (file_put_contents($path, trim($rendered).PHP_EOL)) {
            return sf('Success! %s service file generated.', trim($path, '/'));
        }
        
        throw new \RuntimeException(sf('Failed! Did not create service %s file at %s!', $name, trim($path, '/')));
    }
    
    throw new \RuntimeException(sf('Service %s already exists at path %s.', $name, trim($path, '/')));
};
