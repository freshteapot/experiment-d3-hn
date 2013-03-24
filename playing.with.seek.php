<?php

$dateTime = new \DateTime('@1364163121');
//echo $dateTime->format(\DateTime::ISO8601);
$today = $dateTime->format('Y-m-d');

$fp = fopen("./hacker_news.log", "r");

fseek($fp, 40505662);
//fseek($fp, 50505662);

$entries = array();
while (!feof($fp)) {
    $line = fgets($fp);
    $entry = json_decode($line, true);
    if (isset($entry['id'])) {
        $now = $dateTime->setTimestamp($entry['timeStamp']);
        if ($dateTime->format('Y-m-d') === $today) {
            $entries[$entry['id']] = $entry;
        }
    }
}
    print_r($entries);
fclose($fp);
echo $today . "\n";
