<?php

declare(strict_types=1);

use App\Resources\RequestConverter;
use App\Resources\MessageBuilder;
use App\Controller\MainController;
use App\Exception\BadRequestException;
use MessageBird\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class Kernel {

    const APP_ACCESS_KEY = '3B7j6ewEagFAWHwGNlxOAknSh';
    //const APP_ACCESS_KEY = 'MvUH703GwqedkFLZw4M0pzTR5';

    /** @var MainController */
    private $mainController;

    public function __construct() {
        $this->mainController = new MainController(
            new RequestConverter(),
            new Client(self::APP_ACCESS_KEY));
    }

    public function dispatch(Request $request): JsonResponse {
        try {
            return $this->mainController->send($request);
        } catch(BadRequestException $exception) {
            return new JsonResponse(['message' => $exception->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        } catch (\Exception $exception) {
            return new JsonResponse(['message' => 'Internal server error'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
