<?php
/**
 * Created by VsCode
 * User: andy
 * Date: 2025-12-19
 * Use to edit tracking information had registered
 */
namespace ThankSong\Track123\Request;

use InvalidArgumentException;
use ThankSong\Track123\Response\Response;

class EditTrackingRequest extends Request {
    /**
     * 路径端点常量
     * @var string
     */
    public const ENDPOINT = '/tk/v2.1/track/update';

    /**
     * 跟踪信息参数
     * @var array
     */
    protected array $tracking = [];

    /**
     * EditTrackingRequest constructor.
     * @param array $tracking
     */
    public function __construct(array $tracking = []){
        $this->setEndpoint(self::ENDPOINT);
        if(!empty($tracking)){
            $this -> tracking = $tracking;
        }
    }

    /**
     * 设置新的跟踪信息
     * @param array $tracking
     */
    public function setTracking(array $tracking): static {
        $this->tracking = $tracking;
        return $this;
    }

    /**
     * 发送请求
     * @return Response
     */
    public function send(): Response {
        return parent::send();
    }

    /**
     * 验证参数
     * @throws InvalidArgumentException
     * @return void
     */
    public function validate(){
        $tracking = $this->tracking;
        $errors = [];
        if(empty($tracking)){
            $errors[] = 'Tracking information is empty';
        }

        if(empty($tracking['trackNo'] ?? null )){
            $errors[] = 'Tracking number is required';
        }

        if(empty($tracking['courierCode'] ?? null)){
            $errors[] = 'Courier code is required';
        }

        if(!empty($errors)){
            throw new InvalidArgumentException(implode(', ', $errors));
        }
    }


}