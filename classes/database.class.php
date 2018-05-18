<?php

class Database
{
    private $db = NULL;
    private $recordset = [];
    private $hidden_tables = [
        'books', 'user', 'task', 'tasks'
    ];
    
    public function create_table($table_name)
    {
        $name = $this -> check_table_name($table_name);
        $db = $this -> get_connection();
        $request = 'CREATE TABLE ';
        $request .= $name;
        $request .= '   (
                            `id` INT NOT NULL AUTO_INCREMENT,
                            `name` VARCHAR(30) NOT NULL,
                            `value` FLOAT,
                            PRIMARY KEY(`id`)
                        )
                    ENGINE=INNODB
                    DEFAULT CHARSET=utf8;';
        $params = [];
        $this -> do_request($request, $params);
        return; 
    }

    public function add_column($table_name, $col_name, $col_type)
    {
        $db = $this -> get_connection();
        $request = "ALTER TABLE
                        $table_name 
                    ADD
                        $col_type
                        $col_name";
        $params = [];
        $this -> do_request($request, $params);
        return; 
    }
    
    public function change_column_name($table_name, $col_name, $new_col_name)
    {
        $db = $this -> get_connection();
        $request = "ALTER TABLE
                        $table_name 
                    CHANGE
                        $col_name
                        $new_col_name";
        $params = [];
        $this -> do_request($request, $params);
        return; 
    }

    public function change_column_type($table_name, $col_name, $col_type)
    {
        $db = $this -> get_connection();
        $request = "ALTER TABLE
                        $table_name 
                    MODIFY
                        $col_name
                        $col_type";
        $params = [];
        $this -> do_request($request, $params);
        return; 
    }
    
    public function get_tables()
    {
        $db = $this -> get_connection();
        $params = [];
        $all_tables = [];
        $tables = [];
        $request = 'SHOW TABLES';
        $all_tables = $this -> do_request($request, $params);
        foreach ($all_tables as $table) {
            if (!in_array($table[0], $this -> hidden_tables)) {
                $tables[] = $table;
            }
        }
        return $tables;
    }

    public function get_fields($table_name)
    {
        $db = $this -> get_connection();
        $request = 'DESCRIBE '.$table_name;
        $params = [];
        return $this -> do_request($request, $params);
    }
    
    public function delete_table($table_name)
    {
        if ($this ->is_not_hidden($table_name)) {
            $db = $this -> get_connection();
            $request = 'DROP TABLE '.$table_name;
            $params = [];
            return $this -> do_request($request, $params);
        }
    }

    private function get_connection()//создаем и возвращаем объект PDO
    {
        require_once 'config.php';//подключение файла конфигурации параметров соединения
        try {
            $pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
            return $pdo;
        } catch (Exception $error) {
            return NULL;
        }
    }
    
    public function __construct() {
        $this -> db = $this -> get_connection();//тестируем подключение
        if (!isset($this -> db)) {
            die('Не удалось подключиться к базе данных');
        }
    }
    
    private function do_request($request, $params)//выполняет запрос с параметрами
    {
        $results = [];
        $stmt = NULL;
        try {
            $this -> db = $this -> get_connection();
            $stmt = $this -> db -> prepare($request);
            foreach ($params as $key => $value) {
                $stmt -> bindValue($key, $value);
            }
            $stmt -> execute();
            $this -> db = NULL;
            if (isset($stmt)) {
                while ($row = $stmt -> fetch()) {
                    $results[] = $row;
                }
            } else {
                $results = NULL;
            }
        } catch (Exception $error) {
            echo $error -> getMessage();
        }
        return $results;
    }
    
    private function check_table_name($name)
    {
        $new_name = trim($name);
        $tables = $this -> get_tables();
        if (in_array($name, $tables)) {
            $new_name = 'new_table'.time();
        }
        return $new_name;
    }
    
    private function is_not_hidden($table_name)
    {
        $new_name = trim($table_name);
        if (!in_array($new_name, $this -> hidden_tables)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
   
      
}//===end class===