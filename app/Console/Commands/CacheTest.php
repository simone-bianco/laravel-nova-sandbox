<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CacheTest extends Command
{
    /**
     * @var string
     */
    protected $signature = 'cache:test';

    /**
     * @var string
     */
    protected $description = 'Comando per verificare il funzionamento della cache';

    /**
     * @return int
     */
    public function handle()
    {
        Cache::put('test', 'Hello, World!', 600);
        $this->info(Cache::get('test'));
        Cache::forget('test');

        return self::SUCCESS;
    }
}
