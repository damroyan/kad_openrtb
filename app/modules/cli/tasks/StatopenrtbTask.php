<?php
namespace Tizer\Modules\Cli\Tasks;

use Tizer\Console;

class StatopenrtbTask extends \Phalcon\Cli\Task
{
    public function parseAction($params)
    {
        $params = $this->getParams($params);

        if (preg_match('@^\d{4}\-\d{2}\-\d{2}$@ui', $params['date'])) {
            Console::WriteLine("Подсчет статы за дату: ".$params['date'], Console::COLOR_GREEN);

            $this->updateStat($params['date']);

        } else {
            if (isset($params['day_back'])) {
                $dayBack = params_int_limit($params['day_back'], 0, 0, 100);
                for ($i = 0; $i <= $dayBack; $i++) {
                    $data = new \DateTime('now');
                    $day = $data->sub(new \DateInterval("P{$i}D"))->format('Y-m-d');

                    Console::WriteLine("Подсчет статы за дату: ".$day, Console::COLOR_GREEN);
                }
            } else {
                Console::WriteLine('Дата не введена или введена некорректно. Ожидаем на вход или date=2019-01-01 или day_back=N где N количество дней назад', Console::COLOR_RED);
            }
        }
    }

    protected function updateStat($date, $file_get_contents = true) {
        Console::WriteLine("Ищем файлеги за: ".$date, Console::COLOR_LIGHT_GREEN);

        $logsDir = $this->config->application->logsDir;

        $dirs = scandir($logsDir);

        if (count($dirs)) {
            foreach ($dirs as $dir) {
                if ($dir!= '.' && $dir!='..' && is_dir($logsDir.$dir)) {
                    Console::WriteLine("Сканим директорию: ".$dir);

                    $file_init = $logsDir.$dir.'/init_'.$date.'.txt';
                    $file_log = $logsDir.$dir.'/log_'.$date.'.txt';

                    $stat = [
                        'stat_openrtb_date'  => $date,
                        'partner_id'         => $dir,
                        'stat_openrtb_init'     => 0,
                        'stat_openrtb_empty_responds' => 0,
                        'stat_openrtb_castrated_responds'    => 0,
                        'stat_openrtb_request'  => 0,
                        'stat_openrtb_imp'      => 0,
                        'stat_openrtb_click'    => 0,
                        'stat_openrtb_money'    => 0.00,
                    ];

                    // стата по запросам к серверу по подбору объяв
                    /**
                     * Пример на дату написани коммента
                     *
                     * Array
                    (
                    [action] => init
                    [partner] => 50bd8c21bfafa6e4e962f6a948b1ef92
                    [session] => fe4b6665-6b1d-463b-a621-54c78f67f21b
                    [client] => ae5e16aa-845e-494e-8803-afbc6b115fb1
                    [referer] => https://inforeactor.ru/156935-dmitrii-kharatyan-predlozhil-vernut-kievu-mikhaila-efremova-vmesto-krymskogo-mosta?utm_medium=referral&utm_source=lentainform&utm_campaign=inforeactor&utm_term=1273258&utm_content=6389567
                    [ip] => 5.1.54.41
                    [source] => https://relap.io/openrtb/2_3/videocap/bid_request
                    [bid_floor] => 0.0001
                    [count_needed] => 4
                    [count_received] => 3
                    [date] => 2018-06-06
                    )
                     */
                    if (is_file($file_init)) {
                        Console::WriteLine("Сканим файл запросов: ".$file_init, Console::COLOR_GREEN);
                        if ($file_get_contents) {
                            $data_arr = $this->csv2array(file_get_contents($file_init), false);

                            $stat['stat_openrtb_init'] = count($data_arr);

                            foreach ($data_arr as $d) {
                                if ($d['count_received'] == 0) {
                                    $stat['stat_openrtb_empty_responds']++;
                                } elseif ($d['count_received'] != $d['count_needed']) {
                                    $stat['stat_openrtb_castrated_responds']++;
                                }
                            }
                        } else {

                            // todo допилить построчное забирание из файла.
                            // Использвоать при пересчете уже не наполняемых файлов

//                            foreach ($data_arr as $item) {
//                                $banner_cpc[$item['banner_id']]     = 1;
//                            }
                        }
                    }

                    // фактическая стата по тому, как объявы открутились
                    /**
                     * Пример на дату написания коммента
                     *
                     *
                     * [6192] => Array
                    (
                    [action] => request
                    [partner] => 50bd8c21bfafa6e4e962f6a948b1ef92
                    [session] => 8e0f1c6e-6ece-4db8-96f6-9f7166c622fa
                    [client] => a1ea82b4-bb4e-4238-ae06-ee8dd1697c64
                    [ip] => 185.94.213.211
                    [price_cpc] => 0.04
                    [price_ecpm] => 0.004
                    [id] => 1528305418:21352:pZjdHw
                    [date] => 2018-06-06
                    )

                     *  [6197] => Array
                    (
                    [action] => imp
                    [banner_id] => 1528305418:20136:I9TdHw
                    [partner] => 50bd8c21bfafa6e4e962f6a948b1ef92
                    [session] => 8e0f1c6e-6ece-4db8-96f6-9f7166c622fa
                    [client] => a1ea82b4-bb4e-4238-ae06-ee8dd1697c64
                    [ip] => 185.94.213.211
                    [ua] => mozilla/5.0 (windows nt 5.1) applewebkit/537.36 (khtml, like gecko) chrome/57.0.2987.137 yabrowser/17.4.1.1026 yowser/2.5 safari/537.36
                    [url] => https://relap.io/openrtb/pixel.gif?event=imp&r=KQAa1mMB_OHXY7f5rsg:I9TdHw:ZsuNJQ:kCCEOg:FAKE0UID:WxgXCg:aHR0cHM6Ly9maXRlcmlhLnJ1L3N0YXRqaS9jaHRvLW51emhuby1lc3QtY2h0b2J5LWtodWRldC12LXRhbGlpLmh0bWw_dXRtX2NvbnRlbnQ9djc4NDg0JnV0bV9jYW1wYWlnbj1maXRlcmlhLXVrci1rYXoxMDY4MCZ1dG1fc291cmNlPWZpdGVyaWEtdWtyLWtheiZ1dG1fbWVkaXVtPXJlbGFwLWFkcm9vbQ:uV7V0w:eyJhMiI6MSwiZ3MiOiJVQSIsImltIjowLCJhYyI6MjAxMzYsInBvcyI6NCwiaXIiOjAsInByIjowLjAwNSwiYWxnIjo3NCwidWciOiJVQTo0MzowTE8tU3J5YmtRYyIsImFwaSI6Im9wZW5ydGIiLCJyciI6MC4wMDV9:2:SH3yig&_s=RW2Gtw
                    [date] => 2018-06-06
                    )
                     */

                    $banner_cpc = [];
                    if (is_file($file_log)) {
                        Console::WriteLine("Сканим файл тизеров: ".$file_log, Console::COLOR_GREEN);
                        if ($file_get_contents) {
                            $data_arr = $this->csv2array(file_get_contents($file_log), false);

                            if (count($data_arr)) {
                                foreach ($data_arr as $item) {
                                    switch($item['action']) {
                                        case 'request':

                                            $stat['stat_openrtb_request']++;
                                            $banner_cpc[$item['id']] = $item['price_cpc'];

                                            break;
                                        case 'imp':
                                            $stat['stat_openrtb_imp']++;
                                            break;
                                        case'click':
                                            $stat['stat_openrtb_click']++;
                                            if (isset($banner_cpc[$item['id']])) {
                                                $stat['stat_openrtb_money'] += $banner_cpc[$item['id']];
                                            }
                                            break;
                                        default:
                                            break;
                                    }
                                }

                               // print_r($banner_cpc);
                            }

                        } else {
                            // todo допилить построчное забирание из файла.
                            // Использвоать при пересчете уже не наполняемых файлов
                        }
                    }

                    print_r($stat);
                }
            }
        }
    }

