<?php

require __DIR__.'/../../vendor/autoload.php';

use OlegV\BrickManager;
use OlegV\WallKit\Form\Field\Field;
use OlegV\WallKit\Form\Textarea\Textarea;

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WallKit Textarea Component - UI демо</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--wk-font-family), serif;
            color: var(--wk-dark-gray);
            line-height: var(--wk-line-height-base);
            background-color: var(--wk-white);
            font-weight: var(--wk-font-weight-light);
        }

        /* Заголовки */
        h1 {
            font-size: var(--wk-font-size-3xl);
            font-weight: var(--wk-font-weight-normal);
            letter-spacing: -0.02em;
        }

        h2 {
            font-size: var(--wk-font-size-2xl);
            font-weight: var(--wk-font-weight-normal);
            letter-spacing: -0.01em;
        }

        h3 {
            font-size: var(--wk-font-size-xl);
            font-weight: var(--wk-font-weight-medium);
        }

        p {
            font-size: var(--wk-font-size-base);
            font-weight: var(--wk-font-weight-light);
        }

        /* Основные стили для контейнера */
        .demo-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 var(--wk-spacing-6);
        }

        /* Хедер */
        .demo-header {
            margin: var(--wk-spacing-12) 0 var(--wk-spacing-12);
            text-align: center;
            padding-top: var(--wk-spacing-8);
        }

        .demo-title {
            font-size: 2.5rem;
            font-weight: var(--wk-font-weight-normal);
            letter-spacing: -0.02em;
            margin-bottom: var(--wk-spacing-4);
            color: var(--wk-dark-gray);
        }

        .demo-subtitle {
            font-size: var(--wk-font-size-lg);
            color: var(--wk-medium-gray);
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.6;
        }

        /* Сетка */
        .demo-grid {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: var(--wk-spacing-8);
            margin-bottom: var(--wk-spacing-12);
        }

        /* Боковая панель */
        .demo-sidebar {
            position: sticky;
            top: var(--wk-spacing-8);
            height: fit-content;
        }

        .sidebar-card {
            border: 1px solid var(--wk-border);
            background: var(--wk-white);
            padding: var(--wk-spacing-6);
            margin-bottom: var(--wk-spacing-6);
        }

        .sidebar-title {
            font-size: var(--wk-font-size-lg);
            font-weight: var(--wk-font-weight-medium);
            margin-bottom: var(--wk-spacing-4);
            color: var(--wk-dark-gray);
            display: flex;
            align-items: center;
            gap: var(--wk-spacing-3);
        }

        .sidebar-title i {
            color: var(--wk-accent);
        }

        .demo-nav {
            list-style: none;
        }

        .demo-nav li {
            margin-bottom: var(--wk-spacing-2);
        }

        .demo-nav a {
            display: flex;
            align-items: center;
            gap: var(--wk-spacing-3);
            padding: var(--wk-spacing-3);
            color: var(--wk-medium-gray);
            text-decoration: none;
            border-left: 3px solid transparent;
            transition: all var(--wk-transition-base);
            font-size: var(--wk-font-size-sm);
        }

        .demo-nav a:hover,
        .demo-nav a.active {
            background: var(--wk-light-gray);
            color: var(--wk-dark-gray);
            border-left-color: var(--wk-accent);
        }

        .demo-nav a i {
            width: 20px;
            text-align: center;
            font-size: var(--wk-font-size-base);
        }

        /* Основной контент */
        .demo-content {
            display: flex;
            flex-direction: column;
            gap: var(--wk-spacing-12);
        }

        .section-card {
            border: 1px solid var(--wk-border);
            background: var(--wk-white);
            padding: var(--wk-spacing-8);
            transition: all var(--wk-transition-base);
        }

        .section-card:hover {
            box-shadow: var(--wk-shadow-lg);
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: var(--wk-spacing-4);
            margin-bottom: var(--wk-spacing-8);
            padding-bottom: var(--wk-spacing-4);
            border-bottom: 1px solid var(--wk-border);
        }

        .section-icon {
            width: 48px;
            height: 48px;
            background: var(--wk-light-gray);
            border-radius: var(--wk-radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: var(--wk-font-size-xl);
            color: var(--wk-accent);
        }

        .section-title {
            font-size: var(--wk-font-size-2xl);
            font-weight: var(--wk-font-weight-normal);
            color: var(--wk-dark-gray);
        }

        .section-description {
            color: var(--wk-medium-gray);
            margin-top: var(--wk-spacing-2);
            font-size: var(--wk-font-size-sm);
        }

        /* Сетка компонентов */
        .components-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: var(--wk-spacing-6);
            margin-top: var(--wk-spacing-6);
        }

        .component-card {
            border: 1px solid var(--wk-border);
            padding: var(--wk-spacing-6);
            transition: all var(--wk-transition-base);
            background: var(--wk-white);
        }

        .component-card:hover {
            box-shadow: var(--wk-shadow-md);
        }

        .component-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: var(--wk-spacing-4);
        }

        .component-name {
            font-weight: var(--wk-font-weight-medium);
            color: var(--wk-dark-gray);
            font-size: var(--wk-font-size-base);
        }

        .component-badge {
            display: inline-block;
            padding: var(--wk-spacing-1) var(--wk-spacing-3);
            font-size: var(--wk-font-size-xs);
            font-weight: var(--wk-font-weight-semibold);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-radius: var(--wk-radius-sm);
        }

        .badge-textarea {
            background: rgba(156, 39, 176, 0.1);
            color: #9c27b0;
        }

        .badge-field {
            background: rgba(46, 125, 50, 0.1);
            color: #2e7d32;
        }

        .badge-required {
            background: rgba(211, 47, 47, 0.1);
            color: #d32f2f;
        }

        .badge-help {
            background: rgba(2, 136, 209, 0.1);
            color: #0288d1;
        }

        .badge-disabled {
            background: rgba(97, 97, 97, 0.1);
            color: #616161;
        }

        .component-preview {
            min-height: 150px;
            padding: var(--wk-spacing-6);
            background: var(--wk-light-gray);
            border-radius: var(--wk-radius-sm);
            display: flex;
            align-items: flex-start;
            margin-bottom: var(--wk-spacing-4);
            border: 1px solid var(--wk-border);
        }

        .component-description {
            color: var(--wk-medium-gray);
            font-size: var(--wk-font-size-sm);
            line-height: 1.5;
            margin-bottom: var(--wk-spacing-4);
        }

        .component-code {
            background: var(--wk-dark-gray);
            color: var(--wk-light-gray);
            padding: var(--wk-spacing-4);
            border-radius: var(--wk-radius-sm);
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: var(--wk-font-size-2xs);
            overflow-x: auto;
            margin-top: var(--wk-spacing-4);
        }

        .component-code code {
            font-family: inherit;
        }

        /* Демо формы */
        .form-demo {
            background: var(--wk-light-gray);
            border-radius: var(--wk-radius-md);
            padding: var(--wk-spacing-8);
            margin-top: var(--wk-spacing-6);
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: var(--wk-spacing-6);
            margin-bottom: var(--wk-spacing-6);
        }

        .form-actions {
            display: flex;
            gap: var(--wk-spacing-4);
            margin-top: var(--wk-spacing-8);
            padding-top: var(--wk-spacing-6);
            border-top: 1px solid var(--wk-border);
        }

        .btn {
            padding: var(--wk-spacing-3) var(--wk-spacing-8);
            border-radius: var(--wk-radius-md);
            font-weight: var(--wk-font-weight-medium);
            cursor: pointer;
            transition: all var(--wk-transition-base);
            border: 1px solid transparent;
            font-size: var(--wk-font-size-base);
            display: inline-flex;
            align-items: center;
            gap: var(--wk-spacing-2);
            text-decoration: none;
        }

        .btn-primary {
            background: var(--wk-accent);
            color: var(--wk-white);
            border-color: var(--wk-accent);
        }

        .btn-primary:hover {
            background: var(--wk-dark-gray);
            border-color: var(--wk-dark-gray);
        }

        .btn-secondary {
            background: var(--wk-white);
            color: var(--wk-dark-gray);
            border: 1px solid var(--wk-border);
        }

        .btn-secondary:hover {
            background: var(--wk-light-gray);
            border-color: var(--wk-medium-gray);
        }

        /* Адаптивность */
        @media (max-width: 1024px) {
            .demo-grid {
                grid-template-columns: 1fr;
                gap: var(--wk-spacing-6);
            }

            .demo-sidebar {
                position: static;
                margin-bottom: var(--wk-spacing-6);
            }

            .demo-container {
                padding: 0 var(--wk-spacing-4);
            }
        }

        @media (max-width: 768px) {
            .demo-title {
                font-size: var(--wk-font-size-2xl);
            }

            .components-grid {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .section-card {
                padding: var(--wk-spacing-6);
            }
        }

        /* Утилитные классы */
        .mb-2 {
            margin-bottom: var(--wk-spacing-2);
        }

        .mb-4 {
            margin-bottom: var(--wk-spacing-4);
        }

        .mb-6 {
            margin-bottom: var(--wk-spacing-6);
        }

        .mt-2 {
            margin-top: var(--wk-spacing-2);
        }

        .mt-4 {
            margin-top: var(--wk-spacing-4);
        }

        .mt-6 {
            margin-top: var(--wk-spacing-6);
        }

        /* Стили для самих компонентов Textarea */
        .component-preview .wallkit-textarea__field {
            width: 100%;
            min-height: 120px;
        }

        .component-preview .wallkit-field {
            width: 100%;
            margin-bottom: 0;
        }
    </style>
</head>
<body>
<div class="demo-container">
    <!-- Хедер -->
    <div class="demo-header">
        <h1 class="demo-title">WallKit Textarea Component</h1>
        <p class="demo-subtitle">
            Полное руководство по использованию компонента Textarea.
            Многострочные текстовые поля с полной типизацией и валидацией.
        </p>
    </div>

    <div class="demo-grid">
        <!-- Боковая панель -->
        <aside class="demo-sidebar">
            <div class="sidebar-card">
                <h3 class="sidebar-title">
                    <i class="fas fa-bars"></i>
                    Навигация
                </h3>
                <ul class="demo-nav">
                    <li><a href="#basic" class="active">
                            <i class="fas fa-text-height"></i>
                            Базовые примеры
                        </a></li>
                    <li><a href="#sizes">
                            <i class="fas fa-expand-alt"></i>
                            Размеры и строки
                        </a></li>
                    <li><a href="#validation">
                            <i class="fas fa-check-circle"></i>
                            Валидация
                        </a></li>
                    <li><a href="#states">
                            <i class="fas fa-toggle-on"></i>
                            Состояния
                        </a></li>
                    <li><a href="#with-field">
                            <i class="fas fa-layer-group"></i>
                            С обёрткой Field
                        </a></li>
                    <li><a href="#real-world">
                            <i class="fas fa-globe"></i>
                            Реальные примеры
                        </a></li>
                </ul>
            </div>

            <div class="sidebar-card">
                <h3 class="sidebar-title">
                    <i class="fas fa-info-circle"></i>
                    О компоненте
                </h3>
                <p style="font-size: var(--font-size-sm); color: var(--medium-gray); line-height: 1.5;">
                    Textarea — компонент для ввода многострочного текста.
                    Поддерживает все стандартные атрибуты HTML textarea,
                    строгую типизацию и интеграцию с PHPStan.
                </p>
            </div>
        </aside>

        <!-- Основной контент -->
        <main class="demo-content">
            <!-- Секция 1: Базовые примеры -->
            <section id="basic" class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-text-height"></i>
                    </div>
                    <div>
                        <h2 class="section-title">📝 Базовые примеры</h2>
                        <p class="section-description">
                            Простые варианты использования компонента Textarea
                        </p>
                    </div>
                </div>

                <div class="components-grid">
                    <!-- Пример 1 -->
                    <div class="component-card">
                        <div class="component-header">
                            <div class="component-name">Минимальный Textarea</div>
                            <span class="component-badge badge-textarea">Textarea</span>
                        </div>
                        <div class="component-preview">
                            <?php
                            echo new Textarea(name: 'minimal'); ?>
                        </div>
                        <p class="component-description">
                            Самый простой вариант без дополнительных параметров
                        </p>
                        <pre class="component-code"><code>new Textarea(
    name: 'minimal'
)</code></pre>
                    </div>

                    <!-- Пример 2 -->
                    <div class="component-card">
                        <div class="component-header">
                            <div class="component-name">С плейсхолдером</div>
                            <span class="component-badge badge-textarea">Textarea</span>
                        </div>
                        <div class="component-preview">
                            <?php
                            echo new Textarea(
                                name: 'description',
                                placeholder: 'Опишите вашу идею...',
                            ); ?>
                        </div>
                        <p class="component-description">
                            Текстовое поле с подсказкой внутри
                        </p>
                        <pre class="component-code"><code>new Textarea(
    name: 'description',
    placeholder: 'Опишите вашу идею...'
)</code></pre>
                    </div>

                    <!-- Пример 3 -->
                    <div class="component-card">
                        <div class="component-header">
                            <div class="component-name">С предзаполненным текстом</div>
                            <span class="component-badge badge-textarea">Textarea</span>
                        </div>
                        <div class="component-preview">
                            <?php
                            echo new Textarea(
                                name: 'content',
                                value: 'Это предзаполненный текст. Пользователь может его отредактировать или оставить как есть.',
                            ); ?>
                        </div>
                        <p class="component-description">
                            Поле с начальным значением
                        </p>
                        <pre class="component-code"><code>new Textarea(
    name: 'content',
    value: 'Это предзаполненный текст...'
)</code></pre>
                    </div>
                </div>
            </section>

            <!-- Секция 2: Размеры и строки -->
            <section id="sizes" class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-expand-alt"></i>
                    </div>
                    <div>
                        <h2 class="section-title">📏 Размеры и строки</h2>
                        <p class="section-description">
                            Управление размерами и количеством строк
                        </p>
                    </div>
                </div>

                <div class="components-grid">
                    <!-- Пример 4 -->
                    <div class="component-card">
                        <div class="component-header">
                            <div class="component-name">Маленькое поле (2 строки)</div>
                            <span class="component-badge badge-textarea">rows=2</span>
                        </div>
                        <div class="component-preview">
                            <?php
                            echo new Textarea(
                                name: 'short',
                                rows: 2,
                                placeholder: 'Короткий ответ...',
                            ); ?>
                        </div>
                        <p class="component-description">
                            Компактное поле для кратких ответов
                        </p>
                        <pre class="component-code"><code>new Textarea(
    name: 'short',
    rows: 2,
    placeholder: 'Короткий ответ...'
)</code></pre>
                    </div>

                    <!-- Пример 5 -->
                    <div class="component-card">
                        <div class="component-header">
                            <div class="component-name">Стандартное поле (4 строки)</div>
                            <span class="component-badge badge-textarea">rows=4</span>
                        </div>
                        <div class="component-preview">
                            <?php
                            echo new Textarea(
                                name: 'standard',
                                rows: 4,
                                placeholder: 'Стандартный текст...',
                            ); ?>
                        </div>
                        <p class="component-description">
                            Поле стандартного размера по умолчанию
                        </p>
                        <pre class="component-code"><code>new Textarea(
    name: 'standard',
    rows: 4,
    placeholder: 'Стандартный текст...'
)</code></pre>
                    </div>

                    <!-- Пример 6 -->
                    <div class="component-card">
                        <div class="component-header">
                            <div class="component-name">Большое поле (10 строк)</div>
                            <span class="component-badge badge-textarea">rows=10</span>
                        </div>
                        <div class="component-preview">
                            <?php
                            echo new Textarea(
                                name: 'large',
                                rows: 10,
                                placeholder: 'Подробное описание...',
                            ); ?>
                        </div>
                        <p class="component-description">
                            Для длинных текстов, статей, описаний
                        </p>
                        <pre class="component-code"><code>new Textarea(
    name: 'large',
    rows: 10,
    placeholder: 'Подробное описание...'
)</code></pre>
                    </div>
                </div>
            </section>

            <!-- Секция 3: Валидация -->
            <section id="validation" class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <h2 class="section-title">✅ Валидация</h2>
                        <p class="section-description">
                            Проверка вводимых данных
                        </p>
                    </div>
                </div>

                <div class="components-grid">
                    <!-- Пример 7 -->
                    <div class="component-card">
                        <div class="component-header">
                            <div class="component-name">Обязательное поле</div>
                            <span class="component-badge badge-required">required</span>
                        </div>
                        <div class="component-preview">
                            <?php
                            echo new Textarea(
                                name: 'required_field',
                                placeholder: 'Это поле обязательно для заполнения',
                                required: true,
                            ); ?>
                        </div>
                        <p class="component-description">
                            Без этого поля форма не отправится
                        </p>
                        <pre class="component-code"><code>new Textarea(
    name: 'required_field',
    placeholder: 'Это поле обязательно...',
    required: true
)</code></pre>
                    </div>

                    <!-- Пример 8 -->
                    <div class="component-card">
                        <div class="component-header">
                            <div class="component-name">Ограничение длины</div>
                            <span class="component-badge badge-help">maxlength=200</span>
                        </div>
                        <div class="component-preview">
                            <?php
                            echo new Textarea(
                                name: 'limited',
                                placeholder: 'Не более 200 символов',
                                maxLength: 200,
                                value: 'Текст длиной 50 символов...',
                            ); ?>
                        </div>
                        <p class="component-description">
                            Ограничение максимального количества символов
                        </p>
                        <pre class="component-code"><code>new Textarea(
    name: 'limited',
    placeholder: 'Не более 200 символов',
    maxLength: 200
)</code></pre>
                    </div>

                    <!-- Пример 9 -->
                    <div class="component-card">
                        <div class="component-header">
                            <div class="component-name">Проверка орфографии</div>
                            <span class="component-badge badge-help">spellcheck</span>
                        </div>
                        <div class="component-preview">
                            <?php
                            echo new Textarea(
                                name: 'with_spellcheck',
                                placeholder: 'Текст с проверкой орфографии',
                                spellcheck: true,
                                value: 'Некоторые слова могут быть подчеркнуты',
                            ); ?>
                        </div>
                        <p class="component-description">
                            Включена встроенная проверка правописания
                        </p>
                        <pre class="component-code"><code>new Textarea(
    name: 'with_spellcheck',
    placeholder: 'Текст с проверкой...',
    spellcheck: true
)</code></pre>
                    </div>
                </div>
            </section>

            <!-- Секция 4: Состояния -->
            <section id="states" class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-toggle-on"></i>
                    </div>
                    <div>
                        <h2 class="section-title">⚙️ Состояния</h2>
                        <p class="section-description">
                            Различные состояния текстового поля
                        </p>
                    </div>
                </div>

                <div class="components-grid">
                    <!-- Пример 10 -->
                    <div class="component-card">
                        <div class="component-header">
                            <div class="component-name">Только для чтения</div>
                            <span class="component-badge badge-disabled">readonly</span>
                        </div>
                        <div class="component-preview">
                            <?php
                            echo new Textarea(
                                name: 'readonly_field',
                                value: 'Этот текст нельзя изменить',
                                readonly: true,
                            ); ?>
                        </div>
                        <p class="component-description">
                            Пользователь может только читать, но не редактировать
                        </p>
                        <pre class="component-code"><code>new Textarea(
    name: 'readonly_field',
    value: 'Этот текст нельзя изменить',
    readonly: true
)</code></pre>
                    </div>

                    <!-- Пример 11 -->
                    <div class="component-card">
                        <div class="component-header">
                            <div class="component-name">Отключенное поле</div>
                            <span class="component-badge badge-disabled">disabled</span>
                        </div>
                        <div class="component-preview">
                            <?php
                            echo new Textarea(
                                name: 'disabled_field',
                                value: 'Отключенное поле',
                                disabled: true,
                            ); ?>
                        </div>
                        <p class="component-description">
                            Поле полностью неактивно
                        </p>
                        <pre class="component-code"><code>new Textarea(
    name: 'disabled_field',
    value: 'Отключенное поле',
    disabled: true
)</code></pre>
                    </div>

                    <!-- Пример 12 -->
                    <div class="component-card">
                        <div class="component-header">
                            <div class="component-name">Автофокус</div>
                            <span class="component-badge badge-help">autofocus</span>
                        </div>
                        <div class="component-preview">
                            <?php
                            echo new Textarea(
                                name: 'autofocus_field',
                                placeholder: 'Это поле получит фокус автоматически',
                                autoFocus: true,
                            ); ?>
                        </div>
                        <p class="component-description">
                            Фокус автоматически устанавливается на это поле
                        </p>
                        <pre class="component-code"><code>new Textarea(
    name: 'autofocus_field',
    placeholder: 'Это поле получит фокус...',
    autoFocus: true
)</code></pre>
                    </div>
                </div>
            </section>

            <!-- Секция 5: С обёрткой Field -->
            <section id="with-field" class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div>
                        <h2 class="section-title">🎁 С обёрткой Field</h2>
                        <p class="section-description">
                            Textarea в составе компонента Field с меткой, подсказками и ошибками
                        </p>
                    </div>
                </div>

                <div class="components-grid">
                    <!-- Пример 13 -->
                    <div class="component-card">
                        <div class="component-header">
                            <div class="component-name">С меткой и подсказкой</div>
                            <span class="component-badge badge-field">Field</span>
                        </div>
                        <div class="component-preview">
                            <?php
                            echo new Field(
                                input: new Textarea(
                                    name: 'bio',
                                    placeholder: 'Расскажите о себе...',
                                    rows: 4,
                                    id: 'bio-field',
                                ),
                                label: 'Биография',
                                helpText: 'Опишите ваш опыт и достижения',
                            ); ?>
                        </div>
                        <p class="component-description">
                            Полноценное поле с меткой и вспомогательным текстом
                        </p>
                        <pre class="component-code"><code>new Field(
    input: new Textarea(
        name: 'bio',
        placeholder: 'Расскажите о себе...',
        rows: 4,
        id: 'bio-field'
    ),
    label: 'Биография',
    helpText: 'Опишите ваш опыт и достижения'
)</code></pre>
                    </div>

                    <!-- Пример 14 -->
                    <div class="component-card">
                        <div class="component-header">
                            <div class="component-name">С ошибкой валидации</div>
                            <span class="component-badge badge-field badge-required">Field + Error</span>
                        </div>
                        <div class="component-preview">
                            <?php
                            echo new Field(
                                input: new Textarea(
                                    name: 'comment',
                                    value: 'Слишком короткий комментарий',
                                    rows: 3,
                                    id: 'comment-field',
                                ),
                                label: 'Комментарий',
                                error: 'Комментарий должен содержать минимум 50 символов',
                            ); ?>
                        </div>
                        <p class="component-description">
                            Поле с сообщением об ошибке валидации
                        </p>
                        <pre class="component-code"><code>new Field(
    input: new Textarea(
        name: 'comment',
        value: 'Слишком короткий комментарий',
        rows: 3,
        id: 'comment-field'
    ),
    label: 'Комментарий',
    error: 'Комментарий должен содержать...'
)</code></pre>
                    </div>

                    <!-- Пример 15 -->
                    <div class="component-card">
                        <div class="component-header">
                            <div class="component-name">Обязательное с подсказкой</div>
                            <span class="component-badge badge-field badge-required">Field + Required</span>
                        </div>
                        <div class="component-preview">
                            <?php
                            echo new Field(
                                input: new Textarea(
                                    name: 'review',
                                    placeholder: 'Опишите ваши впечатления...',
                                    rows: 5,
                                    required: true,
                                    id: 'review-field',
                                ),
                                label: 'Отзыв о товаре',
                                helpText: 'Пожалуйста, будьте максимально подробны',
                            ); ?>
                        </div>
                        <p class="component-description">
                            Обязательное поле с поясняющим текстом
                        </p>
                        <pre class="component-code"><code>new Field(
    input: new Textarea(
        name: 'review',
        placeholder: 'Опишите ваши впечатления...',
        rows: 5,
        required: true,
        id: 'review-field'
    ),
    label: 'Отзыв о товаре',
    helpText: 'Пожалуйста, будьте максимально подробны'
)</code></pre>
                    </div>
                </div>
            </section>

            <!-- Секция 6: Реальные примеры -->
            <section id="real-world" class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-globe"></i>
                    </div>
                    <div>
                        <h2 class="section-title">🌍 Реальные примеры</h2>
                        <p class="section-description">
                            Примеры использования в реальных сценариях
                        </p>
                    </div>
                </div>

                <div class="form-demo">
                    <h3 style="margin-bottom: var(--spacing-6); color: var(--dark-gray); font-weight: var(--font-weight-medium);">
                        Форма обратной связи
                    </h3>

                    <div class="form-row">
                        <div>
                            <h4 style="color: var(--dark-gray); margin-bottom: var(--spacing-4); font-weight: var(--font-weight-medium);">
                                Контактная информация
                            </h4>
                            <?php
                            $contactFields = [
                                new Field(
                                    input: new Textarea(
                                        name: 'message',
                                        placeholder: 'Опишите вашу проблему или вопрос...',
                                        rows: 6,
                                        required: true,
                                        id: 'message-field',
                                    ),
                                    label: 'Сообщение',
                                    helpText: 'Будьте максимально подробны',
                                ),
                            ];

                            foreach ($contactFields as $field) {
                                echo '<div style="margin-bottom: var(--spacing-4);">'.$field.'</div>';
                            }
                            ?>
                        </div>
                    </div>

                    <div style="margin-top: var(--spacing-8);">
                        <h4 style="color: var(--dark-gray); margin-bottom: var(--spacing-4); font-weight: var(--font-weight-medium);">
                            Дополнительная информация
                        </h4>
                        <div class="form-row">
                            <?php
                            $additionalFields = [
                                new Field(
                                    input: new Textarea(
                                        name: 'expectations',
                                        placeholder: 'Что вы ожидаете от нашей поддержки?',
                                        rows: 4,
                                        id: 'expectations-field',
                                    ),
                                    label: 'Ожидания',
                                    helpText: 'Необязательное поле',
                                ),
                                new Field(
                                    input: new Textarea(
                                        name: 'additional_info',
                                        placeholder: 'Любая дополнительная информация...',
                                        rows: 4,
                                        id: 'additional-info-field',
                                    ),
                                    label: 'Дополнительно',
                                ),
                            ];

                            foreach ($additionalFields as $field) {
                                echo '<div>'.$field.'</div>';
                            }
                            ?>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i>
                            Отправить сообщение
                        </button>
                        <button type="reset" class="btn btn-secondary">
                            <i class="fas fa-undo"></i>
                            Очистить форму
                        </button>
                    </div>
                </div>
            </section>
        </main>
    </div>
</div>
<!-- Подключение стилей и скриптов компонента -->
<?php
echo BrickManager::getInstance()->renderAssets(); ?>
<script>
    // Навигация по секциям
    document.querySelectorAll('.demo-nav a').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetSection = document.getElementById(targetId);

            if (targetSection) {
                window.scrollTo({
                    top: targetSection.offsetTop - 100,
                    behavior: 'smooth'
                });

                // Обновляем активную ссылку
                document.querySelectorAll('.demo-nav a').forEach(a => a.classList.remove('active'));
                this.classList.add('active');
            }
        });
    });

    // Демо отправки формы
    document.querySelector('.btn-primary')?.addEventListener('click', function (e) {
        e.preventDefault();
        alert('✅ Форма обратной связи отправлена! (Демо-режим)');
    });

    // Автоматическое отслеживание активной секции
    const sections = document.querySelectorAll('.section-card');
    const navLinks = document.querySelectorAll('.demo-nav a');

    window.addEventListener('scroll', () => {
        let current = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            if (scrollY >= (sectionTop - 150)) {
                current = section.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href').substring(1) === current) {
                link.classList.add('active');
            }
        });
    });

    // Динамический счётчик символов для полей с maxlength
    document.querySelectorAll('textarea[maxlength]').forEach(textarea => {
        const maxLength = parseInt(textarea.getAttribute('maxlength'));
        const container = textarea.closest('.component-preview') || textarea.closest('.form-demo');

        if (container) {
            const counter = document.createElement('div');
            counter.className = 'char-counter';
            counter.style.fontSize = 'var(--wk-font-size-xs)';
            counter.style.color = 'var(--wk-medium-gray)';
            counter.style.marginTop = 'var(--wk-spacing-2)';
            counter.style.textAlign = 'right';

            const updateCounter = () => {
                const current = textarea.value.length;
                counter.textContent = `${current}/${maxLength}`;
                counter.style.color = current > maxLength * 0.9 ? 'var(--wk-error-color)' : 'var(--wk-medium-gray)';
            };

            textarea.addEventListener('input', updateCounter);
            textarea.parentNode.insertBefore(counter, textarea.nextSibling);
            updateCounter();
        }
    });
</script>
</body>
</html>