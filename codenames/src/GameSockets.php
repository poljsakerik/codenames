<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
require_once("Game/Word.php");
require_once("Game/Game.php");

class GameSockets implements MessageComponentInterface {
    protected $clients;
    public $game;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->game = new Game();
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        // $this->game->addPlayer($conn);
        echo ("New connection {$conn->resourceId}\n");
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $this->decode_mesagge($from, $msg);
        foreach ($this->clients as $client) {
                // The sender is not the receiver, send to each client connected
                $client->send($this->game->getGameData());
            
        }
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->game->removePlayer($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }

    public function decode_mesagge($conn, $msg) {
        $msg_object = json_decode($msg);
        if ($msg_object == null) {
            return;
        } else if (property_exists($msg_object, "connectionStart")) {
            if (property_exists($msg_object, "uid") && $msg_object->uid !== null) {
                $this->game->addPlayer($msg_object->uid, $conn);
            } else {
                $this->send_error_mesagge($conn, "Didnt recieve uid");
            }  
        } else if (property_exists($msg_object, "pressedWord")) {
            $this->game->pressWord($conn, $msg_object->pressedWord);
        } else if (property_exists($msg_object, "becomeSpy")) {
            $this->game->becomeSpy($conn);
        } else if (property_exists($msg_object, "endTurn")) {
            $this->game->endTurn($conn);
        } else if (property_exists($msg_object, "restart")) {
            $this->game->restart();
        } else if (property_exists($msg_object, "switchTeam")) {
            $this->game->switchTeam($conn);
        }
        else if (property_exists($msg_object, "close")) {
            $this->game->removePlayer($conn);
        }
    }


    public function send_error_mesagge($conn, $msg) {
        $msg_object = (object) ['error' => $msg];
        $msg_object->error = $msg;
        $conn->send(json_encode($msg_object));
    }
}