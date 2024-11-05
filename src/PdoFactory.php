<?php

namespace PDOFactory;

use PDO;
use PDOException;

/**
 * Class PDOFactory
 *
 * A factory class for creating and configuring a PDO instance
 * with simplified database connection settings.
 *
 * This class provides a static method for initializing a PDO connection
 * based on a given configuration array, which includes options for host, 
 * port, database name, user credentials, and optional PDO settings.
 */
class PDOFactory
{
    /**
     * Creates and returns a configured PDO instance.
     *
     * This method simplifies the instantiation of a PDO object by allowing
     * the user to provide a configuration array. The array supports the 
     * following keys:
     *  - 'db_host' (string): Database host, default is 'localhost'.
     *  - 'db_port' (int): Database port, default is 3306.
     *  - 'db_name' (string): Name of the database to connect to.
     *  - 'db_charset' (string): Character set, default is 'utf8'.
     *  - 'db_user' (string): Username for database authentication.
     *  - 'db_pass' (string): Password for database authentication.
     *  - 'options' (array): Optional array of PDO attributes.
     *
     * @param array $config Configuration array for the PDO connection, which
     *                      includes database connection details and optional
     *                      PDO attributes.
     *
     * @return PDO The configured PDO instance ready for database operations.
     *
     * @throws PDOException If the connection fails due to incorrect
     *                      configuration or other database errors.
     */
    public static function create(array $config): PDO
    {
        $host = $config['db_host'] ?? 'localhost';
        $port = $config['db_port'] ?? 3306;
        $dbname = $config['db_name'] ?? '';
        $charset = $config['db_charset'] ?? 'utf8';

        $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset={$charset}";
        $username = $config['db_user'] ?? '';
        $password = $config['db_pass'] ?? '';

        $options = $config['options'] ?? [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        return new PDO($dsn, $username, $password, $options);
    }
}
