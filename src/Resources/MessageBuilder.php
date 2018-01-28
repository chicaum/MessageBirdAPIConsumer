<?php

declare(strict_types=1);

namespace App\Resources;

use MessageBird\Objects\Message;

class MessageBuilder
{
    const PLAIN_SMS_MAX_LENGTH = 1377; //TODO Replace with MAX_GSM_CHARACTERS
    const PLAIN_SMS_SINGLE_MESSAGE_MAX_LENGTH = 160; //TODO Replace with MAX_GSM_SINGLE_MESSAGE_LENGTH
    const PLAIN_SMS_CHUNKED_MESSAGE_MAX_LENGTH = 153; //TODO Replace with MAX_GSM_CONCATENATED_MESSAGE_LENGTH
    const UNICODE_SMS_MAX_LENGTH = 603; //TODO Replace with MAX_UNICODE_CHARACTERS
    const UNICODE_SMS_SINGLE_MESSAGE_MAX_LENGTH = 70; //TODO Replace with MAX_UNICODE_SINGLE_MESSAGE_LENGTH
    const UNICODE_SMS_CHUNKED_MESSAGE_MAX_LENGTH = 67; //TODO Replace with MAX_UNICODE_CONCATENATED_MESSAGE_LENGTH
    
    const MAX_GSM_CHARACTERS                       = 1377;
    const MAX_GSM_SINGLE_MESSAGE_LENGTH            = 160;
    const MAX_GSM_CONCATENATED_MESSAGE_LENGTH      = 153;
    const MAX_UNICODE_CHARACTERS                   = 603;
    const MAX_UNICODE_SINGLE_MESSAGE_LENGTH        = 70;
    const MAX_UNICODE_CONCATENATED_MESSAGE_LENGTH  = 67;

    public function prepareMessage(Message $message){
        return $message;
    }
}
