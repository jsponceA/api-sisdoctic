<?php

namespace App\Console\Commands;

use App\Mail\AlertRecepcionStatus;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckRecepcionesStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-recepciones-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica el estado de las recepciones y envía notificaciones al encargado del proyecto si es necesario';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando verificación de documentos pendientes...');
        Mail::to(["lbellodas@gmail.com","dponce@dezain.com.pe"])->send(new AlertRecepcionStatus());
        // Lógica para verificar el estado de las recepciones
    }
}
