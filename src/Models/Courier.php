<?php
namespace ThankSong\Track123\Models;

use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    public const CACHE_KEY = 'track123:courier_codes';
    public $table = 'track123_couriers';

    protected $fillable = [
        'code',
        'name_cn',
        'name_en',
        'home_page'
    ];
}