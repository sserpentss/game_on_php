<?php

        class FormHandler{

            private $player1 = " ";
            private $player2 = " ";

            private $type = 0;

            public function dbConnection(){
                $mysqli = new mysqli("db", "root", "password", "appDB");
                if (!$mysqli) {
                    die("Connection failed: " . mysqli_connect_error());
                }
                return $mysqli; 
            }

            private function dbClear(){
                $mysqli = $this->dbConnection();
                $sql = "DELETE FROM users WHERE ID > 1;";
                //$result = mysqli_query($mysqli, $sql);
                $result = mysqli_query($mysqli, $sql);
                if ($result == false) {
                    echo mysqli_error($mysqli);
                    print("Произошла ошибка при выполнении запроса");
                } 
            }

            private function newNames(){
                $mysqli = $this->dbConnection();

                //получаем имена игроков
                if (isset($_POST)) {
                    $player1 = $_POST['player1'];
                    $player2 = $_POST['player2'];

                }

                $this->player1 = $player1;
                $this->player2 = $player2;

                //вставляем новые
                $sql = "INSERT INTO users (ID, name, count, score) VALUES (2, '$player1', 0, 0), (3, '$player2', 0, 0);";
                $result = mysqli_query($mysqli, $sql);
            }

            public function result(){
                $mysqli = $this->dbConnection();
                $result = mysqli_query($mysqli, "SELECT * FROM users where id != 1");
                return $result;
            }

            public function names(){
                $this->dbClear();
                $this->newNames();

            }

            public function get_name_p1(){
                return $this->player1;
            }

            public function get_name_p2(){
                return $this->player2;
            }

        }



?>
            