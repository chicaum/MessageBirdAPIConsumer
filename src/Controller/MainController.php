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

class MainController
{
    /** @var Client */
    private $client;

    /** @var RequestConverter */
    private $requestConverter;

    public function __construct(
        RequestConverter $requestConverter,
        Client $client
    ) {
        $this->requestConverter = $requestConverter;
        $this->client = $client;
    }

    public function send(Request $request): JsonResponse
    {
        $messageRequest  = $this->requestConverter->convert($request);
        $messageStrategy = $this->getMessageStrategy($messageRequest);
        $messagesList    = $messageStrategy->prepareMessage();

        try {
            foreach ($messagesList as $message) {
                $this->client->messages->create($message);
            }
            return new JsonResponse(['message' =>  count($messagesList) .  ' - Message sent'], JsonResponse::HTTP_OK);
        } catch (BadRequestException $exception) {
            return new JsonResponse(['message' => $exception->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        } catch (AuthenticateException $exception) {
            return new JsonResponse(['message' => 'Unknown access key'], JsonResponse::HTTP_UNAUTHORIZED);
        } catch (BalanceException $exception) {
            return new JsonResponse(['message' => 'No balance'], JsonResponse::HTTP_UNAUTHORIZED);
        } catch (ServerException $exception) {
            return new JsonResponse(['message' => $exception->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function getMessageStrategy(Message $message) {
        return new StrategyMessage($message);
    }
}