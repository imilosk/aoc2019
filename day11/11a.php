<?php
include '../Intcode/IntcodeComputer.php';

$file = fopen("11.txt","r");
$line = fgets($file);
$program = array_map('intval', explode(',', $line));

$outputCount = 0;
$direction = 90;
$robotPosition = [0, 0];
$paintedPoints = array();
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

echo count($paintedPoints) . PHP_EOL;

fclose($file);
