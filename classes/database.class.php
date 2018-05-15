<?php

class Database
{
    private $db = NULL;
    private $recordset = [];
    
    public function create_table($table_name)
    {
        $db = get_database();
        $request = <<<EOT
            CREATE TABLE IF NOT EXISTS
                'new_table' (
                    `id` INT NOT NULL AUTO_INCREMENT,
                    `name` VARCHAR(30) NOT NULL,
                    `value` FLOAT
                PRIMARY KEY(`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
EOT;
        $params = [
            [
                'fieldName' => '',
                'fieldValue' => ''
            ]
        ];
        $this -> do_request($request, $params);
        return; 
    }

    public function add_column($table_name, $col_name, $col_type)
    {
        $db = get_database();
        $request = <<<EOT
            ALTER TABLE :table_name 
                ADD :col_name :col_type
EOT;
        $params = [
            [
                'fieldName' => ':table_name',
                'fieldValue' => $table_name
            ],
            [
                'fieldName' => ':col_name',
                'fieldValue' => $col_name
            ],
            [
                'fieldName' => ':col_type',
                'fieldValue' => $col_type
            ]
        ];
        $this -> do_request($request, $params);
        return; 
    }
    
    public function change_column_name($table_name, $col_name, $new_col_name)
    {
        $db = get_database();
        $request = <<<EOT
            ALTER TABLE :table_name 
                CHANGE :col_name :new_col_name
EOT;
        $params = [
            [
                'fieldName' => ':table_name',
                'fieldValue' => $table_name
            ],
            [
                'fieldName' => ':col_name',
                'fieldValue' => $col_name
            ],
            [
                'fieldName' => ':new_col_name',
                'fieldValue' => $new_col_name
            ]
        ];
        $this -> do_request($request, $params);
        return; 
    }

    public function change_column_type($table_name, $col_name, $col_type)
    {
        $db = get_database();
        $request = <<<EOT
            ALTER TABLE :table_name 
                MODIFY :col_name
                :col_type
EOT;
        $params = [
            [
                'fieldName' => ':table_name',
                'fieldValue' => $table_name
            ],
            [
                'fieldName' => ':col_name',
                'fieldValue' => $col_name
            ],
            [
                'fieldName' => ':col_type',
                'fieldValue' => $col_type
            ]
        ];
        $this -> do_request($request, $params);
        return; 
    }
    
    public function get_tables()
    {
        $db = get_database();
        $request = 'SHOW TABLES';
        $params = [
            [
                'fieldName' => '-',
                'fieldValue' => '-'
            ]
        ];
        return $this -> do_request($request, $params);
    }

    public function get_fields($table_name)
    {
        $db = get_database();
        $request = 'DESCRIBE :table_name';
        $params = [
            [
                'fieldName' => ':table_name',
                'fieldValue' => $table_name
            ]
        ];
        return $this -> do_request($request, $params);
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
            foreach ($params as $param) {
                $stmt -> bindValue($param['fieldName'], $param['fieldValue']);
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
      
}//===end class===