<?php
require('phpQuery/phpQuery.php');

// INITIALIZE IT
// phpQuery::newDocumentHTML($markup);
// phpQuery::newDocumentXML();
// phpQuery::newDocumentFileXHTML('test.html');
// phpQuery::newDocumentFilePHP('test.php');
// phpQuery::newDocument('test.xml', 'application/rss+xml');
// this one defaults to text/html in utf8
error_reporting(0);


$doc = phpQuery::newDocument('<div/>');
set_time_limit(0);


$con = mysql_connect("139.224.227.186", "sphinx", "sphinx");
if (!$con) {
    die('Could not connect: ' . mysql_error());
}

mysql_select_db("fabu", $con);
$sql = "select cup.id as id,cup.update_time as last_update_time,cup.domain as domain,cup.myurlprev as myurlprev,cup.pid,from_unixtime(cup.session_time) as session_time,from_unixtime(cu.add_time) as add_time,u.mobile,u.qq as qq1,cu.qq as qq2,u.email as email from ftxia_cms_user_pids as cup left join ftxia_cms_user as cu on cup.parentids=cu.parentid LEFT JOIN ftxia_user as u on cup.parentids=u.id  order by cup.id desc limit 0,4000;";

$result = mysql_query($sql);
// session_time<unix_timestamp(now())
// where session_time<unix_timestamp(now()) limit 0,500;
$count = 1;
$count2 = 1;
$csv_arr[] = 'id*domain*company*mobile*qq*last_update_time*session_time*email';
while ($row = mysql_fetch_array($result)) {
    $domain = $row['domain'];
    $mail = $row['qq1'] ?: $row['qq2'];
    // echo $domain;
    if ($domain) {
        $domain = str_replace("http://", '', $domain);
        phpQuery::newDocumentFile('http://' . $domain);
        $logo = pq(".logo");
        $html = $logo->html();
        if (strpos($html, 'yishoudan') || strpos($html, 'wntaoke') || strpos($html, 'logo.png')) {
            echo "yishoudan:" . $domain . "<br/>";
            $row['company'] = 'yishoudan';
        } else {
            $logo = pq(".juan-logo");
            $html = $logo->html();
            if (strpos($html, 'yishoudan') || strpos($html, 'wntaoke') || strpos($html, 'logo.png')) {
                echo "yishoudan:" . $domain . "<br/>";
                $row['company'] = 'yishoudan';
            } else {
                $logo = pq(".site_logo");
                $html = $logo->html();
                if (strpos($html, 'yishoudan') || strpos($html, 'wntaoke') || strpos($html, 'logo.png')) {
                    echo "yishoudan:" . $domain . "<br/>";
                    $row['company'] = 'yishoudan';
                } else {
                    $logo = pq("#search");
                    $html = $logo->html();
                    if (strpos($html, 'kw')) {
                        echo "dataoke:" . $domain . "<br/>";
                        $row['company'] = 'dataoke';
                    } else {
                        $logo = pq("#showList");
                        $html = $logo->html();
                        if (strpos($html, 'dtk')) {
                            echo "dataoke:" . $domain . "<br/>";
                            $row['company'] = 'dataoke';
                        } else {
                            $logo = pq("#app");
                            $html = $logo->html();
                            if (strpos($html, 'transition')) {
                                echo "lanlan:" . $domain . "<br/>";
                                $row['company'] = 'lanlan';
                            } else {
                                $logo = pq(".search_input");
                                $html = $logo->html();
                                if (strpos($html, 'txt_focus')) {
                                    echo "yishoudan:" . $domain . "<br/>";
                                    $row['company'] = 'yishoudan';
                                }else{
                                    $logo = pq(".search-area");
                                    $html = $logo->html();
                                    if (strpos($html, 'off')) {
                                        echo "yishoudan:" . $domain . "<br/>";
                                        $row['company'] = 'yishoudan';
                                    }else{
                                        $logo = pq(".search-area");
                                        $html = $logo->html();
                                        if (strpos($html, 'off')) {
                                            echo "yishoudan:" . $domain . "<br/>";
                                            $row['company'] = 'yishoudan';
                                        }else{
                                            $logo = pq("#showList");
                                            $html = $logo->html();
                                            if (strpos($html, 'search')) {
                                                echo "dataoke:" . $domain . "<br/>";
                                                $row['company'] = 'dataoke';
                                            }else{
                                                $a = file_get_contents($domain);
                                                if ($a) {
                                                    echo "unkonwn:" . $domain . "<br/>";
                                                    $row['company'] = 'unkonwn';
                                                } else {
                                                    echo "die:" . $domain . "<br/>";
                                                    $row['company'] = 'die';
                                                }
                                            }
                                        }
                                    }
                                }

                            }


                        }
                    }

                }
            }
        }
        $csv_arr[] = $row['id'] . '*' . $row['domain'] . '*' . $row['company'] . '*' . $row['mobile'] . '*' . $row['domain'] . '*' . $mail . '*' . $row['last_update_time'] . '*' . $row['session_time'] . '*' . $row['email'];

    }
}
$file = fopen("domain.csv", "w");
foreach ($csv_arr as $line) {
    fputcsv($file, explode('*', $line));
}

fclose($file);