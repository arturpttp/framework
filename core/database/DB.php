<?php


namespace Core\Database;


use Core\System\Request;
use Core\System\System;
use Core\System\Url;

/**
 * Class DB
 * @package Core\Database
 */
class DB extends Connection
{

    /**
     * @var DB
     */
    public static $instance = null;

    /**
     * Select data
     *
     * @var array
     */
    protected $select;

    /**
     * Table name
     *
     * @var string
     */
    protected $table;

    /**
     * Join data
     *
     * @var string
     */
    protected $join;

    /**
     * where data
     *
     * @var string
     */
    protected $where;

    /**
     * Where binding
     *
     * @var array
     */
    protected $where_binding = [];

    /**
     * Group by data
     *
     * @var string
     */
    protected $group_by;

    /**
     * having data
     *
     * @var string
     */
    protected $having;

    /**
     * having binding
     *
     * @var array
     */
    protected $having_binding = [];

    /**
     * Order by data
     *
     * @var string
     */
    protected $order_by;

    /**
     * limit
     *
     * @var string
     */
    protected $limit;

    /**
     * Offset
     *
     * @var string
     */
    protected $offset;

    /**
     * query
     *
     * @var string
     */
    protected $query;

    /**
     * Setter
     *
     * @var string
     */
    protected $setter;

    /**
     * all binding
     *
     * @var array
     */
    protected $binding = [];

