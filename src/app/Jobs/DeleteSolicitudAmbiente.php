<?php

namespace App\Jobs;

use App\Models\SolicitudAmbiente;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteSolicitudAmbiente implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $solicitudIds;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($solicitudIds)
    {
        $this->solicitudIds = $solicitudIds;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        SolicitudAmbiente::whereIn('id', $this->solicitudIds)->delete();
    }
}
