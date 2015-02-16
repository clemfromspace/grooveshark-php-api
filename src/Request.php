<?php
namespace GroovesharkAPI;

class Request
{   
    const API_URL = 'https://api.grooveshark.com/ws3.php';

    /**
     * Make a request to Grooveshark.
     *
     * @param string $method The API method to use.
     * @param array $parameters Optional. Query parameters.
     * @param Session $session Optional. HTTP headers.
     *
     * @return array
     */
    public function send($method, $parameters = array(), $session)
    {
        $payload = array(
            'method' => $method,
            'parameters' => $parameters,
            'header' => array(
                'wsKey' => $session->getClientKey(),
            ),
        );

        $sessionId = $session->getSessionId();
        if (isset($sessionId)) {
            $payload['header']['sessionID'] = $session->getSessionId();
        }

        $c = curl_init();
        $postData = json_encode($payload);
        curl_setopt($c, CURLOPT_POST, 1);
        curl_setopt($c, CURLOPT_POSTFIELDS, $postData);

        $headers = array(
            'Expect:',
            'Content-Type: application/json'
        );
        curl_setopt($c, CURLOPT_HTTPHEADER, $headers);
        

        $signature = $this->createMessageSignature($postData, $session->getClientSecret());
        $url = self::API_URL . "?sig=" . $signature;

        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_TIMEOUT, 6);
        curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($c, CURLOPT_USERAGENT, 'fastest963-GroovesharkAPI-PHP-' . $session->getClientKey());

        $response = curl_exec($c);
        $status = (int) curl_getinfo($c, CURLINFO_HTTP_CODE);
        curl_close($c);

        if ($status != 200) {
            throw new Exception\GroovesharkAPIException('Unexpected return code from Grooveshark API.', $httpCode);
        }

        $body = json_decode($response, true);
        if (isset($body['errors'])) {
            $error = $body['errors'][0];
            if (isset($error['message']) && isset($error['code'])) {
                throw new Exception\GroovesharkAPIException($error['message'], $error['code']);
            } else {
                throw new Exception\GroovesharkAPIException('Unknow Exception');
            }
        }

        return $body;
    }

    /**
     * Creates the message signature before sending to Grooveshark
     *
     * @param array $parameters An array of parameters.
     * @param string $secret The application secret key.
     *
     * @return string
     */
    private function createMessageSignature($parameters, $secret)
    {
        return hash_hmac('md5', $parameters, $secret);
    }
}
