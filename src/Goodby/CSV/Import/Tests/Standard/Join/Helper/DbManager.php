<?php

namespace Goodby\CSV\Import\Tests\Standard\Join\Helper;

class DbManager
{
    private $pdo;

    public function __construct()
    {
        $host = $_SERVER['GOODBY_CSV_TEST_DB_HOST'];
        $db   = $_SERVER['GOODBY_CSV_TEST_DB_NAME_DEFAULT'];
        $user = $_SERVER['GOODBY_CSV_TEST_DB_USER'];
        $pass = $_SERVER['GOODBY_CSV_TEST_DB_PASS'];

        $dsn = 'mysql:host=' . $host;

        $this->pdo = new \PDO($dsn, $user, $pass);
        $stmt = $this->pdo->prepare("CREATE DATABASE " . $db);

        $stmt->execute();
    }

    public function __destruct()
    {
        $db = $_SERVER['GOODBY_CSV_TEST_DB_NAME_DEFAULT'];

        $stmt = $this->pdo->prepare("DROP DATABASE " . $db);
        $stmt->execute();
    }

    public function getPdo()
    {
        return $this->pdo;
    }
}
