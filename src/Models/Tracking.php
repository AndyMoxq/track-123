<?php
namespace ThankSong\Track123\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tracking extends Model {
    public $table='track123_trackings';
    public const INIT = 'INIT';
    public const NO_RECORD = 'NO_RECORD';
    public const INFO_RECEIVED = 'INFO_RECEIVED';
    public const IN_TRANSIT = 'IN_TRANSIT';
    public const WAITING_DELIVERY = 'WAITING_DELIVERY';
    public const DELIVERY_FAILED = 'DELIVERY_FAILED';
    public const ABNORMAL = 'ABNORMAL';
    public const DELIVERED = 'DELIVERED';
    public const EXPIRED = 'EXPIRED';

    public const STATUS_LABELS = [
          self::INIT                => '待揽收',
          self::NO_RECORD           => '无轨迹',
          self::INFO_RECEIVED       => '已预报',
          self::IN_TRANSIT          => '运输中',
          self::WAITING_DELIVERY    => '待派送',
          self::DELIVERY_FAILED     => '派送失败',
          self::ABNORMAL            => '异常',
          self::DELIVERED           => '已签收',
          self::EXPIRED             => '已过期',
    ];

    public function getStatusLabelAttribute() : string {
        return self::STATUS_LABELS[$this->transit_status] ?? '';
    }

    public function getSubStatusLabelAttribute() : string {
        return TrackingDetail::STATUS_LABELS[$this->transit_sub_status] ?? '';
    }
    protected $fillable = [
        'tracking_id',
        'track_no',
        'courider_code',
        'create_time',
        'nextUpdateTime',
        'ship_from',
        'ship_to',
        'tracking_status',
        'transit_status',
        'transit_sub_status',
        'order_time',
        'receipt_time',
        'delivered_time',
        'last_tracking_time',
        'delivered_days',
        'transit_days',
        'stay_days',
        'shipment_type',
        'exception'
    ];

    public function localLogistic(){
        return $this->hasOne(LocalLogistic::class,'track123_tracking_id','id');
    }

    public function details(){
        return $this->hasMany(TrackingDetail::class,'track123_tracking_id','id');
    }

    public function extraInfo(){
        return $this->hasOne(TrackingExtraInfo::class,'track123_tracking_id','id');
    }

    public static function init(array $trackingData){
        return DB::transaction(function () use ($trackingData){
            $tracking = self::updateOrCreate([
                'track_no'              => $trackingData['trackNo'],
            ],[
                'tracking_id'           => $trackingData['id'] ?? null,
                'create_time'           => $trackingData['createTime'] ?? null,
                'nextUpdateTime'        => $trackingData['nextUpdateTime'] ?? null,
                'ship_from'             => $trackingData['shipFrom'] ?? null,
                'ship_to'               => $trackingData['shipTo'] ?? null,
                'tracking_status'       => $trackingData['trackingStatus'] ?? null,
                'transit_status'        => $trackingData['transitStatus'] ?? null,
                'transit_sub_status'    => $trackingData['transitSubStatus'] ?? null,
                'order_time'            => $trackingData['orderTime'] ?? null,
                'receipt_time'          => $trackingData['receiptTime'] ?? null,
                'delivered_time'        => $trackingData['deliveredTime'] ?? null,
                'last_tracking_time'    => $trackingData['lastTrackingTime'] ?? null,
                'delivered_days'        => $trackingData['deliveredDays'] ?? null,
                'transit_days'          => $trackingData['transitDays'] ?? null,
                'stay_days'             => $trackingData['stayDays'] ?? null,
                'shipment_type'         => $trackingData['shipmentType'] ?? null,
            ]);

            $localLogisticsInfo = $trackingData['localLogisticsInfo'] ?? [];
            if(!empty($localLogisticsInfo)){
                $tracking->localLogistic()->updateOrCreate([
                    'courier_code'              => $localLogisticsInfo['courierCode'] ?? null,
                    'courier_name_cn'           => $localLogisticsInfo['courierNameCN'] ?? null,
                    'courier_name_en'           => $localLogisticsInfo['courierNameEN'] ?? null,
                    'courier_home_page'         => $localLogisticsInfo['courierHomePage'] ?? null,
                    'courier_tracking_link'     => $localLogisticsInfo['courierTrackingLink'] ?? null
                ]);

                $trackingDetails = $localLogisticsInfo['trackingDetails'] ?? [];
                if(!empty($trackingDetails)){
                    $tracking->details()->delete();
                    foreach ($trackingDetails as $trackingDetail) {
                        $tracking->details()-> create([
                            'address'               => $trackingDetail['address'] ?? null,
                            'event_time'            => $trackingDetail['eventTime'] ?? null,
                            'event_time_zero_utc'   => $trackingDetail['eventTimeZeroUTC'] ?? null,
                            'event_detail'          => $trackingDetail['eventDetail'] ?? null,
                            'transit_sub_status'    => $trackingDetail['transitSubStatus'] ?? null,

                        ]);
                    }
                }
            }

            $extraInfo = $trackingData['extraInfo'] ?? [];
            if(!empty($extraInfo)){
                $tracking->extraInfo()->updateOrCreate([
                    'pieces'        => $extraInfo['pieces'] ?? 1,
                    'signed_by'     => $extraInfo['signedBy'] ?? null,
                    'service_code'  => $extraInfo['serviceCode'] ?? null,
                    'dimensions'    => $extraInfo['dimensions'] ?? [],
                    'weight'        => $extraInfo['weight']?? []
                ]);
            }
            return $tracking->load(['localLogistic','extraInfo','details']);
        });

    }

}