<?php

declare(strict_types=1);

namespace OlegV\WallKit\Content\Markdown;

use Exception;
use OlegV\Traits\WithInheritance;
use OlegV\WallKit\Base\Base;
use Parsedown;
use RuntimeException;

/**
 * Компонент Markdown — рендеринг Markdown в HTML с использованием Parsedown
 *
 * Этот компонент предоставляет удобный интерфейс для преобразования текста в формате
 * Markdown в валидный HTML. Поддерживает безопасный режим, инлайн-рендеринг и
 * дополнительные опции парсера.
 *
 * ## Примеры использования
 *
 * ### Базовый рендеринг
 * ```php
 * $md = new Markdown('# Заголовок\n\nЭто **жирный** текст.');
 * echo $md->toHtml();
 * ```
 *
 * ### Инлайн-рендеринг (без блоковых элементов)
 * ```php
 * $md = new Markdown('**Жирный текст** и `код`');
 * echo $md->toInlineHtml();
 * ```
 *
 * ### Безопасный режим (экранирование HTML)
 * ```php
 * $md = new Markdown(
 *     content: '<script>alert("xss")</script>',
 *     safeMode: true
 * );
 * // HTML будет экранирован
 * ```
 *
 * ### С дополнительными опциями парсера
 * ```php
 * $md = new Markdown(
 *     content: '...',
 *     options: [
 *         'setBreaksEnabled' => true,
 *         'setUrlsLinked' => false,
 *     ]
 * );
 * ```
 *
 * @package OlegV\WallKit\Content\Markdown
 * @author OlegV
 * @since 1.0.0
 * @version 1.0.0
 * @immutable
 * @readonly
 */
readonly class Markdown extends Base
{
    use WithInheritance;

    /** @var ParsedownEx Экземпляр парсера Markdown */
    private ParsedownEx $parser;

    /**
     * Создаёт новый экземпляр компонента Markdown.
     *
     * @param  string  $content  Markdown-контент для преобразования
     * @param  bool  $safeMode  Безопасный режим (экранирование HTML, по умолчанию true)
     * @param  array<string, mixed>  $options  Дополнительные опции для парсера Parsedown
     *
     * @throws RuntimeException Если библиотека Parsedown не установлена
     */
    public function __construct(
        public string $content,
        public bool $safeMode = true,
        public array $options = [],
    ) {
        // Создаем парсер
        $this->parser = $this->createParser();
    }

    /**
     * Подготовка компонента к рендерингу.
     *
     * Проверяет наличие библиотеки Parsedown и инициализирует парсер
     * с заданными настройками.
     *
     * @return void
     *
     * @throws RuntimeException Если библиотека Parsedown не установлена
     *
     * @internal
     */
    protected function prepare(): void
    {
        // Проверяем наличие библиотеки Parsedown
        if (!class_exists(Parsedown::class)) {
            throw new RuntimeException(
                'Parsedown library is required. Install it with: composer require erusev/parsedown',
            );
        }
    }

    /**
     * Создать и настроить парсер Parsedown.
     *
     * @return ParsedownEx Настроенный экземпляр парсера
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
     * Применить дополнительные опции к парсеру.
     *
     * @param  ParsedownEx  $parser  Экземпляр парсера для настройки
     *
     * @return void
     */
    private function applyOptions(ParsedownEx $parser): void
    {
        foreach ($this->options as $option => $value) {
            if (method_exists($parser, $option)) {
                if (is_array($value)) {
                    // @phpstan-ignore method.dynamicName
                    $parser->$option(...$value);
                } else {
                    // @phpstan-ignore method.dynamicName
                    $parser->$option($value);
                }
            }
        }
    }

    /**
     * Преобразовать Markdown в HTML (полный рендеринг с блоками).
     *
     * @return string HTML-код с преобразованным Markdown-контентом
     */
    public function toHtml(): string
    {
        try {
            $out = $this->parser->text($this->content);
            if (is_string($out)) {
                return $out;
            } else {
                return $this->fallback();
            }
        } catch (Exception $e) {
            trigger_error("Failed to parse inline Markdown: " . $e->getMessage(), E_USER_WARNING);
            // В случае ошибки парсинга возвращаем экранированный контент
            return $this->fallback();
        }
    }

    /**
     * Преобразовать инлайн Markdown в HTML (без блоковых элементов).
     *
     * Подходит для рендеринга коротких фрагментов текста внутри абзацев,
     * заголовков и других inline-контекстов.
     *
     * @return string HTML-код с преобразованным инлайн Markdown
     */
    public function toInlineHtml(): string
    {
        try {
            $out = $this->parser->line($this->content);
            if (is_string($out)) {
                return $out;
            } else {
                return $this->fallback();
            }
        } catch (Exception $e) {
            trigger_error("Failed to parse inline Markdown: " . $e->getMessage(), E_USER_WARNING);
            return $this->fallback();
        }
    }

    private function fallback(): string
    {
        return htmlspecialchars($this->content, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}
