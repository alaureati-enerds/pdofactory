# PDOFactory

A lightweight and reusable PHP factory for creating a configured PDO instance with simplified database connection settings.

## Description

`PDOFactory` is a simple utility class for creating a PDO instance to connect to a MySQL database. It allows you to quickly set up a PDO connection using a configuration array, which includes options for host, port, database name, credentials, and PDO settings.

## Installation

### Via Composer

You can install `PDOFactory` via Composer. Run the following command:

```bash
composer require alaureati-enerds/pdofactory
```

Or add the dependency directly to your composer.json file and run composer install.

### Manual Installation

You can also manually include the PDOFactory.php file in your project if you are not using Composer.

## Usage

Here is how you can use PDOFactory to create a PDO instance.

### Basic Example

```php
require 'vendor/autoload.php';

use YourNamespace\PDOFactory\PDOFactory;

// Database configuratio
$config = [
    'db_host' => 'localhost',
    'db_port' => 3306,
    'db_name' => 'my_database',
    'db_user' => 'my_user',
    'db_pass' => 'my_password',
];

$pdo = PDOFactory::create($config);

$stmt = $pdo->query("SELECT * FROM users");
$results = $stmt->fetchAll();
print_r($results);
```

### Configuration Options

The PDOFactory::create() method accepts an array of configuration options. Here are the supported keys:

    •	db_host (string) - Database host, default is 'localhost'.
    •	db_port (int) - Database port, default is 3306.
    •	db_name (string) - Name of the database to connect to.
    •	db_charset (string) - Character set, default is 'utf8'.
    •	db_user (string) - Username for database authentication.
    •	db_pass (string) - Password for database authentication.
    •	options (array) - Optional array of PDO attributes.

### Example with Additional PDO Options

```php
$config = [
    'db_host' => 'localhost',
    'db_port' => 3306,
    'db_name' => 'my_database',
    'db_user' => 'my_user',
    'db_pass' => 'my_password',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_EMULATE_PREPARES => false,
    ],
];

$pdo = PDOFactory::create($config);
```

### Error Handling

The create() method throws a PDOException if the connection fails due to incorrect configuration or other database errors. You can handle these exceptions as follows:

```php
try {
$pdo = PDOFactory::create($config);
} catch (PDOException $e) {
echo "Connection failed: " . $e->getMessage();
}
```

### License

This project is licensed under the MIT License. See the LICENSE file for details.

### Contributing

Contributions are welcome! Please open an issue to discuss what you would like to change or submit a pull request with your improvements.

Author: Andrea Laureati - a.laureati@enerds.it
