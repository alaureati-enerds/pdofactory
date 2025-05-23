# PDOFactory

A lightweight and reusable PHP factory for creating a configured PDO instance using a typed configuration object.

## Description

`PDOFactory` is a simple utility class for creating a PDO instance to connect to a MySQL database.  
It uses a strongly typed `PDOConfig` object instead of an array to improve clarity, IDE support, and validation.

## Installation

### Via Composer

You can install `PDOFactory` via Composer. Run the following command:

```bash
composer require alaureati-enerds/pdofactory
```

Or add the dependency directly to your composer.json file and run composer install.

### Manual Installation

You can also manually include the `PDOFactory.php` and `PDOConfig.php` files in your project if you are not using Composer.

## Usage

### Basic Example

```php
require 'vendor/autoload.php';

use PDOFactory\PDOFactory;
use PDOFactory\PDOConfig;

$config = new PDOConfig(
    dbHost: 'localhost',
    dbPort: 3306,
    dbName: 'my_database',
    dbUser: 'my_user',
    dbPass: 'my_password'
);

$pdo = PDOFactory::create($config);

$stmt = $pdo->query("SELECT * FROM users");
$results = $stmt->fetchAll();
print_r($results);
```

### Example with Additional PDO Options

```php
$config = new PDOConfig(
    dbHost: 'localhost',
    dbPort: 3306,
    dbName: 'my_database',
    dbUser: 'my_user',
    dbPass: 'my_password',
    dbCharset: 'utf8mb4',
    options: [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
);

$pdo = PDOFactory::create($config);
```

### Creating Configuration from Environment Variables

You can create a `PDOConfig` instance directly from environment variables using the static `fromEnv()` method.

Example:

```php
$config = PDOConfig::fromEnv();
$pdo = PDOFactory::create($config);
```

This expects the following environment variables to be set:

```
DB_HOST=localhost
DB_PORT=3306
DB_NAME=my_database
DB_USER=my_user
DB_PASS=my_password
DB_CHARSET=utf8
```

You can load these variables from a `.env` file using [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv):

```php
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
```

### Creating Configuration from an Array

You can also create a `PDOConfig` instance from an associative array using the `fromArray()` method.

Example:

```php
$configArray = [
    'db_host' => 'localhost',
    'db_port' => 3306,
    'db_name' => 'my_database',
    'db_user' => 'my_user',
    'db_pass' => 'my_password',
    'db_charset' => 'utf8',
];

$config = PDOConfig::fromArray($configArray);
$pdo = PDOFactory::create($config);
```

This approach is useful when working with configuration files or injecting database settings at runtime.

### Error Handling

The `create()` method throws a `PDOException` if the connection fails due to incorrect configuration or other database errors.  
You can handle these exceptions as follows:

```php
try {
    $pdo = PDOFactory::create($config);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
```

## License

This project is licensed under the MIT License. See the LICENSE file for details.

## Contributing

Contributions are welcome! Please open an issue to discuss what you would like to change or submit a pull request with your improvements.

Author: Andrea Laureati - a.laureati@enerds.it
