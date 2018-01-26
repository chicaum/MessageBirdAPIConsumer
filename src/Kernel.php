<?php

declare(strict_types=1);

use App\Component\RequestConverter;
use App\Controller\MessageController;
use MessageBird\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class Kernel {

    const APP_ACCESS_KEY = 'TB07OC8eC6eMVSL6q300eAaiB';

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
        }
        catch (\Exception $exception) {
            return new JsonResponse(['message' => 'Internal server error'], 500);
        }
    }
}
