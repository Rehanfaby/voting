<?php

namespace App\Http\Middleware;

use Closure;

/**
 * Prevent browsers / CDNs from serving a stale HTML shell (wrong MGT version on mobile).
 */
class NoStoreHtml
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $contentType = (string) $response->headers->get('Content-Type', '');
        if (stripos($contentType, 'text/html') !== false || $contentType === '') {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        }

        return $response;
    }
}
