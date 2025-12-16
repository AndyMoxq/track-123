<?php
namespace ThankSong\Track123\Response;

use ThankSong\Track123\Exceptions\InvalidResponseException;
class Response {
    protected $body = [
        'code' => null,
        'message' => null,
        'data' => []
    ];
    protected $code = 0;
    protected $message = '';

    public function __construct(array $body = []){
        $this->setBody($body);
        $this->validate();
        $this->setCode($this->body['code'] ?? 0);
        $this->setMessage($this->body['message'] ?? '');
    }

    public function setBody(array $body){
        $this->body = $body;
        return $this;
    }

    public function getBody(): array {
        return $this->body;
    }

    public function getMessage(): string {
        return $this->message;
    }

    public function getCode(): int {
        return $this->code;
    }

    public function setCode(int $code): static {
        $this->code = $code;
        return $this;
    }

    public function setMessage(string $message): static {
        $this->message = $message;
        return $this;
    }

    public function getData(): array {
        return $this->body['data'] ?? [];
    }

    public function validate() {
        if(($this->body['code'] ?? null) !== '00000'){
            $code = $this->body['code'] ?? 0;
            $message = $this->body['msg'] ?? '';
            throw new InvalidResponseException("错误响应：[{$code}]，错误信息：{$message}");
        }
    }

    public static function from(array $body = []){
        return new static($body);
    }
}