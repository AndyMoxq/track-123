<?php
/**
 * Created by VsCode
 * User: Andy
 * Date: 2025-12-17
 * 通用请求类。
 */
namespace ThankSong\Track123\Request;
use InvalidArgumentException;
use ThankSong\Track123\Response\Response;

class Request extends Client {
    /**
     * 验证参数
     * @return void
     */
    public function validate(){
        if(empty($this->endpoint)){
            throw new InvalidArgumentException("end point was not set", 1);
        }
    }

    /**
     * 发送请求
     * @return Response
     */
    public function send(): Response {
        $this->validate();
        return Response::from($this->sendRequest());
    }
}