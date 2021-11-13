# Pure Expressive

A simple but powerful module loader written for PHP.

---

## Getting Started

### Requirements

- Docker: Required to use `./play` script (`app/console` will work with PHP 8.0).

### Installation

```shell
git clone https://github.com/userdude/pure-expressive.git pure-expressive

cd pure-expressive

./play make/service say/hello  
```

Now you can edit the `app/say/hello.php` file and change the service `return`:

```php
<?php // app/say/hello.php

...

return fn(?string $name = null): string => sprintf('Hello %s!', $name ?: 'world');
```

Now call it: 

```shell
$ ./play say/hello Jim
Hello Jim!
```

---

## About the Project

This is the most basic version of the Pure Expressive project I've been working
on for a while. The objective is to show as minimal as possible example of module
loading using a more modern PHP syntax and controllable environment (container).

Overall, the service implementation with script execution is <200 lines of code.
By itself this isn't too interesting. To a large degree I consider this a replacement
for pretty much any container requirement except where a package expressly needs
a container. Which of course you can add a service to get one for you. Really is
that easy. `:D`

First, let's look at our files.

- `play`: Calls a console service against the `app/console` php script.
- `play-worker`: This command starts a worker session that `play` uses to run the service 
   request. This provides a consistent PHP version (8.0 as of now).

This basic, compat-level expressive only has one folder, and two more files:

- `app/`: Directory with our service definitions.
- `app/console`: Used to call service commands directly from the command line.
- `app/make/service.php`: A simple make script.

**Important!** A service name is always minus the `app/` and `.php`. For instance,
`app/make/service/php` would become `make/service`, as in `./play make/service ...`.

Now let's create our first service. For example, a `foo/bar` service:

```shell
./play make/service foo/bar
```

Running that in the console, you should receive something like:

```shell
$ ./play make/service foo/bar
Success! app/foo/bar.php service file generated.
```

That file is now in your `app/foo` directory, which was also created.

Opening the `app/foo/bar.php` file, you should see this:

```php
<?php

declare(strict_types=1);

namespace App\Foo;

interface Bar {
    public function __invoke();
}

return function() use(&$context) {
    // TODO: Make foo/bar happen.
};
```

Let's replace the `return`. Copy and paste this over the file contents:

```php
<?php

declare(strict_types=1);

namespace App\Foo;

interface Bar {
    public function __invoke();
}

return fn() => 'Baz!!!';
```

**Important!** Returning a string from our service allows `app/console` to output the service's response
directly to the command line, in JSON format. 

Now in a shell, run the following command:

```shell
./play foo/bar
```

You should receive your function's response in the console output.

```shell
$ ./play foo/bar
Baz!!!
```

You have now written your first Pure Expressive-style application!
