<?php

namespace App\Jobs;

use App\Mail\SolicitudRechazadaMailable;
use App\Models\Docente;
use App\Models\SolicitudAmbiente;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendRejectionEmailsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $solicitud;
    protected $docente;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SolicitudAmbiente $solicitud, Docente $docente)
    {
        $this->solicitud = $solicitud;
        $this->docente = $docente;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->docente->usuario && $this->docente->usuario->email) {
            Mail::to($this->docente->usuario->email)->send(new SolicitudRechazadaMailable($this->solicitud, $this->docente));
        }
    }
}
