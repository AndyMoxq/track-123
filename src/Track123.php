<?php
namespace ThankSong\Track123;
use ThankSong\Track123\Request\EditTrackingRequest;
use ThankSong\Track123\Request\InstantTrackRequest;
use ThankSong\Track123\Request\Request;
use ThankSong\Track123\Request\RegisterTrackingRequest;
use ThankSong\Track123\Request\ChangeCarrierRequest;
use ThankSong\Track123\Response\InstantTrackResponse;
use ThankSong\Track123\Response\Response;
use ThankSong\Track123\Response\GetTrackingsResponse;
use ThankSong\Track123\Response\RegisterTrackingResponse;
use ThankSong\Track123\Request\GetTrackingsRequest;
use ThankSong\Track123\Request\DeleteTrackingsRequest;


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

    /**
     * 更新快递公司
     * @param string $oldCourierCode
     * @param string $trackNo
     * @param string $newCourierCode
     * @return Response
     */
    public static function changeCarrier(string $oldCourierCode, string $trackNo, string $newCourierCode): Response {
        return (new ChangeCarrierRequest($oldCourierCode, $trackNo, $newCourierCode)) -> send();
    }

    /**
     * 获取快递承运商列表
     * @return Response
     */
    public static function getCarrierList(): Response {
        $endpoint = 'tk/v2.1/courier/list';
        $request = new Request();
        $request -> setEndpoint($endpoint)-> setMethod('GET');
        return $request -> send();
    }

    /**
     * 删除已注册的快递号
     * @param array $trackings
     * @return Response
     */
    public static function deleteTrackings(array $trackings): Response {
        return (new DeleteTrackingsRequest($trackings))->send();
    }

    /**
     * 实时查询
     * @param string|null $trackNo
     * @param string|null $courierCode
     * @param string|null $postalCode
     * @return InstantTrackResponse
     */
    public static function instantTrack(string $trackNo = null, string $courierCode = null, string $postalCode = null ): InstantTrackResponse {
        return (new InstantTrackRequest($trackNo, $courierCode, $postalCode))->send();
    }

    /**
     * 编辑已注册的快递号
     * @param array $tracking
     * @return Response
     */
    public static function editTracking(array $tracking): Response {
        return (new EditTrackingRequest($tracking))->send();
    }
}
