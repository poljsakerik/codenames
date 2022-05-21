<?php
namespace MyApp;
class Score {
    public $blue;
    public $red;

    public function __construct()
    {
        $this->blue = 0;
        $this->red = 0;
    }

    public function decrement($team)
    {   
        if ($team == "blue") {
            $this->blue--;
        } else if ($team == "red") {
            $this->red--;
        }
    }

    public function set() {
        $this->blue = 8;
        $this->red = 7;
    }
}
