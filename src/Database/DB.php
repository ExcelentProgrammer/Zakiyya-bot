<?php

namespace App\Database;

use Exception;
use PDO;

class DB
{
    public $settings = [
        "type"   =>  "",
        "select" =>  [],
        "insert" =>  [],
        "update" =>  [],
        "delete" =>  [],
        "where"  =>  [],
        "create" =>  [],
        "drop"   =>  [],
        "order"  =>  [],
        "limit"  =>  [],
        "table"  =>  "",
    ];
    public $sql;
    function __construct()
    {
        try {
            $this->con = new PDO($_ENV['DB_TYPE'] . ":host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'] . "", $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
            $this->conInfo = true;
        } catch (Exception $e) {
            try {
                throw new Exception("Malumotlar bazasiga ulanishda xato yuz berdi");
            } catch (Exception $e) {
                $this->conInfo = false;
            }
        }
    }

    public  function select($data = ["*"])
    {
        $this->settings['type'] = "SELECT";
        $this->settings['select'] = $data;

        return $this;
    }
    public  function create(string $data)
    {
        $this->settings['type'] = "CREATE";
        $this->settings['create'] = $data;
        return $this;
    }
    public  function insert(array $data)
    {
        $this->settings['insert'] = $data;
        $this->settings['type'] = "INSERT";

        return $this;
    }
    public  function delete()
    {
        $this->settings['type'] = "DELETE";
        $this->settings['delete'] = "";
        return $this;
    }
    public  function drop()
    {
        $this->settings['type'] = "DROP";
        $this->settings['drop'] = "";
        return $this;
    }
    public  function update($data)
    {
        $this->settings['type'] = "UPDATE";
        $this->settings['update'] = $data;
        return $this;
    }
    public function destroy()
    {
        $this->settings = [
            "type"   =>  "",
            "select" =>  [],
            "insert" =>  [],
            "update" =>  [],
            "delete" =>  [],
            "where"  =>  [],
            "create" =>  [],
            "drop"   =>  [],
            "order"  =>  [],
            "limit"  =>  [],
            "table"  =>  "",
        ];
    }
    public function order($data)
    {
        $this->settings['order'] = $data;
        return $this;
    }
    static public function con()
    {
        $self = new self();
        return $self->con;
    }
    static public function query($sql)
    {
        try {
            $res = (new self())->con->prepare($sql);
            $res->execute();
            return (object)['result' => "true", "sql" => $sql, "response" => $res];
        } catch (Exception $e) {
            return json_decode(json_encode(['result' => "false", "sql" => $sql, "error" => ["message" => $e->getMessage(), "line" => $e->getLine(), "trace" => $e->getTrace()]]), false);
        }
    }
    public function limit($from, $to)
    {
        $this->settings['limit']['from'] = $to;
        $this->settings['limit']['to'] = $from;
        return $this;
    }
    public function where($data)
    {
        $this->settings['where'] = $data;
        return $this;
    }

    public static function table(string $table)
    {
        $self = new self();
        $self->settings['table'] = $table;
        return $self;
    }
    public function run()
    {
        if ($this->conInfo) {
            $type = $this->settings['type'];
            $select = $this->settings['select'];
            $table = $this->settings['table'];
            $where = $this->settings['where'];
            $insert = $this->settings['insert'];
            $update = $this->settings['update'];
            $create = $this->settings['create'];
            $order = !empty($this->settings['order']) ? $this->settings['order'] : "";
            $limitFrom = isset($this->settings['limit']['from']) ? $this->settings['limit']['from'] : "";
            $limitTo = isset($this->settings['limit']['to']) ? $this->settings['limit']['to'] : "";
            if (isset($this->settings['limit']['from']) and isset($this->settings['limit']['to'])) {
                $limit = " LIMIT $limitFrom OFFSET $limitTo ";
            } else {
                $limit = "";
            }


            $select = is_array($select) ? implode(", ", $select) : $select;


            if (!empty($where) and is_array($where)) {
                $wherequery = " WHERE ";
                $whereExec = $where;
                foreach ($where as $key => $value) {
                    $wherequery .= "$key = :$key";
                    if (end($where) !== $value) {
                        $wherequery .= " AND ";
                    }
                }
                $where = $wherequery;
            } elseif (!empty($where)) {
                $where = " WHERE " . $where;
            } else {
                $where = "";
            }


            if (!empty($insert)) {
                $insertKey = implode(",", array_keys($insert));
                $insertValue = implode(",:", array_keys($insert));
            }


            if (!empty($update)) {
                $updatequery = "";
                foreach ($update as $key => $value) {
                    $updatequery .= "$key = :$key";
                    if (end($update) !== $value) {
                        $updatequery .= " , ";
                    }
                }
            } else {
                $updatequery = "";
            }





            switch ($type) {
                case 'SELECT':
                    $query = "SELECT $select FROM $table $where $order $limit";
                    try {
                        $res = $this->con->prepare($query);
                        $res->execute($whereExec);
                        $RowCount = $res->rowCount();
                        $Data = $res->fetchAll();
                        $this->destroy();
                        return (object)["RowCount" => $RowCount, "Data" => $Data, 'result' => "true", 'sql' => $res->queryString];
                    } catch (Exception $e) {
                        return json_decode(json_encode(['result' => "false", "sql" => $query, "error" => ["message" => $e->getMessage(), "line" => $e->getLine(), "trace" => $e->getTrace()]]), false);
                    }
                    break;
                case 'INSERT':
                    $query = "INSERT INTO $table ($insertKey) VALUES (:$insertValue)";
                    try {
                        $con = $this->con;
                        $res = $con->prepare($query);
                        $res->execute($insert);
                        try {
                            $insertId = $con->lastInsertId();
                            $this->destroy();
                            return (object)["InsertId" => $con->lastInsertId(), "result" => "true", "sql" => $res->queryString];
                        } catch (Exception $e) {
                            $this->destroy();
                            return (object)["InsertId" => 'null', "result" => "true", 'sql' => $res->queryString];
                        }
                    } catch (Exception $e) {
                        return json_decode(json_encode(['result' => "false", "sql" => $query, "error" => ["message" => $e->getMessage(), "line" => $e->getLine(), "trace" => $e->getTrace()]]), false);
                    }
                    break;
                case 'UPDATE':
                    $query = "UPDATE $table SET $updatequery $where";
                    try {
                        $res = $this->con->prepare($query);
                        print_r($where);
                        $this->destroy();
                        $res->execute(!empty($whereExec) ? $update + $whereExec : $update);
                        return (object)['result' => "true", 'sql' => $res->queryString];
                    } catch (Exception $e) {
                        return json_decode(json_encode(['result' => "false", "sql" => $query, "error" => ["message" => $e->getMessage(), "line" => $e->getLine(), "trace" => $e->getTrace()]]), false);
                    }
                    break;
                case 'DELETE':
                    $query = "DELETE FROM $table $where";
                    try {
                        $res = $this->con->prepare($query);
                        $this->destroy();
                        $res->execute($whereExec);
                        return (object)['result' => "true", 'sql' => $res->queryString];
                    } catch (Exception $e) {
                        return json_decode(json_encode(['result' => "false", "sql" => $query, "error" => ["message" => $e->getMessage(), "line" => $e->getLine(), "trace" => $e->getTrace()]]), false);
                    }
                    break;
                case 'DROP':
                    $query = "DROP TABLE $table";
                    try {
                        $res = $this->con->prepare($query);
                        $res->execute();
                        $this->destroy();
                        return (object)['result' => "true", 'sql' => $res->queryString];
                    } catch (Exception $e) {
                        return json_decode(json_encode(['result' => "false", "sql" => $query, "error" => ["message" => $e->getMessage(), "line" => $e->getLine(), "trace" => $e->getTrace()]]), false);
                    }
                    break;
                case 'CREATE':
                    $query = "CREATE TABLE $table($create)";
                    try {
                        $res = $this->con->prepare($query);
                        $res->execute();
                        $this->destroy();
                        return (object)['result' => "true", 'sql' => $res->queryString];
                    } catch (Exception $e) {
                        return json_decode(json_encode(['result' => "false", "sql" => $query, "error" => ["message" => $e->getMessage(), "line" => $e->getLine(), "trace" => $e->getTrace()]]), false);
                    }
                    break;

                default:
                    try {
                        throw new Exception("SELECT||UPDATE||DROP||DELETE||INSERT||CREATE|| BIRORTASI TANLASHINGGIZ KERAK");
                    } catch (Exception $e) {
                    }
                    break;
            }
        }
    }
}