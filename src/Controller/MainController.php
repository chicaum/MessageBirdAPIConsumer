<?php

namespace App\Controller;

use App\Resources\RequestConverter;
use App\Resources\MessageBuilder;
use App\Exception\BadRequestException;
use MessageBird\Client;
use MessageBird\Exceptions\AuthenticateException;
use MessageBird\Exceptions\BalanceException;
use MessageBird\Exceptions\ServerException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class MainController
{
    /** @var Client */
    private $client;

    /** @var RequestConverter */
    private $requestConverter;

    /** @var MessageBuilder */
    private $messageBird;

    public function __construct(
        RequestConverter $requestConverter,
        MessageBuilder $messageBird,
        Client $client
    ) {
        $this->requestConverter = $requestConverter;
        $this->messageBird      = $messageBird;
        $this->client           = $client;
    }

    public function send(Request $request): JsonResponse
    {
        $messageRequest = $this->requestConverter->convert($request);
        $preparedMessage = $this->messageBird->prepareMessage($messageRequest);

        try {
            $result = $this->client->messages->create($preparedMessage);
            return new JsonResponse(['message' => $result], JsonResponse::HTTP_OK);
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
}