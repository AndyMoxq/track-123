<?php

namespace ThankSong\Track123\Request;

use Illuminate\Support\Facades\Cache;
use InvalidArgumentException;
use ThankSong\Track123\Models\Courier;
use ThankSong\Track123\Response\Response;

class ChangeCarrierRequest extends Request 
{
    public const END_POINT = 'tk/v2.1/track/update-courier';

    public function __construct(string $courierCode = null,string $trackNo = null,string $newCourierCode = null){
        $this->setEndPoint(self::END_POINT);
        if($courierCode){
            $this->setCourierCode($courierCode);
        }
        if($trackNo){
            $this->setTrackingNo($trackNo);
        }
        if($newCourierCode){
            $this->setNewCourierCode($newCourierCode);
        }
    }

    public function setCourierCode(string $courierCode): static {
        $this->setParam('courierCode', $courierCode);
        return $this;
    }

    public function setTrackingNo(string $trackNo): static {
        $this->setParam('trackNo', $trackNo);
        return $this;
    }

    public function setNewCourierCode(string $newCourierCode): static {
        $this->setParam('newCourierCode', $newCourierCode);
        return $this;
    }

    public function send(): Response {
        $this->validate();
        return parent::send();
    }

    public function validate() {
        $params = $this->getParams();
        $errors = [];
        if (!isset($params['courierCode']) || empty($params['courierCode'])) {
            $errors[] = 'courierCode is required';
        }
        if (!isset($params['trackNo']) || empty($params['trackNo'])) {
            $errors[] = 'trackNo is required';
        }
        if (!isset($params['newCourierCode']) || empty($params['newCourierCode'])) {
            $errors[] = 'newCourierCode is required';
        }

        $courierCodeList = Cache::rememberForever(
            Courier::CACHE_KEY,
            fn () => Courier::query()->orderBy('code')->pluck('code')->all()
        );

        if (! \in_array($params['courierCode'], $courierCodeList)) {
            $errors[] = 'Invalid courierCode';
        }

        if (! \in_array($params['newCourierCode'], $courierCodeList)) {
            $errors[] = 'Invalid newCourierCode';
        }
        if ( \count($errors) > 0) {
            throw new InvalidArgumentException(implode(', ', $errors));
        }

    }


}