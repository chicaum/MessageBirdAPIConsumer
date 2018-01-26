<?php

declare(strict_types=1);

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Controller\MainController;

class Kernel {

    private $environment;

    public function __construct(string $environment) {
        $this->environment = $environment;
    }

    public function dispatch(Request $request): JsonResponse {

        $mainController = new MainController();
        $response = $mainController->sendMessage($request->getContent());

        return new JsonResponse([
            'response' => $response
        ]);
//        $content = $request->getContent();
//        $apiKeyLive = getenv('SMS_API_KEY_LIVE');
//        $apiKeyTest = getenv('SMS_API_KEY_TEST');
//        $apiEnv     = getenv('APP_ENV');
//
//        return new JsonResponse([
//            'api_key_live' => $apiKeyLive,
//            'api_key_test' => $apiKeyTest,
//            'api_env'      => $apiEnv,
//            'file'         => $mainController->getFile(),
//            'content'      => $content,
//            'time'         => \date('l jS \of F Y h:i:s A')
//        ]);
    }
}
