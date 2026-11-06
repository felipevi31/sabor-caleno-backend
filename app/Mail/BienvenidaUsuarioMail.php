<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BienvenidaUsuarioMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('¡Bienvenido a Sabor Caleño! 🍗')
                    ->html("
                        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; background-color: #5B0D18; color: #ffffff; padding: 30px; border-radius: 16px; border: 3px solid #F4B312;'>
                            <h1 style='color: #F4B312; text-align: center; margin-bottom: 10px;'>¡Hola, {$this->user->name}!</h1>
                            <p style='font-size: 16px; text-align: center; color: #fef08a;'>¡Gracias por registrarte en <strong>Sabor Caleño</strong>!</p>
                            <p style='font-size: 14px; line-height: 1.6; color: #e5e7eb;'>
                                Estamos felices de tenerte con nosotros. Ahora podrás realizar tus pedidos de pollo asado, apanado y combos especiales en El Carmen de Viboral de manera rápida y fácil.
                            </p>
                            <div style='text-align: center; margin-top: 25px;'>
                                <a href='http://localhost:5173' style='background-color: #F4B312; color: #5B0D18; font-weight: bold; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-size: 16px; display: inline-block;'>
                                    Hacer mi primer pedido
                                </a>
                            </div>
                            <hr style='border: 0; border-top: 1px solid #F4B312; margin-top: 30px;' />
                            <p style='font-size: 12px; text-align: center; color: #9ca3af;'>Sabor Caleño · El Carmen de Viboral, Antioquia</p>
                        </div>
                    ");
    }
}