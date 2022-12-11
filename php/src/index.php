<html lang="en">
<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" contente="width=device-width">

    <link rel="stylesheet" href="css/main.css">


    <link rel="shortcut icon" href="все/pic main/фавиконка.png" type="image/x-icon">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>


    <title>Add</title>


</head>
<body>

    <?php
        // if($_SERVER['REQUEST_METHOD']=='POST')
        // include 'add.php';
        //echo "<link rel='stylesheet' href='css/main.css'>";
        
    ?>
    <div class = "block"> 
        <div class = modal>
            <form name="feedback" method="POST" action="view/adding.php">
                <label>Имя первого игрока: <br><input type="text" name="player1"></label>
                <br>
                <label>Имя второго игрока: <br><input type="text" name="player2"></label>
                <br>
                <!-- можно проставить смену ходов -->
                <!-- <label>Очередность хода игроков сменяется: <input type="checkbox" name="formWheelchair" value="Yes" /></label> -->
                <input class="but" type="submit" name="send" value="Отправить">
            </form>
        </div>
        
    </div>


</body>
</html>