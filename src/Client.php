<?php

namespace Cronitor;

class Client
{

    protected $monitorId;
    protected $authKey;
    protected $baseURI = 'https://cronitor.link';

    public function __construct($monitorId, $authKey = '')
    {
        $this->monitorId = $monitorId;
        $this->authKey = $authKey;
    }

    /**
    * @codeCoverageIgnore
    */
    public function setMonitorId($monitorId)
    {
        $this->monitorId = $monitorId;
    }

    /**
    * @codeCoverageIgnore
    */
    public function setAuthKey($authKey)
    {
        $this->authKey = $authKey;
    }

    /**
    * @codeCoverageIgnore
    */
    public function setBaseURI($baseURI)
    {
        $this->baseURI = $baseURI;
    }

    public function getcUrl()
    {
        return new \anlutro\cURL\cURL;
    }

    public function run($msg = null)
    {
        return $this->request('run', ['msg' => $msg]);
    }

    public function fail($msg = null)
    {
        return $this->request('fail', ['msg' => $msg]);
    }

    public function pause($duration)
    {
        return $this->request('pause/' . (int) $duration);
    }

    public function complete($msg = null)
    {
        return $this->request('complete', ['msg' => $msg]);
    }

    public function request($endpoint, $parameters = [])
    {
        $curl = $this->getcUrl();

        $request = $curl->newRequest('get', $this->buildUrl($endpoint, $parameters))
            ->setOption(CURLOPT_TIMEOUT, 10)->setOption(CURLOPT_SSL_VERIFYHOST, 0)->setOption(CURLOPT_SSL_VERIFYPEER, 0);

        $response = $request->send();

        return $response;
    }

    public function buildUrl($endpoint, $parameters = [])
    {
        $url = sprintf(
            '%s/%s/%s',
            $this->baseURI,
            $this->monitorId,
            $endpoint
        );

        if ($this->authKey) {
            $parameters['auth_key'] = $this->authKey;
        }

        $queryString = http_build_query($parameters);
        $url .= (empty($queryString)) ? '' : '?' . $queryString;
        return $url;
    }
}
