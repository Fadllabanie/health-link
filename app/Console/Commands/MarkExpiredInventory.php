<?php

namespace App\Console\Commands;

use App\Enums\InventoryStatus;
use App\Models\PharmacyInventory;
use Illuminate\Console\Command;

class MarkExpiredInventory extends Command
{
    /** @var string */
    protected $signature = 'inventory:mark-expired';

    /** @var string */
    protected $description = 'Mark pharmacy inventory items as expired when their expiry date has passed.';

    public function handle(): int
    {
        $count = PharmacyInventory::where('expiry_date', '<', now()->toDateString())
            ->where('status', '!=', InventoryStatus::Expired)
            ->update(['status' => InventoryStatus::Expired]);

        $this->info("Marked {$count} inventory item(s) as expired.");

        return self::SUCCESS;
    }
}
