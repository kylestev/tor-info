<?php

namespace TorInfo;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Contracts\Cache\Repository as Cache;

class CacheTorIPs extends Command
{

    const URL_EXIT_NODES = 'https://check.torproject.org/exit-addresses';

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
        $client   = new Client();
        $response = $client->get(self::URL_EXIT_NODES);
        return $this->extractIPs($response);
    }

    private function extractIPs($response)
    {
        return collect(explode("\n", $response->getBody()))->filter(function ($line) {
            return starts_with($line, 'ExitAddress');
        })->map(function ($line) {
            return explode(' ', $line)[1];
        });
    }

}
