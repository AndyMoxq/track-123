<?php
namespace ThankSong\Track123\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class TrackingDetail extends Model {
    public $table='track123_tracking_details';

    public const IN_TRANSIT_01 = "IN_TRANSIT_01";
    public const IN_TRANSIT_02 = 'IN_TRANSIT_02';
    public const IN_TRANSIT_03 = 'IN_TRANSIT_03';
    public const IN_TRANSIT_04 = 'IN_TRANSIT_04';
    public const IN_TRANSIT_05 = 'IN_TRANSIT_05';
    public const IN_TRANSIT_06 = 'IN_TRANSIT_05';
    public const IN_TRANSIT_07 = 'IN_TRANSIT_07';
    public const IN_TRANSIT_08 = 'IN_TRANSIT_08';

    // Waiting Delivery
    public const WAITING_DELIVERY_01 = 'WAITING_DELIVERY_01';
    public const WAITING_DELIVERY_02 = 'WAITING_DELIVERY_02';
    public const WAITING_DELIVERY_03 = 'WAITING_DELIVERY_03';

    // Delivered
    public const DELIVERED_01 = 'DELIVERED_01';
    public const DELIVERED_02 = 'DELIVERED_02';
    public const DELIVERED_03 = 'DELIVERED_03';
    public const DELIVERED_04 = 'DELIVERED_04';

    // Delivery Failed
    public const DELIVERY_FAILED_01 = 'DELIVERY_FAILED_01';
    public const DELIVERY_FAILED_02 = 'DELIVERY_FAILED_02';
    public const DELIVERY_FAILED_03 = 'DELIVERY_FAILED_03';
    public const DELIVERY_FAILED_04 = 'DELIVERY_FAILED_04';

    // Abnormal
    public const ABNORMAL_01 = 'ABNORMAL_01';
    public const ABNORMAL_02 = 'ABNORMAL_02';
    public const ABNORMAL_03 = 'ABNORMAL_03';
    public const ABNORMAL_04 = 'ABNORMAL_04';
    public const ABNORMAL_05 = 'ABNORMAL_05';
    public const ABNORMAL_06 = 'ABNORMAL_06';
    public const ABNORMAL_07 = 'ABNORMAL_07';
    public const ABNORMAL_08 = 'ABNORMAL_08';

    // Info Received
    public const INFO_RECEIVED_01 = 'INFO_RECEIVED_01';

    public const STATUS_LABELS=[
        self::IN_TRANSIT_01 => '运输中-派送途中',
        self::IN_TRANSIT_02 => '运输中-到达分拣中心',
        self::IN_TRANSIT_03 => '运输中-清关完成',
        self::IN_TRANSIT_04 => '运输中-准备发往机场',
        self::IN_TRANSIT_05 => '运输中-交航空公司',
        self::IN_TRANSIT_06 => '运输中-抵达目的国',
        self::IN_TRANSIT_07 => '运输中-到达快递网点',
        self::IN_TRANSIT_08 => '运输中-航班起飞',

        self::WAITING_DELIVERY_01 => '待派送-派送中',
        self::WAITING_DELIVERY_02 => '待派送-到达自提点',
        self::WAITING_DELIVERY_03 => '待派送-用户要求延迟或二次派送',

        self::DELIVERED_01 => '已签收-正常签收',
        self::DELIVERED_02 => '已签收-自提点取件',
        self::DELIVERED_03 => '已签收-客户签收',
        self::DELIVERED_04 => '已签收-代收',

        self::DELIVERY_FAILED_01 => '派送失败-地址问题',
        self::DELIVERY_FAILED_02 => '派送失败-收件人不在家',
        self::DELIVERY_FAILED_03 => '派送失败-无法联系收件人',
        self::DELIVERY_FAILED_04 => '派送失败-其他原因',

        self::ABNORMAL_01 => '异常-无人认领',
        self::ABNORMAL_02 => '异常-海关扣押',
        self::ABNORMAL_03 => '异常-破损/丢失/废弃',
        self::ABNORMAL_04 => '异常-订单取消',
        self::ABNORMAL_05 => '异常-收件人拒收',
        self::ABNORMAL_06 => '异常-退件已签收',
        self::ABNORMAL_07 => '异常-退件途中',
        self::ABNORMAL_08 => '异常-其他',

        self::INFO_RECEIVED_01 => '已预报-等待揽收',
    ];

    protected $fillable = [
        'track123_tracking_id',
        'address',
        'event_time',
        'event_time_zero_utc',
        'timezone',
        'event_detail',
        'transit_sub_status',
    ];
    protected $hidden = [
        'track123_tracking_id',
        'id'
    ];

    public function setEventTimeZeroUtcAttribute($value): void
    {
        $this->attributes['event_time_zero_utc'] = $value
            ? Carbon::parse($value)->utc()->toDateTimeString()
            : null;
    }

    public function getSubStatusLabelAttribute(): string {
        return self::STATUS_LABELS[$this->transit_sub_status] ?? '';
    }

    public function tracking(){
        return $this->belongsTo(Tracking::class, 'track123_tracking_id', 'id');
    }
}