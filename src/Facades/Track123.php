<?php
namespace ThankSong\Track123\Facades;

use Illuminate\Support\Facades\Facade;

class Track123 extends Facade {
    protected static function getFacadeAccessor(): string {
        return 'Track123';
    }
}