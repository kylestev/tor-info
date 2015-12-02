<?php

namespace TorInfo\Support\Laravel\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Cache\Repository as Cache;
use TorInfo\Commands\FetchTorExitNodes;

class CacheTorIPs extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tor:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Caches known Tor exit nodes.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Cache $cache)
    {
        $ips = $this->fetchExitNodeIPs()->map(function ($ip) use ($cache) {
            $cache->tags(config('torinfo.caching.tags'))->put($ip, true, config('torinfo.caching.expiry'));
            return $ip;
        });
    }

    private function fetchExitNodeIPs()
    {
        return collect((new FetchTorExitNodes)->exec());
    }

}
