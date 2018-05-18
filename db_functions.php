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
    
    function get_current_table(&$params)
    {
        $table = '';//
        if ((isset($params['action'])) && ($params['action'] == 'edit')) {
            $table = $params['table'];
        }
        return $table;
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
        if (isset($params['create_table'])) {
            $db -> create_table($params['table_name']);
        }
        //редактирование полей таблицы
        if (isset($params['edit_fields'])) {
            $db -> change_column_name($params['table_name'], $col_name, $new_col_name);
            $db -> change_column_type($params['table_name'], $col_name, $col_type);
        }
        //удаление таблицы
        if (isset($params['delete_table'])) {
            $db -> delete_table($params['table_name']);
        }
        return;
    }
