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
    
    public function __construct($solicitudId)
    {
        $this->solicitudId = $solicitudId;
    }

    public function envelope()
    {
        //return new Envelope(subject; 'Enviar Correo',);
    }
    
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.enviarCorreo')
                    ->with('solicitudId', $this->solicitudId)
                    ->subject('Sistema de Reservas');
    }
    public function content()
    {
        //return $this->view('mails.enviar-correo');
        /**return new Content(
        *    view: 'mails.enviar-correo',
        *);
        */
    }
}
