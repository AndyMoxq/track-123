<?php

namespace ThankSong\Track123\Request;

use InvalidArgumentException;
use ThankSong\Track123\Response\GetTrackingsResponse;
use function in_array;

class GetTrackingsRequest extends Request {
    public const ENDPOINT = 'tk/v2.1/track/query';

    protected $trackNos = [];

    public function __construct(array $trackNos = []){
        $this->setEndpoint(self::ENDPOINT);
        if(!empty($trackNos)){
            $this->setTrackingNumbers($trackNos);
        }
    }

    /**
     * 批量设置订单编号
     * @param array $order_nos
     * @return GetTrackingsRequest
     */
    public function setOrderNos(array $order_nos): static {
        $this->setParam('orderNos', $order_nos);
        return $this;
    }

    /**
     * 设置单个追踪信息
     * @param string $trackNo
     * @param string $courierCode
     * @return void
     */
    public function setTrackingNumber(string $trackNo,string $courierCode = null){
        if(!in_array($trackNo, $this->trackNos)){
            $tracking['trackNo'] = $trackNo;
            if(!empty($courierCode)){
                $tracking['courierCode'] = $courierCode;
            }
            $this->trackNos[] = $tracking;
        }
        $this->setParam('trackNoInfos', $this->trackNos);
    }

    /**
     * 批量设置追踪信息
     * @param array $trackNos
     * @return static
     */
    public function setTrackingNumbers(array $trackNos){
        foreach($trackNos as $tracking){
            $this->setTrackingNumber($tracking['trackNo'] ?? null,$tracking['courierCode'] ?? null);
        }
        return $this;
    }

    /**
     * 设置起始创建时间
     * @param string $time_start
     * @return GetTrackingsRequest
     */
    public function setCreateTimeStart(string $time_start): static {
        $time_start = date('Y-m-d H:i:s', strtotime($time_start));
        $this->setParam('createTimeStart', $time_start);
        return $this;
    }

    /**
     * 设置结束创建时间
     * @param string $time_end
     * @return GetTrackingsRequest
     */
    public function setCreateTimeEnd(string $time_end): static {
        $time_end = date('Y-m-d H:i:s', strtotime($time_end));
        $this->setParam('createTimeEnd', $time_end);
        return $this;
    }

    /**
     * 设置页大小
     * @param int $page_size
     * @return GetTrackingsRequest
     */
    public function setPageSize(int $page_size=100): static {
        $this->setParam('queryPageSize', $page_size);
        return $this;
    }

    /**
     * 设置游标
     * @param string $cursor
     * @return static
     */
    public function setCursor(string $cursor){
        $this->setParam('cursor', $cursor);
        return $this;
    }

    public function send(): GetTrackingsResponse {
        $this->validate();
        return GetTrackingsResponse::from($this->sendRequest());
    }

    public function validate(){
        $errors = [];
        $prams = $this->getParams();
        if(!empty($prams['trackNoInfos'])){
            foreach ($prams['trackNoInfos'] as $tracking) {
                if(empty($tracking['trackNo'])){
                    $errors[] = 'Tracking number cannot be empty';
                }
            }
        }
        if(!empty($errors)){
            throw new InvalidArgumentException(implode(', ', $errors));
        }
    }



}