<?php

declare(strict_types=1);

namespace OlegV\WallKit\Tests\Form\Textarea;

use InvalidArgumentException;
use OlegV\WallKit\Form\Textarea\Textarea;
use PHPUnit\Framework\TestCase;

class TextareaTest extends TestCase
{
    public function testTextareaRendersBasicAttributes(): void
    {
        $textarea = new Textarea(name: 'description');
        $html = (string)$textarea;

        $this->assertStringContainsString('name="description"', $html);
        $this->assertStringContainsString('wallkit-textarea__field', $html);
        $this->assertStringContainsString('rows="4"', $html);
    }

    public function testTextareaRendersPlaceholder(): void
    {
        $textarea = new Textarea(name: 'bio', placeholder: 'О себе');
        $html = (string)$textarea;

        $this->assertStringContainsString('placeholder="О себе"', $html);
    }

    public function testTextareaRendersValue(): void
    {
        $textarea = new Textarea(name: 'content', value: 'Привет, мир!');
        $html = (string)$textarea;

        $this->assertStringContainsString('>Привет, мир!</textarea>', $html);
    }

    public function testTextareaRequiredAndDisabled(): void
    {
        $textarea = new Textarea(name: 'comment', required: true, disabled: true);
        $html = (string)$textarea;

        $this->assertStringContainsString('required', $html);
        $this->assertStringContainsString('disabled', $html);
    }

    public function testTextareaCustomRows(): void
    {
        $textarea = new Textarea(name: 'message', rows: 10);
        $html = (string)$textarea;

        $this->assertStringContainsString('rows="10"', $html);
    }

    public function testTextareaThrowsExceptionOnEmptyName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Textarea(name: '   ');
    }

    public function testTextareaRendersCustomId(): void
    {
        $textarea = new Textarea(name: 'desc', id: 'custom-id');
        $html = (string)$textarea;

        $this->assertStringContainsString('id="custom-id"', $html);
    }
}