<?php

use PHPUnit\Framework\TestCase;
use PDOFactory\PDOFactory;

class PDOFactoryTest extends TestCase
{
    private \PDOFactory\PDOConfig $validConfig;
    private \PDOFactory\PDOConfig $invalidConfig;

    protected function setUp(): void
    {
        $this->validConfig = new \PDOFactory\PDOConfig(
            dbHost: '192.168.3.8',
            dbPort: 3307,
            dbName: 'arc_blok',
            dbUser: 'root',
            dbPass: 'masterkey'
        );

        $this->invalidConfig = new \PDOFactory\PDOConfig(
            dbHost: 'localhost',
            dbPort: 3306,
            dbName: 'test_database',
            dbUser: 'test_user',
            dbPass: 'wrong_password'
        );
    }

    public function testCreateReturnsPDOInstance()
    {
        // Verifica che PDOFactory::create() restituisca un'istanza di PDO
        $pdo = PDOFactory::create($this->validConfig);
        $this->assertInstanceOf(PDO::class, $pdo);
    }

    public function testConnectionFailsWithInvalidCredentials()
    {
        // Verifica che venga lanciata un'eccezione PDOException con credenziali errate
        $this->expectException(PDOException::class);
        PDOFactory::create($this->invalidConfig);
    }

    public function testDefaultOptionsAreSetCorrectly()
    {
        // Verifica che le opzioni predefinite di PDO siano impostate correttamente
        $pdo = PDOFactory::create($this->validConfig);

        $this->assertEquals(PDO::ERRMODE_EXCEPTION, $pdo->getAttribute(PDO::ATTR_ERRMODE));
        $this->assertEquals(PDO::FETCH_ASSOC, $pdo->getAttribute(PDO::ATTR_DEFAULT_FETCH_MODE));
        // Default emulate prepares should be disabled
        $this->assertFalse($pdo->getAttribute(PDO::ATTR_EMULATE_PREPARES));
    }

    public function testCustomOptionsOverrideDefaults()
    {
        $customConfig = new \PDOFactory\PDOConfig(
            dbHost: '192.168.3.8',
            dbPort: 3307,
            dbName: 'arc_blok',
            dbUser: 'root',
            dbPass: 'masterkey',
            options: [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_EMULATE_PREPARES => true,
            ]
        );

        $pdo = PDOFactory::create($customConfig);

        $this->assertEquals(PDO::ERRMODE_SILENT, $pdo->getAttribute(PDO::ATTR_ERRMODE));
        $this->assertEquals(PDO::FETCH_OBJ, $pdo->getAttribute(PDO::ATTR_DEFAULT_FETCH_MODE));
        $this->assertEquals(1, $pdo->getAttribute(PDO::ATTR_EMULATE_PREPARES));
    }

    /**
     * Tests that testConnection() returns true for a valid configuration.
     */
    public function testTestConnectionReturnsTrueForValidConfig()
    {
        $this->assertTrue(PDOFactory::testConnection($this->validConfig));
    }

    /**
     * Tests that testConnection() returns false for an invalid configuration.
     */
    public function testTestConnectionReturnsFalseForInvalidConfig()
    {
        $this->assertFalse(PDOFactory::testConnection($this->invalidConfig));
    }
}
