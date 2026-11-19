<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BienvenidaUsuarioMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build(): static
    {
        $frontendUrl = rtrim(
            (string) (config('app.frontend_url') ?: env('FRONTEND_URL') ?: 'https://saborcaleno.vercel.app'),
            '/'
        );

        $userName = e($this->user->name ?? 'Cliente');
        $fromAddress = config('mail.from.address') ?: 'felipevillalbaaguirre@gmail.com';
        $fromName = config('mail.from.name') ?: 'Sabor Caleño';

        $html = <<<HTML
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Bienvenido a Sabor Caleño</title>
        </head>
        <body style="margin: 0; padding: 24px 12px; background-color: #f4f1ea; font-family: Arial, Helvetica, sans-serif;">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                <tr>
                    <td align="center">
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width: 600px; background-color: #5B0D18; border-radius: 14px; overflow: hidden; border: 2px solid #F4B312;">
                            <!-- Encabezado -->
                            <tr>
                                <td align="center" style="padding: 28px 24px 12px 24px; background-color: #490912;">
                                    <div style="font-size: 12px; letter-spacing: 2px; text-transform: uppercase; color: #F4B312; font-weight: bold; margin-bottom: 6px;">
                                        Sabor Caleño · El Carmen de Viboral
                                    </div>
                                    <h1 style="margin: 0; font-size: 26px; line-height: 1.3; color: #ffffff;">
                                        ¡Hola, <span style="color: #F4B312;">{$userName}</span>!
                                    </h1>
                                </td>
                            </tr>

                            <!-- Cuerpo -->
                            <tr>
                                <td style="padding: 24px 28px; color: #f9fafb; font-size: 15px; line-height: 1.65;">
                                    <p style="margin: 0 0 14px 0; text-align: center; font-size: 16px; color: #fef08a; font-weight: bold;">
                                        ¡Gracias por registrarte en Sabor Caleño!
                                    </p>
                                    <p style="margin: 0 0 24px 0; color: #e5e7eb; text-align: center;">
                                        Ya puedes hacer tus pedidos de pollo asado, apanado y combos especiales en El Carmen de Viboral de forma rápida, directa y sin complicaciones.
                                    </p>

                                    <!-- Botón CTA -->
                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" style="margin: 8px auto 24px auto;">
                                        <tr>
                                            <td align="center" bgcolor="#F4B312" style="border-radius: 8px;">
                                                <a href="{$frontendUrl}" target="_blank" rel="noopener noreferrer"
                                                   style="display: inline-block; padding: 14px 28px; font-size: 15px; font-weight: bold; color: #5B0D18; text-decoration: none; border-radius: 8px;">
                                                    Hacer mi primer pedido
                                                </a>
                                            </td>
                                        </tr>
                                    </table>

                                    <p style="margin: 0; font-size: 12px; line-height: 1.5; color: #d1d5db; text-align: center; word-break: break-word;">
                                        Si el botón no abre, copia y pega este enlace en tu navegador:<br>
                                        <a href="{$frontendUrl}" style="color: #F4B312; text-decoration: underline;">{$frontendUrl}</a>
                                    </p>
                                </td>
                            </tr>

                            <!-- Pie -->
                            <tr>
                                <td align="center" style="padding: 16px 24px; background-color: #490912; border-top: 1px solid rgba(244, 179, 18, 0.35); font-size: 12px; color: #d1d5db;">
                                    <div><strong>Sabor Caleño</strong> · El Carmen de Viboral, Antioquia</div>
                                    <div style="margin-top: 4px; color: #9ca3af;">Este mensaje fue enviado automáticamente tras tu registro.</div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </body>
        </html>
        HTML;

        return $this->from($fromAddress, $fromName)
                    ->subject('¡Bienvenido a Sabor Caleño!')
                    ->html($html);
    }
}