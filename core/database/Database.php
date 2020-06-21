<?php


namespace Core\Database;


use PDO;

class Database extends Connection
{

    public $rows;
    public $fetch;
    public $query;
    public $statement;
    public $result;
    public $error = false;
    public $boolResult = false;
    private $table;
    private static $instance = null;

    public function __construct($table = null)
    {
        parent::__construct();
        if (is_null($table))
            throw new \Exception("Table is invalid");
        $this->table = $table;
    }

    public static function instance(): Database {
        if (is_null(static::$instance)) {
            static::$instance = new self();
        }
        return static::$instance;
    }

    public function table($table)
    {
        $this->table = $table;
        return $this;
    }

    public function query($statement = false, $params = [], $a = false) {
        try {
            if ($statement)
                $this->statement = $statement;
            $this->query = $this->pdo->prepare($this->statement);
            if ($a) {
                $x = 1;
                foreach ($params as $param) {
                    $this->query->bindValue($x, $param);
                    $x++;
                    $x++;
                }
            }
            if ($this->result = $this->query->execute(!$a ? $params : null)) {
                $this->rows = $this->query->rowCount();
                $this->boolResult = true;
            } else {
                $this->error = "some error on query {$statement}";
            }

        }catch (\PDOException $e) {
            $this->error = $e->getMessage();
        }
        return $this;
    }

    public function All()
    {
        $this->statement = "SELECT * FROM {$this->table}";
        $this->query();
        return $this;
    }

    public function update($data, $where)
    {
        $updater = "";
        $x = 1;
        $params = [];
        $setter = "";
        foreach ($data as $column => $value) {
            $setter .= "{$column}=?";
            if ($x < count($data))
                $setter .= ", ";
            $params[$column] = $value;
            $x++;
        }
        $x = 1;
        foreach ($where as $column => $value) {
            $updater .= "{$column} = ?";
            if ($x < count($where))
                $updater .= ' AND ';
            $params[$column] = $value;
            $x++;
        }
        $sql = "UPDATE {$this->table} SET {$setter} WHERE {$updater}";
        $this->query($sql, $params, true);
        return $this;
    }

    public function insert($data = [])
    {
        if (count($data)) {
            $keys = array_keys($data);
            $values = '';
            $x = 1;
            foreach ($data as $field) {
                $values .= '?';
                if ($x < count($data))
                    $values .= ', ';
                $x++;
            }

            $stmt = "INSERT INTO {$this->table} (`" . implode('`, `', $keys) . "`) VALUES  ({$values});";
            $this->query($stmt, $data, true);
            $this->boolResult = true;
        }else
            $this->error = 'Data is empty';
        return $this;
    }

    public function contains($column, $value)
    {
        return $this->query("SELECT * FROM {$this->table} WHERE {$column} = ?", [$value], true)
                ->count() > 0;
    }

    public function delete($where)
    {
        $deleter = "";
        $x = 1;
        foreach ($where as $column => $value) {
            $deleter .= "{$column} = ?";
            if ($x < count($where))
                $deleter .= ' AND ';
            $x++;
        }
        $sql = "DELETE FROM {$this->table} WHERE {$deleter}";
        $this->query($sql, $where, true);
        return $this;
    }

    public function find($where) {
        $finder = "";
        $x = 1;
        foreach ($where as $column => $value) {
            $finder .= "{$column} = ?";
            if ($x < count($where))
                $finder .= ' AND ';
            $x++;
        }
        $sql = "SELECT * FROM {$this->table} WHERE {$finder}";
        $this->query($sql, $where, true);
        return $this;
    }

    public function deleteById($id)
    {
        if ($this->contains('id', $id))
            $this->query("DELETE FROM {$this->table} WHERE id = :id", [
                ':id' => $id
            ]);
        else
            $this->error = "id: {$id} doesn't exists";
        return $this;
    }

    public function count() {
        $this->error();
        return $this->rows;
    }

    public function result($fetchMethod = PDO::FETCH_ASSOC)
    {
        return $this->fetch($fetchMethod);
    }

    public function first($fetchMethod = PDO::FETCH_ASSOC)
    {
        $results = $this->fetch();
        $first = is_array($results) ? $results[0] : $results;
        return $first;
    }

    public function fetch($fetchMethod = PDO::FETCH_ASSOC) {
        $this->error();
        if (!$this->error) {
            if ($this->count() > 1)
                $this->fetch = $this->query->fetchAll($fetchMethod);
            else
                $this->fetch[] = $this->query->fetch($fetchMethod);
        }else
            error($this->error);
        return $this->fetch;
    }

    public function error()
    {
        if ($this->error != false)
            error($this->error);
        return $this->error;
    }

    public function boolResult()
    {
        $this->error();
        return $this->boolResult;
    }

}