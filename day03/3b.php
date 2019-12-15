<?php

function getPoints($wire) {
    $path = array();
    $current_point = [0, 0];
    foreach ($wire as $step) {
        $direction = substr($step, 0, 1);
        $amount = intval(substr($step, 1));
    
        for ($i = 0; $i < $amount; $i++) {
            switch ($direction) {
                case 'U':
                    $current_point[1] += 1;
                    break;
                case 'D':
                    $current_point[1] -= 1;
                    break;
                case 'L':
                    $current_point[0] -= 1;
                    break;
                case 'R':
                    $current_point[0] += 1;
                    break;
            }
            array_push($path, implode(',', $current_point));
        }
    }
    return $path;
}

function getMinSteps($path1, $path2, $intersection_points) {
    $min_steps = PHP_INT_MAX;
    foreach ($intersection_points as $point) {
        $steps1 = PHP_INT_MAX;
        for ($i = 0; $i < count($path1); $i++) {
            if ($point == $path1[$i]) {
                $steps1 = $i + 1;
                break;
            }
        }
        $steps2 = PHP_INT_MAX;
        for ($i = 0; $i < count($path2); $i++) {
            if ($point == $path2[$i]) {
                $steps2 = $i + 1;
                break;
            }
        }

        $min_steps = min($min_steps, $steps1 + $steps2);

    }
    return $min_steps;
}


$file = fopen('3.txt', 'r');
$line1 = fgets($file);
$line2 = fgets($file);

// $line1 = 'R8,U5,L5,D3';
// $line2 = 'U7,R6,D4,L4';

$wire1 = explode(',', $line1);
$wire2 = explode(',', $line2);

$path1 = getPoints($wire1);
$path2 = getPoints($wire2);

$intersection_points = array_intersect($path1, $path2);

$min_steps = getMinSteps($path1, $path2, $intersection_points);

echo $min_steps . PHP_EOL;
fclose($file);
