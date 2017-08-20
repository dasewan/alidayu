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

$domain = str_replace("http://", '', $domain);
phpQuery::newDocumentFile('http://' . $domain);
$logo = pq(".logo");
$html = $logo->html();
if (strpos($html, 'yishoudan') || strpos($html, 'wntaoke') || strpos($html, 'logo.png')) {
    echo "yishoudan:" . $domain . "<br/>";
    $row['company'] = 'yishoudan';
}
$domain = 'http://youhuika.top';
$a = file_get_contents($domain);
var_dump($a);
if ($a) {
    echo "unkonwn:" . $domain . "<br/>";
    $row['company'] = 'unkonwn';
} else {
    echo "die:" . $domain . "<br/>";
    $row['company'] = 'die';
}