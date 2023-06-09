<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\UserSubscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MembershipExpired extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'membership:expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire user membership';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $userSubscriptions = UserSubscription::where('end_date', date('Y-m-d'))->where('is_expired', false)->get();
            if ($userSubscriptions->count() > 0) {
                foreach ($userSubscriptions as $userSubscription) {
                    $userSubscription->update([
                        'is_expired' => '1'
                    ]);
                    $userSubscription->delete();
                }
            }
            Log::info("Membership expired successfully");
            $this->info("Membership expired successfully");

            return Command::SUCCESS;
        } catch (\Exception $exception) {
            Log::info("Membership Expired Exception: " . $exception->getMessage());
            $this->info("Membership Expired Exception: " . $exception->getMessage());

            return Command::FAILURE;
        }
    }
}
