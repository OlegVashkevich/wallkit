<?php

declare(strict_types=1);

namespace OlegV\WallKit\Demo\DemoStats;

use OlegV\Traits\WithHelpers;
use OlegV\Traits\WithInheritance;
use OlegV\Traits\WithStrictHelpers;
use OlegV\WallKit\Base\Base;

readonly class DemoStats extends Base
{
    use WithHelpers;
    use WithStrictHelpers;
    use WithInheritance;

    public function __construct(
        public int $totalComponents,
        public int $stableComponents,
        public int $plannedComponents,
        public int $demoPages,
        public string $latestVersion = '1.0.0',
    ) {
        parent::__construct();
    }

    public function getProgress(): float
    {
        if ($this->totalComponents === 0) {
            return 0;
        }
        return ($this->stableComponents / $this->totalComponents) * 100;
    }
}