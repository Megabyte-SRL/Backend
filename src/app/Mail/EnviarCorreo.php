<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EnviarCorreo extends Mailable
{
    use Queueable, SerializesModels;
    public $solicitud;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */

    public function __construct($solicitud, $estado)
    {
        $this->solicitud = $solicitud;
        $this->estado = $estado;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->estado == 'aprobada' ? 'Solicitud de Reserva Aprobada' : 'Solicitud de Reserva Rechazada';

        return $this->view('mails.enviarCorreo')
                    ->subject($subject)
                    ->with([
                       'solicitud' => $this->solicitud,
                       'estado' => $this->estado,
                    ]);
    }
}
