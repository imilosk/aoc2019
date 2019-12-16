<?php
include '../Intcode/IntcodeComputer.php';

$file = fopen("11.txt", "r");
$line = fgets($file);
$program = array_map('intval', explode(',', $line));

$outputCount = 0;
$direction = 90;
$robotPosition = [0, 0];
$paintedPoints['0,0'] = 1;
$colorToPaint = 0;

$computer = new IntcodeComputer($program);
$computer->execute(
    function () use (&$outputCount, &$direction, &$robotPosition, &$paintedPoints, &$colorToPaint ) {
        $robotPositionString = implode(',', $robotPosition);
        if (array_key_exists($robotPositionString, $paintedPoints))
            $input = $paintedPoints[$robotPositionString];
        else 
            $input = 0;
        return $input;
    },
    function ($output) use (&$outputCount, &$direction, &$robotPosition, &$paintedPoints, &$colorToPaint ) {
        $outputCount++;
        if ($outputCount === 1) {
            $colorToPaint = (int) $output;
            $robotPositionString = implode(',', $robotPosition);
            $paintedPoints[$robotPositionString] = $colorToPaint;
        } elseif ($outputCount === 2) {
            $outputCount = 0;
            $turnDirection = (int) $output;
            $direction += $turnDirection === 0 ? 90 : -90;
            $orientationX = (int) (cos(deg2rad($direction)));
            $orientationY = (int) (sin(deg2rad($direction)));
            $robotPosition[0] += $orientationX;
            $robotPosition[1] += $orientationY;
        }
    }, 
);

$maxX = PHP_INT_MIN;
$maxY = PHP_INT_MIN;
$minX = PHP_INT_MAX;
$minY = PHP_INT_MAX;

foreach($paintedPoints as $key => $value) {
    $coordinates = explode(',', $key);
    $x = (int) $coordinates[0];
    $y = (int) $coordinates[1];
    $maxX = max($x, $maxX);
    $maxY = max($y, $maxY);
    $minX = min($x, $minX);
    $minY = min($y, $minY);
}
$width = $maxX + abs($minX);
$height = $maxY + abs($minY);
$offsetX = abs($minX);
$offsetY = abs($minY);
for ($i = $maxY; $i >= $minY; $i--) {
    for ($j = $minX; $j <= $maxX; $j++) {
        $coordinates = [$j, $i];
        $coordinates = implode(',', $coordinates);
        if (array_key_exists($coordinates, $paintedPoints) && $paintedPoints[$coordinates] === 1) {
            echo 'X';
        } else {
            echo ' ';
        }
    }
    echo PHP_EOL;
}

fclose($file);
