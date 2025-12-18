<?php

/**
 * Created by VsCode.
 * User: andy
 * Date: 2025-12-18
 */
namespace ThankSong\Track123\Response;

class InstantTrackResponse extends Response {
    /**
     * 获取快递信息
     * @return array
     */
    public function getData(): array {
        return $this->body['data']['accepted'] ?? [];
    }
}