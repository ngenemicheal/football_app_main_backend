<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class TeamService
{
    private $url = 'https://v3.football.api-sports.io';
    private $league = 399;
    private $season = 2022;
    private $team = 5176;

    private function apiRequest($endpoint, $params = [])
    {
        return Http::withHeaders([
            'x-apisports-key' => env('API_SPORTS_KEY'),
            'x-rapidapi-host' => 'v3.football.api-sports.io',
        ])->get("{$this->url}/{$endpoint}", $params);
    }

    public function getPlayers()
    {
        return Cache::remember("team_players_{$this->team}_{$this->season}", 60 * 60, function () {
            $players_response = $this->apiRequest('players', [
                'league' => $this->league,
                'season' => $this->season,
                'team' => $this->team,
            ]);

            if ($players_response->successful()) {
                $data = $players_response->json();
                return array_map(function ($playerData) {
                    $player = $playerData['player'];
                    $statistics = $playerData['statistics'][0];
                    return [
                        'name' => $player['firstname'] . ' ' . $player['lastname'],
                        'position' => $statistics['games']['position'] ?? 'Unknown',
                        'age' => $player['age'],
                        'nationality' => $player['nationality'],
                        'teamLogo' => $statistics['team']['logo'],
                        'photo' => $player['photo'],
                    ];
                }, $data['response'] ?? []);
            }

            return [];
        });
    }

    public function getPastFixtures()
    {
        return Cache::remember("past_fixtures_{$this->team}_{$this->season}", 60 * 60, function () {
            $fixtures_response = $this->apiRequest('fixtures', [
                'league' => $this->league,
                'season' => $this->season,
                'team' => $this->team,
                'last' => 1,
            ]);

            if ($fixtures_response->successful()) {
                $data = $fixtures_response->json();
                return array_map(function ($fixtureData) {
                    $fixture = $fixtureData['fixture'];
                    $teams = $fixtureData['teams'];
                    $goals = $fixtureData['goals'];

                    return [
                        'home_team' => $teams['home']['name'] ?? 'Unknown',
                        'home_logo' => $teams['home']['logo'] ?? null,
                        'away_team' => $teams['away']['name'] ?? 'Unknown',
                        'away_logo' => $teams['away']['logo'] ?? null,
                        'home_goals' => $goals['home'] ?? 0,
                        'away_goals' => $goals['away'] ?? 0,
                        'date' => $fixture['date'] ?? null,
                        'venue' => $fixture['venue']['name'] ?? 'Unknown',
                        'status' => $fixture['status']['short'] ?? 'Unknown',
                    ];
                }, $data['response'] ?? []);
            }

            return [];
        });
    }

    public function getUpcomingFixtures()
    {
        return Cache::remember("upcoming_fixtures_{$this->team}", 60 * 60, function () {
            $upcoming_fixtures_response = $this->apiRequest('fixtures', [
                'team' => $this->team,
                'next' => 3,
            ]);

            if ($upcoming_fixtures_response->successful()) {
                $data = $upcoming_fixtures_response->json();
                return array_map(function ($fixtureData) {
                    $fixture = $fixtureData['fixture'];
                    $teams = $fixtureData['teams'];

                    return [
                        'home_team' => $teams['home']['name'] ?? 'Unknown',
                        'home_logo' => $teams['home']['logo'] ?? null,
                        'away_team' => $teams['away']['name'] ?? 'Unknown',
                        'away_logo' => $teams['away']['logo'] ?? null,
                        'date' => $fixture['date'] ?? null,
                        'venue' => $fixture['venue']['name'] ?? 'Unknown',
                        'status' => $fixture['status']['short'] ?? 'Unknown',
                    ];
                }, $data['response'] ?? []);
            }

            return [];
        });
    }

    public function getLeagueTable()
    {
        return Cache::remember("league_table_{$this->league}_{$this->season}", 60 * 60, function () {
            $standings_response = $this->apiRequest('standings', [
                'league' => $this->league,
                'season' => $this->season,
            ]);

            if ($standings_response->successful()) {
                $data = $standings_response->json();
                $league = $data['response'][0]['league'] ?? [];
                $standings = $league['standings'][0] ?? [];

                $leagueInfo = [
                    'leagueLogo' => $league['logo'] ?? null,
                    'leagueFlag' => $league['flag'] ?? null,
                    'leagueSeason' => $league['season'] ?? null,
                ];

                return [
                    'leagueInfo' => $leagueInfo,
                    'standings' => array_map(function ($team) {
                        return [
                            'rank' => $team['rank'],
                            'teamName' => $team['team']['name'],
                            'teamLogo' => $team['team']['logo'],
                            'points' => $team['points'],
                            'goalsFor' => $team['all']['goals']['for'],
                            'goalsAgainst' => $team['all']['goals']['against'],
                            'goalsDiff' => $team['goalsDiff'],
                            'form' => $team['form'],
                            'totalGamesPlayed' => $team['all']['played'],
                            'totalGamesWon' => $team['all']['win'],
                            'totalGamesDrawn' => $team['all']['draw'],
                            'totalGamesLost' => $team['all']['lose'],
                        ];
                    }, $standings),
                ];
            }

            return [
                'leagueInfo' => [],
                'standings' => [],
            ];
        });
    }
}
