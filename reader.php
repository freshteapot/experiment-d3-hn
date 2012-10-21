<?php
namespace Freshteapot\experiment\hnd3;

/**
 * Very simple reader
 */
$fileToLogData = __DIR__ . "/hacker_news.log";

$data = file_get_contents($fileToLogData);
$data = trim($data);
$items = explode("\n", $data);

foreach($items as $item) {
    $item = json_decode($item, true);
    $str = <<<STRING
{$item["position"]}) {$item["title"]} -> {$item["score"]}
STRING;
    echo $str . PHP_EOL;
}
