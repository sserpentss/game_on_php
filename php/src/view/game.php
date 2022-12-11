<?php

// Подключаем объявление класса игры.
require_once(dirname(__FILE__) . '/../controller/classes.php');

session_start();

// Получаем из сессии текущую игру.
// Если игры еще нет, создаём новую.
$game = isset($_SESSION['game'])? $_SESSION['game']: null;
if(!$game || !is_object($game)) {
    $game = new TicTacGame();
}


// Обрабатываем запрос пользователя, выполняя нужное действие.
$params = $_GET + $_POST;
if(isset($params['action'])) {
    $action = $params['action'];
    
    //////////////////////////////
    if($action == 'giveUp'){
        //обрабатываем кнопку "сдаться"
        $game->giveUp();
        
    }

    if($action == "statistics"){
        $game = new TicTacGame();
        header("Location: http://localhost/statistics.php");
    }

    if($action == 'move') {
        // Обрабатываем ход пользователя.
        $game->makeMove((int)$params['x'], (int)$params['y']);
        
    } else if($action == 'newGame') {
        // Пользователь решил начать новую игру.
        $game = new TicTacGame();
    }
    //////////////////////////////////////////////////////////////////////////////////////////////////

}
// Добавляем вновь созданную игру в сессию.
$_SESSION['game'] = $game;


// Отображаем текущее состояние игры в виде HTML страницы.
$width = $game->getFieldWidth();
$height = $game->getFieldHeight();
$field = $game->getField();
$winnerCells = $game->getWinnerCells();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN"
  "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" version="XHTML+RDFa 1.0" dir="ltr">
<head profile="http://www.w3.org/1999/xhtml/vocab">

<link rel="stylesheet" href="/../css/field.css">
<link rel="stylesheet" href="/../css/table.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>
    
<?php
    echo "<div class = 'page'>";
?>

<?php {?>
    <?php
        $names = $game->playerNames();
        //echo $names;
        //print_r($names);
    ?>
    <?php echo "<p class ='type_p'>";?>
    <br/>Player <?php echo ($names[1][0])?> | X
    <br/>Player <?php echo ($names[2][0])?> | O
    <br/>
    <?php echo "</p>";?>

<?php }?>

<!--  -->     

     

<?php if($game->getCurrentPlayer()) { ?>
    <!-- Отображаем приглашение сделать ход. -->
    <?php
        $name = $game->getPlayerName();
        echo "<p>Now player " . $name . "...</p>";
    ?>
<?php } ?>

<?php if($game->getWinner()) { ?>
    <?php

        $name = $game->getWinnerName();
        echo "<p>Winner " . $name . "! </p>";
    ?>
    <!-- Отображаем сообщение о победителе -->
<?php } ?>

<!-- Рисуем игровое поле, отображая сделанные ходы
и подсвечивая победившую комбинацию. --> 

<?php
    echo "<div class='field'>";
?>
<div class="ticTacField">
    <?php for($y=0; $y < $height; $y++) { ?>
        <div class="ticTacRow">
            <?php for($x=0; $x < $width; $x++) {
                // $player - игрок, сходивший в эту клетку :), или null, если клетка свободна.
                // $winner - флаг, означающий, что эта клетка должна быть подсвечена при победе.
                $player = isset($field[$x][$y])? $field[$x][$y]: null;
                $winner = isset($winnerCells[$x][$y]);
                $class = ($player? ' player' . $player: '') . ($winner? ' winner': '');
                ?>
                <div class="ticTacCell<?php echo $class ?>">
                    <?php if(!$player) { ?>
                        <!-- Клетка свободна. Отображаем здесь ссылку,
                        на которую нужно кликнуть для совершения хода. -->
                        <a href="?action=move&amp;x=<?php echo $x ?>&amp;y=<?php echo $y ?>"></a>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
</div>

<?php
    echo "</div>";
    echo "<br>"
?>

<?php
    echo "<div class='buttons'>"
?>

<form action="" method="POST">
    <input type="submit" formaction="?action=giveUp" name="submit" value="Сдаться" class = "but">
    <input type="submit" formaction="?action=newGame" name="submit" value="Начать новую игру" class = "but">
    <input type="submit" formaction="?action=statistics" name="submit" value="Статистика" class = "but">
</forms>

<?php
    echo "</div>";
?>

            
<!-- <br/><a href="?action=newGame">New game</a> -->

<!-- <br/><a href='statistics.php'>Отобразить статистику</a> -->

<!-- <br/><a href='?action=statistics'>Отобразить статистику</a> -->
          

<?php
    echo "</div>";
?>
</body>
</html>