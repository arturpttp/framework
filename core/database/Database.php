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

    public function __construct($table)
    {
        parent::__construct();
        $this->table = $table;
    }

    public function query($statement = false, $params = [], $a = false) {
        if ($statement)
            $this->statement = $statement;
        $this->query = $this->pdo->prepare($this->statement);
        if ($a) {
            $x = 1;
            foreach ($params as $param) {
                $this->query->bindValue($x, $param);
                $x++;
            }
        }
        if ($this->result = $this->query->execute(!$a ? $params : null)) {
            $this->rows = $this->query->rowCount();
            $this->fetch = $this->query->fetchAll();
            $this->boolResult = true;
        }else {
            $this->error = "some error on query {$statement}";
        }
        return $this;
    }

    public function All()
    {
        $this->statement = "SELECT * FROM {$this->table}";
        $this->query();
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

            $stmt = "INSERT INTO {$this->table} (`" . implode('`, `', $keys) . "`) VALUES  ({$values})";

            $this->query($this->statement, $data, true);
            $this->boolResult = true;
        }
        $this->error = 'Data is empty';
        return $this;
    }

    public function contains($column, $value)
    {
        $this->query("SELECT * FROM {$this->table} WHERE {$column} = :{$column}", [":{$column}", $value]);
        return $this->count() > 0;
    }

    public function delete($where)
    {

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
        return $this->rows;
    }

    public function fetch($fetchMethod = PDO::FETCH_ASSOC) {
        if (!$this->error) {
            if ($this->count() > 1)
                $this->fetch = $this->query->fetchAll($fetchMethod);
            else
                $this->fetch = $this->query->fetch($fetchMethod);
        }else
            error($this->error);
        return $this->fetch;
    }

    public function boolResult()
    {
        return $this->boolResult;
    }

}