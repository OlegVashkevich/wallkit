<?php

require __DIR__.'/../../vendor/autoload.php';

use OlegV\BrickManager;
use OlegV\WallKit\Content\Code\Code;
use OlegV\WallKit\Form\Field\Field;
use OlegV\WallKit\Form\Input\Input;

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WallKit Input & Field Components - UI демо</title>
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

        .badge-input {
            background: rgba(74, 111, 165, 0.1);
            color: var(--wk-accent);
        }

        .badge-field {
            background: rgba(46, 125, 50, 0.1);
            color: var(--wk-nav-accent);
        }

        .badge-required {
            background: rgba(211, 47, 47, 0.1);
            color: var(--wk-error-color);
        }

        .badge-help {
            background: rgba(2, 136, 209, 0.1);
            color: var(--wk-info-color);
        }

        .component-preview {
            min-height: 100px;
            padding: var(--wk-spacing-6);
            background: var(--wk-light-gray);
            border-radius: var(--wk-radius-sm);
            display: flex;
            align-items: center;
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

        /* Бейджи для состояний */
        .badge {
            display: inline-block;
            padding: var(--wk-spacing-1) var(--wk-spacing-3);
            border-radius: var(--wk-radius-sm);
            font-size: var(--wk-font-size-xs);
            font-weight: var(--wk-font-weight-medium);
            margin-left: var(--wk-spacing-2);
        }

        .badge-success {
            background: rgba(46, 125, 50, 0.1);
            color: var(--wk-success-color);
        }

        .badge-warning {
            background: rgba(237, 108, 2, 0.1);
            color: var(--wk-warning-color);
        }

        .badge-error {
            background: rgba(211, 47, 47, 0.1);
            color: var(--wk-error-color);
        }

        .badge-info {
            background: rgba(2, 136, 209, 0.1);
            color: var(--wk-info-color);
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

        /* Стили для самих компонентов Input/Field */
        .component-preview .wallkit-input__field {
            width: 100%;
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
        <h1 class="demo-title">WallKit UI Components</h1>
        <p class="demo-subtitle">
            Полное руководство по использованию компонентов Input и Field.
            Типизированные PHP компоненты с современным дизайном.
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
                            <i class="fas fa-bolt"></i>
                            Базовые поля
                        </a></li>
                    <li><a href="#validation">
                            <i class="fas fa-check-circle"></i>
                            Валидация
                        </a></li>
                    <li><a href="#types">
                            <i class="fas fa-palette"></i>
                            Типы полей
                        </a></li>
                    <li><a href="#advanced">
                            <i class="fas fa-rocket"></i>
                            Расширенные
                        </a></li>
                    <li><a href="#forms">
                            <i class="fas fa-file-alt"></i>
                            Формы
                        </a></li>
                    <li><a href="#accessibility">
                            <i class="fas fa-universal-access"></i>
                            Доступность
                        </a></li>
                </ul>
            </div>

            <div class="sidebar-card">
                <h3 class="sidebar-title">
                    <i class="fas fa-info-circle"></i>
                    О компонентах
                </h3>
                <p style="font-size: var(--font-size-sm); color: var(--medium-gray); line-height: 1.5;">
                    WallKit Input и Field — строго типизированные PHP компоненты
                    для создания форм с полной поддержкой PHPStan уровня max.
                </p>
            </div>
        </aside>

        <!-- Основной контент -->
        <main class="demo-content">
            <!-- Секция 1: Базовые поля -->
            <section id="basic" class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <div>
                        <h2 class="section-title">⚡ Базовые поля</h2>
                        <p class="section-description">
                            Простые примеры использования компонентов Input и Field
                        </p>
                    </div>
                </div>

                <div class="components-grid">
                    <!-- Пример 1 -->
                    <div class="component-card">
                        <div class="component-header">
                            <div class="component-name">Минимальное поле</div>
                            <span class="component-badge badge-input">Input</span>
                        </div>
                        <div class="component-preview">
                            <?php
                            echo new Input(name: 'minimal'); ?>
                        </div>
                        <p class="component-description">
                            Самый простой вариант без дополнительных параметров
                        </p>
                        <pre class="component-code language-php"><code><?= htmlspecialchars(
                                    <<<HTML
                                        <?php 
                                        echo new Input(
                                            name: 'minimal'
                                         );
                                        HTML,
                                ); ?></code></pre>
                    </div>

                    <!-- Пример 2 -->
                    <div class="component-card">
                        <div class="component-header">
                            <div class="component-name">С плейсхолдером</div>
                            <span class="component-badge badge-input">Input</span>
                        </div>
                        <div class="component-preview">
                            <?php
                            echo new Input(
                                name: 'search',
                                placeholder: 'Введите запрос...',
                            ); ?>
                        </div>
                        <p class="component-description">
                            Поле с текстом-подсказкой внутри
                        </p>
                        <pre class="component-code"><code>new Input(
    name: 'search',
    placeholder: 'Введите запрос...'
)</code></pre>
                    </div>

                    <!-- Пример 3 -->
                    <div class="component-card">
                        <div class="component-header">
                            <div class="component-name">С меткой и значением</div>
                            <span class="component-badge badge-field">Field</span>
                        </div>
                        <div class="component-preview">
                            <?php
                            echo new Field(
                                input: new Input(
                                    name: 'username',
                                    value: 'john_doe',
                                    id: 'user-field',
                                ),
                                label: 'Имя пользователя',
                            ); ?>
                        </div>
                        <p class="component-description">
                            Поле с обёрткой, меткой и предзаполненным значением
                        </p>
                        <pre class="component-code"><code>new Field(
    input: new Input(
        name: 'username',
        value: 'john_doe',
        id: 'user-field'
    ),
    label: 'Имя пользователя'
)</code></pre>
                    </div>
                </div>
            </section>

            <!-- Секция 2: Валидация и ошибки -->
            <section id="validation" class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <h2 class="section-title">✅ Валидация и ошибки</h2>
                        <p class="section-description">
                            Проверка данных и обработка ошибок в формах
                        </p>
                    </div>
                </div>

                <div class="components-grid">
                    <!-- Пример 4 -->
                    <div class="component-card">
                        <div class="component-header">
                            <div class="component-name">Обязательное поле</div>
                            <span class="component-badge badge-required">required</span>
                        </div>
                        <div class="component-preview">
                            <?php
                            echo new Field(
                                input: new Input(
                                    name: 'email',
                                    type: 'email',
                                    required: true,
                                    id: 'email-required',
                                ),
                                label: 'Email',
                            ); ?>
                        </div>
                        <p class="component-description">
                            Поле обязательно для заполнения
                        </p>
                        <pre class="component-code"><code>new Field(
    input: new Input(
        name: 'email',
        type: 'email',
        required: true,
        id: 'email-required'
    ),
    label: 'Email'
)</code></pre>
                    </div>

                    <!-- Пример 5 -->
                    <div class="component-card">
                        <div class="component-header">
                            <div class="component-name">С ошибкой валидации</div>
                            <span class="component-badge badge-error">error</span>
                        </div>
                        <div class="component-preview">
                            <?php
                            echo new Field(
                                input: new Input(
                                    name: 'phone',
                                    value: '123',
                                    type: 'tel',
                                    id: 'phone-error',
                                ),
                                label: 'Телефон',
                                error: 'Неверный формат номера',
                            ); ?>
                        </div>
                        <p class="component-description">
                            Поле с сообщением об ошибке и стилизацией
                        </p>
                        <?= new Code(
                            content: <<<HTML
                                new Field(
                                    input: new Input(
                                        name: 'phone',
                                        value: '123',
                                        type: 'tel',
                                        id: 'phone-error'
                                    ),
                                    label: 'Телефон',
                                    error: 'Неверный формат номера'
                                )
                                HTML,
                            language: 'php',
                            showLineNumbers: false,
                        ); ?>
                        <pre class="component-code"><code>new Field(
    input: new Input(
        name: 'phone',
        value: '123',
        type: 'tel',
        id: 'phone-error'
    ),
    label: 'Телефон',
    error: 'Неверный формат номера'
)</code></pre>
                    </div>

                    <!-- Пример 6 -->
                    <div class="component-card">
                        <div class="component-header">
                            <div class="component-name">Поле с подсказкой</div>
                            <span class="component-badge badge-help">help</span>
                        </div>
                        <div class="component-preview">
                            <?php
                            echo new Field(
                                input: new Input(
                                    name: 'password',
                                    type: 'password',
                                    id: 'pass-help',
                                ),
                                label: 'Пароль',
                                helpText: 'Минимум 8 символов, включая цифры и буквы',
                            ); ?>
                        </div>
                        <p class="component-description">
                            Поле с дополнительной информацией для пользователя
                        </p>
                        <pre class="component-code"><code>new Field(
    input: new Input(
        name: 'password',
        type: 'password',
        id: 'pass-help'
    ),
    label: 'Пароль',
    helpText: 'Минимум 8 символов, включая цифры и буквы'
)</code></pre>
                    </div>
                </div>
            </section>

            <!-- Секция 3: Разные типы полей -->
            <section id="types" class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-palette"></i>
                    </div>
                    <div>
                        <h2 class="section-title">🎨 Разные типы полей</h2>
                        <p class="section-description">
                            Специализированные поля для различных типов данных
                        </p>
                    </div>
                </div>

                <div class="components-grid">
                    <!-- Пример 7 -->
                    <div class="component-card">
                        <div class="component-header">
                            <div class="component-name">Email поле</div>
                            <span class="component-badge badge-input">type="email"</span>
                        </div>
                        <div class="component-preview">
                            <?php
                            echo new Input(
                                name: 'user_email',
                                type: 'email',
                                placeholder: 'example@domain.com',
                                autocomplete: 'email',
                            ); ?>
                        </div>
                        <p class="component-description">
                            Специализированное поле для ввода email
                        </p>
                    </div>

                    <!-- Пример 8 -->
                    <div class="component-card">
                        <div class="component-header">
                            <div class="component-name">Числовое поле</div>
                            <span class="component-badge badge-input">type="number"</span>
                        </div>
                        <div class="component-preview">
                            <?php
                            echo new Input(
                                name: 'age',
                                type: 'number',
                                min: 18,
                                max: 120,
                                step: 1,
                                placeholder: 'Введите возраст',
                            ); ?>
                        </div>
                        <p class="component-description">
                            Поле с валидацией числового диапазона
                        </p>
                    </div>

                    <!-- Пример 9 -->
                    <div class="component-card">
                        <div class="component-header">
                            <div class="component-name">Поисковое поле</div>
                            <span class="component-badge badge-input">type="search"</span>
                        </div>
                        <div class="component-preview">
                            <?php
                            echo new Input(
                                name: 'query',
                                type: 'search',
                                placeholder: 'Поиск...',
                                spellcheck: true,
                            ); ?>
                        </div>
                        <p class="component-description">
                            Специальное поле для поисковых запросов
                        </p>
                    </div>
                </div>
            </section>

            <!-- Секция 5: Реальные формы -->
            <section id="forms" class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div>
                        <h2 class="section-title">📋 Реальные формы</h2>
                        <p class="section-description">
                            Примеры использования в реальных сценариях
                        </p>
                    </div>
                </div>

                <div class="form-demo">
                    <h3 style="margin-bottom: var(--spacing-6); color: var(--dark-gray); font-weight: var(--font-weight-medium);">
                        Форма регистрации пользователя
                    </h3>

                    <div class="form-row">
                        <!-- Личная информация -->
                        <div>
                            <h4 style="color: var(--dark-gray); margin-bottom: var(--spacing-4); font-weight: var(--font-weight-medium);">
                                Личная информация
                            </h4>
                            <?php
                            $personalFields = [
                                new Field(
                                    input: new Input(
                                        name: 'first_name',
                                        placeholder: 'Иван',
                                        required: true,
                                        id: 'first-name',
                                    ),
                                    label: 'Имя',
                                ),
                                new Field(
                                    input: new Input(
                                        name: 'last_name',
                                        placeholder: 'Иванов',
                                        required: true,
                                        id: 'last-name',
                                    ),
                                    label: 'Фамилия',
                                ),
                                new Field(
                                    input: new Input(
                                        name: 'birth_date',
                                        type: 'date',
                                        id: 'birth-date',
                                    ),
                                    label: 'Дата рождения',
                                ),
                            ];

                            foreach ($personalFields as $field) {
                                echo '<div style="margin-bottom: var(--spacing-4);">'.$field.'</div>';
                            }
                            ?>
                        </div>

                        <!-- Контактная информация -->
                        <div>
                            <h4 style="color: var(--dark-gray); margin-bottom: var(--spacing-4); font-weight: var(--font-weight-medium);">
                                Контактная информация
                            </h4>
                            <?php
                            $contactFields = [
                                new Field(
                                    input: new Input(
                                        name: 'email',
                                        type: 'email',
                                        placeholder: 'ivan@example.com',
                                        required: true,
                                        id: 'user-email',
                                    ),
                                    label: 'Email',
                                ),
                                new Field(
                                    input: new Input(
                                        name: 'phone',
                                        type: 'tel',
                                        placeholder: '+7 (999) 123-45-67',
                                        id: 'user-phone',
                                    ),
                                    label: 'Телефон',
                                ),
                            ];

                            foreach ($contactFields as $field) {
                                echo '<div style="margin-bottom: var(--spacing-4);">'.$field.'</div>';
                            }
                            ?>
                        </div>
                    </div>

                    <!-- Пароль -->
                    <div style="margin-top: var(--spacing-8);">
                        <h4 style="color: var(--dark-gray); margin-bottom: var(--spacing-4); font-weight: var(--font-weight-medium);">
                            Безопасность
                        </h4>
                        <div class="form-row">
                            <?php
                            $securityFields = [
                                new Field(
                                    input: new Input(
                                        name: 'password',
                                        type: 'password',
                                        required: true,
                                        id: 'user-password',
                                        minLength: 8,
                                    ),
                                    label: 'Пароль',
                                    helpText: 'Минимум 8 символов',
                                ),
                                new Field(
                                    input: new Input(
                                        name: 'password_confirm',
                                        type: 'password',
                                        required: true,
                                        id: 'user-password-confirm',
                                    ),
                                    label: 'Подтверждение пароля',
                                ),
                            ];

                            foreach ($securityFields as $field) {
                                echo '<div>'.$field.'</div>';
                            }
                            ?>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-user-plus"></i>
                            Зарегистрироваться
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
        alert('✅ Форма успешно отправлена! (Демо-режим)');
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
</script>
<!--link href="./prism.css" rel="stylesheet"/-->
<!--script src="./prism.js"></script-->
</body>
</html>