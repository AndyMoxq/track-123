<?php
namespace ThankSong\Track123\Response;

class GetTrackingsResponse extends Response {
    public function getData(): array {
        return $this->body['data']['accepted']['content'] ?? [];
    }

    public function getAccepted(): array {
        return $this->body['data']['accepted']['content'] ?? [];
    }

    public function getTotalElements(): int {
        return $this->body['data']['accepted']['totalElements'] ?? 0;
    }

    public function getTotalPages(): int {
        return $this->body['data']['accepted']['totalPages'] ?? 0;
    }

    public function getCurrentPage(): int {
        return $this->body['data']['accepted']['currentPage'] ?? 1;
    }

    public function getCursor(): string {
        return $this->body['data']['accepted']['cursor'] ?? '';
    }

    public function getRejected(): array {
        return $this->body['data']['rejected'] ?? [];
    }
    
    public function getRejectedMessages(): array {
        $messages = [];
        foreach ($this->getRejected() as $rejected) {
            $messages[] = "跟踪号:" . $rejected['trackNo'] . ' 错误信息:' . $rejected['error']['msg'] ?? '未知错误';
        }
        return $messages;
    }


}