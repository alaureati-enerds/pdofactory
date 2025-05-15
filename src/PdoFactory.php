<?php

namespace PDOFactory;

use PDO;
use PDOException;
use PDOFactory\PDOConfig;

/**
 * Class PDOFactory
 *
 * A factory class for creating PDO instances using a typed configuration.
 *
 * This class provides a static method for initializing a PDO connection
 * based on a {@see PDOConfig} object that encapsulates the required parameters
 * for connecting to a database.
 */
class PDOFactory
{
    /**
     * Creates and returns a configured PDO instance.
     *
     * @param PDOConfig $config Configuration object containing host, port, database name,
     *                          credentials, and optional PDO attributes.
     *
     * @return PDO A ready-to-use PDO instance.
     *
     * @throws PDOException If the database connection fails.
     */
    public static function create(PDOConfig $config): PDO
    {
        $dsn = $config->getDsn();
        return new PDO($dsn, $config->dbUser, $config->dbPass, $config->options);
    }
}
