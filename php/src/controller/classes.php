<?php


class TicTacGame{
    
    // Размер игрового поля   
    private $fieldWidth = 20;
    private $fieldHeight = 20;
    
    //число крестиков или ноликов в ряд для победы.
     
    private $countToWin = 5;
    
    
     //массив сделанных ходов вида $field[$x][$y] = $player;
     
    private $field = array();
    

     // выделить при отображении победившей комбинации.
    private $winnerCells = array();
    
    private $currentPlayer = null; // 1 или 2, а после окончания игры - null.
    private $winner = null; // после окончания игры будет содержать 1 или 2.

    private $flag = 0; //флаг для проверки занесена ли информация о победе в бд

    private $moveCount = 0; //счетчик шагов

    private $giveUpFlag = 0; //флаг для параметра "сдаться"

    public $start; //время начала

    public $end = 0;


    public function __construct(){
        $mysqli = $this->dbConnection();

        $sql="SELECT SUM(count) FROM users";
        $result = mysqli_query($mysqli, $sql);
        $amount = mysqli_fetch_array($result);
        $sum_count = $amount[0] % 2 + 1;
        $this->currentPlayer = $sum_count;


        $time = time();
        $this->start = $time;

        // $time = time();
        // $this->start = $time;
        
    }

    /*
     * Обрабатывает очередной ход. Ставит в указанные координаты на поле
     * символ текущего игрока. Передаёт ход другому игроку, а в случае победы
     * опреляет победителя.
     */
    public function makeMove($x, $y) {

        //защита от дурака
        //echo $this->moveCount;
        if($this->moveCount >= $this->fieldWidth*$this->fieldHeight - 1){
            echo "Ничья!";
            $this->currentPlayer = 0;
        }

        // Учитываем ход, если выполняются все условия:
        // 1) игра ещё идет,
        // 2) клетка находится в пределах игрового поля.
        // 3) в поле на указанном месте ещё пусто,
        if(
                $this->currentPlayer
                &&
                $x >= 0 && $x < $this->fieldWidth
                &&
                $y >= 0 && $y < $this->fieldHeight
                &&
                empty($this->field[$x][$y]))
        {
                $this->moveCount = $this->moveCount + 1;
                if($this->moveCount == 1){
                    $time = time();
                    $this->start = $time;
                }
                $current = $this->currentPlayer;

                $this->field[$x][$y] = $current;
                $this->currentPlayer = ($current == 1) ? 2 : 1;
                
                $this->checkWinner();
        }
    }
    
    /*
     * Делает поиск выигравшей комбинации, проходя по всему полю и проверяя
     * 4 направления (горизонталь, вертикаль и 2 диагонали).
     */
    private function checkWinner() {
        for($y = 0; $y < $this->fieldHeight; $y++) {
            for($x = 0; $x < $this->fieldWidth; $x++)
            {
                $this->checkLine($x, $y, 1, 0);
                $this->checkLine($x, $y, 1, 1);
                $this->checkLine($x, $y, 0, 1);
                $this->checkLine($x, $y, -1, 1);
            }
        }
        if($this->winner) {
            $this->currentPlayer = null;
        }
    }

    public function giveUp(){
        if($this->giveUpFlag == 0){
            $current = $this->currentPlayer;
            $this->currentPlayer = ($current == 1) ? 2 : 1;
            $this->winner = $this->currentPlayer;
            $this->countWinnerVictory();
            $this->currentPlayer = null;
            $this->giveUpFlag = 1;

            //$this->end = date("h:i:s");
            //$this->field = array();
        }

    }
    
    /*
     * Проверяет, а не находится ли в этом месте поля выигрышная комбинация
     * из необходимого числа крестиков или ноликов.
     * Если выигрышная комбинация найдена, запоминает победителя
     * и саму выигрышную комбинацию в массиве winnerCells.
     * 
     * $startX $startY начальная точка, от которой проверять наличие комбинации
     * $dx $dy направление, в котором искать комбинацию
     */
    private function checkLine($startX, $startY, $dx, $dy) {
        $x = $startX;
        $y = $startY;
        $field = $this->field;
        $value = isset($field[$x][$y])? $field[$x][$y]: null;
        $cells = array();
        $foundWinner = false;
        if($value) {
            $cells[] = array($x, $y);
            $foundWinner = true;
            for($i=1; $i < $this->countToWin; $i++) {
                $x += $dx;
                $y += $dy;
                $value2 = isset($field[$x][$y])? $field[$x][$y]: null;
                if($value2 == $value) {
                    $cells[] = array($x, $y);
                } else {
                    $foundWinner = false;
                    break;
                }
            }
        }
        if($foundWinner) {
            foreach($cells as $cell) {
                $this->winnerCells[$cell[0]][$cell[1]] = $value;
            }
            $this->winner = $value;
            $this->countWinnerVictory();
            $this->flag = 1;
            //$this->end =  time();
        }
    }

    
    // public function setPlayer($player) {$this->currentPlayer = $player;}

