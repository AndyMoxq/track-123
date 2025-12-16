<?php
namespace ThankSong\Track123\Models;

use Illuminate\Database\Eloquent\Model;

class TrackingExtraInfo extends Model {
    public $table='track123_tracking_extra_info';

    protected $fillable = [
        'track123_tracking_id',
        'pieces',
        'signed_by',
        'service_code',
        'dimensions',
        'weight',
    ];
    
    protected $casts = [
        'dimensions' => 'array',
        'weight'     => 'array',
    ];

    protected $hidden = [
        'track123_tracking_id',
        'id'
    ];

    public function tracking(){
        return $this->belongsTo(Tracking::class, 'track123_tracking_id', 'id');
    }

}