    public $operators = [
        '=', '<', '>', '<=', '>=', '<>', '!=', '<=>',
        'like', 'like binary', 'not like', 'ilike',
        '&', '|', '^', '<<', '>>',
        'rlike', 'not rlike', 'regexp', 'not regexp',
        '~', '~*', '!~', '!~*', 'similar to',
        'not similar to', 'not ilike', '~~*', '!~~*',
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function query($query = null)
    {
        if ($query == null) {
            if (!$this->table) {
                throw new \Exception("Unknown table");
            }
            $query = "SELECT ";
            $query .= $this->select ?: '*';
            $query .= " FROM " . $this->table . " ";
            $query .= $this->join . " ";
            $query .= $this->where . " ";
            $query .= $this->group_by . " ";
            $query .= $this->having . " ";
            $query .= $this->order_by . " ";
            $query .= $this->limit . " ";
            $query .= $this->offset . " ";
        }

        $this->query = $query;
        $this->binding = array_merge($this->where_binding, $this->having_binding);
        return $this;
    }

    public function join($table, $first, $operator, $second, $type = "INNER")
    {
        if (!$this->isValidOperator($operator))
            throw new \Exception("invalid operator in join()");
        $this->join .= " " . $type . " JOIN " . $table . " ON " . $first . $operator . $second;
        return $this;
    }

    public function rightJoin($table, $first, $operator, $second, $type = "INNER")
    {
        try {
            return $this->join($table, $first, $operator, $second, "RIGHT");
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function leftJoin($table, $first, $operator, $second, $type = "INNER")
    {
        try {
            return $this->join($table, $first, $operator, $second, "LEFT");
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function where($column, $operator, $value, $type = null)
    {
        if (!$this->isValidOperator($operator))
            throw new \Exception("invalid operator in where()");
        $where = "`{$column}`{$operator} ?  ";
        if (!$this->where)
            $statement = " WHERE {$where}";
        else
            if ($type = null)
                $statement = " AND {$where}";
            else
                $statement = " {$type} {$where}";

        $this->where .= $statement;
        $this->where_binding[] = htmlspecialchars($value);
        return $this;
    }

    public function orWhere($column, $operator, $value)
    {
        return $this->where($column, $operator, $value, "OR");
    }

    public function having($column, $operator, $value)
    {
        if (!$this->isValidOperator($operator))
            throw new \Exception("invalid operator in having()");
        $having = "`{$column}`{$operator} ?  ";
        if (!$this->where)
            $statement = " HAVING {$having}";
        else
            $statement = " AND {$having}";

        $this->having .= $statement;
        $this->having_binding[] = htmlspecialchars($value);
        return $this;
    }

    public function orderBy($column, $type = 'asc')
    {
        $sep = $this->order_by ? " , " : " ORDER BY ";
        $type = strtoupper($type);
        $type = ($type != null && in_array($type, ['ASC', 'DESC'])) ? $type : "ASC";
        $statement = $sep . $column . " " . $type . " ";
        $this->order_by .= $statement;
        return $this;
    }

    public function limit($limit)
    {
        $this->limit = "LIMIT {$limit} ";
        return $this;
    }

    public function offset($offset)
    {
        $this->offset = "OFFSET {$offset} ";
        return $this;
    }

    private function isValidOperator($operator)
    {
        if (!in_array($operator, $this->operators))
            return false;
        return true;
    }

    public function table($table)
    {
        $this->table = $table;
        return $this;
    }

    public function from($table)
    {
        $this->table($table);
        return $this;
    }

    public function select()
    {
        $select = func_get_args();
        $select = implode(', ', $select);
        $this->select = $select;
        return $this;
    }

    public function fetchExecute()
    {
        try {
            $this->query($this->query);
            $query = trim($this->query, ' ');
            $data = $this->pdo->prepare($this->getQuery());
            $data->execute($this->binding);
            $this->clear();
            return $data;
        } catch (\Exception $e) {
            error($e->getMessage());
            return null;
        }
    }

    public function get()
    {
        $data = $this->fetchExecute();
        return $data->fetchAll();
    }

    public function first()
    {
        $data = $this->fetchExecute();
        return $data->fetch();
    }

    public function all() {
        $this->execute([], "SELECT * FROM {$this->table}");
        return $this;
    }

    public function execute(Array $data, $query, $where = null)
    {
        if (!$this->table) {
            throw new \Exception("Unknow table");
        }

        foreach ($data as $key => $value) {
            $this->setter .= '`' . $key . '` = ?, ';
            $this->binding[] = filter_var($value, FILTER_SANITIZE_STRING);
        }
        $this->setter = trim($this->setter, ', ');

        $query .= $this->setter;
        $query .= $where != null ? $this->where . " " : '';

        $this->binding = $where != null ? array_merge($this->binding, $this->where_binding) : $this->binding;

        $data = $this->pdo->prepare($query);
        $data->execute($this->binding);

        $this->clear();
        return $this;
    }

    public function insert($data, $return_object = false)
    {
        $table = $this->table;
        $query = "INSERT INTO " . $table . " SET ";
        try {
            $this->execute($data, $query);
        } catch (\Exception $e) {
            error($e->getMessage());
        }

        $object_id = $this->pdo->lastInsertId();
        if ($return_object)
            return $this->table($table)->where('id', '=', $object_id)->first();
        else
            return $this;
    }

    public static function instance()
    {
        if (is_null(self::$instance))
            self::$instance = new DB();
        return self::$instance;
    }

    public function update($data) {
        $query = "UPDATE " . $this->table . " SET ";
        $this->execute($data, $query, true);

        return true;
    }
    
    public function delete() {
        $query = "DELETE FROM " . $this->table . " ";
        $this->execute([], $query, true);

        return true;
    }

    private function clear()
    {
        $this->select = '';
        $this->join = '';
        $this->where = '';
        $this->where_binding = [];
        $this->group_by = '';
        $this->having = '';
        $this->having_binding = [];
        $this->order_by = '';
        $this->limit = '';
        $this->offset = '';
        $this->query = '';
        $this->binding = [];
        self::$instance = '';
    }

    public function getQuery(): string
    {
        try {
            $this->query();
        } catch (\Exception $e) {
            error($e->getMessage());
        }
        return $this->query;
    }

    public function paginate($items_per_page = 15)
    {
        try {
            $this->query($this->query);
        } catch (\Exception $e) {
        }
        $query = trim($this->query, ' ');
        $data = $this->pdo->prepare($query);
        $data->execute();
        $pages = ceil($data->rowCount() / $items_per_page);

        $page = Request::get('page');
        $current_page = (!is_numeric($page) || Request::get('page') < 1) ? "1" : $page;
        $offset = ($current_page - 1) * $items_per_page;
        $this->limit($items_per_page);
        $this->offset($offset);
        try {
            $this->query();
        } catch (\Exception $e) {
        }

        $data = $this->fetchExecute();
        $result = $data->fetchAll();

        $response = ['data' => $result, 'items_per_page' => $items_per_page, 'pages' => $pages, 'current_page' => $current_page];

        return $response;
    }
    
    public function links($current_page, $pages)
    {
        $links = '';
        $from = $current_page - 2;
        $to = $current_page + 2;
        if ($from < 2) {
            $from = 2;
            $to = $from + 4;
        }
        if ($to >= $pages) {
            $diff = $to - $pages + 1;
            $from = ($from > 2) ? $from - $diff : 2;
            $to = $pages - 1;
        }
        if ($from < 2) {
            $from = 1;
        }
        if ($to >= $pages) {
            $to = ($pages - 1);
        }

        if ($pages > 1) {
            $links .= "<ul class='pagination'>";
            $full_link = Url::path(Request::full_url());
            $full_link = preg_replace('/\?page=(.*)/', '', $full_link);
            $full_link = preg_replace('/\&page=(.*)/', '', $full_link);

            $current_page_active = $current_page == 1 ? 'active' : '';
            $href = strpos($full_link, '?') ? ($full_link . '&page=1') : ($full_link . '?page=1');
            $links .= "<li class='link' $current_page_active><a href='$href'>First</a></li>";

            for ($i = $from; $i <= $to; $i++) {
                $current_page_active = $current_page == $i ? 'active' : '';
                $href = strpos($full_link, '?') ? ($full_link . '&page=' . $i) : ($full_link . '?page=' . $i);
                $links .= "<li class='link' $current_page_active><a href='$href'>$i</a></li>";
            }

            if ($pages > 1) {
                $current_page_active = $current_page == $pages ? 'active' : '';
                $href = strpos($full_link, '?') ? ($full_link . '&page=' . $pages) : ($full_link . '?page=' . $pages);
                $links .= "<li class='link' $current_page_active><a href='$href'>Last</a></li>";
            }

            return $links;
        }
    }
}