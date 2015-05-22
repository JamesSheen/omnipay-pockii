<?php

namespace JamesSheen\Pockii\Message;

/**
 * Pockii Token Request
 */
class TokenRequest extends TransRequest
{
    public function getData()
    {
    }

    public function sendData($data = [])
    {
        return parent::sendData($data);
    }
}

