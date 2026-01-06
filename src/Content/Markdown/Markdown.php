<?php

// src/Content/Markdown/Markdown.php

declare(strict_types=1);

namespace OlegV\WallKit\Content\Markdown;

use InvalidArgumentException;
use OlegV\WallKit\Base\Base;

/**
 * Компонент для рендеринга Markdown в HTML
 *
 * @property string $content Markdown-контент
 * @property bool $allowHtml Разрешить HTML в Markdown (опасно!)
 * @property bool $safeMode Безопасный режим (экранирование HTML)
 * @property array $extensions Включенные расширения
 */
readonly class Markdown extends Base
{
    /**
     * Базовые расширения Markdown
     */
    private const BASE_EXTENSIONS = [
        'headers',      // # Заголовки
        'bold',         // **жирный**
        'italic',       // *курсив*
        'links',        // [ссылки](url)
        'images',       // ![изображения](src)
        'lists',        // списки
        'code',         // `код` и ```блоки кода```
        'blockquotes',  // > цитаты
        'horizontal',   // --- горизонтальные линии
    ];

    public function __construct(
        public string $content,
        public bool $allowHtml = false,
        public bool $safeMode = true,
        public array $extensions = self::BASE_EXTENSIONS,
    ) {
        if (empty(trim($this->content))) {
            throw new InvalidArgumentException('Markdown content cannot be empty');
        }

        // Проверка расширений
        $invalidExtensions = array_diff($this->extensions, self::getAvailableExtensions());
        if (!empty($invalidExtensions)) {
            throw new InvalidArgumentException(
                'Invalid extensions: '.implode(', ', $invalidExtensions),
            );
        }

        parent::__construct();
    }

    /**
     * Получить список доступных расширений
     */
    public static function getAvailableExtensions(): array
    {
        return array_merge(
            self::BASE_EXTENSIONS,
            [
                'tables',       // таблицы
                'strikethrough', // ~~зачеркивание~~
                'autolinks',    // автоматические ссылки
                'superscript',  // верхний индекс
                'subscript',    // нижний индекс
                'footnotes',    // сноски
            ],
        );
    }

    /**
     * Преобразовать Markdown в HTML
     */
    public function toHtml(): string
    {
        $html = $this->content;

        // Применяем преобразования в порядке важности
        $html = $this->applyTransformations($html);

        // Безопасный режим: экранируем HTML
        if ($this->safeMode && !$this->allowHtml) {
            $html = htmlspecialchars($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        return $html;
    }

    /**
     * Применить все преобразования Markdown
     */
    private function applyTransformations(string $text): string
    {
        // Обработка блоков кода перед другими преобразованиями
        if (in_array('code', $this->extensions)) {
            $text = $this->processCodeBlocks($text);
        }

        // Обработка заголовков
        if (in_array('headers', $this->extensions)) {
            $text = $this->processHeaders($text);
        }

        // Обработка жирного текста
        if (in_array('bold', $this->extensions)) {
            $text = $this->processBold($text);
        }

        // Обработка курсива
        if (in_array('italic', $this->extensions)) {
            $text = $this->processItalic($text);
        }

        // Обработка ссылок
        if (in_array('links', $this->extensions)) {
            $text = $this->processLinks($text);
        }

        // Обработка изображений
        if (in_array('images', $this->extensions)) {
            $text = $this->processImages($text);
        }

        // Обработка списков
        if (in_array('lists', $this->extensions)) {
            $text = $this->processLists($text);
        }

        // Обработка цитат
        if (in_array('blockquotes', $this->extensions)) {
            $text = $this->processBlockquotes($text);
        }

        // Обработка горизонтальных линий
        if (in_array('horizontal', $this->extensions)) {
            $text = $this->processHorizontalRules($text);
        }

        // Дополнительные расширения
        if (in_array('tables', $this->extensions)) {
            $text = $this->processTables($text);
        }

        if (in_array('strikethrough', $this->extensions)) {
            $text = $this->processStrikethrough($text);
        }

        return $text;
    }

    // Методы для обработки конкретных синтаксисов...
    private function processHeaders(string $text): string
    {
        // Заголовки: # H1, ## H2, etc.
        return preg_replace_callback(
            '/^(#{1,6})\s+(.+)$/m',
            function ($matches) {
                $level = strlen($matches[1]);
                $content = trim($matches[2]);
                return "<h$level>$content</h$level>";
            },
            $text,
        );
    }

    private function processBold(string $text): string
    {
        // **жирный** или __жирный__
        return preg_replace('/(\*\*|__)(.*?)\1/', '<strong>$2</strong>', $text);
    }

    private function processItalic(string $text): string
    {
        // *курсив* или _курсив_
        return preg_replace('/([*_])(.*?)/', '<em>$2</em>', $text);
    }

    private function processCodeBlocks(string $text): string
    {
        // Блоки кода ```
        $text = preg_replace_callback(
            '/```(\w+)?\s*\n([\s\S]*?)\n```/',
            function ($matches) {
                $language = $matches[1] ?? '';
                $code = htmlspecialchars($matches[2], ENT_QUOTES | ENT_HTML5, 'UTF-8');

                if ($language) {
                    return "<pre><code class=\"language-$language\">$code</code></pre>";
                }

                return "<pre><code>$code</code></pre>";
            },
            $text,
        );

        // Инлайн код `
        return preg_replace('/`([^`]+)`/', '<code>$1</code>', $text);
    }

    private function processLinks(string $text): string
    {
        // [текст](url "title")
        return preg_replace_callback(
            '/\[([^]]+)]\(([^)\s]+)(?:\s+"([^"]+)")?\)/',
            function ($matches) {
                $text = $matches[1];
                $url = $matches[2];
                $title = $matches[3] ?? '';

                $titleAttr = $title ? " title=\"$title\"" : '';
                return "<a href=\"$url\"$titleAttr>$text</a>";
            },
            $text,
        );
    }

    private function processImages(string $text): string
    {
        // ![alt](src "title")
        return preg_replace_callback(
            '/!\[([^]]*)]\(([^)\s]+)(?:\s+"([^"]+)")?\)/',
            function ($matches) {
                $alt = $matches[1];
                $src = $matches[2];
                $title = $matches[3] ?? '';

                $titleAttr = $title ? " title=\"$title\"" : '';
                return "<img src=\"$src\" alt=\"$alt\"$titleAttr>";
            },
            $text,
        );
    }

    private function processLists(string $text): string
    {
        // Неупорядоченные списки
        $lines = explode("\n", $text);
        $inList = false;
        $result = [];

        foreach ($lines as $line) {
            if (preg_match('/^[*+-]\s+(.+)$/', $line, $matches)) {
                if (!$inList) {
                    $result[] = '<ul>';
                    $inList = true;
                }
                $result[] = "<li>$matches[1]</li>";
            } elseif (preg_match('/^\d+\.\s+(.+)$/', $line, $matches)) {
                if (!$inList) {
                    $result[] = '<ol>';
                    $inList = true;
                }
                $result[] = "<li>$matches[1]</li>";
            } else {
                if ($inList) {
                    $result[] = '</ul>';
                    $inList = false;
                }
                $result[] = $line;
            }
        }

        if ($inList) {
            $result[] = '</ul>';
        }

        return implode("\n", $result);
    }

    private function processBlockquotes(string $text): string
    {
        // > цитаты
        $lines = explode("\n", $text);
        $inQuote = false;
        $result = [];
        $quoteContent = [];

        foreach ($lines as $line) {
            if (preg_match('/^>\s?(.+)$/', $line, $matches)) {
                if (!$inQuote) {
                    $inQuote = true;
                }
                $quoteContent[] = $matches[1];
            } else {
                if ($inQuote && !empty($quoteContent)) {
                    $result[] = '<blockquote>'.implode("\n", $quoteContent).'</blockquote>';
                    $quoteContent = [];
                    $inQuote = false;
                }
                $result[] = $line;
            }
        }

        if ($inQuote && !empty($quoteContent)) {
            $result[] = '<blockquote>'.implode("\n", $quoteContent).'</blockquote>';
        }

        return implode("\n", $result);
    }

    private function processHorizontalRules(string $text): string
    {
        // ---, ***, ___
        return preg_replace('/^[-*_]{3,}$/m', '<hr>', $text);
    }

    private function processTables(string $text): string
    {
        // Базовая поддержка таблиц
        return preg_replace_callback(
            '/^(.+)\n(-+\s*\|?)+\n(.+)(?:\n(.+))*/ms',
            function ($matches) {
                $headers = explode('|', trim($matches[1]));
                $rows = array_slice($matches, 3);

                $html = "<table>\n<thead>\n<tr>\n";
                foreach ($headers as $header) {
                    $html .= "<th>".trim($header)."</th>\n";
                }
                $html .= "</tr>\n</thead>\n<tbody>\n";

                foreach ($rows as $row) {
                    if (trim($row)) {
                        $cells = explode('|', trim($row));
                        $html .= "<tr>\n";
                        foreach ($cells as $cell) {
                            $html .= "<td>".trim($cell)."</td>\n";
                        }
                        $html .= "</tr>\n";
                    }
                }

                $html .= "</tbody>\n</table>";
                return $html;
            },
            $text,
        );
    }

    private function processStrikethrough(string $text): string
    {
        // ~~зачеркивание~~
        return preg_replace('/~~(.+?)~~/', '<del>$1</del>', $text);
    }
}