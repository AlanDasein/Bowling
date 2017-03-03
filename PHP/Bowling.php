<?php

class Bowling {

    const PINS = 10;                     // number of pins to knock down
	const TURNS = 10;                    // number of turns per game
    const TRIES = 2;                     // minimum number of tries per turn

    private $current_turn = 0;            // the current turn
    private $current_try = 0;             // the current try for the current turn
    private $played_turns = [];           // array with the turns

    /*
     * main function to manage all the functionality
     * only callable function by class members
     */

    public function play() {
        $this->setTry();
        $this->setTurn();
        $this->roll();
        $this->getScore();
    }

    /*
     * game over: calculate score and display results
     */

    private function getScore() {
        if($this->gameCompleted()) {
            $total = 0; // total score
            echo PHP_EOL; // adding line for output format
            foreach($this->played_turns as $key => $value) {
                $this->played_turns[$key]["score"] = 0; // the score for each turn
                echo "TURN ".($key < 10 ? "0".$key : $key).": (".implode(", ", $value).") "; // output results (begin)
                foreach($value as $value2) $this->played_turns[$key]["score"] += $value2; // check for regular points obtained on each try
                if($key < self::TURNS) { // if it's not the last turn, check for bonus points
                    if($this->isStrike($key)) {
                        $this->played_turns[$key]["score"] += ($this->played_turns[$key + 1][1] + $this->played_turns[$key + 1][2]);
                        if($this->isStrike($key + 1) && isset($this->played_turns[$key + 2])) {
                            $this->played_turns[$key]["score"] += $this->played_turns[$key + 2][1];
                        }
                    }
                    else if($this->isSpare($key)) $this->played_turns[$key]["score"] += $this->played_turns[$key + 1][1];
                }
                $total += $this->played_turns[$key]["score"]; // add the turn's score to total score
                echo "(turn score: ".$this->played_turns[$key]["score"].") (total score: ".$total.")".PHP_EOL; // output results (begin)
            }
            echo PHP_EOL."TOTAL SCORE: ".$total.PHP_EOL.PHP_EOL;
        }
    }

    /*
     * set current try
     */

    private function setTry() {
        if($this->current_try < self::TRIES) $this->current_try++;
        else $this->current_try = $this->extraTry() ? ($this->current_try + 1) : 1;
    }

    /*
     * set current turn and create entry to store the turn's tries
     */

    private function setTurn() {
        if($this->current_turn < self::TURNS && $this->current_try === 1) $this->played_turns[++$this->current_turn] = [];
    }

    /*
     * set knocked down pins on a given turn/trie
     * get the remaining pins by deducting the pins that were knocked down in the previous attempt if
     * it's the last or extra try and
     * a) it's not the last turn, or
     * b) it's the last turn and it's the last regular try and a strike was not scored in the previous try, or
     * c) it's the last turn and it's the extra try and neither a strike was scored in the previous try nor a spare was scored in the turn
     */

    private function roll() {
        $pins_left = self::PINS;
        if(
            $this->current_try >= self::TRIES &&
            (
                $this->current_turn < self::TURNS ||
                ($this->current_try === self::TRIES && !$this->isStrike($this->current_turn)) ||
                ($this->current_try > self::TRIES && (!$this->isStrike($this->current_turn, $this->current_try - 1) || !$this->isSpare($this->current_turn)))
            )
        ) {$pins_left -= $this->played_turns[$this->current_turn][$this->current_try - 1];}
        $this->played_turns[$this->current_turn][$this->current_try] = rand(0, $pins_left);
    }

    /*
     * check if all available turns and tries + a possible extra try were played; the game is over if
     * a) it's the last turn and all regular tries + an extra try were played
     * b) it's the last turn and all regular tries were played and neither a strike or a spare was scored in the turn
     */

    private function gameCompleted() {
        return $this->current_turn === self::TURNS && ($this->current_try > self::TRIES || ($this->current_try === self::TRIES && !$this->extraTry()));
    }

    /*
     * check if it's possible to get an extra try; it's possible only if
     * it's the last turn and it's the last regular try and a strike or a spare was scored in the turn
     */

    private function extraTry() {
        return (
            $this->current_turn === self::TURNS && $this->current_try === self::TRIES &&
            ($this->isStrike($this->current_turn) || $this->isSpare($this->current_turn))
        );
    }

    /*
     * check if all the pins were knocked down on a given trie
     */

    private function isStrike($turn, $try = 1) {
        return $this->played_turns[$turn][$try] === self::PINS;
    }

    /*
     * check if all the pins were knocked down on a given turn
     */

    private function isSpare($turn) {
        return $this->played_turns[$turn][1] + $this->played_turns[$turn][2] === self::PINS;
    }

}