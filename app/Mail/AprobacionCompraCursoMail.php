<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AprobacionCompraCursoMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $usuarioCurso, $curso;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $data;
    public function __construct($data)
    {
        $this->data = $data;
        /*$this->usuarioCurso = $usuarioCurso;
        $this->curso = $curso;*/
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('stephanyherreravasquez@gmail.com', 'AcademiaCoMarca')
            ->view('correos.aprobacion_compra_curso')
            ->subject("Notificación de aprobación de la compra de tu curso")
            ->with($this->data);
    }
}
