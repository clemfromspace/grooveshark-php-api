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
     * Get the session id.
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
     * Authenticate a user using his credentials.
     * http://developers.grooveshark.com/docs/public_api/v3/#authenticateEx
     *
     * @param string $username Required. Valid username or email.
     * @param string $password Required. Password.
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
     * Authenticate a user with an access token.
     * http://developers.grooveshark.com/docs/public_api/v3/#authenticateToken
     *
     * Requires a valid Session ID.
     *
     * @param string $token Required. The token.
     */
    public function authenticateToken($token)
    {
        if (empty($token)
            throw new Exception\GroovesharkAPIException('You must provide a valid token');
        }

        $options = array(
            'token' => $token,
        );

        $response = $this->request->send('authenticateToken', $options, $this);

        if (empty($response['result']['UserID'])) {
            throw new Exception\GroovesharkAPIException('Ooops, bad response from Grooveshark. Check the validity of the token.');
        }
        return $result;
    }

    /**
     * Log out any authenticated user from the current session.
     * http://developers.grooveshark.com/docs/public_api/v3/#logout
     *
     * Requires a valid Session ID.
     * 
     */
    public function logout()
    {
        if (!isset($this->sessionId)) {
            throw new Exception\GroovesharkAPIException('Trying to log out without a valid Session ID');
        }

        $response = $this->request->send('logout', array(), $this);

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
