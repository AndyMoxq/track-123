<?php
namespace ThankSong\Track123;
use ThankSong\Track123\Request\Request;
use ThankSong\Track123\Request\RegisterTrackingRequest;
use ThankSong\Track123\Request\ChangeCarrierRequest;
use ThankSong\Track123\Response\Response;
use ThankSong\Track123\Response\GetTrackingsResponse;
use ThankSong\Track123\Response\RegisterTrackingResponse;
use ThankSong\Track123\Request\GetTrackingsRequest;


class Track123 {
    /**
     * 注册单号
     * @param array $trackings
     * @return RegisterTrackingResponse
     * @throws \Exception
     */
    public static function register(array $trackings = []): RegisterTrackingResponse {
        $request = new RegisterTrackingRequest($trackings);
        return $request -> send();
    }

    /**
     * 查询单个跟踪信息
     * @param string $trackNo
     * @param string|null $carrier
     * @return GetTrackingsResponse
     * @throws \Exception
     */
    public static function query(string $trackNo, string $carrier=null): GetTrackingsResponse{
        $request = new GetTrackingsRequest();
        $request -> setTrackingNumber($trackNo, $carrier);
        return $request -> send();
    }

    /**
     * 批量查询跟踪信息
     * @param array $trackings
     * @return GetTrackingsResponse
     * @throws \Exception
     */
    public static function queryTrackings(array $trackings): GetTrackingsResponse {
        $request = new GetTrackingsRequest();
        $request -> setTrackingNumbers($trackings);
        return $request -> send();
    }

    public static function changeCarrier(string $oldCourierCode, string $trackNo, string $newCourierCode): Response {
        return (new ChangeCarrierRequest($oldCourierCode, $trackNo, $newCourierCode)) -> send();
    }

    public static function getCarrierList(): Response {
        $endpoint = 'tk/v2.1/courier/list';
        $request = new Request();
        $request -> setEndpoint($endpoint)-> setMethod('GET');
        return $request -> send();
    }
}
