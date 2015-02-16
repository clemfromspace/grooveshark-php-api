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
     * Requires a valid Session ID and authenticated user.
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
     * Requires a valid Session ID and authenticated user.
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
     * Get the logged-in user's playlists
     * Requires a valid Session ID and authenticated user
     * http://developers.grooveshark.com/docs/public_api/v3/#getUserPlaylists
     *
     * - int limit Optional.
     *
     * @return array
     */
    public function getUserPlaylists($limit = null)
    {
        $options = array();

        if (!empty($limit)) {
            $options['limit'] = (int)$limit;
        }

        $response = $this->request->send('getUserPlaylists', $options, $this->session);

        return $response['result'];
    }

    /**
     * Get the playlists owned by the given userID
     * http://developers.grooveshark.com/docs/public_api/v3/#getUserPlaylistsByUserID
     *
     * @param integer $userId a valid user ID.
     * - int limit Optional.
     *
     * @return array
     */
    public function getUserPlaylistsByUserID(int $userId, $limit = null)
    {
        $options = array(
            'userID' => $userId
        );

        if (!empty($limit)) {
            $options['limit'] = (int)$limit;
        }

        $response = $this->request->send('getUserPlaylistsByUserID', $options, $this->session);

        return $response['result'];
    }

    /**
     * Get the logged-in user's library
     * Requires a valid Session ID and authenticated user
     * http://developers.grooveshark.com/docs/public_api/v3/#getUserLibrarySongs
     *
     * - int limit Optional.
     *
     * @return array
     */
    public function getUserLibrary($limit = null)
    {
        $options = array();

        if (!empty($limit)) {
            $options['limit'] = (int)$limit;
        }

        $response = $this->request->send('getUserLibrarySongs', $options, $this->session);

        return $response['result'];
    }

    /**
     * Get the logged-in user's favorites
     * Requires a valid Session ID and authenticated user
     * http://developers.grooveshark.com/docs/public_api/v3/#getUserFavoriteSongs
     *
     * - int limit Optional.
     *
     * @return array
     */
    public function getUserFavorites($limit = null)
    {
        $options = array();

        if (!empty($limit)) {
            $options['limit'] = (int)$limit;
        }

        $response = $this->request->send('getUserFavoriteSongs', $options, $this->session);

        return $response['result'];
    }

    /**
     * Adds one song to the logged-in user's favorites
     * Requires a valid Session ID and authenticated user
     * http://developers.grooveshark.com/docs/public_api/v3/#addUserFavoriteSong
     *
     * @param integer $songId a valid song ID.
     *
     * @return array
     */
    public function addUserFavoriteSong(int $songId)
    {
        $options = array(
            'songID' => $songId
        );

        $response = $this->request->send('addUserFavoriteSong', $options, $this->session);

        return $response['result'];
    }

    /**
     * Adds multiple songs to the logged-in user's library
     * Requires a valid Session ID and authenticated user
     * http://developers.grooveshark.com/docs/public_api/v3/#addUserLibrarySongs
     *
     * @param array $songs An array of objects each like (songID => 2341, artistID => 124445, albumID => 993284)
     *
     * @return array
     */
    public function addUserLibrarySongs(array $songs)
    {
        $options = array(
            'songs' => $songs
        );

        $response = $this->request->send('addUserFavoriteSong', $options, $this->session);

        return $response['result'];
    }

    /**
     * Creates a playlist for the logged-in user
     * Requires a valid Session ID and authenticated user
     * http://developers.grooveshark.com/docs/public_api/v3/#createPlaylist
     *
     * @param string $name The name of the playlist
     * @param array $songIds An array of valid song Ids
     *
     * @return array
     */
    public function createPlaylist($name, array $songIds)
    {
        $options = array(
            'name' => $name,
            'songIds' => $songIds
        );

        $response = $this->request->send('createPlaylist', $options, $this->session);

        return $response['result'];
    }

    /**
     * Adds a song to the end of a playlist
     * Requires a valid Session ID and authenticated user
     *
     * @param integer $playlistId A valid playlist ID
     * @param integer $songId A valid song ID
     *
     * @return array
     */
    public function addSongToPlaylist(int $playlistId, int $songId) 
    {
        $songs = $this->getPlaylistSongs($playlistID);
        $songIDs = array();

        foreach ($songs as $song) {
            $songIDs[] = (int)$song['SongID'];
        }
        $songIDs[] = $songID;

        return $this->setPlaylistSongs($playlistId, $songIDs);
    }

    /**
     * Changes a playlist's songs
     * Requires a valid Session ID and authenticated user
     * http://developers.grooveshark.com/docs/public_api/v3/#setPlaylistSongs
     *
     * @param integer $playlistId A valid playlist ID
     * @param array $songIds An array of valid song ID
     *
     * @return array
     */
    public function setPlaylistSongs(int $playlistId, array $songIds)
    {
        $options = array(
            'playlistID' => $playlistId,
            'songIDs' => $songIds,
        );

        $response = $this->request('setPlaylistSongs', $options, $this->session);

        return $response['result'];
    }

    /**
     * Get songs on a given playlistID
     * Requires a valid Session ID and authenticated user
     * http://developers.grooveshark.com/docs/public_api/v3/#getPlaylistSongs
     *
     * @param integer $playlistId A valid playlist ID
     *
     * @return array
     */
    public function getPlaylistSongs(int $playlistId)
    {
        $options = array(
            'playlistID' => $playlistID
        );

        $response = $this->request('getPlaylistSongs', $options, $this->session);

        return $response['result']['songs'];
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