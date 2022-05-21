<?php
namespace MyApp;
require_once "/Applications/XAMPP/xamppfiles/htdocs/codenames/src/model/WordsDB.php";

class Word {
    public int $key = 0;
    public string $text = "";
    public string $team = "";
    public bool $hasBeenPressed = false;

    public function __construct($key, $text, $team, $hasBeenPressed = false) {
        $this->key = $key;
        $this->text = $text;
        $this->team = $team;
        $this->hasBeenPressed = $hasBeenPressed;
    }


    static public function createNew() {
        $words = WordsDB::getRandom();
        $teams = array("blue", "blue", "blue", "blue", "blue", "blue", "blue", "blue", "red", "red", "red", "red", "red", "red", "red", "black", 
        "gray", "gray", "gray", "gray", "gray", "gray", "gray", "gray", "gray");
        shuffle($teams);
        $word_objects = array();
        $i = 0;

        foreach($words as $word) {
            $word = new Word($i, $word["word"], $teams[$i]);
            $word_objects[] = $word;
            $i = $i + 1;
        }
        return $word_objects;
    }
}