<!DOCTYPE html>
<?php
    require_once 'db_functions.php';
    $default_table = 'new_table';
    $table_name = '';
    $fields = [];
    $tables =[];
    $params = [];
    
    if (isset($_POST)) {
        $params = filter_input_array(INPUT_POST, $_POST);
        unset($_POST);
    } else {
        $params['describe'] = $default_table;
    }
    var_dump($params);
    $tables = get_tables();
    $fields = do_command($params);
    unset($params);
?>

<html lang="ru">
    <head>
        <meta charset="utf-8">
        <title>SQL lesson 4</title>
        <link rel="stylesheet" href="css/styles.css"/>
    </head>
    <body>
        <header>
            <h1>Задание к лекции 4.4 «Управление таблицами и базами данных»</h1>
        </header>
        <section class="main-container">
            <div class="form-container">
                <form method="post">
                    <input type="text" name="table_name" placeholder="имя таблицы">
                    <input type="submit" name="create_table" value="Создать таблицу">
                </form>
            </div>
            <div class="db-view">
                <ul>
                    Таблицы
                    <?php foreach($tables as $table): ?>
                    <li>
                        <a href=""><?=$table[0];?></a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="form-container">
                <form method="post">
                    <input type="text" name="table_name" placeholder="имя таблицы">
                    <select>
                        <?php foreach($fields as $field): ?>
                            <option value=<?=$field['col_name'];?>></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" name="сol_name" placeholder="имя поля">
                    <input type="text" name="col_type" placeholder="тип поля">
                    <input type="submit" name="change_col" value="Изменить">
                </form>
            </div>
        </section>
    </body>
</html>

