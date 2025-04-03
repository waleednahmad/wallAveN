<?php

namespace App\Console\Commands;

use App\Models\Dealer;
use Illuminate\Console\Command;

class CheckDealersBannerSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-dealers-banner-settings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dealers = Dealer::all();

        foreach ($dealers as $dealer) {
            if (!$dealer->bannerSetting) {
                $dealer->bannerSetting()->create([
                    'text' => 'Golden Rugs – Exclusive Deals Just for You',
                    'text_color' => '#000000',
                    'bg_color' => '#f1c55e',
                ]);
                $this->info("Dealer ID {$dealer->id} did not have a banner setting. Created default settings.");
            } else {
                $this->info("Dealer ID {$dealer->id} has a banner setting.");
            }
        }

        return Command::SUCCESS;
    }
}
