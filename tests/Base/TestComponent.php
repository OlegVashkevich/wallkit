<?php

declare(strict_types=1);

namespace OlegV\WallKit\Tests\Base;

use OlegV\WallKit\Base\Base;
use PHPUnit\Framework\TestCase;

readonly class TestComponent extends Base
{
    public function __construct(
        public string $message = 'Hello World',
    ) {
        parent::__construct();
    }
}

class RenderTest extends TestCase
{
    public function testComponentRenders(): void
    {
        $component = new TestComponent('Test Message');
        $html = (string)$component;
        $this->assertStringContainsString('Test Message', $html);
    }
}