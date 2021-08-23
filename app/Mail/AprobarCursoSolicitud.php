<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AprobarCursoSolicitud extends Mailable
{
    use Queueable, SerializesModels;
    protected $curso;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $data;
    public function __construct($data)
    {
        //
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('info@academiacomarca.com', 'AcademiaCoMarca')
            ->view('correos.aprobacion_solicitud_curso')
            ->subject("La solicitud de tu curso creado ha sido aprobado.")
            ->with($this->data);
    }
}
