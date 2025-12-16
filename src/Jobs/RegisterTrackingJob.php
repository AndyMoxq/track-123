<?php

namespace ThankSong\Track123\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use ThankSong\Track123\Track123;
use ThankSong\Track123\Models\Tracking;

class RegisterTrackingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $trackings;

    public function __construct(array $trackings){
        $this->trackings = $trackings;
    }

    public function handle(){
        $res = Track123::register($this->trackings);

        foreach ($res -> getAccepted() as $acceptedData) {
            Tracking::updateOrCreate([
                'track_no' => $acceptedData['trackNo'],
            ]);
        }

        foreach ($res->getRejected() as $rejectedData) {
            Tracking::updateOrCreate([
                'track_no' => $rejectedData['trackNo'],
            ],[
                'exception' => $rejectedData['error']['msg'] ?? 'Unknown error'
            ]);
            Log::error('RegisterTrackingJob has rejected data' . json_encode($rejectedData));
        }
    }

}