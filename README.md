# GiBiLogic "Elements" library

Often we find ourselves writing the same code for different projects, over and over again.

We got bored, so we decided to collect all these bits and pieces of code in a single library.

## Installation

Add this package to the composer.json of your application with the console command:

```bash
composer require gibilogic/elements
```

Or, if you are using the `composer.phar` version, use the console command:

```bash
php composer.phar require gibilogic/elements
```

## Elements

Here you can find an overview for all the elements of our library. 

### Flashable trait

Some useful methods to add user flash messages, packed into an handy [PHP Trait](http://php.net/manual/en/language.oop5.traits.php). [Read its documentation](Resources/doc/flashable_trait.md).

### Session storage service

A simple key-based session storage service for arrays made of key-value pairs. [Read its documentation](Resources/doc/session_storage_service.md).

### Slugger service 

A simple slugger, used to "sanitize" strings by removing bizarre and URL-unfriendly characters. [Read its documentation](Resources/doc/slugger_service.md).

## Contributions

You can contribute to the growth of this library in a lot of different ways:

* Create an issue about a bug or a feature you would like to see implemented
* Open pull requests about fixes, new features, tests, documentation, etc.
* Use the library and let us know ;)

## License

See the attached [LICENSE](LICENSE) file.
