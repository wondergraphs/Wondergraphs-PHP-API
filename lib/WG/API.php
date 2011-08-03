<?php
namespace WG;

require_once dirname(__FILE__) . '/Exceptions.php';
require_once dirname(__FILE__) . '/Model.php';

class API {
    private $version = '$Id$';

    private $organization;
    private $apiKey;
    private $urlRoot = 'https://api.wondergraphs.com/api';
    private $apiVersion = 'v1';

    private $client;

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

    function __destruct() {
        curl_close($this->client);
    }

    public function getUsers() {
        $users = $this->doGet('users');
        $users = $this->boxList($users, 'WG\User');
        return $users;
    }

    public function setUrlRoot($root) {
        $this->urlRoot = $root;
    }

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
