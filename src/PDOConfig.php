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

    /**
     * Creates a new PDOConfig instance from environment variables.
     *
     * Expected environment variables:
     *  - DB_HOST
     *  - DB_PORT
     *  - DB_NAME
     *  - DB_USER
     *  - DB_PASS
     *  - DB_CHARSET (optional, default: 'utf8')
     *
     * @return self
     */
    public static function fromEnv(): self
    {
        return new self(
            dbHost: $_ENV['DB_HOST'] ?? 'localhost',
            dbPort: is_numeric($_ENV['DB_PORT'] ?? null) ? (int) $_ENV['DB_PORT'] : 3306,
            dbName: $_ENV['DB_NAME'] ?? '',
            dbUser: $_ENV['DB_USER'] ?? '',
            dbPass: $_ENV['DB_PASS'] ?? '',
            dbCharset: $_ENV['DB_CHARSET'] ?? 'utf8',
            options: null
        );
    }

    /**
     * Creates a new PDOConfig instance from an associative array.
     *
     * Expected array keys:
     *  - db_host
     *  - db_port
     *  - db_name
     *  - db_user
     *  - db_pass
     *  - db_charset (optional, default: 'utf8')
     *  - options (optional, PDO options array)
     *
     * @param array $config
     * @return self
     */
    public static function fromArray(array $config): self
    {
        return new self(
            dbHost: $config['db_host'] ?? 'localhost',
            dbPort: (int) ($config['db_port'] ?? 3306),
            dbName: $config['db_name'] ?? '',
            dbUser: $config['db_user'] ?? '',
            dbPass: $config['db_pass'] ?? '',
            dbCharset: $config['db_charset'] ?? 'utf8',
            options: $config['options'] ?? null
        );
    }

    /**
     * Returns the configuration as an associative array.
     *
     * @param bool $includeSensitive If true, includes the password; otherwise masks it.
     * @return array
     */
    public function toArray(bool $includeSensitive = false): array
    {
        return [
            'db_host' => $this->dbHost,
            'db_port' => $this->dbPort,
            'db_name' => $this->dbName,
            'db_user' => $this->dbUser,
            'db_pass' => $includeSensitive ? $this->dbPass : '***',
            'db_charset' => $this->dbCharset,
            'options' => $this->options,
        ];
    }

    /**
     * Returns the configuration as a JSON string.
     *
     * @param bool $includeSensitive If true, includes the password; otherwise masks it.
     * @return string
     */
    public function toJson(bool $includeSensitive = false): string
    {
        return json_encode($this->toArray($includeSensitive), JSON_PRETTY_PRINT);
    }
}
