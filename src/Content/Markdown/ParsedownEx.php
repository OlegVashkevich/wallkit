<?php

namespace OlegV\WallKit\Content\Markdown;

use OlegV\WallKit\Content\Code\Code;
use Parsedown;

/**
 * Расширенный Parsedown с поддержкой кастомного компонента Code
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
            var_dump($language);
            // Используем ваш компонент Code
            $codeComponent = new Code(
                content: $code,
                language: $language,
                highlight: true,
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