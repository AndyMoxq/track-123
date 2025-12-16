<?php
namespace ThankSong\Track123\Response;

abstract class AbstractResponse{
    protected $body = [
        'code' => null,
        'message' => null,
        'data' => []
    ];
    protected $code = 0;
    protected $message = '';

    public function __construct(array $body = []){
        $this->setBody($body);
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
    abstract public function validate();
}