<?php

namespace App\Console\Commands;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Console\Command;

class GenerateApiToken extends Command
{
    protected $signature = 'token:generate {plainToken?}';
    protected $description = 'Generate encrypted API token and save it to .env';

    public function handle()
    {
        $plainToken = $this->argument('plainToken') ?? bin2hex(random_bytes(16));
        $encryptedToken = Crypt::encryptString($plainToken);

        $path = base_path('.env');
        $envContent = file_get_contents($path);

        if (str_contains($envContent, 'API_TOKEN=')) {
            $envContent = preg_replace('/^API_TOKEN=.*$/m', 'API_TOKEN='.$encryptedToken, $envContent);
        } else {
            $envContent .= "\nAPI_TOKEN=".$encryptedToken;
        }

        file_put_contents($path, $envContent);

        $this->info(" API Token generated successfully!");
        $this->line("Plain Token will be used automatically by CMS.");
    }
}
