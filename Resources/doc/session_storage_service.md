# Session storage service

This service helps when dealing with key-value arrays that must be saved indipendently inside the session.

The classic use-case is when two or more CRUD-based entity lists have session-persisted filters: each list has its own key-value filters and they must be managed indipendently.

## Symfony configuration

If you want to use this service as a Symfony service, add the following configuration to your `services.yml` file:

```yaml
services:
    # Other services...
    gibilogic.session_storage:
        class: Gibilogic\Elements\Service\SessionStorageService
        arguments:
            - "@session"
```

You'll be able to get an instance of the service from the service container:

```php
/* @var \Gibilogic\Elements\Service\SessionStorageService $sessionStorageService */
$sessionStorageService = $this->container->get('gibilogic.session_storage');
```

## Usage

With this service you can `set`, `get` and `remove` key-value arrays by giving them a name (aka. "key").

For setting a key-value array use the `set` method:

```php
$sessionStorageService->set('order', [
    'fromDate' => '2016-01-01',
    'toDate' => '2016-02-01',
]);
```

Getting the data back is as simple as calling the `get` method:

```php
$filters = $sessionStorageService->get('order');
```

Call the `remove` method to clear your data:

```php
$sessionStorageService->remove('order');
```

### Setting with overwrite

By default the `set` method will use an `array_replace` to save your data: existing data will be merged by replace with the new data.

If you want to disable this feature, just pass a boolean `true` as second parameter to the `set` method: 

```php
$sessionStorageService->set('order', [
    'fromDate' => '2016-01-01',
    'toDate' => '2016-02-01',
], true);
```

The default behaviour can be useful when dealing with CRUD/APIs filters: you may want to update the changed values only, keeping the unchanged ones.
