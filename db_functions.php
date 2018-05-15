<?php
    require_once './classes/database.class.php';
    
    $db = NULL;//ссылка на базу данных (объект PDO);
    
    function get_database()
    {
        global $db;
        if ($db === NULL) {
            $db = new Database();
        }
        return $db;
    }
    
    function get_tables()
    {
        $result = [];//массив для результата запроса на выборку
        $db = get_database();//получаем ссылку на текущую базу данных
        $result = $db -> get_tables();
        return $result;
    }
    
    function get_fields($table_name)
    {
        $result = [];//массив для результата запроса на выборку
        $db = get_database();//получаем ссылку на текущую базу данных
        $result = $db -> get_fields($table_name);
        return $result;
    }
    
    function do_command(&$params)
    {
        $result = [];//массив для результата запроса на выборку
        $db = get_database();//получаем ссылку на текущую базу данных
        
        //создание новой таблицы
        if (isset($params['create'])) {
            $db -> create_table($params['table_name']);
            $result = $db -> get_fields($params['table_name']);
        }
        //описание полей таблицы
        if (isset($params['describe'])) {
            $result = $db -> get_fields($params['describe']);
        }
        return $result;
    }
