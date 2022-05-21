<?php
namespace MyApp;
class GameData {
    public $words;
    public $players;
    public $turn;
    public $winner;
    public $score;

    public function __construct($wordsArray = array(), $playersArray = array(), $turn = null, $winner = null, $score = null, $isSpy = false)
    {   
        $this->words = $wordsArray;
        $this->players = $playersArray;
        $this->turn = $turn;
        $this->winner = $winner;
        $this->score = $score;
        if (!$isSpy) {
            $newArray = array();
            foreach ($wordsArray as $word ) {
                if ($word->hasBeenPressed) {
                    $newArray[] = $word;
                } else {
                    $newWord = clone $word;
                    $newWord->team = "gray";
                    $newArray[] = $newWord;
                }
            }
            $this->words = $newArray;
        }
    }
}