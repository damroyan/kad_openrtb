<?php
namespace Tizer\Modules\Cli\Tasks;

use Tizer\Common\Models\StatBanner;
use Tizer\Common\Models\StatOpenrtb;
use Tizer\Console;
use Tizer\Common\Models\Source;

class StatopenrtbTask extends \Phalcon\Cli\Task
{
    /**
     * Парсинг статы и складывание ее в БД
     *
     * @param $params
     * @throws \Exception
     */
    public function parseAction($params)
    {
        $params = $this->getParams($params);

        if (isset($params['date']) && preg_match('@^\d{4}\-\d{2}\-\d{2}$@ui', $params['date'])) {
            Console::WriteLine("Подсчет статы за дату: ".$params['date'], Console::COLOR_GREEN);

            $this->updateStat($params['date']);

        } else {
            if (isset($params['day_back'])) {
                $dayBack = params_int_limit($params['day_back'], 0, 0, 100);
                for ($i = 0; $i <= $dayBack; $i++) {
                    $data = new \DateTime('now');
                    $day = $data->sub(new \DateInterval("P{$i}D"))->format('Y-m-d');

                    Console::WriteLine("Подсчет статы за дату: ".$day, Console::COLOR_GREEN);

                    $this->updateStat($day);
                }
            } else {
                Console::WriteLine('Дата не введена или введена некорректно. Ожидаем на вход или date=2019-01-01 или day_back=N где N количество дней назад', Console::COLOR_RED);
            }


        }
    }

    /**
     * Освобождает память. Логи старше $max_log_days сжимает, логи старше $max_gzip_days удаляет
     */
    public function freeMemoryAction() {
        $max_gzip_days = 60;
        $max_log_days = 3;

        $logsDir = $this->config->application->logsDir;

        $dirs = scandir($logsDir);

        if (count($dirs)) {
            foreach ($dirs as $dir) {
                if ($dir != '.' && $dir != '..' && is_dir($logsDir . $dir)) {
                    Console::WriteLine("Сканим директорию: " . $dir);

                    $scan_dir = $logsDir . $dir . '/';

                    $files = scandir($scan_dir);

                    if (count($files)) {
                        foreach ($files as $file) {
                            if (is_file($scan_dir.$file)){
                                preg_match('@\d{4}\-\d{2}\-\d{2}@ui', $file,$date);

                                $file_ext = explode('.',$file);

                                if (isset($date[0])) {
                                    if ($file_ext[count($file_ext) - 1] == 'txt') {
                                        if (strtotime($date[0]) < time() - 60 * 60 * 24 * $max_log_days) {
                                            Console::WriteLine('Файл требует сжатия:' . $file);

                                            if ($this->gzCompressFile($scan_dir . $file)) {
                                                unlink($scan_dir . $file);
                                            };
                                        }
                                    }


                                    if ($file_ext[count($file_ext) - 1] == 'gz') {
                                        if (strtotime($date[0]) < time() - 60 * 60 * 24 * $max_gzip_days) {
                                            unlink($scan_dir . $file);
                                        }
                                    }
                                }

                            }
                        }
                    }

                 //   $file_init = $logsDir . $dir . '/init_' . $date . '.txt';
                 //   $file_log = $logsDir . $dir . '/log_' . $date . '.txt';
                }
            }
        }
    }

