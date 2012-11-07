<?php
namespace Freshteapot\experiment\hnd3;

class Entry
{
    private $startTime;
    private $finishTime;
    private $range;
    private $id;
    private $title;
    private $intervals;
    private $disableIntervals = false;

    function __construct()
    {

    }

    function setUrl ($url)
    {
        $this->url = $url;
        return $this;
    }

    function setDomain ($domain)
    {
        $this->domain = $domain;
        return $this;
    }

    function setInterval ($raw)
    {
        $this->intervals[] = array(
                "position" => $raw["position"],
                "score" => $raw["score"],
                "timeStamp" => $raw["timeStamp"],
        );

        return $this;
    }

    function addRange ($type, $data)
    {
        if (!isset($this->range[$type])) {
            $this->range[$type] = array();
        }
        $this->range[$type][] = $data;
        return $this;
    }

    function setTitle ($title)
    {
        $this->title = $title;
        return $this;
    }

    function setId ($value)
    {
        $this->id = $value;
        return $this;
    }

    function disableIntervals ($status)
    {
        $this->disableIntervals = $status;
    }

    function __toString ()
    {
        sort($this->range["score"]);
        sort($this->range["position"]);
        sort($this->range["timeIntervals"]);

        $a = array(
                "id" => $this->id,
                "url" => $this->url,
                "domain" => $this->domain,
                "title" => $this->title,
                "score" => array(
                        "high" => end($this->range["score"]),
                        "low" => reset($this->range["score"]),
                        "range" => $this->range["score"],
                ),
                "position" => array(
                        "high" => reset($this->range["position"]),
                        "low" => end($this->range["position"]),
                        "range" => $this->range["position"],
                ),
                "timings"  => array(
                        "high" => end($this->range["timeIntervals"]),
                        "low" => reset($this->range["timeIntervals"]),
                        "range" => $this->range["timeIntervals"],
                        "frontPage" => null,
                ),
                "intervals" => $this->intervals
        );

        if ($this->disableIntervals) {
            unset($a["intervals"]);
        }

        unset($a["timings"]["range"]);
        unset($a["score"]["range"]);
        unset($a["position"]["range"]);

        $a["timings"]["frontPage"] = $a["timings"]["high"] - $a["timings"]["low"];
        return json_encode($a);
    }
}