    /**
     * Парсинг CSV
     *
     * @param $csv
     * @param bool $header
     * @return array
     */
    protected function csv2array($csv, $header = true) {
        $contentArray = array_map(function($value) {
            $value = trim($value);

            if (!$value) {
                return null;
            }

            return str_getcsv(
                $value,
                "\t",
                "\"",
                "\\"
            );
        }, explode("\n", $csv));


        $array = [];
        if (count($contentArray)) {
            foreach ($contentArray as $line) {
                if (trim($line[0]) == '') {
                    continue;
                }
                $array[] = json_decode($line[2], true);

                $index = count($array) - 1;
                $array[$index]['date'] = date('Y-m-d', strtotime(str_replace('[','',str_replace(']','',$line[0]))));

            }
        }

      /*  if ($header) {

            foreach ($contentArray as $line => $item) {
                if (!$line) {
                    $header = $item;
                    continue;
                }

                if (is_array($item)) {
                    foreach ($item as $key => $value) {
                        if (!isset($header[$key])) {
                            continue;
                        }

                        $array[$line][
                        $header[$key]
                        ] = $value;
                    }
                }
            }
        }*/


        return $array;
    }

    protected function getParams($params) {
        $p = [];
        foreach($params as $value) {
            $e = explode('=', trim($value), 2);

            $key = ltrim($e[0], '-');
            $value = trim($e[1], '"');

            if ($key) {
                if ($value) {
                    $p[$key] = $value;
                }
                else {
                    $p[$key] = true;
                }
            }
        }

        return $p;
    }


}