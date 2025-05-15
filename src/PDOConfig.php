<?php

namespace PDOFactory;

use InvalidArgumentException;

/**
 * Class PDOConfig
 *
 * Represents a configuration container for establishing a PDO connection.
 *
 * This class encapsulates all necessary parameters such as host, port,
 * database name, credentials, charset, and optional PDO attributes.
 * It also provides a helper method to generate a DSN string.
 */
class PDOConfig
{
    public readonly string $dbHost;
    public readonly int $dbPort;
    public readonly string $dbName;
    public readonly string $dbUser;
    public readonly string $dbPass;
    public readonly string $dbCharset;
    public readonly array $options;

    /**
     * PDOConfig constructor.
     *
     * @param string $dbHost   Database host (e.g., 'localhost').
     * @param int    $dbPort   Database port (e.g., 3306).
     * @param string $dbName   Name of the database.
     * @param string $dbUser   Username for the database connection.
     * @param string $dbPass   Password for the database connection.
     * @param string $dbCharset Character set to use (default: 'utf8').
     * @param array|null $options Optional PDO attributes.
     *
     * @throws InvalidArgumentException if required parameters are missing or invalid.
     */
    public function __construct(
        string $dbHost,
        int $dbPort,
        string $dbName,
        string $dbUser,
        string $dbPass,
        string $dbCharset = 'utf8',
        ?array $options = null
    ) {
        if (empty($dbName)) {
            throw new InvalidArgumentException("Database name cannot be empty.");
        }

        if (empty($dbUser)) {
            throw new InvalidArgumentException("Database user cannot be empty.");
        }

        $this->dbHost = $dbHost;
        $this->dbPort = $dbPort;
        $this->dbName = $dbName;
        $this->dbUser = $dbUser;
        $this->dbPass = $dbPass;
        $this->dbCharset = $dbCharset;

        $this->options = $options ?? [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ];
    }

    /**
     * Builds and returns the DSN string for PDO.
     *
     * @return string The DSN string.
     */
    public function getDsn(): string
    {
        return "mysql:host={$this->dbHost};port={$this->dbPort};dbname={$this->dbName};charset={$this->dbCharset}";
    }
}
