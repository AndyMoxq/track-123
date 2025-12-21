<?php
/**
 * created by VsCode
 * User: Andy
 * DateTime 2025-12-19 14:26:00
 * Use to change carrier for track123
 */
namespace ThankSong\Track123\Request;

use Illuminate\Support\Facades\Cache;
use InvalidArgumentException;
use ThankSong\Track123\Models\Courier;
use ThankSong\Track123\Response\Response;

class ChangeCarrierRequest extends Request 
{
    /**
     * 路径端点常量
     * @var string
     */
    public const END_POINT = 'tk/v2.1/track/update-courier';

    /**
     * 构造函数
     * @param string|null $courierCode 原始快递公司代码
     * @param string|null $trackNo 运单号
     * @param string|null $newCourierCode 新快递公司代码
     */
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

    /**
     * 设置原始快递公司代码
     * @param string $courierCode
     * @return static
     */
    public function setCourierCode(string $courierCode): static {
        $this->setParam('courierCode', $courierCode);
        return $this;
    }

    /**
     * 设置运单号
     * @param string $trackNo
     * @return static
     */
    public function setTrackingNo(string $trackNo): static {
        $this->setParam('trackNo', $trackNo);
        return $this;
    }

    /**
     * 设置新快递公司代码
     * @param string $newCourierCode
     * @return static
     */
    public function setNewCourierCode(string $newCourierCode): static {
        $this->setParam('newCourierCode', $newCourierCode);
        return $this;
    }

    /**
     * 发送请求
     * @return Response
     */
    public function send(): Response {
        $this->validate();
        return parent::send();
    }

    /**
     * 验证参数
     * @throws InvalidArgumentException
     */
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