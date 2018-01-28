<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Controller\MainController;
use MessageBird\Objects\Message;
use MessageBird\Exceptions\AuthenticateException;
use MessageBird\Exceptions\BalanceException;
use MessageBird\Exceptions\ServerException;
use Symfony\Component\Dotenv\Dotenv;

echo 'Starting simple redis consumer' . PHP_EOL;

(new Dotenv())->load(__DIR__.'/../.env');
$redisClient       = Kernel::getRedisClient();
$messageBirdClient = Kernel::getMessageBirdClient();

$messageThroughputInSeconds = 1;

while (true) {
    $queuedMessageEntry = $redisClient->blpop([MainController::REDIS_QUEUE], 3);

    if (!is_null($queuedMessageEntry)) {
        $queuedMessage = $queuedMessageEntry[1];
        $messageDecoded = json_decode($queuedMessage);

        echo 'Sending message: ' . $queuedMessage . PHP_EOL;

        $message = new Message();
        $message->loadFromArray($messageDecoded);

        try {
            $messageBirdClient->messages->create($message);
            echo 'message sent ' . PHP_EOL;
        } catch (AuthenticateException $exception) {
            echo 'Unknown access key' . PHP_EOL;
        } catch (BalanceException $exception) {
            echo 'No balance' . PHP_EOL;
        } catch (ServerException $exception) {
            echo 'Internal server error' . PHP_EOL;
        }
    }

    sleep($messageThroughputInSeconds);
    echo 'Waiting for a message' . PHP_EOL;
}
