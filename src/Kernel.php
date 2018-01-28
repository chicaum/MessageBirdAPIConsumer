<?php

declare(strict_types=1);

use App\Resources\RequestConverter;
use App\Controller\MainController;
use App\Exception\BadRequestException;
use MessageBird\Client;
use Predis\Client as RedisClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class Kernel
{
    const LIVE_ENVIRONMENT = 'LIVE';
    const TEST_ENVIRONMENT = 'TEST';

    /** @var MainController */
    private $mainController;

    private static $messageBirdAppKey;

    public function __construct()
    {
        static::$messageBirdAppKey = getenv('ENVIRONMENT') == static::LIVE_ENVIRONMENT
            ? getenv('MESSAGE_BIRD_APP_KEY_LIVE')
            : getenv('MESSAGE_BIRD_APP_KEY_TEST');

        $this->mainController = new MainController(
            new RequestConverter(),
            static::getMessageBirdClient(),
            static::getRedisClient()
        );
    }

    public function dispatch(Request $request): JsonResponse
    {
        try {
            return $this->mainController->send($request);
        } catch (BadRequestException $exception) {
            return new JsonResponse(['message' => $exception->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        } catch (\Exception $exception) {
            return new JsonResponse(['message' => 'Internal server error'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public static function getMessageBirdClient(){
       return new Client(static::$messageBirdAppKey);
    }

    public static function getRedisClient()
    {
        return new RedisClient([
            "scheme"   => getenv('REDIS_SCHEME'),
            "host"     => getenv('REDIS_HOST'),
            "port"     => getenv('REDIS_PORT'),
            "password" => getenv('REDIS_PASSWORD')]);
    }
}
