<?php

declare(strict_types=1);

namespace Domain\Make;

use function Format\pascal;
use function Format\sf;
use function Service\format;
use function Service\ns;
use function Service\path;

interface Service {
    public function __invoke(string $name): string;
}

return function(string $name): string {
    if (!file_exists($path = path($name))) {
        $directory = dirname($path);
        
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        
        $template = sf(trim('
<?php

declare(strict_types=1);

namespace %s;

interface %s {
    public function __invoke();
}

return function() use(&$context) {
    // TODO: Make %s happen.
};
'), ns($name, 1), pascal(basename($name)), $name);
        
        if (file_put_contents($path, $template)) {
            return sf('Success! %s service file generated.', substr($path, 1));
        }
        
        return 'Failed! Did not create %s!';
    }
    
    return sf('Service %s already exists.', $name);
};
