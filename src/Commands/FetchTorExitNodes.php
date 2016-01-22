<?php

namespace TorInfo\Commands;

use GuzzleHttp\Client;

class FetchTorExitNodes
{
    const URL_EXIT_NODES = 'https://check.torproject.org/exit-addresses';

    public function exec()
    {
        return $this->extractIPs($this->retrieveExitNodes());
    }

    private function retrieveExitNodes()
    {
        return (new Client())->get(self::URL_EXIT_NODES);
    }

    private function extractIPs($response)
    {
        return array_map(function ($line) {
            return explode(' ', $line)[1];
        }, array_filter(explode("\n", $response->getBody()), function ($line) {
            return strpos($line, 'ExitAddress') === 0;
        }));
    }
}
