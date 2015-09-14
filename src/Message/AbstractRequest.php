<?php

namespace JamesSheen\Pockii\Message;

use SoapClient;
use SoapFault;
use SoapHeader; 
use DateTime; 
use DateTimeZone; 

/**
 * Pockii Abstract Request
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    const API_VERSION = 'v1.3';

    protected $liveEndpoint = 'https://www.pockii.com:9443/ctcb-olp-api-webservice/ws';
    protected $testEndpoint = 'https://www.pockii.com:9443/ctcb-olp-api-webservice/ws/sandbox';
    protected $ns = 'http://www.pockii.chinatrust.com.tw/api'; 

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

    protected function setType($type)
    {
        $this->liveEndpoint .= '/'.$type.'.wsdl'; 
        $this->testEndpoint .= '/'.$type.'.wsdl'; 
	$this->ns .= '/'.$type; 
    }

    private function getTime()
    {
        $d = new DateTime();
	$t = microtime(true);
	$t = sprintf("%03d", ($t - floor($t)) * 1000);
	$d->setTimezone(new DateTimeZone('Asia/Taipei')); 
	$d = explode('+', $d->format(DateTime::ATOM)); 
	return $d[0].'.'.$t.'+'.$d[1]; 
    }

    private function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint; 
    }

    public function sendData($data)
    {
        $h = []; 
	$h['apiUserId'] = $this->getUsername(); 
	$h['apiUserPwd'] = $this->getPassword();
	$h['encryption'] = $this->getEnc(); 
	$h['requestTime'] = $this->getTime();
	$h['version'] = static::API_VERSION; 
        $context_opts = [
	    'http' => [
	    ], 
	    'ssl' => [
	    ], 
	//    'socket' => [
	//    	'bindto' => null,
	//    ], 
	];
	$context = stream_context_create($context_opts);
	$soap_opts = [
	    'encoding' => 'utf-8', 
	    'exceptions' => true,
	    'trace' => true, 
	    'cache_wsdl' => WSDL_CACHE_NONE, 
	    'stream_context' => $context,
	    //'proxy_host' => 'localhost', 
	    //'proxy_port' => 80, 
	];
	try{
	    $client = new SoapClient($this->getEndpoint(), $soap_opts);
	} catch (SoapFault $e) {
	    throw new \Exception($e->getMessage(), $e->getCode());
	}
	$data = [
	    'apiRqHeader' => $h, 
	    'apiRqBody' => $data
	];
	$data = [
	    'getTokenIdRequest' => $data
	]; 
	return $client->__soapCall('getTokenId', $data);
    }
}

