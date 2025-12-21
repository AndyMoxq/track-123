<?php

/**
 * Created by VsCode.
 * User: Andy
 * Date: 2025-12-18
 */
namespace ThankSong\Track123\Response;

class GetTrackingsResponse extends Response {
    /**
     * 获取成功列表
     * @return array
     */
    public function getData(): array {
        return $this->body['data']['accepted']['content'] ?? [];
    }

    /**
     * 获取成功列表
     * @return array
     */
    public function getAccepted(): array {
        return $this->body['data']['accepted']['content'] ?? [];
    }

    /**
     * 获取成功列表总数
     * @return int
     */
    public function getTotalElements(): int {
        return $this->body['data']['accepted']['totalElements'] ?? 0;
    }

    /**
     * 获取成功列表总页数
     * @return int
     */
    public function getTotalPages(): int {
        return $this->body['data']['accepted']['totalPages'] ?? 0;
    }

    /**
     * 获取当前页码
     * @return int
     */
    public function getCurrentPage(): int {
        return $this->body['data']['accepted']['currentPage'] ?? 1;
    }

    /**
     * 获取下一页的游标
     * @return string
     */
    public function getCursor(): string {
        return $this->body['data']['accepted']['cursor'] ?? '';
    }

    /**
     * 获取失败列表
     * @return array
     */
    public function getRejected(): array {
        return $this->body['data']['rejected'] ?? [];
    }

    /**
     * 获取失败列表的错误信息
     * @return array
     */
    public function getRejectedMessages(): array {
        $messages = [];
        foreach ($this->getRejected() as $rejected) {
            $messages[] = "跟踪号:" . $rejected['trackNo'] . ' 错误信息:' . $rejected['error']['msg'] ?? '未知错误';
        }
        return $messages;
    }


}