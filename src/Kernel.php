<?php

declare(strict_types=1);

use App\Component\RequestConverter;
use App\Controller\MessageController;
use App\Exception\BadRequestException;
use MessageBird\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class Kernel {

    const APP_ACCESS_KEY = '3B7j6ewEagFAWHwGNlxOAknSh';

    /** @var RequestConverter */
    private $requestConverter;

    /** @var MessageController */
    private $messageController;

    public function __construct() {
        $this->requestConverter  = new RequestConverter();
        $this->messageController = new MessageController(
            new Client(self::APP_ACCESS_KEY)
        );
    }

    public function dispatch(Request $request): JsonResponse {
        try {
            $messageRequest = $this->requestConverter->convert($request);

            return $this->messageController->sendMessage($messageRequest);
        } catch(BadRequestException $exception) {
            return new JsonResponse(['message' => $exception->getMessage()], 400);
        } catch (\Exception $exception) {
            return new JsonResponse(['message' => '500 - Internal server error'], 500);
        }
    }
}
