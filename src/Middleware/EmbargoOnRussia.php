<?php

namespace Pretzelhands\Embargoed\Middleware;

use Closure;
use Illuminate\Http\Request;
use GeoIp2\Database\Reader;

class EmbargoOnRussia
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws Reader\InvalidDatabaseException
     */
    public function handle($request, Closure $next)
    {
        $reader = new Reader(__DIR__ . '/resources/data/geoip.mmdb');
        $record = $reader->country($request->ip());

        // Go Ukraine 🇺🇦
        if ($record && $record->country->isoCode === 'RU') {
            return response()->view('embargoed', [], 403);
        }

        return $next($request);
    }
}
