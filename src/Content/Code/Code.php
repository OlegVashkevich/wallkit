<?php

declare(strict_types=1);

namespace OlegV\WallKit\Content\Code;

use OlegV\WallKit\Base\Base;

/**
 * Компонент для отображения блока кода с подсветкой
 *
 * @property string $content Код
 * @property string $language Язык (php, js, html и т.д.)
 * @property bool $showLineNumbers Показывать нумерацию строк
 * @property bool $inline Инлайн или блочный вывод
 */
readonly class Code extends Base
{
    public string $content;

    public function __construct(
        string $content,
        public string $language = 'plaintext',
        public bool $showLineNumbers = false,
        public bool $inline = false,
    ) {
        $this->content = trim($content);
        parent::__construct();
    }
}