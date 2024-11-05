<?php

use PHPUnit\Framework\TestCase;
use PDOFactory\PDOFactory;

class PDOFactoryTest extends TestCase
{
    private array $validConfig;
    private array $invalidConfig;

    protected function setUp(): void
    {
        // Configurazione valida
        $this->validConfig = [
            'db_host' => '192.168.3.8',
            'db_port' => 3307,
            'db_name' => 'arc_blok',
            'db_user' => 'root',
            'db_pass' => 'masterkey',
        ];

        // Configurazione non valida per testare gli errori di connessione
        $this->invalidConfig = [
            'db_host' => 'localhost',
            'db_port' => 3306,
            'db_name' => 'test_database',
            'db_user' => 'test_user',
            'db_pass' => 'wrong_password',
        ];
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
    }

    public function testCustomOptionsOverrideDefaults()
    {
        $customConfig = $this->validConfig;
        $customConfig['options'] = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES => true,
        ];

        $pdo = PDOFactory::create($customConfig);

        $this->assertEquals(PDO::ERRMODE_SILENT, $pdo->getAttribute(PDO::ATTR_ERRMODE));
        $this->assertEquals(PDO::FETCH_OBJ, $pdo->getAttribute(PDO::ATTR_DEFAULT_FETCH_MODE));
        $this->assertEquals(1, $pdo->getAttribute(PDO::ATTR_EMULATE_PREPARES));
    }

    public function testMissingDbUserThrowsException()
    {
        // Configurazione mancante dell'utente del database
        $config = $this->validConfig;
        unset($config['db_user']);

        $this->expectException(PDOException::class);
        PDOFactory::create($config);
    }

    public function testMissingDbPassThrowsException()
    {
        // Configurazione mancante della password del database
        $config = $this->validConfig;
        unset($config['db_pass']);

        $this->expectException(PDOException::class);
        PDOFactory::create($config);
    }
}
