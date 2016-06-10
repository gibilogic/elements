# Slugger service

The service's slugify operation:

* Removes new lines (`\n`) and/or carriage returns (`\r`)
* Replaces extended characters ("æ" and "Ñ", for example) into their "plain" versions ("ae" and "N", for example)
* Converts the entire string in lower case (by using the `mb_strtolower` function)
* Replaces every non-letter non-number character with a separator (defaults to `-`)

## Symfony configuration

If you want to use this service as a Symfony service, add the following configuration to your `services.yml` file:

```yaml
services:
    # Other services...
    gibilogic.slugger:
        class: Gibilogic\Elements\Service\SluggerService
```

You'll be able to get an instance of the slugger service from the service container:

```php
/* @var \Gibilogic\Elements\Service\SluggerService $sluggerService */
$sluggerService = $this->container->get('gibilogic.slugger');
```

## Usage

Inside your application, get (or manually create) an instance of the slugger service and then call its `slugify` method:

```php
$slug = $sluggerService->slugify($string);
```

You can also specify the slug character separator (defaults to `-`):

```php
$slug = $sluggerService->slugify($string, '_');
```
