<?php

namespace ThankSong\Track123\Request;

use Illuminate\Support\Facades\Cache;
use InvalidArgumentException;
use ThankSong\Track123\Response\RegisterTrackingResponse;
use ThankSong\Track123\Models\Courier;

class RegisterTrackingRequest extends Request {
    public const ENDPOINT = 'tk/v2.1/track/import';

    public function __construct(array $trackings = []){
        $this->setEndpoint(self::ENDPOINT);
        if(!empty($trackings)){
            $this->addTrackings($trackings);
        }
    }

    protected array $trackings = [];

    public function addTracking(array $tracking){
        $this->trackings[] = $tracking;
    }

    public function addTrackings(array $trackings){
        $this->trackings = [...$this->trackings, ...$trackings];

    }
    public function validate() {
        $courierCodeList = Cache::rememberForever(
            Courier::CACHE_KEY,
            fn () => Courier::query()->orderBy('code')->pluck('code')->all()
        );
        $trackings = $this->trackings;
        if (empty($trackings)) {
            throw new InvalidArgumentException('至少需要一个跟踪信息');
        }
        foreach ($trackings as $index => $tracking) {
            if (!isset($tracking['trackNo']) || empty($tracking['trackNo'])) {
                $errors[] = "第" . $index + 1 . "个跟踪信息中，跟踪号不能为空";
            }
            if (! $tracking['courierCode'] ?? null) {
                $errors[] = "第" . $index + 1 . "个跟踪信息中，运营商代码不能为空";
            }

            if(! \in_array( strtolower($tracking['courierCode']),$courierCodeList)){
                $errors[] = "第" . $index + 1 . "个跟踪信息中，运营商代码不支持";
            }
        }
        if (!empty($errors)) {
            throw new InvalidArgumentException(implode("\n", $errors));
        }
    }
    public function send(): RegisterTrackingResponse {
        $this->setParams($this->trackings);
        return RegisterTrackingResponse::from($this->sendRequest());
    }
}