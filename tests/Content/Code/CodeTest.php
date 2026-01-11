<?php

declare(strict_types=1);

namespace OlegV\WallKit\Tests\Content\Code;

use InvalidArgumentException;
use OlegV\Exceptions\RenderException;
use OlegV\WallKit\Content\Code\Code;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Тесты для компонента Code
 */
class CodeTest extends TestCase
{
    /**
     * Тест: класс существует и является readonly
     */
    public function testCodeClassExists(): void
    {
        $this->assertTrue(class_exists(Code::class));
    }

    public function testCodeIsReadonly(): void
    {
        $reflection = new ReflectionClass(Code::class);
        $this->assertTrue($reflection->isReadOnly());
    }

    /**
     * Тест: конструктор корректно инициализирует свойства
     */
    public function testConstructorInitializesProperties(): void
    {
        $code = new Code(
            content: 'echo "test";',
            language: 'php',
            highlight: true,
            lineNumbers: true,
            copyButton: true,
            showLanguage: true,
        );

        $this->assertEquals('echo "test";', $code->content);
        $this->assertEquals('php', $code->language);
        $this->assertTrue($code->highlight);
        $this->assertTrue($code->lineNumbers);
        $this->assertTrue($code->copyButton);
        $this->assertTrue($code->showLanguage);
    }

    /**
     * Тест: значения по умолчанию
     */
    public function testDefaultValues(): void
    {
        $code = new Code(content: 'test');

        $this->assertEquals('test', $code->content);
        $this->assertEquals('plaintext', $code->language);
        $this->assertFalse($code->highlight);
        $this->assertFalse($code->lineNumbers);
        $this->assertFalse($code->copyButton);
        $this->assertFalse($code->showLanguage);
    }

    /**
     * Тест: getHighlightedContent без подсветки возвращает экранированный текст
     */
    public function testGetHighlightedContentWithoutHighlighting(): void
    {
        $code = new Code(
            content: '<script>alert("test")</script>',
            language: 'html',
            highlight: false,
        );

        $result = $code->getHighlightedContent();
        $expected = htmlspecialchars('<script>alert("test")</script>');

        $this->assertEquals($expected, $result);
        $this->assertStringContainsString('&lt;script&gt;', $result);
    }

    /**
     * Тест: render вызывает prepare и возвращает строку
     */
    public function testRenderReturnsString(): void
    {
        $code = new Code(
            content: 'console.log("test")',
            language: 'javascript',
        );

        $result = (string) $code;

        $this->assertIsString($result);
        $this->assertStringContainsString('wallkit-code', $result);
        $this->assertStringContainsString('data-language="javascript"', $result);
    }

    /**
     * Тест: render с включенной подсветкой
     */
    public function testRenderWithHighlight(): void
    {
        $code = new Code(
            content: 'function test() { return true; }',
            language: 'javascript',
            highlight: true,
        );

        $result = (string) $code;

        $this->assertStringContainsString('hljs', $result);
        $this->assertStringContainsString('language-javascript', $result);
    }

    /**
     * Тест: render с номерами строк
     */
    public function testRenderWithLineNumbers(): void
    {
        $content = "Line 1\nLine 2\nLine 3";
        $code = new Code(
            content: $content,
            language: 'plaintext',
            lineNumbers: true,
        );

        $result = (string) $code;

        $this->assertStringContainsString('wallkit-code__lines', $result);
        $this->assertStringContainsString('wallkit-code__line-number', $result);
        $this->assertStringContainsString('1', $result);
        $this->assertStringContainsString('2', $result);
        $this->assertStringContainsString('3', $result);
    }

    /**
     * Тест: render с кнопкой копирования
     */
    public function testRenderWithCopyButton(): void
    {
        $code = new Code(
            content: 'Copy me',
            language: 'text',
            copyButton: true,
        );

        $result = (string) $code;

        $this->assertStringContainsString('wallkit-code__copy-button', $result);
        $this->assertStringContainsString('Копировать', $result);
        $this->assertStringContainsString('data-action="copy-code"', $result);
        $this->assertStringContainsString('data-copied-text="Скопировано!"', $result);
    }

    /**
     * Тест: render с меткой языка
     */
    public function testRenderWithShowLanguage(): void
    {
        $code = new Code(
            content: 'Test content',
            language: 'python',
            showLanguage: true,
        );

        $result = (string) $code;

        $this->assertStringContainsString('wallkit-code__language', $result);
        $this->assertStringContainsString('python', $result);
    }

    /**
     * Тест: render со всеми функциями включенными
     */
    public function testRenderWithAllFeatures(): void
    {
        $content = "<?php\n\necho 'Hello';\n\nreturn 0;";
        $code = new Code(
            content: $content,
            language: 'php',
            highlight: true,
            lineNumbers: true,
            copyButton: true,
            showLanguage: true,
        );

        $result = (string) $code;

        $this->assertStringContainsString('wallkit-code__header', $result);
        $this->assertStringContainsString('wallkit-code__language', $result);
        $this->assertStringContainsString('wallkit-code__copy-button', $result);
        $this->assertStringContainsString('wallkit-code__lines', $result);
        $this->assertStringContainsString('hljs', $result);
        $this->assertStringContainsString('language-php', $result);
    }

