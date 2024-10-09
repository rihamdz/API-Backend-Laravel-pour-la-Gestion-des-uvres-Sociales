<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;

class CleanUpTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    

     
    protected $signature = 'app:clean-up-tokens';

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
        $recordsToUpdate = User::whereNotNull('confirmation_token')
                                     ->where('created_at', '<=', Carbon::now()->subDays(3))
                                     ->get();

        foreach ($recordsToUpdate as $record) {
            $record->update([
                'confirmation_token' => null
            ]);
        }

        $this->info('Tokens cleaned up successfully.');
    }
}
