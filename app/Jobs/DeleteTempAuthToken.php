<?php

namespace App\Jobs;

use App\Models\Option;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

class DeleteTempAuthToken implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $option_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($option_id)
    {
        $this->option_id = $option_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Option::where("id", $this->option_id)->delete();
    }
}
