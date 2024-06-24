<?php

namespace App\Jobs;

use App\Mail\SolicitudAprobadaMailable;
use App\Models\Docente;
use App\Models\SolicitudAmbiente;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendApprovalEmailsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $solicitud;
    protected $docente;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\SolicitudAmbiente $solicitud
     * @param \App\Models\Docente $docente
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
            Mail::to($this->docente->usuario->email)->send(new SolicitudAprobadaMailable($this->solicitud, $this->docente));
        }
    }
}
