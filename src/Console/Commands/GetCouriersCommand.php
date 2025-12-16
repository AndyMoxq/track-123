<?php
namespace ThankSong\Track123\Console\Commands;
use Illuminate\Console\Command;
use ThankSong\Track123\Models\Courier;
use ThankSong\Track123\Track123;
class GetCouriersCommand extends Command 
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'track123:get-couriers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get couriers from Track123 API';

    /**
     * Execute the console command.
     */
    public function handle(): int {
        $this->info('Start to get couriers from Track123 API');
        try {
            $res = Track123::getCarrierList();
            foreach ($res->getData() as $courierData) {
                try {
                    $courier = Courier::updateOrCreate([
                        'code'=>$courierData['courierCode'],
                    ],[
                        'name_cn'   => $courierData['courierNameCN'] ?? null,
                        'name_en'   => $courierData['courierNameEN'] ?? null,
                        'home_page' => $courierData['courierHomePage'] ?? null
                    ]);
                    $this->info("courierCode:{$courier->code} name:{$courier->name_cn} updated");
                } catch (\Throwable $th) {
                    $this->error('Error when save courier data to database: '. $th->getMessage());
                }
            }
            $this->info('Finish to get couriers from Track123 API');
            cache()->forget(Courier::CACHE_KEY);
            cache()->forever(Courier::CACHE_KEY,Courier::query()->orderBy('code')->pluck('code')->all());
            return Command::SUCCESS;
        } catch (\Throwable $th) {
            $this->error('Error when get track123 couriers from api:' . $th->getMessage());
            return Command::FAILURE;
        }
    }
    
}