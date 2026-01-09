<?php

namespace OlegV\WallKit\Content\Markdown;

use OlegV\WallKit\Content\Code\Code;
use Parsedown;

/**
 * Расширенный Parsedown с поддержкой кастомного компонента Code
 *
 * Этот класс расширяет стандартный парсер Parsedown для интеграции с компонентом
 * Code из WallKit. Вместо стандартного рендеринга блоков кода используется
 * компонент `Code` с подсветкой синтаксиса и дополнительными возможностями.
 *
 * ## Особенности
 *
 * - Автоматическое определение языка из fenced code blocks (```php, ```js и т.д.)
 * - Использование компонента `Code` для рендеринга с подсветкой синтаксиса
 * - Поддержка безопасного режима Parsedown
 * - Сохранение всех стандартных возможностей Parsedown
 *
 * ## Пример использования
 *
 * ```php
 * $parser = new ParsedownEx();
 * $html = $parser->text($markdownContent);
 * ```
 *
 * Будет преобразовано:
 * ```markdown
 * ```php
 * echo "Hello World";
 * ```
 * ```
 *
 * В:
 * ```html
 * <div class="wallkit-code">
 *   <!-- Рендеринг через компонент Code -->
 * </div>
 * ```
 *
 * @package OlegV\WallKit\Content\Markdown
 * @author OlegV
 * @since 1.0.0
 * @version 1.0.0
 *
 * @extends Parsedown
 */
class ParsedownEx extends Parsedown
{
    /**
     * Обработка fenced code blocks (```)
     */
    protected function blockFencedCode($Line)
    {
        $block = parent::blockFencedCode($Line);

        if ($block !== null) {
            // Помечаем блок для кастомной обработки
            $block['custom'] = true;
        }

        return $block;
    }

    /**
     * Завершение обработки fenced code blocks
     */
    protected function blockFencedCodeComplete($Block)
    {
        if (isset($Block['custom']) && $Block['custom'] === true) {
            // Получаем код и язык
            $code = $Block['element']['text']['text'];

            // Извлекаем язык из атрибутов
            $language = 'text';
            if (isset($Block['element']['text']['attributes']['class'])) {
                $class = $Block['element']['text']['attributes']['class'];
                if (str_starts_with($class, 'language-')) {
                    $language = substr($class, 9);
                }
            }

            // Используем ваш компонент Code
            $codeComponent = new Code(
                content: $code,
                language: $language,
                highlight: true,
                lineNumbers: strlen($code) > 250, // для длинного кода
                copyButton: true,
                showLanguage: $language !== 'text',// кроме текста
            );

            // Используем rawHtml для вывода без экранирования
            $Block['element'] = [
                'name' => 'div',
                'rawHtml' => (string)$codeComponent,
                'allowRawHtmlInSafeMode' => true, // Разрешаем raw HTML даже в безопасном режиме
            ];

            unset($Block['custom']);
        }

        return $Block;
    }
}