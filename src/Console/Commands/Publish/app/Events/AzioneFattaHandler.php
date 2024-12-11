<?php

namespace App\Events;

use AlanGiacomin\LaravelBasePack\Events\EventHandler;
use App\Commands\EseguiAzione;
use App\Notifications\InvoicePaid;
use Illuminate\Support\Facades\Log;

class AzioneFattaHandler extends EventHandler
{
    public AzioneFatta $event;

    public function execute(): void
    {
        Log::alert('STO NOTIFICANDO L\'ESITO');
    }
}
