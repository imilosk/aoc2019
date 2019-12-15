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

    public static function equals(Vector3D $vector1, Vector3D $vector2): bool {
        return $vector1->x === $vector2->x && 
               $vector1->y === $vector2->y &&
               $vector1->z === $vector2->z;
    }

}

class Moon {
    public string $name;
    public Vector3D $position;
    public Vector3D $initialPosition;
    public Vector3D $velocity;

    function __construct(string $name = '') {
        $this->name = $name;
        $this->position = new Vector3D();
        $this->initialPosition = new Vector3D();
        $this->velocity = new Vector3D();
    }

    public function updatePosition(): void {
        $this->position->x += $this->velocity->x;
        $this->position->y += $this->velocity->y;
        $this->position->z += $this->velocity->z;
    }

    public function calculateTotalEnergy(): float {
        return $this->calculatePotencialEnergy() * $this->calculateKineticEnergy();
    } 

    public static function equals(Moon $moon1, Moon $moon2): bool {
        return Vector3D::equals($moon1->position, $moon2->position) &&
               Vector3D::equals($moon1->velocity, $moon2->velocity);
    }

    private function calculatePotencialEnergy(): float {
        return abs($this->position->x) + abs($this->position->y) + abs($this->position->z);
    }

    private function calculateKineticEnergy(): float {
        return abs($this->velocity->x) + abs($this->velocity->y) + abs($this->velocity->z);
    }

    public function toString() {
        $p = implode(', ', $this->position->getAll());
        $v = implode(', ', $this->velocity->getAll());
        return '<' . $p . '> <' . $v . '> ' . PHP_EOL;
    }

}

function gcd (int $a, int $b): int {
    if ($a === 0)
        return $b;
    return gcd ($b % $a, $a);
}

function lcm (int $a, int $b): int {
    return ($a * $b) / gcd($a, $b);
}

function areEqual(int $id, array $moons): bool {
    for ($i = 0; $i < count($moons); $i++) {
        if ($moons[$i]->position->x !== $moons[$i]->initialPosition->x && $id === 0) {
            return false;
        } elseif ($moons[$i]->position->y !== $moons[$i]->initialPosition->y && $id === 1) {
            return false;
        }elseif ($moons[$i]->position->z !== $moons[$i]->initialPosition->z && $id === 2) {
            return false;
        }
    }

    for ($i = 0; $i < count($moons); $i++) {
        if ($moons[$i]->velocity->x != 0 && $id === 0) {
            return false;
        } elseif ($moons[$i]->velocity->y != 0 && $id === 1) {
            return false;
        }elseif ($moons[$i]->velocity->z != 0 && $id === 2) {
            return false;
        }
    }
    return true;
    
}

// 

// $timeSteps = 1000;

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
    $moon = new Moon();
    $moon->position->setAll($values);
    $moon->initialPosition->setAll($values);
    $moons[] = $moon;
}

$stepsToMatch = 0;
$x = 0;
$y = 0;
$z = 0;
do {
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
        }
        
        $moons[$i]->updatePosition();
    }

    $stepsToMatch++;

    if (areEqual(0, $moons) && $x === 0) {
        $x = $stepsToMatch;
    }

    if (areEqual(1, $moons) && $y === 0) {
        $y = $stepsToMatch;
    }

    if (areEqual(2, $moons) && $z === 0) {
        $z = $stepsToMatch;
    }
    // echo $x . ' ' . $y . ' ' . $z;
} while($x === 0 || $y === 0 || $z === 0);

$tcp = lcm( lcm($x, $y), $z );

echo $tcp . PHP_EOL;