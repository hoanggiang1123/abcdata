<?php
namespace App\Utils;
use Sunra\PhpSimple\HtmlDomParser;
use Illuminate\Support\Facades\Log;
use App\Models\HighLight;

use Illuminate\Support\Str;

class Curl {

    public $count = 0;

    public $baseUrl = 'https://bongdanet.vn/';

    public function getContent ($url, $refers = 'auto') {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_REFERER, $refers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept:application/json, text/javascript, */*; q=0.01',
            'X-Requested-With:XMLHttpRequest',
            'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36'
        ));
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $data = curl_exec($ch);
        curl_close($ch);

        if ($data) {
            $this->count = 0;
            return $data;
        }

        $this->count++;

        if ($this->count < 3) {
            $this->getContent($url);
        }
        return null;
    }

    public function getFootballSchedule ($url) {

        $content = $this->getContent($url);

        $html = HtmlDomParser::str_get_html($content);

        if ($html && $html->find('.list-schedule-all', 0)) {

            $nodes = $html->find('.list-schedule-all', 0)->find('div[class=title-schedule-league], p[class=item-schedule]');

            if (isset($nodes) && count($nodes) > 0) {

                $results = [];

                $index = '';

                $results['title'] = $html->find('.content-list-live h1', 0) ? $html->find('.content-list-live h1', 0)->text() : '';

                foreach($nodes as $node) {

                    $class = explode(' ', $node->getAttribute('class'));

                    if (in_array('title-schedule-league', $class)) {
                        $index = $node->find('.league-name', 0)->text();
                        $flag = $node->find('.league-name', 0) && $node->find('.league-name', 0)->find('span', 0) ? str_replace('flag flag-', '', $node->find('.league-name', 0)->find('span', 0)->getAttribute('class')) : '';
                        $index = $flag !== '' ? $index .' - ' .$flag : $index;
                    }

                    if (in_array('item-schedule', $class)) {
                        $time = $node->find('.time-info', 0) ? $node->find('.time-info', 0)->text() : '';
                        $home = $node->find('.home-text', 0) ? $node->find('.home-text', 0)->text() : '';
                        $vs = $node->find('.scorelink', 0) ? $node->find('.scorelink', 0)->text() : '';
                        $away = $node->find('.away-text', 0) ? $node->find('.away-text', 0)->text() : '';

                        if ($index) $results['schedule'][$index][] = ['time' => $time, 'home' => $home, 'vs' => $vs, 'away' => $away];
                    }
                }

                return $results;
            }

            return [];

        }

        return [];
    }
    public function getFootballScheduleLeague ($url) {

        $content = $this->getContent($url);

        $html = HtmlDomParser::str_get_html($content);

        $results = [];

        $results['title'] = $html->find('h1.title-news', 0) ? $html->find('h1.title-news', 0)->text() : '';

        $results['rounds'] = [];

        if ($html && $html->find('div[class=zone-league-season-rounds]', 0) && $html->find('div[class=zone-league-season-rounds]', 0)->find('a')) {

            $rounds = $html->find('div[class=zone-league-season-rounds]', 0)->find('a');

            if (isset($rounds) && count($rounds) > 0) {

                foreach ($rounds as $round) {

                    $url = explode('/', $round->getAttribute('data-url'));

                    if (isset($url[2]) && isset($url[3])) $results['rounds'][] = ['league_id' => $url[2], 'round' => $url[3]];
                }
            }

        }

        $results['current_round'] = $html->find('span[id=span-number-round]', 0) ? $html->find('span[id=span-number-round]', 0)->text() : '';

        if ($html && $html->find('table[class=listing-match]', 0)) {

            $nodes =  $html->find('table[class=listing-match]', 0)->find('tr[class="match-row"]');

            if (isset($nodes) && count($nodes) > 0) {



                foreach($nodes as $node) {

                    if(count($node->find('td')) === 1) continue;

                    $time = $node->find('.time-result-text', 0) ? $node->find('.time-result-text', 0)->text() : '';
                    $home = $node->find('.text-right', 0) ? $node->find('.text-right', 0)->text() : '';
                    $vs = $node->find('.result-goal-td', 0) ? $node->find('.result-goal-td', 0)->text() : '';
                    $away = $node->find('.text-left', 0) ? $node->find('.text-left', 0)->text() : '';
                    $round = $node->find('.text-center', 0) ? $node->find('.text-center', 0)->text() : '';

                    $results['schedule'][] = ['time' => $time, 'home' => $home, 'vs' => $vs, 'away' => $away, 'round' => $round];
                }

                return $results;
            }

            return [];

        }

        return [];
    }

    public function getFootballScheduleLeagueAjax ($url) {

        $content = $this->getContent($url);

        $html = HtmlDomParser::str_get_html($content);

        $results = [];

        if ($html && $html->find('table[class=listing-match]', 0)) {

            $nodes =  $html->find('table[class=listing-match]', 0)->find('tr[class="match-row"]');

            if (isset($nodes) && count($nodes) > 0) {



                foreach($nodes as $node) {

                    if(count($node->find('td')) === 1) continue;

                    $time = $node->find('.time-result-text', 0) ? $node->find('.time-result-text', 0)->text() : '';
                    $home = $node->find('.text-right', 0) ? $node->find('.text-right', 0)->text() : '';
                    $vs = $node->find('.result-goal-td', 0) ? $node->find('.result-goal-td', 0)->text() : '';
                    $away = $node->find('.text-left', 0) ? $node->find('.text-left', 0)->text() : '';
                    $round = $node->find('.text-center', 0) ? $node->find('.text-center', 0)->text() : '';

                    $results[] = ['time' => $time, 'home' => $home, 'vs' => $vs, 'away' => $away, 'round' => $round];
                }
            }
        }

        return $results;
    }

    public function getFootballResultLive ($url) {

        $content = $this->getContent($url);

        $html = HtmlDomParser::str_get_html($content);

        if ($html && $html->find('.list-schedule-all', 0)) {

            $nodes = $html->find('.list-schedule-all', 0)->find('div[class=title-schedule-league], p[class=item-schedule]');

            if (isset($nodes) && count($nodes) > 0) {

                $results = [];

                $index = '';

                foreach($nodes as $node) {

                    $class = explode(' ', $node->getAttribute('class'));

                    if (in_array('title-schedule-league', $class)) {
                        $index = $node->find('.league-name', 0)->text();
                        $flag = $node->find('.league-name', 0) && $node->find('.league-name', 0)->find('span', 0) ? str_replace('flag flag-', '', $node->find('.league-name', 0)->find('span', 0)->getAttribute('class')) : '';
                        $index = $flag !== '' ? $index .' - ' .$flag : $index;
                    }

                    if (in_array('item-schedule', $class)) {
                        $time = $node->find('.time-info', 0) ? trim($node->find('.time-info', 0)->text()) : '';
                        $home = $node->find('.home-text', 0) ? $node->find('.home-text', 0)->text() : '';
                        $vs = $node->find('.scorelink', 0) ? $node->find('.scorelink', 0)->text() : '';
                        $away = $node->find('.away-text', 0) ? $node->find('.away-text', 0)->text() : '';
                        $ht = $node->find('span a.scorelink', 0) ? $node->find('span a.scorelink', 0)->text() : '';

                        if ($index) $results[$index][] = ['time' => $time, 'home' => $home, 'vs' => $vs, 'away' => $away, 'ht' => $ht];
                    }
                }

                return ['result' => $results, 'title' => 'Tỷ số trực tuyến hôm nay - Livescore trực tiếp bóng đá'];
            }

            return [];

        }

        return [];
    }

    public function getFootballResult ($url) {

        $content = $this->getContent($url);

        $html = HtmlDomParser::str_get_html($content);

        if ($html && $html->find('.list-schedule-all', 0)) {

            $nodes = $html->find('.list-schedule-all', 0)->find('div[class=title-schedule-league], p[class=item-schedule]');

            if (isset($nodes) && count($nodes) > 0) {

                $results = [];

                $index = '';

                $results['title'] = $html->find('.content-list-live h1', 0) ? $html->find('.content-list-live h1', 0)->text() : '';

                foreach($nodes as $node) {

                    $class = explode(' ', $node->getAttribute('class'));

                    if (in_array('title-schedule-league', $class)) {
                        $index = $node->find('.league-name', 0)->text();
                        $flag = $node->find('.league-name', 0) && $node->find('.league-name', 0)->find('span', 0) ? str_replace('flag flag-', '', $node->find('.league-name', 0)->find('span', 0)->getAttribute('class')) : '';
                        $index = $flag !== '' ? $index .' - ' .$flag : $index;
                    }

                    if (in_array('item-schedule', $class)) {
                        $time = $node->find('.time-info', 0) ? $node->find('.time-info', 0)->text() : '';
                        $home = $node->find('.home-text', 0) ? $node->find('.home-text', 0)->text() : '';
                        $vs = $node->find('.scorelink', 0) ? $node->find('.scorelink', 0)->text() : '';
                        $away = $node->find('.away-text', 0) ? $node->find('.away-text', 0)->text() : '';
                        $ht = $node->find('.asia-away', 0) ? $node->find('.asia-away', 0)->text() : '';
                        $memo = $node->find('span.memo', 0) ? $node->find('span.memo', 0)->text() : '';

                        if ($index) $results['result'][$index][] = ['time' => $time, 'home' => $home, 'vs' => $vs, 'away' => $away, 'ht' => $ht, 'memo' => $memo];
                    }
                }

                return $results;
            }

            return [];

        }

        return [];
    }
    public function getFootballResultLeague ($url) {

        $content = $this->getContent($url);

        $html = HtmlDomParser::str_get_html($content);

        $results = [];

        $results['title'] = $html->find('.content-list-live h1', 0) ? $html->find('.content-list-live h1', 0)->text() : '';

        $results['rounds'] = [];

        if ($html->find('div[class=zone-league-season-rounds]', 0) && $html->find('div[class=zone-league-season-rounds]', 0)->find('a')) {

            $rounds = $html->find('div[class=zone-league-season-rounds]', 0)->find('a');

            if (isset($rounds) && count($rounds) > 0) {

                foreach ($rounds as $round) {

                    $url = explode('/', $round->getAttribute('data-url'));

                    if (isset($url[2]) && isset($url[3])) $results['rounds'][] = ['league_id' => $url[2], 'round' => $url[3]];
                }
            }

        }

        $results['current_round'] = $html->find('span[id=result-round]', 0) ? $html->find('span[id=result-round]', 0)->text() : '';

        if ($html && $html->find('table[class=listing-match]', 0)) {

            $nodes =  $html->find('table[class=listing-match]', 0)->find('tr[class="match-row"]');

            if (isset($nodes) && count($nodes) > 0) {

                foreach($nodes as $node) {

                    if(count($node->find('td')) === 1) continue;

                    if (count($node->find('td')) === 4) {
                        $memo = $node->find('td', 2) ? $node->find('td', 2)->text() : '';

                        $results['result'][] = ['time' => '', 'home' => '', 'vs' => '', 'away' => '', 'round' => '', 'ht' => '', 'memo' => $memo];
                    }
                    else {
                        $time = $node->find('.time-result-text', 0) ? $node->find('.time-result-text', 0)->text() : '';
                        $home = $node->find('.text-right', 0) ? $node->find('.text-right', 0)->text() : '';
                        $vs = $node->find('.result-goal-td', 0) ? $node->find('.result-goal-td', 0)->text() : '';
                        $away = $node->find('td', 4) ? $node->find('td', 4)->text() : '';
                        $round = $node->find('td', 1) ? $node->find('td', 1)->text() : '';

                        $ht = $node->find('td', 5) ? $node->find('td', 5)->text() : '';

                        $results['result'][] = ['time' => $time, 'home' => $home, 'vs' => $vs, 'away' => $away, 'round' => $round, 'ht' => $ht];
                    }
                }

                return $results;
            }

            return [];

        }

        return [];
    }

    public function getFootballResultLeagueAjax ($url) {

        $content = $this->getContent($url);

        $html = HtmlDomParser::str_get_html($content);

        $results = [];

        if ($html && $html->find('table[class=listing-match]', 0)) {

            $nodes =  $html->find('table[class=listing-match]', 0)->find('tr[class="match-row"]');

            if (isset($nodes) && count($nodes) > 0) {

                foreach($nodes as $node) {

                    if(count($node->find('td')) === 1) continue;

                    $time = $node->find('.time-result-text', 0) ? $node->find('.time-result-text', 0)->text() : '';
                    $home = $node->find('.text-right', 0) ? $node->find('.text-right', 0)->text() : '';
                    $vs = $node->find('.result-goal-td', 0) ? $node->find('.result-goal-td', 0)->text() : '';
                    $away = $node->find('td', 4) ? $node->find('td', 4)->text() : '';
                    $round = $node->find('td', 1) ? $node->find('td', 1)->text() : '';

                    $ht = $node->find('td', 5) ? $node->find('td', 5)->text() : '';

                    $results[] = ['time' => $time, 'home' => $home, 'vs' => $vs, 'away' => $away, 'round' => $round, 'ht' => $ht];
                }
            }
        }

        return $results;
    }



    public function getFootballRanking ($url) {

        $content = $this->getContent($url);

        $html = HtmlDomParser::str_get_html($content);

        $results = [];

        if ($html->find('.ul-list-standings', 0)->find('li')) {
            $leagues = $html->find('.ul-list-standings', 0)->find('li');

            if (isset($leagues) && count($leagues) > 0) {

                $results['title'] = $html->find('.content-list-live h1', 0) ? $html->find('.content-list-live h1', 0)->text() : '';

                foreach ($leagues as $league) {

                    $links = $league->find('a', 0) ? explode('/', $league->find('a', 0)->getAttribute('href')) : [];

                    $slug = '';

                    if (count($links) > 0) $slug = isset($links[2]) ? $links[2] : '';

                    $logo = $league->find('.standing-logo img', 0) ? $league->find('.standing-logo img', 0)->getAttribute('src') : '';

                    if ($logo !== '' && startsWith($logo, 'http') === false) $logo = $this->baseUrl . $logo;

                    $league_name = $league->find('.title', 0) ? $league->find('.title', 0)->text() : '';

                    $results['leagues'][] = ['logo' => $logo, 'league_name' => $league_name, 'slug' => $slug];
                }

            }

        }

        return $results;
    }

    public function getFootballRankingLeague ($url) {

        $content = $this->getContent($url);

        $html = HtmlDomParser::str_get_html($content);

        $results = [];

        if ($html->find('#zone-table-home', 0)) {

            if ($html->find('#zone-table-all .standings tr')) {

                $ranks = $html->find('#zone-table-all .standings tr');

                if (isset($ranks) && count($ranks) > 0) {

                    $results['title'] = $html->find('.content-list-live h1', 0) ? $html->find('.content-list-live h1', 0)->text() : '';

                    $results['round'] = $html->find('div[style="margin-top: 20px;"]', 0) ? $html->find('div[style="margin-top: 20px;"]', 0)->text() : '';

                    for ($i = 1; $i < count($ranks); $i++) {
                        $rank = $ranks[$i];
                        if (count($rank->find('td')) === 1) continue;
                        $ranking_num = $rank->find('td', 0) ?  $rank->find('td', 0)->text() : '';
                        $team_name = $rank->find('td', 1) ?  $rank->find('td', 1)->text() : '';
                        $total_match = $rank->find('td', 2) ?  $rank->find('td', 2)->text() : '';
                        $win = $rank->find('td', 3) ?  $rank->find('td', 3)->text() : '';
                        $draw = $rank->find('td', 4) ?  $rank->find('td', 4)->text() : '';
                        $lose = $rank->find('td', 5) ?  $rank->find('td', 5)->text() : '';
                        $goal_win = $rank->find('td', 6) ?  $rank->find('td', 6)->text() : '';
                        $goal_lose = $rank->find('td', 7) ?  $rank->find('td', 7)->text() : '';
                        $difference = $rank->find('td', 8) ?  $rank->find('td', 8)->text() : '';
                        $point = $rank->find('td', 9) ?  $rank->find('td', 9)->text() : '';
                        $performance = $rank->find('td', 10) ?  $rank->find('td', 10)->text() : '';

                        $results['ranks'][] = ['ranking_num' => $ranking_num, 'team_name' => $team_name, 'total_match' => $total_match, 'win' => $win, 'draw' => $draw, 'lose' => $lose, 'goal_win' => $goal_win, 'goal_lose' => $goal_lose, 'difference' => $difference, 'point' => $point, 'performance' => $performance];

                    }
                }

            }
        }
        else {
            $tables =  $html->find('#zone-table-all');

            if (isset($tables) && count($tables) > 0) {

                $results['title'] = $html->find('.content-list-live h1', 0) ? $html->find('.content-list-live h1', 0)->text() : '';

                $results['round'] = '';

                $round_name = '';

                foreach ($tables as $table) {

                    $ranking_tbl = $table->find('table');

                    if (isset($ranking_tbl) && count($ranking_tbl) > 0) {

                        $round_name = $table->find('h4 strong', 0) ? $table->find('h4 strong', 0)->text() : '';

                        foreach ($ranking_tbl as $tbl) {

                            $ranks = $tbl->find('tr');

                            $group_name = '';

                            if (isset($ranks) && count($ranks) > 0) {

                                foreach ($ranks as $rank) {

                                    if ($rank->getAttribute('class') === 'row-header') {

                                        $group_name = $rank->find('td', 0) ?  $rank->find('td', 0)->text() : '';

                                        continue;
                                    }

                                    $ranking_num = $rank->find('td', 0) ?  $rank->find('td', 0)->text() : '';
                                    $team_name = $rank->find('td', 1) ?  $rank->find('td', 1)->text() : '';
                                    $total_match = $rank->find('td', 2) ?  $rank->find('td', 2)->text() : '';
                                    $win = $rank->find('td', 3) ?  $rank->find('td', 3)->text() : '';
                                    $draw = $rank->find('td', 4) ?  $rank->find('td', 4)->text() : '';
                                    $lose = $rank->find('td', 5) ?  $rank->find('td', 5)->text() : '';
                                    $goal_win = $rank->find('td', 6) ?  $rank->find('td', 6)->text() : '';
                                    $goal_lose = $rank->find('td', 7) ?  $rank->find('td', 7)->text() : '';
                                    $difference = $rank->find('td', 8) ?  $rank->find('td', 8)->text() : '';
                                    $point = $rank->find('td', 9) ?  $rank->find('td', 9)->text() : '';

                                    $results['ranks'][$round_name][$group_name][] =  ['ranking_num' => $ranking_num, 'team_name' => $team_name, 'total_match' => $total_match, 'win' => $win, 'draw' => $draw, 'lose' => $lose, 'goal_win' => $goal_win, 'goal_lose' => $goal_lose, 'difference' => $difference, 'point' => $point];

                                }
                            }
                        }
                    }
                }
            }
        }

        return $results;

    }


    public function getTylekeo ($url) {
        $content = $this->getContent($url);

        $html = HtmlDomParser::str_get_html($content);

        $results = [];

        if ($html->find('.content-list-schedule', 0)) {

            $results['title'] = $html->find('.content-list-live h1', 0) ? $html->find('.content-list-live h1', 0)->text() : 'Tỷ lệ kèo bóng đá trực tiếp';

            $oddrows = $html->find('.content-list-schedule', 0)->find('div');

            if (isset($oddrows) && count($oddrows) > 0) {

                $league_name = '';

                foreach ($oddrows as $row) {

                    $classes = explode(' ', $row->getAttribute('class'));

                    if (in_array('clear', $classes)) continue;

                    if (in_array('title-schedule-league', $classes)) {

                        $league_name = $row->find('.league-name', 0) ? $row->find('.league-name', 0)->text() : '';
                        $flag = $row->find('.league-name', 0) && $row->find('.league-name', 0)->find('h2 a span', 0) ? str_replace('flag flag-', '', $row->find('.league-name', 0)->find('h2 a span', 0)->getAttribute('class')) : '';
                        $league_name = $flag !== '' ? $league_name .' - ' .$flag : $league_name;
                    }

                    if (in_array('list-link-of-league', $classes)) {

                        $odds = $row->find('.content-odds-item');

                        if (isset($odds) && count($odds) > 0) {

                            foreach ($odds as $odd) {

                                $time = $odd->find('.time-info', 0) ? $odd->find('.time-info', 0)->text() : '';

                                $strong = '';

                                $home = $odd->find('.club-name span', 0) ?  $odd->find('.club-name span', 0)->text() : '';

                                $away = $odd->find('.club-name span', 1) ?  $odd->find('.club-name span', 1)->text() : '';

                                $teams = $odd->find('.club-name span');

                                if (isset($teams) && count($teams) > 0) {

                                    foreach ($teams as $team) {

                                        if ($team->find('strong', 0)) {

                                            $strong = $team->text();

                                            break;
                                        }
                                    }
                                }

                                $handi_odds = $odd->find('.odds-content', 0) ? $this->getOdds($odd->find('.odds-content', 0)) : [];

                                $over_under = $odd->find('.odds-content', 1) ? $this->getOdds($odd->find('.odds-content', 1)) : [];

                                $euro = $odd->find('.odds-content', 2) ? $this->getOdds($odd->find('.odds-content', 2)) : [];

                                $results['odds'][$league_name][] = [
                                    'time' => $time,
                                    'home' => $home,
                                    'away' => $away,
                                    'strong' => $strong,
                                    'handi_odds' => $handi_odds,
                                    'over_under' => $over_under,
                                    'euro' => $euro,
                                ];

                            }
                        }
                    }
                }
            }
        }

        return $results;

    }

    public function getOdds ($dom) {

        $odds = [];

        $ratios = $dom->find('p');

        if (isset($ratios) && count($ratios) > 0) {

            foreach ($ratios as $ratio) {

                $odds[] = $ratio->find('span', 0) ? $ratio->find('span', 0)->text() : '';

                $odds[] = $ratio->find('span', 1) ? $ratio->find('span', 1)->text() : '';
            }
        }

        return $odds;
    }


    public function parseLiveFootball($content) {

        if ($content) {

            $html = HtmlDomParser::str_get_html($content);

            if ($html && $html->find('ul[class=tb_fixtures tb_matches tb_fixtures-sidebar tb_matches-sidebar]', 0)) {

                $items = $html->find('ul[class=tb_fixtures tb_matches tb_fixtures-sidebar tb_matches-sidebar]', 0)
                                ->find('div[class=match-info well]');

                $liveFootball = [];

                if ($items && count($items) > 0) {

                    $home_name = $home_flag = $away_name = $awy_flag = $live_time = $league_name = '';

                    foreach ($items as $key => $item) {

                        $flag = $item->find('.icon');

                        if ($flag && count($flag) === 2) {
                            $home_flag = $flag[0]->find('img', 0)->getAttribute('src');
                            $away_flag = $flag[1]->find('img', 0)->getAttribute('src');
                        }

                        $team = $item->find('.team');

                        if ($team && count($team) === 2) {
                            $home_name = $team[0]->text();
                            $away_name = $team[1]->text();
                        }

                        $meta = $item->find('.meta', 0);

                        if ($meta) {
                            $time = $meta->find('h4', 0)->text();
                            $timeArr = explode(' ngày ', $time);
                            $live_time = $timeArr[1]. '-' . date("Y") . ' '. $timeArr[1];
                            $league_name = $meta->find('span', 0)->text();
                        }

                        if ($home_flag !== '' && $home_name !== '' && $away_name !== '' && $away_flag !== '' && $live_time !== '' && $league_name !== '') {

                            $title = 'Trận đấu giữa '. $home_name. ' vs '. $away_name. ' ngày '. $live_time;

                            $liveFootball[] = [
                                'home_flag' => $home_flag,
                                'home_name' => $home_name,
                                'away_name' => $away_name,
                                'away_flag' => $away_flag,
                                'live_time' => $live_time,
                                'league_name' => $league_name
                            ];
                        }

                    }
                }

                dd($liveFootball);
            }
        }
    }

    public function getLinkPlay ($url, $refers) {
        $content = $this->getContent($url, $refers);

        $linkPlay = $type = null;

        if ($content) {
            $html = HtmlDomParser::str_get_html($content);

            $iframe = $html->find('.coming_boxs2 iframe', 0);

            if ($iframe) {
                $link = $iframe->getAttribute('src');

                if (strpos($link, 'youtube') === false) {

                    $data = $this->recursiveCurlLinkPlay($link, $url);

                    if (count($data) > 0) {
                        $type = $data['type'];
                        $linkPlay = $data['link_play'];
                    }

                } else {
                    $type = 'embeded';
                    $linkPlay = $link;
                }
            }
        }

        return [
            'link_play' => $linkPlay,
            'type' => $type
        ];
    }

    public function recursiveCurlLinkPlay ($url,  $refers) {

        $return = [];

        $content = $this->getContent($url, $refers);

        if ($content) {

            $html = HtmlDomParser::str_get_html($content);

            if ($html->find('iframe', 0)) {

                $linkStream = $html->find('iframe', 0)->getAttribute('src');
                if (strpos($linkStream, 'youtube')) {

                    $return['type'] = 'embeded';

                    $return['link_play'] = $linkStream;

                } else {

                    $linkStreamArr = explode('?sv=', $linkStream);

                    if (!isset($linkStreamArr[1])) {

                        $linkStreamJoin = $linkStream;

                        if(strpos($linkStream, 'http') === false) $linkStreamJoin = $this->getDomainNamFromUrl($url, $linkStream);

                        $data = $this->recursiveCurlLinkPlay($linkStreamJoin, $url);

                        if (count($data) > 0) return $data;

                        return [];

                    } else {

                        $return['type'] = 'hls';
                        $return['link_play'] = $linkStreamArr[1];

                    }
                }

            }
            else if ($html->find('#live_server', 0)) {
                preg_match('/live\(\'(.*?)\',/i', $html->find('#live_server', 0), $matches );

                if ($matches[1]) {
                    $return['type'] = 'hls';
                    $return['link_play'] = $matches[1];
                }
            }

        }
        return $return;
    }

    public function getDomainNamFromUrl ($url, $path) {
        $parse = parse_url($url);

        return $parse['scheme']. '://' . $parse['host'] . $path;
    }

    public function getHighLightMeta ($url) {

        $data = $linkMeta = [];

        $BASE_URL = 'http://keonhacai.vin/';

        $content = $this->getContent($url);

        if ($content) {
            $html = HtmlDomParser::str_get_html($content);

            if ($html) {
                if ($html->find('.cat-hot-news', 0)) {
                    $as = $html->find('.cat-hot-news', 0)->find('a');
                    if (isset($as) && count($as) > 0) {
                        foreach ($as as $a) {
                            if ($a->find('img', 0)) {
                                $link = $BASE_URL . ($a->getAttribute('href'));
                                $img = $a->find('img', 0)->getAttribute('src');
                                $name = $a->find('img', 0)->getAttribute('alt');
                                $slug = Str::slug($name, '-') . '-'. time();
                                $linkMeta[] = ['link' => $link, 'img' => $img, 'name' => $name, 'slug' => $slug];
                            }
                        }
                    }
                }
                if ($html->find('.lst-news', 0)) {
                    $as = $html->find('.lst-news', 0)->find('a');
                    if (isset($as) && count($as) > 0) {
                        foreach ($as as $a) {
                            if ($a->find('img', 0)) {
                                $link = $BASE_URL . ($a->getAttribute('href'));
                                $img = $a->find('img', 0)->getAttribute('src');
                                $name = $a->find('img', 0)->getAttribute('alt');
                                $slug = Str::slug($name, '-') . '-'. time();
                                $linkMeta[] = ['link' => $link, 'img' => $img, 'name' => $name, 'slug' => $slug];
                            }
                        }
                    }
                }
            }
        }

        if (count($linkMeta) > 0) {
            $data = [];

            foreach($linkMeta as $value) {
                $item = HighLight::where('link', $value['link'])->first();
                if (!$item) $data[] = $value;
            }
        }

        return $data;

    }

    public function getHighLightLink ($data) {
        foreach ($data as $key => $value) {

            $content = $this->getContent($value['link']);

            if ($content) {
                preg_match("/= \['(.*?)',]/", $content, $matches);

                if (count($matches) > 0 && isset($matches[1])) {
                    $data[$key]['link_play'] = $matches[1];
                }
                else {
                    $html = HtmlDomParser::str_get_html($content);
                    if ($html->find('iframe', 0)) {
                        $linkPlay = $html->find('iframe', 0)->getAttribute('src');
                        if (strpos($linkPlay, 'https') === false) {
                            $linkPlay = str_replace('http', 'https', $linkPlay);
                        }
                        $data[$key]['link_play'] = $linkPlay;
                    }
                }
            }
        }

        return array_reverse($data);
    }

    // public function getFootballSchedule ($url) {
    //     $content = $this->getContent($url);
    //     $html = HtmlDomParser::str_get_html($content);

    //     if ($html && $html->find('.match-football', 0)) {
    //         $data = self::parseSchedule($html);
    //         return $data;
    //     }
    //     return [];
    // }

    public function parseSchedule ($html) {

        $leagues = $html->find('.match-football');

        $data = [];

        $league_name = [];

        if (isset($leagues) && count($leagues) > 0) {

            foreach ($leagues as $league) {

                if ($league->find('.football-header h3', 0)) {

                    $league_name = $league->find('.football-header h3', 0)->text();
                }

                $matches = $league->find('.football-match');

                if (isset($matches) && count($matches) > 0) {
                    foreach ($matches as $match) {
                        $time = $date = $round = $home_name = $home_flag = $away_name = $away_flag = $result = '';
                        if ($match->find('.columns-time .time', 0)) $time = $match->find('.columns-time .time', 0)->text();
                        if ($match->find('.columns-time .date', 0)) $date = $match->find('.columns-time .date', 0)->text();
                        if ($match->find('.columns-time .vongbang', 0)) $round = $match->find('.columns-time .vongbang', 0)->text();
                        if ($match->find('.columns-club', 0)) $home_name = $match->find('.columns-club', 0)->text();
                        if ($match->find('.columns-club', 0)->find('img', 0)) $home_flag = $match->find('.columns-club', 0)->find('img', 0)->getAttribute('src');
                        if ($match->find('.columns-club', 1)) $away_name = $match->find('.columns-club', 1)->text();
                        if ($match->find('.columns-club', 1)->find('img', 0)) $away_flag = $match->find('.columns-club', 1)->find('img', 0)->getAttribute('src');

                        if ($match->find('.columns-number', 0)) $result = $match->find('.columns-number', 0)->text();

                        $data[$league_name][] = ['time' => $time, 'date' => $date, 'round' => $round, 'home_name' => $home_name, 'home_flag' => $home_flag, 'away_name' => $away_name, 'away_flag' => $away_flag, 'result' => $result ];

                    }
                }
            }
        }

        return $data;
    }

    public function getFootballRankingOld ($url) {
        $content = $this->getContent($url);
        $html = HtmlDomParser::str_get_html($content);
        if ($html && $html->find('.table-scrol-x', 0)) {
            $data = self::parseResult($html);
            return $data;
        }
        return [];
    }

    public function parseResult ($html) {
        $tables = $html->find('.table-scrol-x');

        $bxh = [];

        $group = '';

        if (isset($tables) && count($tables) >  0) {
            foreach ($tables as $table) {
                $teams = $table->find('tr');

                if (isset($teams) && count ($teams) > 0) {

                    $group = $teams[0]->find('th', 0) ? $teams[0]->find('th', 0)->text() : '';

                    foreach ($teams as $team) {
                        if ($team->find('th', 0)) continue;
                        $stt = $flag = $team_name = $matches = $win = $draw = $loss = $hs = $point = $last_five_matches = '';

                        if ($team->find('td', 0)) $stt = $team->find('td', 0)->text();
                        if ($team->find('td', 1)) $team_name = $team->find('td', 1)->text();
                        if ($team->find('td', 1)->find('img', 0)) $flag = $team->find('td', 1)->find('img', 0)->getAttribute('src');
                        if ($team->find('td', 2)) $matches = $team->find('td', 2)->text();
                        if ($team->find('td', 3)) $win = $team->find('td', 3)->text();
                        if ($team->find('td', 4)) $draw = $team->find('td', 4)->text();
                        if ($team->find('td', 5)) $loss = $team->find('td', 5)->text();
                        if ($team->find('td', 6)) $hs = $team->find('td', 6)->text();
                        if ($team->find('td', 7)) $point = $team->find('td', 7)->text();
                        if ($team->find('td', 8)) $last_five_matches = $team->find('td', 8)->text();

                        $bxh[$group][] = [ 'stt' => $stt, 'flag' => $flag, 'team_name' => $team_name, 'matches' => $matches, 'win' => $win, 'draw' => $draw, 'loss' => $loss, 'hs' => $hs, 'point' => $point, 'last_five_matches' => $last_five_matches ];
                    }
                }
            }
        }

        return $bxh;
    }
}
