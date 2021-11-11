<?php

declare(strict_types=1);

namespace Service\Make;

use function Service\ns;
use function Service\path;
use function Format\pascal;

interface Service {
    public function __invoke(string $name): string;
}

return function(string $name): string {
    if (!file_exists($path = path($name))) {
        $directory = dirname($path);
        
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        
        $template = sprintf(trim('
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
            return sprintf('Success! %s service file generated.', substr($path, 1));
        }
        
        return 'Failed! Did not create %s!';
    }
    
    return sprintf('Service %s already exists.', $name);
};
