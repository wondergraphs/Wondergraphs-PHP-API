<?php
/*

Copyright (C) 2011 by Wondergraphs LLC

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

*/

namespace WG;

require_once dirname(__FILE__) . '/Exceptions.php';
require_once dirname(__FILE__) . '/Model.php';

/**
 * This class wraps all API operations for the Wondergraphs REST API.
 */
class API {
    private $version = '$Id$';

    private $organization;
    private $apiKey;
    private $urlRoot = 'https://api.wondergraphs.com/api';
    private $apiVersion = 'v1';

    private $client;

    /**
     * Create a new API instance.
     *
     * @param string $organization The name of the organization
     * @param string $apiKey The API key to use.
     */
    public function __construct($organization, $apiKey) {
        $this->organization = $organization;
        $this->apiKey = $apiKey;

        $this->client = curl_init();
        curl_setopt_array($this->client, array(
            CURLOPT_FOLLOWLOCATION => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_USERAGENT      => 'Wondergraphs-PHP-API (' . $this->version . ')',
            CURLOPT_HTTPAUTH       => CURLAUTH_BASIC,
            CURLOPT_USERPWD        => $this->organization . ':' . $this->apiKey
        ));
    }

    /**
     * Free curl after use.
     */
    function __destruct() {
        curl_close($this->client);
    }

    /**
     * Set the URL of the API server to use.
     *
     * This is something you will probably never need to do.
     */
    public function setUrlRoot($root) {
        $this->urlRoot = $root;
    }

    /* -----------------------------------------------------------
                          API methods below 
       ----------------------------------------------------------- */

    /**
     * Get a list of all users in the current organization.
     *
     * @return An array of WG\User objects.
     */
    public function getUsers() {
        $users = $this->doGet('users');
        $users = $this->boxList($users, 'WG\User');
        return $users;
    }

    /* -----------------------------------------------------------
                        Utility methods below 
       ----------------------------------------------------------- */

    /**
     * Execute a GET request
     */
    private function doGet($method) {
        $url = $this->buildUrl($method);

        curl_setopt($this->client, CURLOPT_HTTPGET, TRUE);
        curl_setopt($this->client, CURLOPT_URL, $url);

        $result = curl_exec($this->client);
        if ($result === false) {
            throw new RequestException('Unexpected request error: ' . curl_error($this->client));
        }

        $info = curl_getinfo($this->client);
        if ($info['http_code'] != 200) {
            switch ($info['http_code']) {
            case 401:
                throw new UnauthorizedException();
            default:
                throw new RequestException('Unexpected response code: ' . $info['http_code']);
            }
        }

        $response = json_decode($result);
        return $response;
    }

    /**
     * Builds a request URL
     */
    private function buildUrl($method) {
        $url = $this->urlRoot;
        if ($url[strlen($url)-1] != '/') {
            $url .= '/';
        }
        $url .= $this->apiVersion;
        if ($method[0] != '/') {
            $url .= '/';
        }
        return $url . $method;
    }

    /**
     * Convert a list of stdClass objects into typed objects.
     *
     * @param array $list A list of stdClass objects.
     * @param string $type The full type name of the desired type.
     * @return An array of objects from the supplied type.
     */
    private function boxList(array $list, $type) {
        $result = array();
        foreach ($list as $key => $val) {
            $result[$key] = $this->box($val, $type);
        }
        return $result;
    }

    /**
     * Convert a stdClass object into a typed object.
     *
     * @param object $orig A stdClass object.
     * @param string $type The full type name of the desired type.
     * @return An object from the supplied type.
     */
    private function box(\stdClass $orig, $type) {
        $obj = new $type();
        foreach ($orig as $key => $val) {
            $obj->$key = $val;
        }
        return $obj;
    }
}
