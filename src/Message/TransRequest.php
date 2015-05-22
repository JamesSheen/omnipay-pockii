<?php

namespace JamesSheen\Pockii\Message;

/**
 * Pockii Trans Request
 */
class TransRequest extends AbstractRequest
{
    public function getData()
    {
    }

    public function sendData($data)
    {
        parent::setType('trans');
	$data['apiUserId'] = $this->getUsername(); 
	$data['apiUserPassword'] = $this->getPassword(); 
        return parent::sendData($data);
    }
}