    private function dbConnection(){
        $mysqli = new mysqli("db", "user", "password", "appDB");
        if (!$mysqli) {
            die("Connection failed: " . mysqli_connect_error());
        }
        return $mysqli;
    }

    public function getWinnerName(){
        $mysqli = $this->dbConnection();
        $id_winner = $this->getWinner() + 1;

        $sql = "SELECT name FROM users WHERE ID = $id_winner";
        $result = mysqli_query($mysqli, $sql);
        $name_array = mysqli_fetch_array($result);
        $name = $name_array[0];
        return $name;
    }

    public function countWinnerVictory(){
        $mysqli = $this->dbConnection();
        $id_winner = $this->getWinner() + 1;

        $sql="SELECT count FROM users WHERE ID = $id_winner";
        $result = mysqli_query($mysqli, $sql);
        $amount = mysqli_fetch_array($result);
        $winner_count = $amount[0] + 1;
        $sql = "UPDATE users SET count=$winner_count WHERE ID = $id_winner";
        $result = mysqli_query($mysqli, $sql);

        //задать максимальный счет
        $sql="SELECT score FROM users WHERE ID = $id_winner";
        $result = mysqli_query($mysqli, $sql);
        $score_arr = mysqli_fetch_array($result);
        $score = $score_arr[0];
        $new_score = $this->getScore();
        if($score < $new_score){
            $sql = "UPDATE users SET score=$new_score WHERE ID = $id_winner";
            $result = mysqli_query($mysqli, $sql);  
        }

        //задать суммарный счет
        $sql="SELECT sum_score FROM users WHERE ID = $id_winner";
        $result = mysqli_query($mysqli, $sql);
        $score_arr = mysqli_fetch_array($result);
        $sum_score = $score_arr[0] + $new_score;
        $sql = "UPDATE users SET sum_score=$sum_score WHERE ID = $id_winner";
        $result = mysqli_query($mysqli, $sql); 
    }

    public function getPlayerName(){
        $mysqli = $this->dbConnection();

        $player_active = $this->getCurrentPlayer();
        $id_player_active = $player_active + 1;
        $sql = "SELECT name FROM users WHERE ID = $id_player_active";
        $result = mysqli_query($mysqli, $sql);
        $name_array = mysqli_fetch_array($result);
        $name = $name_array[0];
        return $name;
    }

    public function playerNames(){
        $mysqli = $this->dbConnection();
        
        $sql = "SELECT name FROM users";
        $result = mysqli_query($mysqli, $sql);

        $name_array[0] = mysqli_fetch_array($result);
        $name_array[1] = mysqli_fetch_array($result);
        $name_array[2] = mysqli_fetch_array($result);
        return $name_array;
    }

    private function getScore(){
        $score = 0;
        $time = $this->getTime();
       // echo $time;
        if($time==0) $time = 1;
        if ($time <= 60){
            $score = 6000 + (int)(2000/$time);
        }else if ($time <= 120){
            $score = 6000 - (int)(2000/($time-60));
        }else if ($time <= 300){
            $score = 4000 - (int)(1000/($time-120));
        }else{
            $score = 2000;
        }
        return $score;
    }

    public function getCurrentPlayer() { return $this->currentPlayer; }
    public function getWinner()        { return $this->winner; }
    public function getTime() {
        $now = time(); // текущее время на сервере

        // echo $this->start . "   ";
        // echo $now . "  ";
        $time = (int)($now - $this->start);

        return $time;
    }
    public function getField()         { return $this->field; }
    public function getWinnerCells()   { return $this->winnerCells; }
    public function getFieldWidth()    { return $this->fieldWidth; }
    public function getFieldHeight()   { return $this->fieldHeight; }
}