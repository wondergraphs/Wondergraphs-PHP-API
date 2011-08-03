<?php
namespace WG;

class UnauthorizedException extends \Exception {
}

class RequestException extends \Exception {
    public function __construct($msg) {
        parent::__construct($msg);
    }
}
