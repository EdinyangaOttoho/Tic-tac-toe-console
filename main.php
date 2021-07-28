<?php
    /*Tic Tac Toe console Game
        <Developed By Edinyanga Ottoho>
    */
    function main() {
        echo "\n<------------ GAME ------------>\n";
        echo "Welcome! Type a letter to proceed...\n";
        echo "s: Start game\ni: Instructions\ne: Exit Game\n-----------------------------------\n";
        init:
        $option = strtolower(readline("? "));
        switch ($option) {
            case "s":
                startgame();
                break;
            case "e":
                quitgame();
                break;
            case "i":
                instructions();
                break;
            default:
                echo "Please, select either e or s to proceed...\n";
                goto init;
                break;
        }
    }
    function instructions() {
        echo "\n<------------ HOW TO PLAY ------------>\nYou are to select the slots for the game, which range from 1,1 to 3,3.\nThe first box to the top-left corner is 1,1 and the box at the bottom-right corner is 3,3.\nEach player has to select from the empty (available) slots at each turn.\nGoodluck.\n";
        main();
    }
    function yesorno($x, $y) {
        echo "Are you sure? y or n\n";
        $yes_or_no = readline();
        switch ($yes_or_no) {
            case "y":
                call_user_func($y);
                break;
            case "n":
                main();
                break;
            default:
                call_user_func($y);
                break;
        }
    }
    function startgame() {
        echo "\nGame begins!\n";
        $player_variables = ["X", "O"];
        shuffle($player_variables);
        $options = [
            "Player_One"=>$player_variables[0],
            "Player_Two"=>$player_variables[1]
        ];
        echo "\n";
        foreach ($options as $k=>$v) {
            echo str_replace("_", " ", $k)." takes ".$v."\n";
        }
        $player_one_points = 0;
        $player_two_points = 0;
        startover:
        $board = [
            ["_","_","_"],
            ["_","_","_"],
            ["_","_","_"]
        ];
        $turn = 0;
        $is_playing = true;
        while ($is_playing == true) {
            echo "\n";
            $expected = "";
            if ($turn == 0) {
                echo "Play your turn [Player One]\n";
                $expected = $options["Player_One"];
            }
            else {
                echo "Play your turn [Player Two]\n";
                $expected = $options["Player_Two"];
            }
            print_board($board);
            $which_play = empty_slots($board);
            echo "\nChoose a slot\n";
            foreach ($which_play as $k=>$v) {
                echo "$k: $v\n";
            }
            choice:
            $play = strtolower(readline("? "));
            $cnt = 0;
            foreach ($which_play as $k=>$v) {
                if ($k == $play) {
                    $cnt++;
                }
            }
            if ($cnt == 0) {
                echo "You must choose from the provided slots!\n";
                goto choice;
            }
            else {
                $selected_slot = $which_play[$play];
                $coords = explode(",", $selected_slot);
                $x = $coords[0];
                $y = $coords[1];
                $board[$x-1][$y-1] = $expected;
                $turn = ($turn == 0)?1:0;
                $is_over = check_win($board, $options);
                if ($is_over[0] == true) {
                    print_board($board);
                    if ($is_over[1] == 0) {
                        echo "\nPlayer One Wins! +5 points\n";
                        $turn = 0;
                        $player_one_points += 5;
                    }
                    else if ($is_over[1] == 1) {
                        echo "\nPlayer Two Wins! +5 points\n";
                        $turn = 1;
                        $player_two_points += 5;
                    }
                    else {
                        $turn = mt_rand(0, 1);
                        echo "\nIt's a tie!\n";
                    }
                    echo "\nPlayer One score: $player_one_points\n";
                    echo "Player Two score: $player_two_points\n";
                    sleep(1);
                    echo "\nContinue playing? y or n? ";
                    continue_playing:
                    $continue = strtolower(readline());
                    $is_playing = false;
                    switch ($continue) {
                        case "y":
                            echo "\nNew Game\n";
                            goto startover;
                            break;
                        case "n":
                            main();
                            break;
                        default:
                            echo "\nPlease choose y or n ? ";
                            goto continue_playing;
                            break;
                    }
                }
            }
        }
    }
    function checkboard($symbol, $board) {
        //horizontal
        $check_a = [0,1,2];
        $check_b = [0,1,2];
        foreach ($check_a as $i) {
            $cnt = 0;
            foreach ($check_b as $j) {
                if ($board[$i][$j] == $symbol) {
                    $cnt++;
                }
            }
            if ($cnt == 3) {
                return true;
                break;
            }
        }
        //vertical
        foreach ($check_a as $i) {
            $cnt = 0;
            foreach ($check_b as $j) {
                if ($board[$j][$i] == $symbol) {
                    $cnt++;
                }
            }
            if ($cnt == 3) {
                return true;
                break;
            }
        }
        //Diagonal - Forward
        $cnt = 0;
        for ($i = 0; $i <= 2; $i++) {
            if ($board[$i][$i] == $symbol) {
                $cnt++;
            }
        }
        if ($cnt == 3) {
            return true;
        }
        //Diagonal - Backwards
        $cnt = 0;
        for ($i = 2; $i >= 0; $i--) {
            $j = 2 - $i;
            if ($board[$i][$j] == $symbol) {
                $cnt++;
            }
        }
        if ($cnt == 3) {
            return true;
        }
        return false;
    }
    function check_win($board, $options) {
        $which = ["X"=>-1, "O"=>-1];
        $choice = "_";
        $gameover = false;
        foreach ($options as $k=>$v) {
            $player = ($k == "Player_One")?0:1;
            $which[$v] = $player;
        }

        //determiation block goes here...
        $check_x = checkboard("X", $board);
        $check_o = checkboard("O", $board);

        if ($check_x == true) {
            $choice = "X";
            $gameover = true;
        }
        else if ($check_o == true) {
            $choice = "O";
            $gameover = true;
        }
        else {
            $cnt = 0;
            foreach ($board as $v) {
                foreach ($v as $i) {
                    if ($i != "_") {
                        $cnt++;
                    }
                }
            }
            if ($cnt == 9) {
                $gameover = true;
                $choice = "_";
            }
            else {
                $gameover = false;
            }
        }
        //ends here

        $decision = "";
        switch ($choice) {
            case "X":
                $decision = $which[$choice];
                break;
            case "O":
                $decision = $which[$choice];
                break;
            case "_":
                $decision = -1;
                break;
        }
        return [$gameover, $decision];
    }
    function empty_slots($board) {
        $alphabets = ["a","b","c","d","e","f","g","h","i"];
        $output = [];
        $cnt = -1;
        for ($i = 0; $i < count($board); $i++) {
            for ($j = 0; $j < count($board[$i]); $j++) {
                if ($board[$i][$j] == "_") {
                    $x = $i+1;
                    $y = $j+1;
                    $concat = "$x,$y";
                    $cnt++;
                    $output[$alphabets[$cnt]] = $concat;
                }
            }
        }
        return $output;
    }
    function print_board($board) {
        echo "\n";
        foreach ($board as $i) {
            echo "   ".implode(" ", $i)."\n";
        }
        echo "\n";
    }
    function quitgame() {
        exit;
    }
    main();
?>