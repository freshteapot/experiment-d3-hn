<?php
namespace Freshteapot\experiment\hnd3;


/**
 * Very simple reader
 */
$fileToLogData = __DIR__ . "/hacker_news.log";

$data = file_get_contents($fileToLogData);
$data = trim($data);
$items = explode("\n", $data);

$dataPoints = array();
foreach($items as $item) {
    $item = json_decode($item, true);

    $id = $item["id"];
    if (!isset($dataPoints[$id])) {
        $dataPoints[$id] = array();
    }
    $dataPoints[$id][] = $item;
}

//Quick hack. @TODO use psr-0
include "./Entry.php";
foreach($dataPoints as $key => $data) {
    $a = new \Freshteapot\experiment\hnd3\Entry();
    $a->setId($key);
    foreach($data as $item) {
        $a
            ->setUrl($item["url"])
            ->setDomain($item["domain"])
            ->setTitle($item["title"])
            ->addRange("score", $item["score"])
            ->addRange("position", $item["position"])
            ->addRange("timeIntervals", $item["timeStamp"])
            ->setInterval($item)
        ;
    }
    $a->disableIntervals(true);
    $dataPoints[$key] = (string)$a;
}

/*
 * As im taking advantage of the toString in the class.
 * I fake the array on the outside.
 *
 * To make the output pretty
 * | python -mjson.tool
 */
echo "[" . implode(",", $dataPoints) . "]";