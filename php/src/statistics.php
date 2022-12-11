<?php

require_once(dirname(__FILE__) . '/controller/add_class.php');

$adding = new FormHandler();

?>

<html lang="en">
<head>
<title>Statistics</title>

<link rel="stylesheet" href="css/main.css">
<link rel="stylesheet" href="css/table.css">
</head>
<body>
 <!-- <h1>Рейтинг игроков</h1>
<table>
    <tr><th>Id</th><th>Name</th><th>Surname</th></tr>  -->

<div class = modal>
    <?php echo '<div class = "add"> '; ?>
    <?php echo "<table class = 'table_style'>"; ?>
    <?php echo "<tr><th>Имя</th><th>Количество побед</th><th>Максимальный счет</th><th>Общий счет</th></tr>"; ?>
        <?php
            //подключение к бд
            $mysqli = new mysqli("db", "user", "password", "appDB");
            if (!$mysqli) {
                die("Connection failed: " . mysqli_connect_error());
            }
            //вывод игроков
            $result = $adding->result();
            foreach ($result as $row){
                echo "<tr><td>{$row['name']}</td><td>{$row['count']}</td><td>{$row['score']}</td><td>{$row['sum_score']}</td></tr>";
            }



        mysqli_close($mysqli);
        ?>
        
    <?php echo "</table>"; ?>

    <?php
        echo '<div class = "as_but"> ';
        echo '<a href = view/game.php class="game_start">Вернуться к игре</a>';
        echo '</div> ';

         echo '<div class = "as_but"> ';
         echo '<a href = index.php class="game_start">Новая игра</a>';
         echo '</div> ';
         echo '</div> ';
    ?>
<!-- 
<br/><a href='index.php'>add</a> -->


</div>




</body>
</html>