    /**
     * Тест: неподдерживаемый язык вызывает исключение при подсветке
     */
    public function testUnsupportedLanguageThrowsException(): void
    {
        $this->expectException(RenderException::class);

        // Создаем фиктивный класс Highlighter для тестирования
        if (!class_exists('Highlight\Highlighter')) {
            eval(
                '
                namespace Highlight;
                class Highlighter {
                    public static function listBundledLanguages(): array {
                        return ["php", "javascript", "html", "css"];
                    }
                    public function highlight($language, $code) {
                        throw new Exception("Language not supported");
                    }
                }
            '
            );
        }

        try {
            $code = new Code(
                content: 'test',
                language: 'unsupported-language',
                highlight: true,
            );

            $code->renderOriginal();
        } catch (RenderException $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e->getPrevious());
            $this->assertStringContainsString(
                'не поддерживается библиотекой highlight.php',
                $e->getPrevious()->getMessage(),
            );
            throw $e;
        }
    }

    /**
     * Тест: языки plaintext и text не проверяются на поддержку
     */
    public function testPlaintextLanguageDoesNotCheckSupport(): void
    {
        // Этот тест должен пройти без исключений
        $code = new Code(
            content: 'Plain text content',
            language: 'plaintext',
            highlight: true,
        );

        $result = (string) $code;
        $this->assertStringContainsString('wallkit-code', $result);
    }

    /**
     * Тест: подготовка с некорректным языком (через renderOriginal)
     */
    public function testPrepareWithInvalidLanguage(): void
    {
        $this->expectException(RenderException::class);

        // Мокаем проверку языков
        if (!class_exists('Highlight\Highlighter')) {
            eval(
                '
                namespace Highlight;
                class Highlighter {
                    public static function listBundledLanguages(): array {
                        return ["php", "javascript"];
                    }
                }
            '
            );
        }

        try {
            $code = new Code(
                content: 'test',
                language: 'invalid-lang',
                highlight: true,
            );

            $code->renderOriginal();
        } catch (RenderException $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e->getPrevious());
            throw $e;
        }
    }

    /**
     * Тест: HTML-экранирование работает корректно
     */
    public function testHtmlEscaping(): void
    {
        $content = '<div class="test">&amp;</div>';
        $code = new Code(
            content: $content,
            language: 'html',
            highlight: false,
        );

        $result = $code->getHighlightedContent();

        // Проверяем что все спецсимволы экранированы
        $this->assertStringContainsString('&lt;div', $result);
        $this->assertStringContainsString('&amp;amp;', $result); // & должно быть двойное экранирование
        $this->assertStringContainsString('&lt;/div&gt;', $result);
    }

    /**
     * Тест: валидация работает только при включенной подсветке
     */
    public function testValidationOnlyWhenHighlightEnabled(): void
    {
        // Этот тест должен пройти без исключений, даже с неподдерживаемым языком
        // потому что подсветка отключена
        $code = new Code(
            content: 'test',
            language: 'totally-unsupported-language',
            highlight: false,
        );

        $result = (string) $code;
        $this->assertStringContainsString('wallkit-code', $result);
    }

    /**
     * Тест: корректное отображение многострочного кода с номерами строк
     */
    public function testMultilineContentWithLineNumbers(): void
    {
        $content = "Первая строка\nВторая строка\n\nЧетвертая строка";
        $code = new Code(
            content: $content,
            language: 'text',
            lineNumbers: true,
        );

        $result = (string) $code;

        // Проверяем количество номеров строк
        $this->assertEquals(4, substr_count($result, 'wallkit-code__line-number'));

        // Проверяем что пустые строки тоже нумеруются
        $lines = explode("\n", $content);
        foreach ($lines as $i => $line) {
            $this->assertStringContainsString((string) ($i + 1), $result);
        }
    }

    /**
     * Тест: ensureHighlightLibrary не выбрасывает исключение при ошибке получения списка языков
     */
    public function testEnsureHighlightLibraryHandlesListException(): void
    {
        if (!class_exists('Highlight\Highlighter')) {
            eval(
                '
                namespace Highlight;
                class Highlighter {
                    public static function listBundledLanguages(): array {
                        throw new \Exception("Cannot list languages");
                    }
                }
            '
            );
        }

        // Не должно быть исключения
        $code = new Code(
            content: 'test',
            language: 'php',
            highlight: true,
        );

        // Проверяем что render не выбрасывает исключение
        $result = (string) $code;
        $this->assertStringContainsString('wallkit-code', $result);
    }
}
