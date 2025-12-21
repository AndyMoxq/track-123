<?php
/**
 * created by VsCode
 * User: Andy
 * DateTime 2025-12-17 14:26:00
 * Client of Track123 API
 */
namespace ThankSong\Track123\Request;

use Illuminate\Support\Facades\Http;
use ThankSong\Track123\Exceptions\InvalidResponseException;
use InvalidArgumentException;

abstract class Client {
    /**
     * 请求参数
     * @var array
     */
    protected $params = [];

    /**
     * API URL
     * @var string
     */
    protected $api_url = 'https://api.track123.com/gateway/open-api/';

    /**
     * API Token
     * @var string
     */
    private $api_token = null;

    /**
     * 请求路径
     * @var string
     */
    protected $endpoint = null;

    /**
     * 请求方法
     * @var string
     */
    protected $method = 'POST';

    /**
     * 请求头
     * @var array
     */
    protected $headers = [
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
    ];

    /**
     * 设置请求体
     * @param array $params
     * @return static
     */
    pubLic function setParams(array $params): static {
        $this->params = $params;
        return $this;
    }

    /**
     * 获取请求体
     * @return array
     */
    public function getParams(): array {
        return $this->params;
    }

    /**
     * 设置API Token
     * @param string $api_token
     * @return static
     */
    public function setApiToken(string $api_token): static {
        $this->api_token = $api_token;
        return $this;
    }

    /**
     * 获取API Token
     * @return string
     */
    private function getApiToken(): string {
        return $this->api_token ?? config('track123.api_token');
    }

    /**
     * 设置请求头
     * @param string $key
     * @param string $value
     * @return static
     */
    public function setHeader(string $key, string $value){
        $this->headers[$key] = $value;
        return $this;
    }

    /**
     * 设置API URL
     * @param string $api_url
     * @return static
     */
    public function setApiUrl(string $api_url): static {
        $this->api_url = $api_url;
        return $this;
    }

    /**
     * 获取请求头
     * @return array
     */
    public function getHeaders(): array {
        return $this->headers;
    }

    /**
     * 设置请求路径
     * @param string $endpoint
     * @return static
     */
    public function setEndpoint(string $endpoint): static {
        $this->endpoint = $endpoint;
        return $this;
    }

    /**
     * 获取完整URL
     * @return string
     */
    private function getFullUrl(): string {
        // 去除末尾的/
        $api_url = $this->api_url ?: config('track123.api_url');
        $api_url = rtrim($api_url, '/');
        if($this->endpoint == null){
            throw new InvalidArgumentException('Endpoint is not set');
        }
        // 去除开头的/
        $endpoint = ltrim($this->endpoint, '/');
        // 拼接完整url
        return "{$api_url}/{$endpoint}";
    }

    /**
     * 设置请求方法
     * @param string $method
     * @return static
     */
    public function setMethod(string $method='POST'): static {
        $this->method = strtoupper($method);
        return $this;
    }
    /**
     * 获取请求方法
     * @return string
     */
    public function getMethod(): string {
        return $this->method;
    }

    /**
     * 设置请求参数
     * @param string $key
     * @param $value
     * @return static
     */
    public function setParam(string $key, $value){
        $this->params[$key] = $value;
        return $this;
    }
    /**
     * 发送请求
     * @param array $params
     * @return array
     * @throws InvalidResponseException
     */
    protected function sendRequest(array $params = []): array {
        $headers['Track123-Api-Secret'] = $this->getApiToken();
        $params = [...$this->params, ...$params];
        $url = $this->getFullUrl();
        $method = $this->getMethod();
        $this->validate();
        $res = $method=='GET'? Http::withHeaders($headers)->get($url, $params) : Http::withHeaders($headers)->post($url, $params);
        if($res->status() != 200){
            throw new InvalidResponseException('Invalid response from server ' . $res->status() . ',error message:' . ($res->json('msg') ?? '未知错误'));
        }
        return $res->json();
    }

    /**
     * 验证请求参数
     * @return void
     */
    abstract protected function validate();
    
    /**
     * 发送请求
     * @return mixed
     */
    abstract protected function send();

}