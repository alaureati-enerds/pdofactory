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

    /**
     * Tests whether a database connection can be successfully established.
     *
     * @param PDOConfig $config The configuration to test.
     * @return bool True if the connection succeeds, false otherwise.
     */
    public static function testConnection(PDOConfig $config): bool
    {
        try {
            new PDO($config->getDsn(), $config->dbUser, $config->dbPass, $config->options);
            return true;
        } catch (PDOException) {
            return false;
        }
    }
}
