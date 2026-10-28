<?php

namespace App\Console\Commands;

use App\Contracts\WhatsAppGateway;
use App\Support\PhoneNumber;
use Illuminate\Console\Command;
use Throwable;

class SendWhatsAppTest extends Command
{
    protected $signature = 'whatsapp:test {phone : Número internacional, por ejemplo +5491123456789}';

    protected $description = 'Envía un mensaje de prueba por WhatsApp';

    public function handle(WhatsAppGateway $gateway): int
    {
        $phone = PhoneNumber::normalize((string) $this->argument('phone'));

        if (! $phone || ! preg_match(PhoneNumber::E164_REGEX, $phone)) {
            $this->error('Usá un número en formato internacional, por ejemplo +5491123456789.');

            return self::FAILURE;
        }

        try {
            $gateway->send($phone, '✅ Prueba de recordatorios de Solomon.');
        } catch (Throwable $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $this->info('Mensaje de prueba enviado.');

        return self::SUCCESS;
    }
}
