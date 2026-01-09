<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use OlegV\BrickManager;
use OlegV\WallKit\Content\Markdown\Markdown;

BrickManager::enableDebug();
// Чтение Markdown из файла
function renderMarkdownFile(string $filePath): string
{
    if (!file_exists($filePath)) {
        throw new RuntimeException("File not found: $filePath");
    }

    $content = file_get_contents($filePath);

    // Автоматическое определение расширений по содержимому
    /*$extensions = ['headers', 'bold', 'italic', 'links', 'lists', 'code'];

    if (str_contains($content, '---')) {
        $extensions[] = 'tables';
    }

    if (str_contains($content, '~~')) {
        $extensions[] = 'strikethrough';
    }

    if (str_contains($content, '> ')) {
        $extensions[] = 'blockquotes';
    }*/

    // Создаем Markdown компонент
    $markdown = new Markdown(
        content: $content,
        safeMode: true, // Всегда безопасно для внешних файлов
    );

    return (string)$markdown;
}

// Пример использования
$readmeContent = renderMarkdownFile(__DIR__.'/../README.md');
/*$readmeContent = new Markdown(
    content: '',
    safeMode: true, // Всегда безопасно для внешних файлов
);*/
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Markdown из файла</title>
</head>
<body>
<div style="max-width: 800px; margin: 0 auto; padding: 2rem;">
    <?= $readmeContent ?>
</div>
<!-- Подключение стилей и скриптов компонента -->
<?php
echo BrickManager::getInstance()->renderAssets(); ?>
</body>
</html>
