<?php
include '../Intcode/IntcodeComputer.php';

$file = fopen("13.txt","r");
$line = fgets($file);
$program = array_map('intval', explode(',', $line));

$instructions = [];

$computer = new IntcodeComputer($program);
$generator = $computer->execute();

while (!$computer->halt) {
    $output = $generator->current();

    if ($output !== null) {
        $instructions[] = $output;
    }

    $generator->next();
}

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
