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
        } elseif (isset($params['table_name'])) {
            $table = $params['table_name'];
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
        if (isset($params['change_col'])) {
            $table = $params['table_name'];
            $name = $params['сol_name'];
            if (!empty($params['new_сol_name'])) {
                $new_name = $params['new_сol_name'];
            } else {
                $new_name = $name;
            }
            $new_type = $params['new_col_type'];
            $db -> change_column_type($table, $name, $new_type);
            $db -> change_column_name($table,  $name,  $new_name, $new_type);
        }
        //удаление таблицы
        if (isset($params['delete_table'])) {
            $db -> delete_table($params['table_name']);
            $table = '';
        }
        return;
    }
