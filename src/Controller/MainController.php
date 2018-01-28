<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\BadRequestException;
use App\Resources\Message\StrategyMessage;
use App\Resources\RequestConverter;
use MessageBird\Client;
use MessageBird\Exceptions\AuthenticateException;
use MessageBird\Exceptions\BalanceException;
use MessageBird\Exceptions\ServerException;
use MessageBird\Objects\Message;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Predis\Client as RedisClient;

class MainController
{
    const REDIS_QUEUE = 'message-bird';

    /** @var Client */
    private $client;

    /** @var RequestConverter */
    private $requestConverter;

    /** @var  RedisClient */
    private $redisClient;

    public function __construct(
        RequestConverter $requestConverter,
        Client $client,
        RedisClient $redisClient
    ) {
        $this->requestConverter = $requestConverter;
        $this->client           = $client;
        $this->redisClient      = $redisClient;
    }

    public function send(Request $request): JsonResponse
    {
        try {
            $messageRequest  = $this->requestConverter->convert($request);
            $messageStrategy = $this->getMessageStrategy($messageRequest);
            $messagesList    = $messageStrategy->prepareMessage();

            foreach ($messagesList as $message) {
                $this->redisClient->rpush(static::REDIS_QUEUE, json_encode($message));
            }

            return new JsonResponse(['message' =>  'Message sent'], JsonResponse::HTTP_OK);
        } catch (BadRequestException $exception) {
            return new JsonResponse(['message' => $exception->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        } catch (\Exception $exception) {
            return new JsonResponse(['message' => 'Internal Server Error'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function getMessageStrategy(Message $message) {
        return new StrategyMessage($message);
    }
}