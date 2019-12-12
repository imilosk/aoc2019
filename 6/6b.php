<?php

$file = fopen("6.txt","r");
$nodes = [];

$nodeCounts = [];
$nodePaths = [
                'SAN' => [],
                'YOU' => [],
            ];

function calculateOrbitsRec($nodes, $target, $target_orig, $count = 0) {
    global $nodeCounts;
    global $nodePaths;
    foreach ($nodes as $key => $value) {
        if (in_array($target, $nodes[$key])) {
            if (array_key_exists($key, $nodeCounts)) {
                $count += $nodeCounts[$key] + 1;
                break;
            }
            $count++;
            $nodePaths[$target_orig] += [$key => $count];
            $count += calculateOrbitsRec($nodes, $key, $target_orig, $count);
            break;
        }
    }
    return $count;
}

function calculateOrbits($nodes, $target1, $target2) {
    global $nodePaths;
    calculateOrbitsRec($nodes, $target1, $target1);
    calculateOrbitsRec($nodes, $target2, $target2);
    $intersection = array_intersect(array_keys($nodePaths[$target1]), array_keys($nodePaths[$target2]));
    
    $intersection_nodes = array_filter($nodePaths[$target1], function ($key) use ($intersection){
        return in_array($key, $intersection);
    }, ARRAY_FILTER_USE_KEY);

    $intersection_node = array_keys($intersection_nodes, min($intersection_nodes))[0];  # array('cats')

    $count1 = $nodePaths[$target1][$intersection_node] - 1;
    $count2 = $nodePaths[$target2][$intersection_node] - 1;
    return $count1 + $count2;
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

echo calculateOrbits($nodes, 'YOU', 'SAN');

fclose($file);