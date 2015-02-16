<?php
namespace GroovesharkAPI;

class Session
{
    private $clientKey = '';
    private $clientSecret = '';

    private $request = null;
    private $sessionId = null;

    /**
     * Constructor
     * Set up client credentials.
     *
     * @param string $clientKey The client Key.
     * @param string $clientSecret The client secret.
     * @param string $redirectUri The redirect URI.
     * @param Request $request Optional. The Request object to use.
     *
     * @return void
     */
    public function __construct($clientKey, $clientSecret, $request = null)
    {
        $this->setClientKey($clientKey);
        $this->setClientSecret($clientSecret);

        if (is_null($request)) {
            $request = new Request();
        }

        $this->request = $request;
    }

    /**
     * Get the access token.
     *
     * @return string
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * Get the client Key.
     *
     * @return string
     */
    public function getClientKey()
    {
        return $this->clientKey;
    }

    /**
     * Get the client secret.
     *
     * @return string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * Start a new session.
     *
     * @return void
     */
    public function startSession()
    {
        $response = $this->request->send('startSession', array(), $this);
        if (empty($response['result']['sessionID'])) {
            throw new Exception\GroovesharkAPIException('Ooops, bad response from Grooveshark.');
        } else {
            $this->setSessionId($response['result']['sessionID']);
        }
    }

    /**
     * Authenticate a user with his credentials.
     *
     * @param Session $request Required. The Session object to use.
     * @param Request $request Optional. The Request object to use.
     */
    public function authenticateCredentials($username, $password)
    {
        if (empty($username) || empty($password)) {
            throw new Exception\GroovesharkAPIException('You must provide a valid username and password');
        }

        $options = array(
            'login' => $username,
            'password' => $password,
        );

        $response = $this->request->send('authenticateEx', $options, $this);

        if (empty($response['result']['UserID'])) {
            throw new Exception\GroovesharkAPIException('Ooops, bad response from Grooveshark. Check the credentials.');
        }
        return $response['result'];
    }

    /**
     * Set the client Key.
     *
     * @param string $clientKey The client Key.
     *
     * @return void
     */
    public function setClientKey($clientKey)
    {
        $this->clientKey = $clientKey;
    }

    /**
     * Set the client secret.
     *
     * @param string $clientSecret The client secret.
     *
     * @return void
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
    }

    /**
     * Set the client's session ID.
     *
     * @param string $sessionId The session ID.
     *
     * @return void
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;
    }
}
