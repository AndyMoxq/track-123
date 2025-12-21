<?php

/**
 * Created by VsCode.
 * User: Andy
 * Date: 2025-12-18
 */
namespace ThankSong\Track123\Response;

class RegisterTrackingResponse extends Response {
    /**
     * 获取成功列表
     * @return array
     */
    public function getAccepted(): array {
        return $this->getData()['accepted'] ?? [];
    }

    /**
     * 获取失败列表
     * @return array
     */
    public function getRejected(): array {
        return $this->getData()['rejected'] ?? [];
    }

    /**
     * 获取失败信息列表
     * @return array
     */
    public function getRejectedMessages(): array {
        $messages = [];
        foreach ($this->getRejected() as $rejected){
            $messages[] = "Index:" . $rejected['index'] . " trackNo:". $rejected['trackNo'] . " message:". $rejected['error']['msg'] ?? 'Unknonw error';
        }
        return $messages;
    }
}