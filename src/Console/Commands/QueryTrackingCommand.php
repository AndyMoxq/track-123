<?php
namespace ThankSong\Track123\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use ThankSong\Track123\Models\Tracking;
use ThankSong\Track123\Track123;

class QueryTrackingCommand extends Command 
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'track123:query {tracking_number}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Query tracking information from Track123 API';

    /**
     * Execute the console command.
     */
    public function handle(): int {
        try {
            $tracking_number = $this->argument('tracking_number');
            $res = Track123::query($tracking_number);
            foreach ($res -> getAccepted() as $trackingData) {
                try {
                    $tk = Tracking::init($trackingData);
                    $this->info("查询更新{$tracking_number}跟踪信息成功。最新状态：" . $tk -> status_label );
                } catch (\Throwable $th) {
                    $this->error("查询成功，但更新{$tracking_number}跟踪信息失败。" . $th -> getMessage());
                }
            }
            if($res->getRejectedMessages()){
                foreach ($res->getRejectedMessages() as $message) {
                    $this->error("查询失败：$message");
                }
            }
            return Command::SUCCESS;
        } catch (\Throwable $th) {
            $msg = '获取Track123响应失败：' . $th -> getMessage();
            $this->error($msg);
            Log::error("track123:query command error: $msg");
            return Command::FAILURE;
        }
    }

}