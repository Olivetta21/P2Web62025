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
        if (!$this->tableExists($tableName)) {
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



    public function create($table, $arr)
    {
        try {
            if (!$this->tableExists($table)) {
                throw new Exception("Tabela $table não existe!");
            }

            $fields = array_keys($arr);
            $values = array_values($arr);
            $placeholders = str_repeat("?,", count($fields) - 1) . "?";
            $valuesPlaceholders = implode(", ", $fields);

            $sql = "INSERT INTO $table ($valuesPlaceholders) VALUES ($placeholders)";

            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute($values);

            if ($result) {
                return [
                    'success' => true,
                    'id' => $this->pdo->lastInsertId(),
                    'message' => 'Registro criado com sucesso!'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erro ao criar o registro'
                ];
            }
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Erro no banco: ' . $e->getMessage()
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }

    }

    public function read($table, $conditions = [], $limit = null, $offset = null)
    {
        try {
            if (!$this->tableExists($table)) {
                throw new Exception("Tabela $table não existe!");
            }

            $sql = "SELECT * FROM $table";
            $params = [];

            if (!empty($conditions)) {
                $where_conditions = [];
                foreach ($conditions as $field => $value) {
                    $where_conditions[] = "$field = ?";
                    $params[] = $value;
                }
                $sql .= " WHERE " . implode(" AND ", $where_conditions);
            }

            if ($limit !== null) {
                $sql .= " LIMIT $limit";
                if ($offset !== null) {
                    $sql .= " OFFSET $offset";
                }
            }
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);

            return [
                'success' => true,
                'data' => $stmt->fetchAll(PDO::FETCH_ASSOC),
                'count' => $stmt->rowCount()
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Erro no banco: ' . $e->getMessage()
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }

    }

    public function update($table, $data, $conditions = [])
    {
        try {
            if (!$this->tableExists($table)) {
                throw new Exception("Tabela $table não existe!");
            }

            $sql = "UPDATE $table SET ";
            $params = [];

            $set_fields = [];
            foreach ($data as $field => $value) {
                $set_fields[] = "$field = ?";
                $params[] = $value;
            }
            $sql .= implode(", ", $set_fields);


            if (!empty($conditions)) {
                $where_conditions = [];
                foreach ($conditions as $field => $value) {
                    $where_conditions[] = "$field = ?";
                    $params[] = $value;
                }
                $sql .= " WHERE " . implode(" AND ", $where_conditions);
            }

            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute($params);

            return [
                'success' => true,
                'affected_rows' => $stmt->rowCount(),
                'message' => $stmt->rowCount() > 0 ? 'Registro(s) alterados(s) com sucesso!' : 'Nenhum registro foi alterado'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Erro no banco: ' . $e->getMessage()
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }

    }

    public function delete($table, $conditions = [])
    {
        try {
            if (!$this->tableExists($table)) {
                throw new Exception("Tabela $table não existe!");
            }

            $sql = "DELETE FROM $table";
            $params = [];

            if (!empty($conditions)) {
                $where_conditions = [];
                foreach ($conditions as $field => $value) {
                    $where_conditions[] = "$field = ?";
                    $params[] = $value;
                }
                $sql .= " WHERE " . implode(" AND ", $where_conditions);
            }

            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute($params);

            return [
                'success' => true,
                'affected_rows' => $stmt->rowCount(),
                'message' => $stmt->rowCount() > 0 ? 'Registro(s) deletado(s) com sucesso!' : 'Nenhum registro foi deletado'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Erro no banco: ' . $e->getMessage()
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }

    }

    public function tableExists($table)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM pg_tables WHERE tablename = ?");
            $stmt->execute([$table]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }
}
