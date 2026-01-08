<?php

declare(strict_types=1);

namespace OlegV\WallKit\Content\Code;

use Exception;
use Highlight\Highlighter;
use InvalidArgumentException;
use OlegV\WallKit\Base\Base;

/**
 * Компонент для отображения блоков кода с возможностью подсветки синтаксиса
 *
 * @property string $content Исходный код
 * @property string $language Язык программирования (php, javascript, html и т.д.)
 * @property bool $highlight Включить подсветку синтаксиса
 * @property bool $lineNumbers Показать номера строк
 * @property bool $copyButton Показать кнопку копирования
 * @property bool $showLanguage Показать метку языка
 */
readonly class Code extends Base
{
    public function __construct(
        public string $content,
        public string $language = 'plaintext',
        public bool $highlight = false,
        public bool $lineNumbers = false,
        public bool $copyButton = false,
        public bool $showLanguage = false,
    ) {}

    protected function prepare(): void
    {
        // Валидация языка (только для подсветки)
        if ($this->highlight && !in_array($this->language, ['plaintext', 'text'])) {
            $this->ensureHighlightLibrary();
        }

        // Проверка, установлена ли библиотека подсветки
        if ($this->highlight && !class_exists('Highlight\HighlightResult')) {
            trigger_error(
                'WallKit: Библиотека scrivo/highlight.php не установлена. Подсветка кода отключена. '.
                'Установите: composer require scrivo/highlight.php',
                E_USER_WARNING,
            );
        }
    }

    /**
     * Проверяет, поддерживается ли язык для подсветки
     */
    private function ensureHighlightLibrary(): void
    {
        // Если библиотека установлена, проверяем поддержку языка
        if (class_exists('Highlight\Highlighter')) {
            $highlighter = new Highlighter();
            try {
                $highlighter::listBundledLanguages();
            } catch (Exception) {
                // Если не можем получить список языков, пропускаем проверку
                return;
            }

            if (!in_array($this->language, $highlighter::listBundledLanguages())) {
                throw new InvalidArgumentException(
                    "Язык '$this->language' не поддерживается библиотекой highlight.php. ".
                    "Используйте один из: ".implode(', ', $highlighter::listBundledLanguages()),
                );
            }
        }
    }

    /**
     * Возвращает подсвеченный код, если библиотека установлена
     */
    public function getHighlightedContent(): string
    {
        if (!$this->highlight || !class_exists('Highlight\Highlighter')) {
            return htmlspecialchars($this->content);
        }

        $highlighter = new Highlighter();
        try {
            $highlighted = $highlighter->highlight($this->language, $this->content);
            return $highlighted->value;
        } catch (Exception) {
            // В случае ошибки подсветки возвращаем обычный текст
            return htmlspecialchars($this->content);
        }
    }
}