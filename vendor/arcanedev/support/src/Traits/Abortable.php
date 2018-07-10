<?php namespace Arcanedev\Support\Traits;

/**
 * Trait     Abortable
 *
 * @package  Arcanedev\Support\Traits
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @deprecated Use directly the abort() helper function instead.
 */
trait Abortable
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Throw Page not found [404].
     *
     * @param  string  $message
     * @param  array   $headers
     */
    protected static function pageNotFound($message = 'Page not Found', array $headers = [])
    {
        return abort(404, $message, $headers);
    }

    /**
     * Throw AccessNotAllowed [403].
     *
     * @param  string  $message
     * @param  array   $headers
     */
    protected static function accessNotAllowed($message = 'Access denied !', array $headers = [])
    {
        return abort(403, $message, $headers);
    }
}
