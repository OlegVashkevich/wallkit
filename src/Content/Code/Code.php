<?php

declare(strict_types=1);

namespace OlegV\WallKit\Content\Code;

use Exception;
use Highlight\Highlighter;
use InvalidArgumentException;
use OlegV\Traits\WithInheritance;
use OlegV\WallKit\Base\Base;

/**
 * Компонент Code — отображение блоков исходного кода с подсветкой синтаксиса
 *
 * Компонент предназначен для визуального оформления фрагментов кода в документации,
 * примерах использования или любых других контекстах, где требуется читаемое представление
 * исходного кода. Поддерживает подсветку синтаксиса для более чем 190 языков программирования
 * через внешнюю библиотеку `scrivo/highlight.php`.
 *
 * ## Особенности
 *
 * - **Автоматическое экранирование** — весь код безопасно экранируется для вывода в HTML
 * - **Поддержка подсветки** — опциональная подсветка через highlight.php
 * - **Номера строк** — возможность отображения нумерации строк
 * - **Кнопка копирования** — встроенная кнопка для быстрого копирования кода в буфер обмена
 * - **Метка языка** — отображение названия языка программирования
 * - **Прогрессивное улучшение** — если библиотека подсветки не установлена, код отображается как простой текст
 *
 * ## Примеры использования
 *
 * ### Простой блок кода
 * ```php
 * $code = new Code(
 *     content: '<?php echo "Hello, World!"; ?>',
 *     language: 'php'
 * );
 * echo $code;
 * ```
 *
 * ### С подсветкой и номерами строк
 * ```php
 * $code = new Code(
 *     content: 'function sum(a, b) { return a + b; }',
 *     language: 'javascript',
 *     highlight: true,
 *     lineNumbers: true
 * );
 * ```
 *
 * ### С кнопкой копирования и меткой языка
 * ```php
 * $code = new Code(
 *     content: '<div class="container">Content</div>',
 *     language: 'html',
 *     copyButton: true,
 *     showLanguage: true
 * );
 * ```
 *
 * ## Требования к зависимостям
 *
 * Для использования подсветки синтаксиса требуется установка библиотеки:
 * ```bash
 * composer require scrivo/highlight.php
 * ```
 *
 * Если библиотека не установлена, компонент будет работать в режиме простого текста
 * с автоматическим экранированием HTML-символов.
 *
 * @package OlegV\WallKit\Content\Code
 * @author OlegV
 * @since 1.0.0
 * @version 1.0.0
 * @immutable
 * @readonly
 */
readonly class Code extends Base
{
    use WithInheritance;

    /**
     * Создаёт новый экземпляр компонента Code.
     *
     * @param  string  $content  Исходный код для отображения
     * @param  string  $language  Язык программирования. По умолчанию 'plaintext'.
     *                         Для подсветки должен быть одним из поддерживаемых highlight.php
     * @param  bool  $highlight  Включить подсветку синтаксиса. Требует установки scrivo/highlight.php
     * @param  bool  $lineNumbers  Отображать номера строк слева от кода
     * @param  bool  $copyButton  Добавить кнопку для копирования кода в буфер обмена
     * @param  bool  $showLanguage  Показывать метку с названием языка в правом верхнем углу
     *
     * @throws InvalidArgumentException Если указан неподдерживаемый язык при включённой подсветке
     */
    public function __construct(
        public string $content,
        public string $language = 'plaintext',
        public bool $highlight = false,
        public bool $lineNumbers = false,
        public bool $copyButton = false,
        public bool $showLanguage = false,
    ) {}

    /**
     * Подготовка компонента к рендерингу.
     *
     * Выполняет валидацию параметров и проверку доступности библиотеки подсветки.
     * Вызывается автоматически при рендеринге компонента.
     *
     * @return void
     *
     * @throws InvalidArgumentException Если указан неподдерживаемый язык для подсветки
     * @internal
     */
    protected function prepare(): void
    {
        // Валидация языка (только для подсветки)
        if ($this->highlight && !in_array($this->language, ['plaintext', 'text'], true)) {
            $this->ensureHighlightLibrary();
        }

        // Проверка, установлена ли библиотека подсветки
        if ($this->highlight && !class_exists('Highlight\HighlightResult')) {
            trigger_error(
                'WallKit: Библиотека scrivo/highlight.php не установлена. Подсветка кода отключена. '
                . 'Установите: composer require scrivo/highlight.php',
                E_USER_WARNING,
            );
        }
    }

    /**
     * Проверяет поддержку языка библиотекой highlight.php.
     *
     * Если библиотека установлена, проверяет, входит ли указанный язык
     * в список поддерживаемых. Если язык не поддерживается, выбрасывает исключение.
     *
     * @return void
     *
     * @throws InvalidArgumentException Если язык не поддерживается библиотекой подсветки
     * @internal
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

            if (!in_array($this->language, $highlighter::listBundledLanguages(), true)) {
                throw new InvalidArgumentException(
                    "Язык '$this->language' не поддерживается библиотекой highlight.php. "
                    . "Используйте один из: " . implode(', ', $highlighter::listBundledLanguages()),
                );
            }
        }
    }

    /**
     * Возвращает содержимое кода с применённой подсветкой синтаксиса.
     *
     * Если подсветка отключена или библиотека не установлена, возвращает
     * безопасно экранированный текст.
     *
     * @return string HTML-код с подсветкой синтаксиса или экранированный текст
     *
     * @example
     * ```php
     * $code = new Code('<?php echo "test"; ?>', 'php', true);
     * echo $code->getHighlightedContent();
     * // Возвращает: <span class="hljs-meta">&lt;?php</span> echo <span class="hljs-string">"test"</span>;
     * ```
     */
    public function getHighlightedContent(): string
    {
        if (!$this->highlight || !class_exists('Highlight\Highlighter')) {
            return htmlspecialchars($this->content);
        }

        $highlighter = new Highlighter();
        try {
            $highlighted = $highlighter->highlight($this->language, $this->content);
            if (isset($highlighted->value) && is_string($highlighted->value)) {
                return $highlighted->value;
            } else {
                return htmlspecialchars($this->content);
            }
        } catch (Exception) {
            // В случае ошибки подсветки возвращаем обычный текст
            return htmlspecialchars($this->content);
        }
    }
}
