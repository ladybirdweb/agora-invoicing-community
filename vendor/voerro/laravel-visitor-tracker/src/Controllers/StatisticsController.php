<?php

namespace Voerro\Laravel\VisitorTracker\Controllers;

use Voerro\Laravel\VisitorTracker\Models\Visit;
use Carbon\Carbon;
use Voerro\Laravel\VisitorTracker\Facades\VisitStats;

class StatisticsController
{
    protected function viewSettings()
    {
        return [
            'visitortrackerLayout' => config('visitortracker.layout'),
            'visitortrackerSectionContent' => config('visitortracker.section_content'),
        ];
    }

    public function summary()
    {
        $visits24h = VisitStats::query()->visits()
            ->except(['ajax', 'bots'])
            ->period(Carbon::now()->subHours(24));

        $visits1w = VisitStats::query()->visits()
            ->except(['ajax', 'bots'])
            ->period(Carbon::now()->subDays(7));

        $visits1m = VisitStats::query()->visits()
            ->except(['ajax', 'bots'])
            ->period(Carbon::now()->subMonth(1));

        $visits1y = VisitStats::query()->visits()
            ->except(['ajax', 'bots'])
            ->period(Carbon::now()->subYears(1));

        return view('visitstats::summary', array_merge([
            'lastVisits' => VisitStats::query()
                ->visits()
                ->withUsers()
                ->latest()
                ->paginate(10),

            'visits24h' => $visits24h->count(),
            'unique24h' => $visits24h->unique()->count(),

            'visits1w' => $visits1w->count(),
            'unique1w' => $visits1w->unique()->count(),

            'visits1m' => $visits1m->count(),
            'unique1m' => $visits1m->unique()->count(),

            'visits1y' => $visits1y->count(),
            'unique1y' => $visits1y->unique()->count(),

            'visitsTotal' => Visit::count(),
            'uniqueTotal' => VisitStats::query()->visits()->unique()->count(),
        ], $this->viewSettings()));
    }

    public function allRequests()
    {
        return view('visitstats::visits', array_merge([
            'visits' => VisitStats::query()
                ->visits()
                ->withUsers()
                ->latest()
                ->paginate(config('visitortracker.results_per_page', 15)),
            'visitortrackerSubtitle' => 'All Requests',
        ], $this->viewSettings()));
    }

    public function visits()
    {
        return view('visitstats::visits', array_merge([
            'visits' => VisitStats::query()
                ->visits()
                ->withUsers()
                ->latest()
                ->except(['ajax', 'bots', 'login_attempts'])
                ->paginate(config('visitortracker.results_per_page', 15)),
            'visitortrackerSubtitle' => 'Page Visits',
        ], $this->viewSettings()));
    }

    public function ajaxRequests()
    {
        return view('visitstats::visits', array_merge([
            'visits' => VisitStats::query()
                ->visits()
                ->withUsers()
                ->latest()
                ->ajax()
                ->paginate(config('visitortracker.results_per_page', 15)),
            'visitortrackerSubtitle' => 'Ajax Requests',
        ], $this->viewSettings()));
    }

    public function bots()
    {
        return view('visitstats::visits', array_merge([
            'visits' => VisitStats::query()
                ->visits()
                ->withUsers()
                ->latest()
                ->bots()
                ->paginate(config('visitortracker.results_per_page', 15)),
            'visitortrackerSubtitle' => 'Bots / Crawlers',
        ], $this->viewSettings()));
    }

    public function loginAttempts()
    {
        return view('visitstats::visits', array_merge([
            'visits' => VisitStats::query()
                ->visits()
                ->withUsers()
                ->latest()
                ->loginAttempts()
                ->paginate(config('visitortracker.results_per_page', 15)),
            'visitortrackerSubtitle' => 'Login Attempts',
        ], $this->viewSettings()));
    }

    protected function groupedVisits($view, $groupBy, $subtitle)
    {
        return view("visitstats::{$view}", array_merge([
            'visits' => VisitStats::query()
                ->visits()
                ->withUsers()
                ->except(['ajax', 'bots', 'login_attempts'])
                ->orderBy('visitors_count', 'DESC')
                ->groupBy($groupBy)
                ->paginate(config('visitortracker.results_per_page', 15)),
            'visitortrackerSubtitle' => $subtitle,
        ], $this->viewSettings()));
    }

    public function countries()
    {
        return $this->groupedVisits('countries', 'country_code', 'Countries');
    }

    public function os()
    {
        return $this->groupedVisits('os', 'os_family', 'Operating Systems');
    }

    public function browsers()
    {
        return $this->groupedVisits('browsers', 'browser_family', 'Browsers');
    }

    public function languages()
    {
        return $this->groupedVisits('languages', 'browser_language_family', 'Languages');
    }

    public function unique()
    {
        return $this->groupedVisits('unique', 'ip', 'Unique Visitors');
    }

    public function users()
    {
        return $this->groupedVisits('users', 'user_id', 'Registered Users');
    }

    public function urls()
    {
        return $this->groupedVisits('urls', 'url', 'URLs');
    }
}
