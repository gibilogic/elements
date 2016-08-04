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

### Session storage service

A simple key-based session storage service for arrays made of key-value pairs. [Read its documentation](Resources/doc/session_storage_service.md).

### Slugger service 

A simple slugger, used to "sanitize" strings by removing bizarre and URL-unfriendly characters. [Read its documentation](Resources/doc/slugger_service.md).
