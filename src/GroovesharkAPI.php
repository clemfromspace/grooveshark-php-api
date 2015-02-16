<?php
namespace GroovesharkAPI;

class GroovesharkAPI
{
	private $session = null;
    private $request = null;

    /**
     * Constructor
     * Set up Session (Grooveshark API credentials) and Request object.
     *
     * @param Session $request Required. The Session object to use.
     * @param Request $request Optional. The Request object to use.
     *
     * @return void
     */
    public function __construct($session, $request = null)
    {
        $this->session = $session;

        if (is_null($request)) {
            $request = new Request();
        }

        $this->request = $request;
    }

    /**
     * Get the country from the ip address.
     * If the ip is omitted, grooveshark will use the request ip address.
     * http://developers.grooveshark.com/docs/public_api/v3/#getCountry
     *
     * @param string $ipAddress The ip address to get the country from.
     *
     * @return array
     */
    public function getCountry($ipAddress = null)
    {
        $options = array();

        if (isset($ipAddress)) {
            $options['ip'] = $ipAddress;
        }

        $response = $this->request->send('getCountry', $options, $this->session);

        return $response['result'];
    }

    /**
     * Get logged-in user info from sessionID
     * Requires a valid Session ID.
     * http://developers.grooveshark.com/docs/public_api/v3/#getUserInfo
     *
     * This method does not take any parameters.
     *
     * @return array
     */
    public function getUserInfo()
    {
        $response = $this->request->send('getUserInfo', array(), $this->session);

        return $response['result'];
    }

    /**
     * Get logged-in user subscription info. Returns type of subscription and either dateEnd or recurring.
     * Requires a valid Session ID.
     * http://developers.grooveshark.com/docs/public_api/v3/#getUserSubscriptionDetails
     *
     * This method does not take any parameters.
     *
     * @return array
     */
    public function getUserSubscriptionDetails()
    {
        $response = $this->request->send('getUserInfo', array(), $this->session);

        return $response['result'];
    }

    /**
     * Get playlist info and songs.
     * http://developers.grooveshark.com/docs/public_api/v3/#getPlaylist
     *
     * @param string $playlistId The playlist Identifier.
     * - int limit Optional.
     *
     * @return array
     */
    public function getPlaylist($playlistId, $limit = null)
    {
        $options = array(
            'playlistID' => $playlistId
        );

        if (isset($limit)) {
            $options['limit'] = $limit;
        }

        $response = $this->request->send('getPlaylist', $options, $this->session);

        return $response['result'];
    }

    /**
     * Perform a song search.
     * http://developers.grooveshark.com/docs/public_api/v3/#getSongSearchResults
     *
     * @param string $query The query string.
     * - int limit Optional.
     * - int offset Optional.
     *
     * @return array
     */
    public function getSongSearchResults($query, $limit = null, $offset = null)
    {
        $options = array(
            'query' => $query,
            'country' => $this->getCountry(),
        );

        $response = $this->request->send('getSongSearchResults', $options, $this->session);

        return $response['result'];
    }

    /**
     * Set the Session object to use.
     *
     * @param Session $session The session object.
     *
     * @return void
     */
    public function setSession(Session $session)
    {
        $this->session = $session;
    }
}