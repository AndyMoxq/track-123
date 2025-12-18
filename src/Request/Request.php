<?php
namespace ThankSong\Track123\Request;
use ThankSong\Track123\Response\Response;

class Request extends Client {
    public function validate(){

    }

    public function send(): Response {
        return Response::from($this->sendRequest());
    }
}