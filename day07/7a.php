<?php
include '../Intcode/IntcodeComputer.php';

function getAllCombos($arr) {
    $combinations = array();
    $words = sizeof($arr);
    $combos = 1;
    for($i = $words; $i > 0; $i--) {
        $combos *= $i;
    }
    while(sizeof($combinations) < $combos) {
        shuffle($arr);
        $combo = implode(" ", $arr);
        if(!in_array($arr, $combinations)) {
            $combinations[] = $arr;
        }
    }
    return $combinations;
}

$file = fopen("7.txt", "r");
$line = fgets($file);
$program = array_map('intval', explode(',', $line));

$possibleSettings = [0, 1, 2, 3, 4];
$maxSignal = 0;
$settingCombinations = getAllCombos($possibleSettings);

for ($i = 0; $i < 5; $i++) { 
    $computers[$i] = new IntcodeComputer($program);
}

foreach ($settingCombinations as $settings) {
    $signal = 0;
    for ($i = 0; $i < count($settings); $i++) {
        $setting = $settings[$i];
        $firstArgument = true;

        $computers[$i]->execute(
            function () use (&$setting, &$signal, &$firstArgument) {
                if ($firstArgument && $setting !== null) {
                    $firstArgument = false;
                    return $setting;
                } else {
                    return $signal;
                }
            },
            function ($output) use (&$signal) {
                $signal = $output;
            }
        );

    }
    $maxSignal = max($signal, $maxSignal);
}

echo $maxSignal . PHP_EOL;

fclose($file);