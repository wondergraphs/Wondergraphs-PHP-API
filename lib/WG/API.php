<?php
namespace WG;

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
    }

    public function getUsers() {
        var_dump($this->buildUrl('users'));
        var_dump($this);
    }

    public function setUrlRoot($root) {
        $this->urlRoot = $root;
    }

    private function doGet($method) {
        $url = $this->buildUrl($method);

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
}
