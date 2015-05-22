<?php

namespace JamesSheen\Pockii;

use Omnipay\Common\AbstractGateway;

/**
 * Pockii Class
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Pockii';
    }

    public function getDefaultParameters()
    {
        return array(
            'Username' => '',
            'Password' => '',
	    'Enc' => 'N', 
            'testMode' => true,
        );
    }

    public function getUsername()
    {
        return $this->getParameter('Username');
    }

    public function setUsername($value)
    {
        return $this->setParameter('Username', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('Password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('Password', $value);
    }

    public function getEnc()
    {
        return $this->getParameter('Enc'); 
    }

    public function setEnc($value)
    {
        return $this->setParameter('Enc', $value); 
    }

    public function token($parameters = [])
    {
        return $this->createRequest('\JamesSheen\Pockii\Message\TokenRequest', $parameters); 
    }

    public function purchase($parameters = [])
    {
        return $this->createRequest('\JamesSheen\Pockii\Message\PurchaseRequest', $parameters);
    }
}

