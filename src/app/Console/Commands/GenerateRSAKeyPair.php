<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use OpenSSL;

class GenerateRSAKeyPair extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:generate-keys';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate key pair to exchange securely with agents';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Dossier où les clés seront sauvegardées
        $directory = storage_path('app/private/keys');
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        // Génération de la clé privée
        $privateKeyResource = openssl_pkey_new([
            "private_key_bits" => 4096,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ]);

        // Génération de la clé publique correspondante
        $publicKeyDetails = openssl_pkey_get_details($privateKeyResource);
        $publicKey = $publicKeyDetails["key"];

        // Export de la clé privée au format PEM
        openssl_pkey_export($privateKeyResource, $privateKeyPem);

        // Sauvegarde des clés dans le dossier
        Storage::put('private/keys/agent_exchange', $privateKeyPem);
        Storage::put('private/keys/agent_exchange.pub', $publicKey);

        $this->info('Les clés ont été générées et enregistrées.');
    }
}
