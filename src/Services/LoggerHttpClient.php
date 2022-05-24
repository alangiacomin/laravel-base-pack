<?php

namespace Alangiacomin\LaravelBasePack\Services;

use Http\Client\Common\Plugin\AddHostPlugin;
use Http\Client\Common\PluginClient;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;

class LoggerHttpClient
{
    /**
     * @var string Uri
     */
    private string $uri;

    /**
     * Constructor
     *
     * @param  string|null  $uri  Uri
     */
    public function __construct(string $uri = null)
    {
        $this->uri = $uri ?? config('basepack.log.uri');
    }

    /**
     * Get PSR18 client standard
     *
     * @return PluginClient
     */
    public function Psr18(): PluginClient
    {
        $httpClient = Psr18ClientDiscovery::find();
        $plugins = array();
        $uri = Psr17FactoryDiscovery::findUriFactory()->createUri($this->uri);
        $plugins[] = new AddHostPlugin($uri);
        return new PluginClient($httpClient, $plugins);
    }
}
