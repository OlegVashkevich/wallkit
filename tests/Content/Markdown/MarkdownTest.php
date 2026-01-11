<?php

declare(strict_types=1);

namespace OlegV\WallKit\Tests\Content\Markdown;

use OlegV\Traits\WithInheritance;
use OlegV\WallKit\Content\Markdown\Markdown;
use OlegV\WallKit\Content\Markdown\ParsedownEx;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Тесты для компонента Markdown
 */
class MarkdownTest extends TestCase
{
    public function testMarkdownClassExists(): void
    {
        $this->assertTrue(class_exists(Markdown::class));
    }

    public function testMarkdownIsReadonly(): void
    {
        $reflection = new ReflectionClass(Markdown::class);
        $this->assertTrue($reflection->isReadOnly());
    }

    public function testMarkdownUsesWithInheritanceTrait(): void
    {
        $traits = class_uses(Markdown::class);
        $this->assertArrayHasKey(WithInheritance::class, $traits);
    }

    public function testMarkdownProperties(): void
    {
        $content = '# Test Content';
        $md = new Markdown($content, true, ['setBreaksEnabled' => true]);

        $this->assertEquals($content, $md->content);
        $this->assertTrue($md->safeMode);
        $this->assertEquals(['setBreaksEnabled' => true], $md->options);
    }

    public function testMarkdownRendersEmptyString(): void
    {
        $md = new Markdown('');
        $output = (string)$md;
        // Шаблон всегда рендерит div, даже для пустого контента
        $this->assertStringContainsString('<div class="wallkit-markdown">', $output);
        $this->assertStringContainsString('</div>', $output);
        // Проверяем что внутри div пусто или только пробелы/переносы строк

        $innerContent = preg_replace('/^<div[^>]*>|<\/div>$/s', '', $output);
        $this->assertEmpty(trim($innerContent));
    }

    public function testMarkdownRendersSimpleContent(): void
    {
        $content = '# Hello World';
        $md = new Markdown($content);

        $output = (string)$md;

        $this->assertStringContainsString('wallkit-markdown', $output);
        $this->assertStringContainsString('<h1', $output);
        $this->assertStringContainsString('Hello World', $output);
    }

    public function testMarkdownRendersBoldText(): void
    {
        $content = '**bold text**';
        $md = new Markdown($content);

        $output = (string)$md;

        $this->assertStringContainsString('<strong>', $output);
        $this->assertStringContainsString('bold text', $output);
    }

    public function testMarkdownRendersItalicText(): void
    {
        $content = '*italic text*';
        $md = new Markdown($content);

        $output = (string)$md;

        $this->assertStringContainsString('<em>', $output);
        $this->assertStringContainsString('italic text', $output);
    }

    public function testMarkdownRendersLinks(): void
    {
        $content = '[Google](https://google.com)';
        $md = new Markdown($content);

        $output = (string)$md;

        $this->assertStringContainsString('<a', $output);
        $this->assertStringContainsString('href="https://google.com"', $output);
        $this->assertStringContainsString('Google', $output);
    }

    public function testMarkdownRendersCodeBlocks(): void
    {
        $content = "```php\necho 'Hello';\n```";
        $md = new Markdown($content);

        $output = (string)$md;

        $this->assertStringContainsString('wallkit-code', $output);
    }

    public function testToHtmlMethod(): void
    {
        $content = '# Title';
        $md = new Markdown($content);

        $html = $md->toHtml();

        $this->assertStringContainsString('<h1>', $html);
        $this->assertStringContainsString('Title', $html);
    }

    public function testToInlineHtmlMethod(): void
    {
        $content = '**bold** and *italic*';
        $md = new Markdown($content);

        $html = $md->toInlineHtml();

        $this->assertStringContainsString('<strong>', $html);
        $this->assertStringContainsString('<em>', $html);
        $this->assertStringNotContainsString('<p>', $html);
    }

    public function testSafeModeEscapesHtml(): void
    {
        $content = '<script>alert("xss")</script>';
        $md = new Markdown($content, true);

        $output = (string)$md;

        $this->assertStringContainsString('&lt;script&gt;', $output);
        $this->assertStringNotContainsString('<script>', $output);
    }

    public function testUnsafeModeAllowsHtml(): void
    {
        $content = '<div>test</div>';
        $md = new Markdown($content, false);

        $output = (string)$md;

        $this->assertStringContainsString('<div>test</div>', $output);
    }

    public function testParserOptions(): void
    {
        $content = "line1\nline2";
        $md = new Markdown($content, true, ['setBreaksEnabled' => true]);

        $output = (string)$md;

        // При включенных переносах строк должны быть <br>
        $this->assertStringContainsString('<br />', $output);
    }

    public function testMarkdownTemplateStructure(): void
    {
        $content = '# Test';
        $md = new Markdown($content);

        $output = (string)$md;

        // Проверяем структуру вывода
        $this->assertStringStartsWith('<div class="wallkit-markdown">', $output);
        $this->assertStringEndsWith('</div>', $output);
        $this->assertStringContainsString('<h1>Test</h1>', $output);
    }

    public function testMarkdownWithComplexContent(): void
    {
        $content = <<<'MD'
            # Main Title
            
            This is a **bold** and *italic* text.
            
            ## Subtitle
            
            - Item 1
            - Item 2
            - Item 3
            
            ```php
            echo "Hello World";
            ```
            MD;

        $md = new Markdown($content);
        $output = (string)$md;

        $this->assertStringContainsString('<h1>Main Title</h1>', $output);
        $this->assertStringContainsString('<h2>Subtitle</h2>', $output);
        $this->assertStringContainsString('<strong>bold</strong>', $output);
        $this->assertStringContainsString('<em>italic</em>', $output);
        $this->assertStringContainsString('<ul>', $output);
        $this->assertStringContainsString('wallkit-code', $output);
    }

    public function testMarkdownConstructorDoesNotThrow(): void
    {
        $this->expectNotToPerformAssertions();

        echo new Markdown('test');
        echo new Markdown('test', false);
        echo new Markdown('test', true, ['setBreaksEnabled' => true]);
    }

    public function testParsedownExClassExists(): void
    {
        $this->assertTrue(class_exists(ParsedownEx::class));
    }

    public function testParsedownExExtendsParsedown(): void
    {
        $this->assertTrue(is_subclass_of(ParsedownEx::class, 'Parsedown'));
    }

    public function testMarkdownWorksWithParsedownEx(): void
    {
        $content = "```php\necho 'test';\n```";
        $md = new Markdown($content);

        $output = (string)$md;

        // Должен использовать ParsedownEx для рендеринга блоков кода
        $this->assertStringContainsString('wallkit-code', $output);
    }
}