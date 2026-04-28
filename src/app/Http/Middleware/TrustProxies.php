<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Fideloper\Proxy\TrustProxies as Middleware;

class TrustProxies extends Middleware
{
    // Trust all proxies
    protected $proxies = '*';

    // Detect protocol from X-Forwarded headers
    protected $headers = Request::HEADER_X_FORWARDED_ALL;
}
