<?php
namespace ThankSong\Track123\Response;

class RegisterTrackingResponse extends Response {
    public function getAccepted(): array{
        return $this->getData()['accepted'] ?? [];
    }

    public function getRejected(): array{
        return $this->getData()['rejected'] ?? [];
    }

    public function getRejectedMessages(): array {
        $messages = [];
        foreach ($this->getRejected() as $rejected){
            $messages[] = "Index:" . $rejected['index'] . " trackNo:". $rejected['trackNo'] . " message:". $rejected['error']['msg'] ?? 'Unknonw error';
        }
        return $messages;
    }
}