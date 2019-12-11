<?php

$file = fopen("6.txt","r");
$nodes = [];

$nodeCounts = [];

function calculateOrbitsRec($nodes, $target, $count = 0) {
    global $nodeCounts;
    foreach ($nodes as $key => $value) {
        if (in_array($target, $nodes[$key])) {
            if (array_key_exists($key, $nodeCounts)) {
                $count += $nodeCounts[$key] + 1;
                break;
            }
            $count++;
            $count += calculateOrbitsRec($nodes, $key);
            $nodeCounts[$target] = $count;
            break;
        }
    }
    return $count;
}

function calculateOrbits($nodes, $count = 0) {
    foreach ($nodes as $key => $value) {
        $count += calculateOrbitsRec($nodes, $key);
    }
    return $count;
}

while(!feof($file)) {
    $line = fgets($file);
    $arr = explode(')', $line);
    $node1 = trim($arr[0]);
    $node2 = trim($arr[1]);

    if (!isset($nodes[$node2]))
        $nodes[$node2] = [];
    if (!isset($nodes[$node1]))
        $nodes[$node1] = [$node2];
    else
        array_push($nodes[$node1], $node2);
}

echo calculateOrbits($nodes);

fclose($file);