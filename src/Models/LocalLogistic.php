<?php
namespace ThankSong\Track123\Models;

use Illuminate\Database\Eloquent\Model;

class LocalLogistic extends Model {
    public $table='track123_local_logistics';

    protected $fillable = [
        'track123_tracking_id',
        'courier_code',
        'courier_name_cn',
        'courier_name_en',
        'courier_home_page',
        'courier_tracking_link'
    ];
    protected $hidden = [
        'track123_tracking_id',
        'id'
    ];

    public function tracking(){
        return $this->belongsTo(Tracking::class, 'track123_tracking_id', 'id');
    }
}