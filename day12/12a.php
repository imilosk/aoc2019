<?php

class Vector3D {
    public float $x = 0;
    public float $y = 0;
    public float $z = 0;

    function getAll(): array {
        return [$this->x, $this->y, $this->z];
    }

    function setAll(array $values): void {
        $this->x = $values[0];
        $this->y = $values[1];
        $this->z = $values[2];
    }

}

class Moon {
    public string $name;
    public Vector3D $position;
    public Vector3D $velocity;

    function __construct(string $name) {
        $this->name = $name;
        $this->position = new Vector3D();
        $this->velocity = new Vector3D();
    }

    function updatePosition(): void {
        $this->position->x += $this->velocity->x;
        $this->position->y += $this->velocity->y;
        $this->position->z += $this->velocity->z;
    }

    function calculateTotalEnergy(): float {
        return $this->calculatePotencialEnergy() * $this->calculateKineticEnergy();
    } 

    private function calculatePotencialEnergy(): float {
        return abs($this->position->x) + abs($this->position->y) + abs($this->position->z);
    }

    private function calculateKineticEnergy(): float {
        return abs($this->velocity->x) + abs($this->velocity->y) + abs($this->velocity->z);
    }

}

// 

$timeSteps = 1000;

$file = fopen("12.txt","r");
$moons = [];
while(!feof($file)) {
    $line = trim(fgets($file));
    $definitions = explode(', ', $line);
    for ($i = 0; $i < 3; $i++) {
        $definition = $definitions[$i];
        $startIndex = strpos($definition, '=') + 1;
        $values[$i] = (int) substr($definition, $startIndex);
    }
    $moon = new Moon('');
    $moon->position->setAll($values);
    $moons[] = $moon;
}

for ($steps = 0; $steps < $timeSteps; $steps++) {
    for ($i = 0; $i < count($moons); $i++) {
        for ($j = $i + 1; $j < count($moons); $j++) {
            $moon1 = $moons[$i];
            $moon2 = $moons[$j];
            // x
            $cmp = $moon1->position->x <=> $moon2->position->x;
            $moon1->velocity->x += ($cmp * -1);
            $moon2->velocity->x += ($cmp);
            // y
            $cmp = $moon1->position->y <=> $moon2->position->y;
            $moon1->velocity->y += ($cmp * -1);
            $moon2->velocity->y += ($cmp);
            // z
            $cmp = $moon1->position->z <=> $moon2->position->z;
            $moon1->velocity->z += ($cmp * -1);
            $moon2->velocity->z += ($cmp);
            // update position
        }
        $moons[$i]->updatePosition();
    }
}

$totalEnergy = 0;
foreach ($moons as $moon) {
    $totalEnergy += $moon->calculateTotalEnergy();
}

echo $totalEnergy . PHP_EOL;