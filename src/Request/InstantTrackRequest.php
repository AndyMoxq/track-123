<?php
/**
 * Created by VsCode.
 * User: andy
 * Date: 2025-12-18
 * Instant Tracking lets you create and get tracking results in one call.
 * Returns the latest shipment status immediately.
 */
namespace ThankSong\Track123\Request;

use InvalidArgumentException;
use ThankSong\Track123\Response\InstantTrackResponse;

class InstantTrackRequest extends Request {
    /**
     * 路径断点常量
     * @var string
     */
    public const ENDPOINT = '/tk/v2.1/track/query-realtime';
    /**
     * 跟踪信息（数组）
     * @var array
     */
    protected array $tracking = [];

    public function __construct(string $trackNo = null, string $courierCode = null, string $postalCode = null ) {
        $this->setEndpoint(self::ENDPOINT);
        if($trackNo !== null && $courierCode !== null){
            $this->tracking = [
                'trackNo' => $trackNo,
                'courierCode' => $courierCode
            ];
        }
        if($postalCode !== null){
            $this->tracking['postalCode'] = $postalCode;
        }
    }

    /**
     * 设置跟踪号和快递公司代码
     * @param array $tracking
     * @return static
     */
    public function setTracking(array $tracking){
        if(empty($tracking['trackNo'] ?? null) || empty($tracking['courierCode'] ?? null)){
            throw new InvalidArgumentException('trackNo and courierCode are required');
        }
        $this->tracking = [
            'trackNo' => $tracking['trackNo'],
            'courierCode' => $tracking['courierCode']
        ];
        return $this;
    }

    public function getTracking(): array {
        return $this->tracking;
    }

    /**
     * 验证
     * @throws InvalidArgumentException
     * @return void
     */
    public function validate(){
        if(empty($this->tracking)){
            throw new InvalidArgumentException('Tracking information is required');
        }
        if(empty($this->tracking['trackNo']) || empty($this->tracking['courierCode'])){
            throw new InvalidArgumentException('trackNo and courierCode are required');
        }
    }
    
    /**
     * 发送请求
     * @return array
     */
    public function send(): InstantTrackResponse {
        $this->setParams($this->getTracking());
        $this->validate();
        return InstantTrackResponse::from($this->sendRequest());
    }
}