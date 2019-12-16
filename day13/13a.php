<?php
include '../Intcode/IntcodeComputer.php';

$file = fopen("13.txt","r");
$line = fgets($file);
$program = array_map('intval', explode(',', $line));

$instructions = [];

$computer = new IntcodeComputer($program);
$computer->execute(
    null,
    function ($output) use(&$instructions) {
        $instructions[] = $output;
    }, 
);

$blockTilesNumber = 0;
for ($i = 0; $i < count($instructions); $i += 3) {
    $x = $instructions[$i];
    $y = $instructions[$i + 1];
    $tileId = $instructions[$i + 2];
    if ($tileId === 2)
        $blockTilesNumber++;
}
echo $blockTilesNumber . PHP_EOL;

fclose($file);
