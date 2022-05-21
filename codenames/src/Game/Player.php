<?php
namespace MyApp;
class Player {
    public $username;
    public $socket;
    public $team;
    public $isSpy;
    public $uid;

    public function __construct($username, $socket, $team, $uid) {
         $this->username = $username;
         $this->socket = $socket;
         $this->team = $team;
         $this->isSpy = false;
         $this->uid = $uid;

    }
}