<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SolicitudRechazadaMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $solicitud;
    public $docente;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($solicitud, $docente)
    {
        $this->solicitud = $solicitud;
        $this->docente = $docente;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.solicitud_rechazada')
            ->with([
                'solicitud' => $this->solicitud,
                'docente' => $this->docente,
            ]);
    }
}
