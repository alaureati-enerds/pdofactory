

<?php

use PHPUnit\Framework\TestCase;
use PDOFactory\PDOConfig;

class PDOConfigTest extends TestCase
{
    public function testConstructorAssignsPropertiesCorrectly()
    {
        $config = new PDOConfig(
            dbHost: 'localhost',
            dbPort: 3306,
            dbName: 'my_database',
            dbUser: 'my_user',
            dbPass: 'my_pass'
        );

        $this->assertEquals('localhost', $config->dbHost);
        $this->assertEquals(3306, $config->dbPort);
        $this->assertEquals('my_database', $config->dbName);
        $this->assertEquals('my_user', $config->dbUser);
        $this->assertEquals('my_pass', $config->dbPass);
        $this->assertEquals('utf8', $config->dbCharset);
        $this->assertIsArray($config->options);
    }

    public function testCustomOptionsAreUsedIfProvided()
    {
        $customOptions = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_SILENT,
        ];

        $config = new PDOConfig(
            dbHost: 'localhost',
            dbPort: 3306,
            dbName: 'my_database',
            dbUser: 'my_user',
            dbPass: 'my_pass',
            dbCharset: 'utf8mb4',
            options: $customOptions
        );

        $this->assertSame($customOptions, $config->options);
    }

    public function testGetDsnReturnsExpectedString()
    {
        $config = new PDOConfig(
            dbHost: '127.0.0.1',
            dbPort: 3307,
            dbName: 'demo',
            dbUser: 'admin',
            dbPass: 'admin123',
            dbCharset: 'latin1'
        );

        $expected = 'mysql:host=127.0.0.1;port=3307;dbname=demo;charset=latin1';
        $this->assertEquals($expected, $config->getDsn());
    }

    public function testMissingDbNameThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        new PDOConfig(
            dbHost: 'localhost',
            dbPort: 3306,
            dbName: '',
            dbUser: 'user',
            dbPass: 'pass'
        );
    }

    public function testMissingDbUserThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        new PDOConfig(
            dbHost: 'localhost',
            dbPort: 3306,
            dbName: 'testdb',
            dbUser: '',
            dbPass: 'pass'
        );
    }

    public function testFromEnvCreatesConfigCorrectly()
    {
        $_ENV['DB_HOST'] = 'localhost';
        $_ENV['DB_PORT'] = '3306';
        $_ENV['DB_NAME'] = 'env_database';
        $_ENV['DB_USER'] = 'env_user';
        $_ENV['DB_PASS'] = 'env_pass';
        $_ENV['DB_CHARSET'] = 'utf8mb4';

        $config = PDOConfig::fromEnv();

        $this->assertEquals('localhost', $config->dbHost);
        $this->assertEquals(3306, $config->dbPort);
        $this->assertEquals('env_database', $config->dbName);
        $this->assertEquals('env_user', $config->dbUser);
        $this->assertEquals('env_pass', $config->dbPass);
        $this->assertEquals('utf8mb4', $config->dbCharset);
    }

    public function testFromArrayCreatesConfigCorrectly()
    {
        $array = [
            'db_host' => '127.0.0.1',
            'db_port' => 3307,
            'db_name' => 'array_db',
            'db_user' => 'array_user',
            'db_pass' => 'array_pass',
            'db_charset' => 'latin1',
            'options' => [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        ];

        $config = PDOConfig::fromArray($array);

        $this->assertEquals('127.0.0.1', $config->dbHost);
        $this->assertEquals(3307, $config->dbPort);
        $this->assertEquals('array_db', $config->dbName);
        $this->assertEquals('array_user', $config->dbUser);
        $this->assertEquals('array_pass', $config->dbPass);
        $this->assertEquals('latin1', $config->dbCharset);
        $this->assertEquals(PDO::ERRMODE_EXCEPTION, $config->options[PDO::ATTR_ERRMODE]);
    }
}