    protected function updateStat($date) {
        Console::WriteLine("Ищем файлеги за: ".$date, Console::COLOR_LIGHT_GREEN);

        $logsDir = $this->config->application->logsDir;

        $dirs = scandir($logsDir);

        if (count($dirs)) {
            foreach ($dirs as $dir) {
                if ($dir!= '.' && $dir!='..' && is_dir($logsDir.$dir)) {
                    Console::WriteLine("Сканим директорию: ".$dir);

                    $file_init = $logsDir.$dir.'/init_'.$date;
                    $file_log = $logsDir.$dir.'/log_'.$date;
                    $file_track = $logsDir.$dir.'/track_'.$date;

                    $stat = [
                        'stat_openrtb_date'  => $date,
                        'partner_id'         => $dir,
                        'stat_openrtb_init'  => 0,
                        'hosts'              => [
                            'undef'     => [
                                'stat_openrtb_init'     => 0,
                                'stat_openrtb_empty_responds' => 0,
                                'stat_openrtb_castrated_responds'    => 0,
                                'stat_openrtb_request'  => 0,
                                'stat_openrtb_imp'      => 0,
                                'stat_openrtb_click'    => 0,
                                'stat_openrtb_money'    => 0.00,
                            ]
                        ],
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
                    $is_txt = false;
                    $is_gz = false;
                    if (($is_txt = is_file($file_init.'.txt')) || ($is_gz = is_file($file_init.'.txt.gz'))) {

                        $fopen = 'fopen';
                        $fgets = 'fgets';
                        $fclose = 'fclose';
                        if ($is_gz) {
                            $fopen = 'gzopen';
                            $fgets = 'gzgets';
                            $fclose = 'gzclose';
                            $file_init = $file_init.'.txt.gz';
                        } else {
                            $file_init = $file_init.'.txt';
                        }

                        Console::WriteLine("Сканим файл запросов: ".$file_init, Console::COLOR_GREEN);

                        $f_init = $fopen($file_init,'r');
                        while (($line = $fgets($f_init, 4096)) !== false) {

                            $d = $this->csv2array($line, false);
                            if ($d[0]) {
                                $d = $d[0];
                            }


                            $stat['stat_openrtb_init']++;

                            $host = 'undef';
                            if (isset($d['referer'])) {
                                $u = parse_url($d['referer']);

                                if (isset($u['host'])) {
                                    $host = $u['host'];
                                    if (!isset($stat['hosts'][$host])) {
                                        $stat['hosts'][$host] = [
                                            'stat_openrtb_init' => 0,
                                            'stat_openrtb_empty_responds' => 0,
                                            'stat_openrtb_castrated_responds' => 0,
                                            'stat_openrtb_request' => 0,
                                            'stat_openrtb_imp' => 0,
                                            'stat_openrtb_click' => 0,
                                            'stat_openrtb_money' => 0.00,
                                        ];
                                    }
                                }

                                $stat['hosts'][$host]['stat_openrtb_init']++;
                            } else {
                                continue;
                            }

                            if ($d['count_received'] == 0) {
                                $stat['hosts'][$host]['stat_openrtb_empty_responds']++;
                            } elseif ($d['count_received'] != $d['count_needed']) {
                                $stat['hosts'][$host]['stat_openrtb_castrated_responds']++;
                            }

                        }
                        $fclose($f_init);
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
                    $banner_stat = [];
                    if (($is_txt = is_file($file_log.'.txt')) || ($is_gz = is_file($file_log.'.txt.gz'))) {

                        $fopen = 'fopen';
                        $fgets = 'fgets';
                        $fclose = 'fclose';
                        if ($is_gz) {
                            $fopen = 'gzopen';
                            $fgets = 'gzgets';
                            $fclose = 'gzclose';
                            $file_log = $file_log.'.txt.gz';
                        } else {
                            $file_log = $file_log.'.txt';
                        }

                        Console::WriteLine("Сканим файл тизеров: ".$file_log, Console::COLOR_GREEN);

                        $f_log = $fopen($file_log,'r');
                        while (($line = $fgets($f_log, 4096)) !== false) {

                            $data_arr = $this->csv2array($line, false);

                            if (count($data_arr)) {
                                $item = $data_arr[0];

                                $host = 'undef';
                                if (isset($item['host'])) {
                                    $host = $item['host'];
                                }

                                switch ($item['action']) {

                                    case 'request':
                                        $stat['hosts'][$host]['stat_openrtb_request']++;
                                        $banner_cpc[$item['id']] = $item['price_cpc'];

                                        if (isset($item['banner_id'])) {
                                            if(!isset($banner_stat[$item['banner_id']])) {
                                                $banner_stat[$item['banner_id']] = [
                                                    'banner_id'     => $item['banner_id'],
                                                    'partner_id'    => $dir,
                                                    'stat_banner_date'  => $date,
                                                    'stat_banner_imp'   => 0,
                                                    'stat_banner_money' => 0,
                                                    'stat_banner_click' => 0,
                                                ];
                                            }

                                            $banner_stat[$item['banner_id']]['stat_banner_text'] = $item['text'];
                                        }
                                        break;

                                    case 'imp':
                                        $stat['hosts'][$host]['stat_openrtb_imp']++;

                                        if (isset($item['banner_id'])) {
                                            if(!isset($banner_stat[$item['banner_id']])) {
                                                $banner_stat[$item['banner_id']] = [
                                                    'banner_id'     => $item['banner_id'],
                                                    'partner_id'    => $dir,
                                                    'stat_banner_date'  => $date,
                                                    'stat_banner_imp'   => 0,
                                                    'stat_banner_money' => 0,
                                                    'stat_banner_click' => 0,
                                                ];
                                            }

                                            $banner_stat[$item['banner_id']]['stat_banner_imp']++;
                                        }
                                        break;

                                    case'click':
                                        $stat['hosts'][$host]['stat_openrtb_click']++;
                                        if (isset($item['banner_id']) && isset($banner_cpc[$item['banner_id']])) {
                                            $stat['hosts'][$host]['stat_openrtb_money'] += $banner_cpc[$item['banner_id']];

                                            if(isset($banner_stat[$item['banner_id']])) {
                                                $banner_stat[$item['banner_id']]['stat_banner_click']++;
                                                $banner_stat[$item['banner_id']]['stat_banner_money'] += $banner_cpc[$item['banner_id']];
                                            }
                                        }
                                        break;

                                    default:
                                        break;
                                }

                                // print_r($banner_cpc);
                            }


                        }
                        $fclose($f_log);
                    }

                    // сохраняем stat_openrtb
                    if (count($stat['hosts'])) {
                        foreach ($stat['hosts'] as $host => $data) {

                            if (!$data['stat_openrtb_init'] || $data['stat_openrtb_init'] == 0) {
                                continue;
                            }

                            $line = StatOpenrtb::findFirst([
                                'conditions'    => 'stat_openrtb_date = :stat_openrtb_date: AND partner_id = :partner_id: AND stat_openrtb_host = :stat_openrtb_host:',
                                'bind'          => [
                                    'stat_openrtb_date'          => $stat['stat_openrtb_date'],
                                    'partner_id'                 => $stat['partner_id'],
                                    'stat_openrtb_host'          => $host,
                                ]
                            ]);

                            if (!$line) {
                                $line = new StatOpenrtb();
                                $line->assign([
                                    'stat_openrtb_date' => $stat['stat_openrtb_date'],
                                    'partner_id'        => $stat['partner_id'],
                                    'stat_openrtb_host' => $host,
                                ]);
                            }

                            $line->assign([
                                'stat_openrtb_init' => $data['stat_openrtb_init'],
                                'stat_openrtb_empty_responds' => $data['stat_openrtb_empty_responds'],
                                'stat_openrtb_castrated_responds' => $data['stat_openrtb_castrated_responds'],
                                'stat_openrtb_request' => $data['stat_openrtb_request'],
                                'stat_openrtb_imp' => $data['stat_openrtb_imp'],
                                'stat_openrtb_click' => $data['stat_openrtb_click'],
                                'stat_openrtb_money' => $data['stat_openrtb_money'],
                            ]);

                            $line->save();
                        }
                    }

                    //сохраняем стату по баннерам
                    if(count($banner_stat) > 0) {
                        foreach ($banner_stat as $b) {
                            $banner = StatBanner::findFirst([
                                'conditions'    => 'stat_banner_date=:stat_banner_date: AND banner_id = :banner_id: AND  partner_id = :partner_id:',
                                'bind'          => [
                                    'stat_banner_date'  => $date,
                                    'banner_id'         => $b['banner_id'],
                                    'partner_id'        => $b['partner_id'],
                                ]
                            ]);

                            if (!$banner) {
                                $banner = new StatBanner();
                                $banner->assign([
                                    'stat_banner_date'  => $date,
                                    'banner_id'         => $b['banner_id'],
                                    'partner_id'        => $b['partner_id'],
                                ]);

                            }

                            $banner->assign([
                                'stat_banner_imp'   => $b['stat_banner_imp'],
                                'stat_banner_click' => $b['stat_banner_click'],
                                'stat_banner_money' => $b['stat_banner_money'],
                            ]);

                            $banner->save();

                        }
                    }


                    // вынимаем общую стату по хостам
                    if ($is_txt = is_file($file_track.'.txt')) {

                        $fopen = 'fopen';
                        $fgets = 'fgets';
                        $fclose = 'fclose';
                        $file_track = $file_track.'.txt';
                        $traffic = [];

                        $f_track = $fopen($file_track,'r');
                        while (($line = $fgets($f_track, 4096)) !== false) {


                            $data_arr = $this->csv2array($line, false);

                            if (count($data_arr)) {
                                $item = $data_arr[0];

                                $host = 'undef';
                                if (isset($item['host'])) {
                                    $host = $item['host'];
                                }

                                if (!isset($traffic[$host])) {
                                    $traffic[$host] = [
                                        'source_date'       => $date,
                                        'source_partner'    => $dir,
                                        'source_host'       => $host,
                                        'source_traffic'    => 0,
                                    ];
                                }

                                $traffic[$host]['source_traffic']++;
                            }
                        }
                        $fclose($f_track);


                        if (count($traffic) > 0) {
                            foreach ($traffic as $host) {

                                $db_line = Source::findFirst([
                                    'conditions'    => 'source_date = :source_date: AND source_host = :source_host:',
                                    'bind'          => [
                                        'source_date'   => $host['source_date'],
                                        'source_host'          => $host['source_host'],
                                    ]
                                ]);

                                if (!$db_line) {
                                    $db_line = new Source();
                                }

                                $db_line->assign($host);
                                $db_line->save();

                            }
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
                if (trim($line[0]) == '' || !isset($line[2])) {
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

    protected function gzCompressFile($source, $level = 9){
        $dest = $source . '.gz';
        $mode = 'wb' . $level;
        $error = false;
        if ($fp_out = gzopen($dest, $mode)) {
            if ($fp_in = fopen($source,'rb')) {
                while (!feof($fp_in))
                    gzwrite($fp_out, fread($fp_in, 1024 * 512));
                fclose($fp_in);
            } else {
                $error = true;
            }
            gzclose($fp_out);
        } else {
            $error = true;
        }
        if ($error)
            return false;
        else
            return true;
    }




}
