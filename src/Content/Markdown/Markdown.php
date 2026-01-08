<?php

declare(strict_types=1);

namespace OlegV\WallKit\Content\Markdown;

use Exception;
use OlegV\Traits\WithInheritance;
use OlegV\WallKit\Base\Base;
use Parsedown;

/**
 * Компонент для рендеринга Markdown в HTML с использованием Parsedown
 *
 * @property string $content Markdown-контент
 * @property bool $safeMode Безопасный режим (экранирование HTML)
 * @property array $options Дополнительные опции парсера
 */
readonly class Markdown extends Base
{
    use WithInheritance;

    private ParsedownEx $parser;

    public function __construct(
        public string $content,
        public bool $safeMode = true,
        public array $options = [],
    ) {
        // Проверяем наличие библиотеки Parsedown
        if (!class_exists(Parsedown::class)) {
            trigger_error(
                "Parsedown library is required. Install it with: composer require erusev/parsedown",
                E_USER_WARNING,
            );
        }

        // Создаем парсер
        $this->parser = $this->createParser();
    }

    /**
     * Создать и настроить парсер Parsedown
     */
    private function createParser(): ParsedownEx
    {
        $parser = new ParsedownEx();

        // Настройка безопасного режима
        if ($this->safeMode) {
            $parser->setSafeMode(true);
        }

        // Применение дополнительных опций
        $this->applyOptions($parser);

        return $parser;
    }

    /**
     * Применить дополнительные опции к парсеру
     */
    private function applyOptions(ParsedownEx $parser): void
    {
        foreach ($this->options as $option => $value) {
            if (method_exists($parser, $option)) {
                if (is_array($value)) {
                    $parser->$option(...$value);
                } else {
                    $parser->$option($value);
                }
            }
        }
    }

    /**
     * Преобразовать Markdown в HTML
     */
    public function toHtml(): string
    {
        try {
            return $this->parser->text($this->content);
        } catch (Exception $e) {
            trigger_error("Failed to parse inline Markdown: ".$e->getMessage(), E_USER_WARNING);
            // В случае ошибки парсинга возвращаем экранированный контент
            return htmlspecialchars($this->content, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
    }

    /**
     * Преобразовать инлайн Markdown в HTML (без блоковых элементов)
     */
    public function toInlineHtml(): string
    {
        try {
            return $this->parser->line($this->content);
        } catch (Exception $e) {
            trigger_error("Failed to parse inline Markdown: ".$e->getMessage(), E_USER_WARNING);
            return htmlspecialchars($this->content, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
    }
}