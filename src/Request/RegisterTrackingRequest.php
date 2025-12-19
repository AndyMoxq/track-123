<?php
/**
 * Created by VsCode
 * User: Andy
 * Date: 2025-12-19
 * Use to register trackings
 */
namespace ThankSong\Track123\Request;

use Illuminate\Support\Facades\Cache;
use InvalidArgumentException;
use ThankSong\Track123\Response\RegisterTrackingResponse;
use ThankSong\Track123\Models\Courier;

class RegisterTrackingRequest extends Request {
    /**
     * 路径断点常量
     * @var string
     */
    public const ENDPOINT = 'tk/v2.1/track/import';

    /**
     * 需要注册的单号信息
     * @var array
     */
    protected array $trackings = [];

    /**
     * RegisterTrackingRequest constructor.
     * @param array $trackings
     */
    public function __construct(array $trackings = []){
        $this->setEndpoint(self::ENDPOINT);
        if(!empty($trackings)){
            $this->addTrackings($trackings);
        }
    }

    /**
     * 单个添加跟踪信息
     * @param array $tracking
     * @return static
     */
    public function addTracking(array $tracking): static {
        $this->trackings[] = $tracking;
        return $this;
    }

    /**
     * 批量添加跟踪信息
     * @param array $trackings
     * @return static
     */
    public function addTrackings(array $trackings): static {
        $this->trackings = [...$this->trackings, ...$trackings];
        return $this;
    }

    /**
     * 验证参数
     * @throws InvalidArgumentException
     * @return void
     */
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

    /**
     * 发送请求
     * @return RegisterTrackingResponse
     */ 
    public function send(): RegisterTrackingResponse {
        $this->setParams($this->trackings);
        return RegisterTrackingResponse::from($this->sendRequest());
    }
}