<?php
namespace ThankSong\Track123\Response;

use ThankSong\Track123\Exceptions\InvalidResponseException;
class Response {
    /**
     * @var array $body 响应体
     */
    protected array $body = [
        'code' => null,
        'message' => null,
        'data' => []
    ];

    /**
     * @var int $code 响应码
     */
    protected int $code = 0;

    /**
     * @var string $message 响应消息
     */
    protected string $message = '';

    /**
     * Response constructor.
     * @param array $body
     */
    public function __construct(array $body = []){
        $this->setBody($body);
        $this->validate();
        $this->setCode($this->body['code'] ?? 0);
        $this->setMessage($this->body['message'] ?? '');
    }

    /**
     * 设置响应体
     * @param array $body
     * @return $this
     */
    public function setBody(array $body){
        $this->body = $body;
        return $this;
    }

    /**
     * 获取响应体
     * @return array
     */
    public function getBody(): array {
        return $this->body;
    }

    /**
     * 设置响应消息
     * @return string
     */
    public function getMessage(): string {
        return $this->message ?? '';
    }

    /**
     * 获取响应码
     * @return int
     */
    public function getCode(): int {
        return $this->code ?? 0;
    }

    /**
     * 设置响应码
     * @param int $code
     * @return static
     */
    public function setCode(int $code): static {
        $this->code = $code;
        return $this;
    }

    /**
     * 设置响应消息
     * @param string $message
     * @return static
     */
    public function setMessage(string $message): static {
        $this->message = $message;
        return $this;
    }

    /**
     * 获取响应数据
     * @return array
     */
    public function getData(): array {
        return $this->body['data'] ?? [];
    }

    /**
     * 从响应体创建响应对象
     * @param array $body
     * @return static
     */
    public static function from(array $body = []): static {
        return new static($body);
    }

    /**
     * 验证响应体
     * @throws InvalidResponseException
     */
    public function validate() {
        if(($this->body['code'] ?? null) !== '00000'){
            $this->setCode($this->body['code'] ?? 0);
            $this->setMessage($this->body['msg'] ?? '');
            throw new InvalidResponseException("错误响应：[{$this->code}]，错误信息：{$this->message}");
        }
    }
}