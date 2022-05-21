<?php
namespace MyApp;
require_once( "Word.php");
require "GameData.php";
require "Player.php";
require_once("Score.php");
require_once '/Applications/XAMPP/xamppfiles/htdocs/codenames/src/model/UserDB.php';
require_once "/Applications/XAMPP/xamppfiles/htdocs/codenames/src/model/UserStatsDB.php";
class Game
{
    public $players;
    public $wordsList;
    public $hasStarted;
    public $winner;
    public $turn;
    public $hasEnded;
    public $score;

    public function __construct() {
        $this->players = array();
        $this->wordsList = [];
        $this->hasStarted = false;
        $this->winner = null;
        $this->turn = null;
        $this->hasEnded = false;
        $this->score = new Score();
        $this->score->set();
    }

    private function negateTeam($team) {
        if ($team == "blue") {
            return "red";
        }
        return "blue";
    }

    public function addPlayer($uid, $socket) {
        $username = UserDB::getUsername($uid);
        $n_blue = count($this->getTeamMembers("blue"));
        $n_red = count($this->getTeamMembers("red"));
        $player = null;
        if ($n_blue > $n_red) {
            $player = new Player($username, $socket, "red", $uid);
        }
        else {
            $player = new Player($username, $socket, "blue", $uid);
        }
        $this->players[] = $player;
        $this->sendGameDataAll();
        UserDB::setActive($uid, true);
        echo("Added player \n");
    }

    public function removePlayer($socket) {
        $new_array = array();
        foreach ($this->players as $player ) {
            if ($player->socket !== $socket) {
                $new_array[] = $player;
            } else {
                UserDB::setActive($player->uid, false);
                echo("Removed player \n");
            }
        }
        $this->players = $new_array;
        if (sizeof($this->players) < 4 && $this->hasStarted) {
            $this->hasEnded = true;
            $this->restart();
        }
        $this->sendGameDataAll();
    }

    public function becomeSpy($socket) {
        if ($this->hasStarted) {
            $this->sendMessage("You can't become a spy during the game");
            return;
        }
        $player = $this->findPlayerBySocket($socket);
        if (!$this->teamHasSpy($player->team)) {
            $player->isSpy = true;
            $this->sendGameDataAll();
            $this->shouldGameStart();
            return;
        }
        $this->sendMessage($socket, "Your team already has a spy");
    }

    public function shouldGameStart() {
        if (count($this->players) > 3){
            if ($this->teamHasSpy("blue") && $this->teamHasSpy("red")) {
                $this->start();
            }
        }
        return false;
    }

    public function start() {
        $this->turn = "blue";
        $this->hasStarted = true;
        $this->wordsList = Word::createNew();
        $this->sendGameDataAll();
    }

    public function pressWord( $socket, $key ) {
        $player = $this->findPlayerBySocket($socket);
        if ($player->team != $this->turn || $this->hasEnded || !$this->hasStarted || $player->isSpy) {
            return;
        }
        foreach($this->wordsList as $word) {
            if ($word->key == $key) {
                $this->checkPressedWord($word);
                $word->hasBeenPressed = true;
                UserStatsDB::updateWord($player->uid, $player->team, $word);
            }
        }
        $this->checkWinner();
        $this->sendGameDataAll();
    }

    public function checkPressedWord($word) {
        $this->score->decrement($word->team);
        if ($word->team == "black") {
            $this->handleWin($this->negateTeam($this->turn));
            return;
        }
        if ($word->team != $this->turn) {
            $this->turn = $this->negateTeam($this->turn);
            return;
        }
    }

    public function checkWinner() {
        if ($this->score->blue == 0) {
            $this->handleWin("blue");
        } else if ($this->score->red == 0) {
            $this->handleWin("red");
        }
    }

    public function handleWin($team) {
        $this->winner = $team;
        $this->hasEnded = true;
        $this->hasStarted = false;
        foreach ($this->players as $player) {
            UserStatsDB::updateGameResult($player->uid, $player->team, $this->winner);
        }
    }

    public function endTurn($socket) {
        $player = $this->findPlayerBySocket($socket);
        if ($player->team != $this->turn || $this->hasEnded || !$this->hasStarted || $player->isSpy) {
            return;
        }
        $this->turn = $this->negateTeam($this->turn);
        $this->sendGameDataAll();
    }

    public function getGameData($isSpy = false) {
        return json_encode(new GameData($this->wordsList, $this->players, $this->turn, $this->winner, $this->score, $isSpy));
    }

    public function sendGameDataAll() {
        $spyGameData = $this->getGameData(true);
        $playerGameData = $this->getGameData();
        foreach ($this->players as $player) {
            if ($player->isSpy || $this->hasEnded) {
                $player->socket->send($spyGameData);
            } else {
                $player->socket->send($playerGameData);
            }
        }
    }

    public function restart()
    {
        if ($this->hasEnded) {
            $this->wordsList = [];
            $this->hasStarted = false;
            $this->winner = null;
            $this->turn = null;
            $this->hasEnded = false;
            $this->score = new Score();
            $this->score->set();
            foreach ($this->players as $player) {
                if ($player->isSpy) {
                    $player->isSpy = false;
                }
            }
            $this->sendGameDataAll();
        }
    }

    private function sendGameData($conn, $isSpy = false) {
        $conn->send($this->getGameData($isSpy));
    }

    public function switchTeam($socket) {
        $player = $this->findPlayerBySocket($socket);
        $nOtherTeam = count($this->getTeamMembers($this->negateTeam($player->team)));
        $nThisTeam = count($this->getTeamMembers($player->team));
        if (!($nOtherTeam > 1 && $nThisTeam > 1)) {
            $this->sendMessage($socket, "More players need to join in order to switch teams");
        } else if (!($nOtherTeam < $nThisTeam)) {
            $this->sendMessage($socket, "The other team doesn't have enough players");
        } else {
            $player->team = $this->negateTeam($player->team);
            $this->sendGameDataAll();
        }
    }

    private function getTeamMembers($team) {
        $teamMembers = array();
        foreach ($this->players as $player) {
            if ($player->team == $team) {
                $teamMembers[] = $player;
            }
        }
        return $teamMembers;
    }

    private function teamHasSpy($team) {
        $teamMembers = $this->getTeamMembers($team);
        foreach ($teamMembers as $player ) {
            if ($player->isSpy) {
                return true;
            }
        }
        return false;
    }

    private function findPlayerBySocket($socket) {
        foreach ( $this->players as $player ) {
            if ( $player->socket == $socket) {
                return $player;
            }
        }   
    }

    public function sendMessage($socket, $msg) {
        $msg_object = (object) ['message' => $msg];
        $msg_object->message = $msg;
        $socket->send(json_encode($msg_object));
    }

}