<?php

namespace App\Controller;

use MessageBird\Client;
use MessageBird\Objects\Message;
use MessageBird\Exceptions\AuthenticateException;
use MessageBird\Exceptions\BalanceException;

class MainController {
    public function sendMessage($content) {
        $messageBird = new Client('TB07OC8eC6eMVSL6q300eAaiB');

        $message             = new Message();
        $message->originator = 'MessageBird';
        $message->recipients = [4915256218793];
        $message->body       = $content;

        try {
            return $message->body;
//            $messageResult = $messageBird->messages->create($message);
//            var_dump($messageResult);
        }
        catch (AuthenticateException $e) {
            // That means that your accessKey is unknown
            return '\MessageBird\Exceptions\AuthenticateException :: wrong login';
        }
        catch (BalanceException $e) {
            // That means that you are out of credits, so do something about it.
            echo '\MessageBird\Exceptions\BalanceException :: no balance';
        }
        catch (\Exception $e) {
            echo '\Exception ' . $e->getMessage();
        }
    }
}