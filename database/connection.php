<?php
require_once 'config.php';

class Database
{
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        try {
            $this->pdo = new PDO(DB_DSN);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->initializeDatabase();
        } catch (PDOException $e) {
            die("Erro na conexão: " . $e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->pdo;
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function createTable($tableName, $createSql)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM pg_tables WHERE tablename = ?");
        $stmt->execute([$tableName]);
        $res = $stmt->fetchAll();
        $tableExists = count($res) > 0;
        
        if (!$tableExists) {
            $this->pdo->exec($createSql);
        }
    }

    private function initializeDatabase()
    {
        $this->createTable('usuarios', "
            CREATE TABLE usuarios (
                id SERIAL PRIMARY KEY,
                nome TEXT NOT NULL,
                email TEXT UNIQUE NOT NULL,
                senha TEXT NOT NULL
            )
        ");
        $this->createTable('agendamentos', "
            CREATE TABLE agendamentos (
                id SERIAL PRIMARY KEY,
                usuario_id INTEGER REFERENCES usuarios(id),
                data_hora TIMESTAMP NOT NULL,
                descricao TEXT
            )
        ");
    }
}

$db = Database::getInstance();