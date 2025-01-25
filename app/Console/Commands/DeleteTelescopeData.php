<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteTelescopeData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telescope:clear-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '清空 Telescope 跑出来的缓存数据';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        DB::table('telescope_entries')->delete();
        DB::table('telescope_entries_tags')->delete();
        DB::table('telescope_monitoring')->delete();

        return self::SUCCESS;
    }
}
