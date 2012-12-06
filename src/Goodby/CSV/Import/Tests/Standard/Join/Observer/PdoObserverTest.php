<?php

namespace Goodby\CSV\Import\Tests\Standard\Join\Observer;

use Mockery as m;

use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\Observer\PdoObserver;
use Goodby\CSV\Import\Tests\Standard\Join\Helper\DbManager;

/**
 * unit test for pdo observer
 *
 */
class PdoObserverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Goodby\CSV\Import\Tests\Standard\Join\Helper\DbManager
     */
    private $manager = null;

    private $host;
    private $db;
    private $user;
    private $pass;

    public function setUp()
    {
        $this->manager = new DbManager();

        $this->host = $_SERVER['GOODBY_CSV_TEST_DB_HOST'];
        $this->db   = $_SERVER['GOODBY_CSV_TEST_DB_NAME_DEFAULT'];
        $this->user = $_SERVER['GOODBY_CSV_TEST_DB_USER'];
        $this->pass = $_SERVER['GOODBY_CSV_TEST_DB_PASS'];

        $pdo = $this->getPdo();
        $stmt = $pdo->prepare("CREATE TABLE test (id INT, name VARCHAR(32), age INT, flag TINYINT, flag2 TINYINT, status VARCHAR(32), contents TEXT)");
        $stmt->execute();
    }

    public function testUsage()
    {
        $interpreter = new Interpreter();

        $table = 'test';

        $dsn = 'mysql:dbname=' . $this->db . ';host=' . $this->host;
        $options = array('user' => $this->user, 'password' => $this->pass);

        $sqlObserver = new PdoObserver($table, array('id', 'name', 'age', 'flag', 'flag2', 'status', 'contents'), $dsn, $options);

        $interpreter->addObserver(array($sqlObserver, 'notify'));

        $interpreter->interpret(array('123', 'test', '28', 'true', 'false', 'null', 'test"test'));

        $pdo = $this->getPdo();
        $stmt = $pdo->prepare("SELECT * FROM " . $table);
        $stmt->execute();

        $result = $stmt->fetch();

        $this->assertEquals(123, $result[0]);
        $this->assertEquals('test', $result[1]);
        $this->assertEquals(28, $result[2]);
        $this->assertEquals(1, $result[3]);
        $this->assertEquals(0, $result[4]);
        $this->assertEquals('NULL', $result[5]);
        $this->assertEquals('test"test', $result[6]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidLine()
    {
        $interpreter = new Interpreter();

        $table = 'test';

        $dsn = 'mysql:dbname=' . $this->db . ';host=' . $this->host;
        $options = array('user' => $this->user, 'password' => $this->pass);

        $sqlObserver = new PdoObserver($table, array('id', 'name'), $dsn, $options);

        $interpreter->addObserver(array($sqlObserver, 'notify'));

        $interpreter->interpret(array('123', array('test', 'test')));
    }

    private function getPdo()
    {
        $dsn = 'mysql:dbname=' . $this->db . ';host=' . $this->host;
        return new \PDO($dsn, $this->user, $this->pass);
    }
}
