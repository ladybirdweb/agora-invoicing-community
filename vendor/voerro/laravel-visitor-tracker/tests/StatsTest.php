<?php

namespace Voerro\Laravel\VisitorTracker\Test;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Voerro\Laravel\VisitorTracker\VisitStats;
use Voerro\Laravel\VisitorTracker\Models\Visit;
use Carbon\Carbon;

class StatsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();

        // Fake visits
        $this->visitCount = 50;

        for ($i = 0; $i < $this->visitCount; $i++) {
            $data = [
                'ip' => '127.0.0.1',
                'method' => ['GET', 'POST'][mt_rand(0, 1)],
                'url' => 'http://example.com/',
                'user_agent' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:58.0) Gecko/20100101 Firefox/58.0',
                'os_family' => ['Windows', 'Linux'][mt_rand(0, 1)],
                'os' => ['Windows 10.0', 'Ubuntu'][mt_rand(0, 1)],
                'browser_family' => ['Chrome', 'Firefox'][mt_rand(0, 1)],
                'browser' => ['Chrome 67.0', 'Firefox 58.0'][mt_rand(0, 1)],
                'country' => ['Russia', 'Philippines'][mt_rand(0, 1)],
                'country_code' => ['RU', 'PH'][mt_rand(0, 1)],
                'city' => ['Moscow', 'St. Petersburg', 'Manila', 'Cebu'][mt_rand(0, 3)],
                'lat' => 123.235435,
                'long' => 23.235435,
                'browser_language_family' => ['ru', 'en', 'tl'][mt_rand(0, 1)],
                'browser_language' => ['ru-RU', 'en-US', 'tl-PH'][mt_rand(0, 1)],
                'created_at' => Carbon::now()->subDays($i)
            ];

            Visit::create($data);
        }
    }

    public function testGetAllVisits()
    {
        $visits = VisitStats::query()->visits()->get();

        $this->assertCount($this->visitCount, $visits);
    }

    public function testGetPaginatedVisits()
    {
        $visits = VisitStats::query()->visits()->paginate(20);

        $this->assertCount(20, $visits);

        $this->assertEquals(50, $visits->total());
    }

    public function testGetTotalVisitsCount()
    {
        $count = VisitStats::query()->visits()->count();

        $this->assertEquals(50, $count);
    }

    public function testGetVisitsCountForPeriod()
    {
        $count = VisitStats::query()
            ->visits()
            ->period(
                Carbon::now()->subDays(6)->setTime(0, 0, 0),
                Carbon::now()->setTime(23, 59, 59)
            )
            ->count();

        $this->assertEquals(7, $count);

        $count = VisitStats::query()
            ->visits()
            ->period(
                Carbon::now()->subDays(29)->setTime(0, 0, 0),
                Carbon::now()->setTime(23, 59, 59)
            )
            ->count();

        $this->assertEquals(30, $count);
    }

    public function testGetLoginAttempts()
    {
        Visit::create([
            'ip' => '127.0.0.1',
            'url' => 'localhost/',
            'is_login_attempt' => true,
            'browser_family' => 'Pairpaks',
        ]);

        $results = VisitStats::query()->visits()->loginAttempts()->get();

        $this->assertCount(1, $results);
        $this->assertEquals('Pairpaks', $results[0]->browser_family);
    }

    public function testGetBots()
    {
        Visit::create([
            'ip' => '127.0.0.1',
            'url' => 'localhost/',
            'is_bot' => true,
            'browser_family' => 'Pairpaks',
        ]);

        $results = VisitStats::query()->visits()->bots()->get();

        $this->assertCount(1, $results);
        $this->assertEquals('Pairpaks', $results[0]->browser_family);
    }

    public function testAjaxRequests()
    {
        Visit::create([
            'ip' => '127.0.0.1',
            'url' => 'localhost/',
            'is_ajax' => true,
            'browser_family' => 'Pairpaks',
        ]);

        $results = VisitStats::query()->visits()->ajax()->get();

        $this->assertCount(1, $results);
        $this->assertEquals('Pairpaks', $results[0]->browser_family);
    }

    public function testGetAllRequestsExcept()
    {
        Visit::create([
            'ip' => '127.0.0.1',
            'url' => 'localhost/',
            'is_login_attempt' => true,
            'browser_family' => 'Pairpaks',
        ]);

        Visit::create([
            'ip' => '127.0.0.1',
            'url' => 'localhost/',
            'is_bot' => true,
            'browser_family' => 'Pairpaks',
        ]);

        Visit::create([
            'ip' => '127.0.0.1',
            'url' => 'localhost/',
            'is_ajax' => true,
            'browser_family' => 'Pairpaks',
        ]);

        $this->assertCount(
            52,
            VisitStats::query()->visits()->except(['login_attempts'])->get()
        );

        $this->assertCount(
            51,
            VisitStats::query()->visits()->except(['login_attempts', 'bots'])->get()
        );

        $this->assertCount(
            50,
            VisitStats::query()->visits()->except(['login_attempts', 'bots', 'ajax'])->get()
        );
    }

    public function testGetVisitsGroupedByCountry()
    {
        for ($i = 0; $i < 5; $i++) {
            Visit::create([
                'ip' => '127.0.0.1',
                'url' => 'localhost/',
                'country_code' => 'FI',
            ]);
        }

        for ($i = 0; $i < 6; $i++) {
            Visit::create([
                'ip' => '127.0.0.1',
                'url' => 'localhost/',
                'country_code' => 'EE',
            ]);
        }

        $results = VisitStats::query()->visits()->groupBy('country_code')->get();

        $this->assertCount(4, $results);

        $this->assertEquals(6, $results[0]->visits_count);
        $this->assertEquals(1, $results[0]->visitors_count);

        $this->assertEquals(5, $results[1]->visits_count);
        $this->assertEquals(1, $results[1]->visitors_count);
    }

    public function testGroupVisitsAndGetCountOfGroups()
    {
        $count = VisitStats::query()->visits()->groupBy('country_code')->count();
        // dd($count);

        $this->assertEquals(2, $count);
    }

    public function testGetUniqueVisitors()
    {
        $results = VisitStats::query()->visits()->unique()->get();

        $this->assertCount(1, $results);
        $this->assertEquals(50, $results[0]->visits_count);
    }

    public function testGetUniqueVisitorsCount()
    {
        $count = VisitStats::query()->visits()->unique()->count();

        $this->assertEquals(1, $count);
    }
}
