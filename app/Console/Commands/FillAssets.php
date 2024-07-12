<?php

namespace App\Console\Commands;

use Airtable;
use App\Models\Item;
use GuzzleHttp\Client;
use App\Models\ItemAsset;
use App\Models\Exhibition;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\QueryException;
use Spatie\SimpleExcel\SimpleExcelReader;

class FillAssets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill:assets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import assets from API DAM to the database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $client = new Client();
        $items = Item::whereNull('asset_id')->get()->take(1000);
        $imported = 0;

        $progressBar = $this->output->createProgressBar($items->count());
        $progressBar->start();

        foreach ($items as $item) {
            $response = $client->post(config('services.dam.url') . '?quicksearchstring='.$item->id.'&field={cc7f9f0e-6c0b-4143-9478-d44333ece2dc}&field={05f6f3f0-833a-45a0-ade4-8e48542f37ef}&field={af4b2e51-5f6a-11d2-8f20-0000c0e166dc}&field={af4b2e69-5f6a-11d2-8f20-0000c0e166dc}&field={938322a8-9e83-4d4f-bbec-a3905468134c}&field={af4b2e00-5f6a-11d2-8f20-0000c0e166dc}', [
                'auth' => [config('services.dam.username'), config('services.dam.password')],

            ]);

            $responseData = json_decode($response->getBody(), true)['items'];

            if (!empty($responseData)) {
                $hash = Arr::get($responseData, '0.{cc7f9f0e-6c0b-4143-9478-d44333ece2dc}');
                $item->asset_id = $hash;
                $item->save();
                $imported++;
            }

            $progressBar->advance();
        }

        $progressBar->finish();

        $this->info("Imported $imported assets");
    }
}
