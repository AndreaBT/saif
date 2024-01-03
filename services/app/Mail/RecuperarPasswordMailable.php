<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RecuperarPasswordMailable extends Mailable {
    use Queueable, SerializesModels;

    public $subject     = 'Restablezca su contraseña';
    public $NomEmpresa;
    public $Nombre;
    public $Correo;
    public $Link;
    public $OperationTime;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct() {
        $this->siteURLAdmin  = '';
        $this->NomEmpresa    = '';
        $this->Nombre        = '';
        $this->Correo        = '';
        $this->Link          = '';
        $this->OperationTime = '';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {

        return $this->view('emails.RecuperarPassword', [
            'siteURLAdmin'  => $this->siteURLAdmin,
            'NomEmpresa'    => $this->NomEmpresa,
            'Correo'        => $this->Correo,
            'Link'          => $this->Link,
            'OperationTime' => $this->OperationTime
        ]);
        
    }

}
