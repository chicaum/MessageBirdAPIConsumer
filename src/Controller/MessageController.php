<?php

namespace App\Controller;

use App\Entity\MessageRequest;
use MessageBird\Client;
use MessageBird\Objects\Message;
use MessageBird\Exceptions\AuthenticateException;
use MessageBird\Exceptions\BalanceException;
use Symfony\Component\HttpFoundation\JsonResponse;

class MessageController {

    /** @var Client*/
    private $client;

    public function __construct(Client $client) {
        $this->client = $client;
    }

    public function sendMessage(MessageRequest $messageRequest) {
        $message             = new Message();
        $message->originator = $messageRequest->getOriginator();
        $message->recipients = [$messageRequest->getRecipient()];
        $message->body       = $messageRequest->getMessage();

        try {
            $this->client->messages->create($message);
            return new JsonResponse(['message' => 'Message sent'], 200);
        }
        catch (AuthenticateException $e) {
            return new JsonResponse(['message' => 'Unknown access key'], 401);
        }
        catch (BalanceException $e) {
            return new JsonResponse(['message' => 'No balance'], 401);
        }
        catch (\Exception $exception) {
            return new JsonResponse(['message' => $exception->getMessage()], 500);
        }
    }
}