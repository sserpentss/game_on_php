<?php

require_once(dirname(__FILE__) . '/../controller/add_class.php');

$adding = new FormHandler();

?>

<header>


    <link rel="stylesheet" href="/../css/main.css">
</header>

<body>


    <div class = "modal">
        <?php

        $adding->names();
        echo '<div class = "modal"> ';
        echo '<div class = "add"> ';
        echo '<p>Name player 1: '.$adding->get_name_p1() .' ';
        echo '<br> Name player 2: '.$adding->get_name_p2() .' </p>';
        echo '<div class = "as_but"> ';
        echo '<a href = game.php class="game_start">Game</a>';
        echo '</div> ';
        echo '</div> ';
        echo '</div>';
        ?>

    </div>
    
</body>



