<?php

namespace Sportmonks\SoccerAPI;

use GuzzleHttp\Client;
use Sportmonks\SoccerAPI\Exceptions\ApiRequestException;

class SoccerAPIClient {

    /* @var $client Client */
    protected $client;

    protected $apiToken;
    protected $withoutData;
    protected $include = [];
    protected $perPage = 300;
    protected $page = 1;
    
    public function __construct($api_token)
    {
        $options = [
            'base_uri'  => 'https://soccer.sportmonks.com/api/v2.0/',
            'verify'    =>  true,
        ];
        $this->client = new Client($options);

        $this->apiToken = $api_token;
        if(empty($this->apiToken))
        {
            throw new \InvalidArgumentException('No API token set');
        }
        // stripped of it. Set to true to get body within 'data'
        $this->withoutData = false;
    }

    protected function call($url, $hasData = false)
    {
        $query = [
            'api_token' => $this->apiToken,
            'per_page' => $this->perPage,
            'page' => $this->page
        ];
        if(count($this->include))
        {
            $query['include'] = $this->include;
        }
        $response = $this->client->get($url, ['query' => $query]);

        $body = json_decode($response->getBody()->getContents());

        if(property_exists($body, 'error'))
        {
            if(is_object($body->error))
            {
                throw new ApiRequestException($body->error->message, $body->error->code);
            }
            else
            {
                throw new ApiRequestException($body->error, 500);
            }

            return $response;
        }

        if($hasData && $this->withoutData)
        {
            return $body->data;
        }

        return $body;
    }

    protected function callData($url)
    {
        return $this->call($url, true);
    }

    /**
     * @param $include - string or array of relations to include with the query
     */
    public function setInclude($include)
    {
        if(is_array($include))
        {
            $include = implode(',', $include);
        }

        $this->include = $include;

        return $this;
    }

    /**
     * @param $perPage - int of per_page limit data in request
     */
    public function setPerPage($perPage)
    {
        $this->perPage = $perPage;

        return $this;
    }

    /**
     * @param $page - int of requested page
     */
    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    // Bookmarks
    public function allBookmarks()
    {
        return $this->callData('bookmakers');
    }

    //Commentary
    public function CommentaryByMatchId($matchId)
    {
        return $this->callData('commentaries/fixture/' . $matchId);
    }

    //Continent
    public function allContinents()
    {
        return $this->callData('continents/');
    }

    public function continentById($continentId)
    {
        return $this->call('continents/' . $continentId);
    }

    //Countries
    public function allCountry()
    {
        return $this->callData('countries');
    }

    public function countryById($countryId)
    {
        return $this->call('countries/' . $countryId);
    }

    //Fixtures

    public function fixturesBetweenDates($fromDate,$toDate)
    {
        if($fromDate instanceof Carbon)
        {
            $fromDate = $fromDate->format('Y-m-d');
        }

        if($toDate instanceof Carbon)
        {
            $toDate = $toDate->format('Y-m-d');
        }

        return $this->callData('fixtures/between/' . $fromDate . '/' .$toDate);
    }

    public function fixturesByDate($date)
    {
        if($date instanceof Carbon)
        {
            $date = $date->format('Y-m-d');
        }

        return $this->callData('fixtures/date/' . $date);
    }

    public function fixturesByMatchId($id)
    {
        return $this->call('fixtures/' . $id);
    }

    public function fixturesHeadToHead($firstTeamId,$secondTeamId)
    {
        return $this->call('head2head/' . $firstTeamId . '/' . $secondTeamId);
    }
    
    // head2head
    public function head2headBetweenTeams($team1Id,$team2Id)
    {
        return $this->callData('head2head/' . $team1Id . '/' . $team2Id);
    }

    // League Calls
    public function allLeagues()
    {
        return $this->callData('leagues');
    }

    public function leagueById($competitionId)
    {
        return $this->call('leagues/' . $competitionId);
    }

    //Livescore

    public function livescoresToday()
    {
        return $this->callData('livescores');
    }

    public function livescoresNow()
    {
        return $this->callData('livescores/now');
    }

    //Odds
    public function oddsByMatchId($matchId)
    {
        return $this->callData('odds/fixture/' . $matchId);
    }

    public function oddsByMatchAndBookmakerId($matchId, $bookmakerId)
    {
        return $this->callData('odds/fixture/' . $matchId . '/bookmaker/' . $bookmakerId);
    }

    public function oddsInplayByMatchId($matchId)
    {

        return $this->callData('odds/inplay/fixture/' . $matchId );
    }

    //Player
    public function playerById($playerId)
    {
        return $this->callData('players/' . $playerId);
    }

    //Round
    public function roundById($roundId)
    {
        return $this->call('rounds/' . $roundId);
    }

    public function roundBySeasonId($seasonId)
    {
        return $this->call('rounds/season/' . $seasonId);
    }

    //Seasons

    public function allSeasons()
    {
        return $this->callData('seasons');
    }

    public function seasonById($seasonId)
    {
        return $this->call('seasons/' . $seasonId);
    }

    //Standings
    public function standingsBySeasonId($seasonId)
    {
        return $this->callData('standings/season/' . $seasonId);
    }

    //Teams
    public function allTeamsBySeasonId($seasonId)
    {
        return $this->callData('teams/season/' . $seasonId);
    }

    public function teamById($teamId)
    {
        return $this->call('teams/' . $teamId);
    }

    //Topscore
    public function topScorerBySeasonId($seasonId)
    {
        return $this->callData('topscorers/season/' . $seasonId);
    }

    //TvStation
    public function tvStationsbyMatchId($id)
    {
        return $this->call('tvstations/fixture/' . $id);
    }

    //Venue
    public function venueById($venueId)
    {
        return $this->call('venues/' . $venueId);
    }

    //Videos
    public function allVideos()
    {
        return $this->callData('highlights/');
    }

    public function videoByMatchId($matchId)
    {
        return $this->callData('highlights/fixture/' . $matchId);
    }

}
