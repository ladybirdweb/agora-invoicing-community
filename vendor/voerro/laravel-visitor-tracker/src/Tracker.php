<?php

namespace Voerro\Laravel\VisitorTracker;

use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\OperatingSystem;
use Voerro\Laravel\VisitorTracker\Models\Visit;
use Voerro\Laravel\VisitorTracker\Jobs\GetGeoipData;

class Tracker
{
    protected static $botBrowsers = [
        'curl',
        'python-requests',
        'python-urllib',
        'wget',
        'unk',
        'perl',
        'go-http-client',
    ];

    /**
     * Records a visit/request based on the request()
     *
     * @param string $agent
     * @return Voerro\Laravel\VisitorTracker\Models\Visit
     */
    public static function recordVisit($agent = null)
    {
        if (!self::shouldTrackUser()) {
            return;
        }

        if (!self::shouldTrackAuthenticatedUser()) {
            return;
        }

        $data = self::getVisitData($agent ?: request()->userAgent());

        // Determine if the request is a login attempt
        if (request()->route()
        && '/' . request()->route()->uri == config('visitortracker.login_attempt.url')
        && $data['method'] == config('visitortracker.login_attempt.method')
        && $data['is_ajax'] == config('visitortracker.login_attempt.is_ajax')) {
            $data['is_login_attempt'] = true;
        }

        if (!self::shouldRecordRequest($data)) {
            return;
        }

        $visit = Visit::create($data);

        GetGeoipData::dispatch($visit);

        return $visit;
    }

    /**
     * Determine if the user should be tracked based on whether they are
     * authenticated or not
     *
     * @return boolean
     */
    protected static function shouldTrackUser()
    {
        if (config('visitortracker.dont_track_authenticated_users') && auth()->check()) {
            return false;
        }

        if (config('visitortracker.dont_track_anonymous_users') && !auth()->check()) {
            return false;
        }

        return true;
    }

    /**
     * Determine if the authenticated user should be tracked
     *
     * @return boolean
     */
    protected static function shouldTrackAuthenticatedUser()
    {
        if (auth()->check()) {
            foreach (config('visitortracker.dont_track_users') as $fields) {
                $conditionsMet = 0;
                foreach ($fields as $field => $value) {
                    if (auth()->user()->{$field} == $value) {
                        $conditionsMet++;
                    }
                }

                if ($conditionsMet == count($fields)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Determine if the request/visit should be recorded
     *
     * @return boolean
     */
    protected static function shouldRecordRequest($data)
    {
        foreach (config('visitortracker.dont_record') as $fields) {
            $conditionsMet = 0;
            foreach ($fields as $field => $value) {
                if ($data[$field] == $value) {
                    $conditionsMet++;
                }
            }

            if ($conditionsMet == count($fields)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Collect and form into array data about the current visit based on the request() and UA
     *
     * @param string $agent User agent
     * @return array
     */
    protected static function getVisitData($agent)
    {
        $dd = new DeviceDetector($agent);
        $dd->parse();

        // Browser
        $browser = $dd->getClient('version')
            ? $dd->getClient('name') . ' ' . $dd->getClient('version')
            : $dd->getClient('name');

        $browserFamily = str_replace(' ', '-', strtolower($dd->getClient('name')));

        // Browser language
        preg_match_all('/([a-z]{2})-[A-Z]{2}/', request()->server('HTTP_ACCEPT_LANGUAGE'), $matches);

        $lang = count($matches) && count($matches[0]) ? $matches[0][0] : '';
        $langFamily = count($matches) && count($matches[1]) ? $matches[1][0] : '';

        // OS
        $os = $dd->getOs('version')
            ? $dd->getOs('name') . ' ' . $dd->getOs('version')
            : $dd->getOs('name');

        $osFamily = str_replace(
            ' ',
            '-',
            strtolower(OperatingSystem::getOsFamily($dd->getOs('short_name')))
        );
        $osFamily = $osFamily == 'gnu/linux' ? 'linux' : $osFamily;

        // "UNK UNK" browser and OS
        $browserFamily = ($browser == 'UNK UNK') ? 'unk' : $browserFamily;
        $osFamily = ($os == 'UNK UNK') ? 'unk' : $osFamily;

        // Whether it's a bot
        $bot = null;
        $isBot = $dd->isBot();
        if ($isBot) {
            $bot = $dd->getBot();
        } else {
            if (in_array($browserFamily, static::$botBrowsers)) {
                $isBot = true;
                $bot = ['name' => $browserFamily];
            }
        }

        return [
            'user_id' => auth()->check() ? auth()->id() : null,
            'ip' => request()->ip(),
            'method' => request()->method(),
            'url' => request()->fullUrl(),
            'referer' => request()->headers->get('referer'),
            'is_ajax' => request()->ajax(),

            'user_agent' => $agent,
            'is_mobile' => $dd->isMobile(),
            'is_desktop' => $dd->isDesktop(),
            'is_bot' => $isBot,
            'bot' => $bot ? $bot['name'] : null,
            'os' => $os,
            'os_family' => $osFamily,
            'browser_family' => $browserFamily,
            'browser' => $browser,

            'browser_language_family' => $langFamily,
            'browser_language' => $lang,
        ];
    }
}
