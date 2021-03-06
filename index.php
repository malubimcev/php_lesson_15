<!DOCTYPE html>
<?php
    require_once 'db_functions.php';
    $default_table = 'new_table';
    $table_name = '';
    $fields = [];
    $tables = [];
    $request_params = [];

    if ((!empty($_GET)) && (empty($_POST))) {
        $request_params = filter_input_array(INPUT_GET, $_GET);
        unset($_GET);
    } else {
        if (isset($_POST)) {
            unset($_GET);
            $request_params = filter_input_array(INPUT_POST, $_POST);
            unset($_POST);
        }
    }
    
    do_command($request_params);
    $tables = get_tables();
    $table_name = get_current_table($request_params);
    $fields = get_fields($table_name);
    unset($request_params);
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
                        <a href="?action=edit&table=<?=$table[0];?>"><?=$table[0];?></a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="form-container">
                <?php if(!empty($table_name)):?>
                <form method="post">
                    <input type="text" name="table_name" value="<?=$table_name;?>">
                    <input type="submit" name="delete_table" value="Удалить таблицу"><br><br>
                </form>
                <div class="fields-info">
                    <div class="form-container">
                        <label class="field-name">Имя поля</label>
                        <label class="field-type">Тип поля</label>
                        <label class="field-new-name">Новое имя</label>
                        <label class="field-new-type">Новый тип</label>
                    </div>
                    <?php foreach($fields as $field): ?>
                    <form method="post">
                        <input type="hidden" name="table_name" value="<?=$table_name;?>">
                        <input type="text" class="field-name" name="сol_name" value="<?=$field['Field'];?>">
                        <input type="text" class="field-type" name="сol_type" value="<?=$field['Type'];?>">
                        <input type="text" class="field-new-name" name="new_сol_name" placeholder="новое имя поля">
                        <select class="field-new-type" name="new_col_type">
                            <option value="<?=$field['Type'];?>"><?=$field['Type'];?></option>
                            <option value="int">INT</option>
                            <option value="varchar(255)">VARCHAR</option>
                            <option value="float">FLOAT</option>
                        </select>
                        <input type="submit" name="change_col" value="Изменить"><br>
                    </form>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </section>
    </body>
</html>