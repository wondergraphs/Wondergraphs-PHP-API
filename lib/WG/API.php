<?php
namespace WG;

class API {
    private $organization;
    private $apiKey;
    private $urlRoot = 'https://api.wondergraphs.com/api';

    public function __construct($organization, $apiKey) {
        $this->organization = $organization;
        $this->apiKey = $apiKey;
    }

    public function getUsers() {
        var_dump($this);
    }

    public function setUrlRoot($root) {
        $this->urlRoot = $root;
    }
}
