<?php

require __DIR__.'/../../vendor/autoload.php';

use OlegV\BrickManager;
use OlegV\WallKit\Form\Field\Field;
use OlegV\WallKit\Form\Input\Input;

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Примеры Input компонента - WallKit</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--wk-font-family), serif;
            line-height: 1.6;
            color: var(--wk-color-gray-900);
            background: var(--wk-color-gray-50);
            min-height: 100vh;
            padding: var(--wk-spacing-4);
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: calc(var(--wk-radius-md) * 2);
            box-shadow: var(--wk-shadow-sm);
            overflow: hidden;
        }

        .header {
            background: var(--wk-color-gray-900);
            color: white;
            padding: var(--wk-spacing-4);
            text-align: center;
        }

        .header h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: var(--wk-spacing-2);
        }

        .header p {
            font-size: var(--wk-font-size-sm);
            opacity: 0.9;
        }

        .content {
            padding: var(--wk-spacing-4);
        }

        .section {
            margin-bottom: var(--wk-spacing-4);
            padding-bottom: var(--wk-spacing-3);
            border-bottom: 1px solid var(--wk-color-gray-200);
        }

        .section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: var(--wk-font-weight-medium);
            color: var(--wk-color-gray-900);
            margin-bottom: var(--wk-spacing-3);
            padding-bottom: var(--wk-spacing-2);
            border-bottom: 2px solid var(--wk-color-blue-500);
        }

        .example-grid {
            display: grid;
            gap: var(--wk-spacing-4);
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        }

        .example-box {
            background: var(--wk-color-gray-50);
            border-radius: var(--wk-radius-md);
            padding: var(--wk-spacing-3);
            border: 1px solid var(--wk-color-gray-200);
            transition: var(--wk-transition-default);
        }

        .example-box:hover {
            border-color: var(--wk-color-blue-500);
            box-shadow: var(--wk-shadow-outline-blue);
        }

        .example-title {
            font-weight: var(--wk-font-weight-medium);
            color: var(--wk-color-gray-700);
            margin-bottom: var(--wk-spacing-3);
            font-size: var(--wk-font-size-base);
        }

        .example-description {
            color: var(--wk-color-gray-500);
            font-size: var(--wk-font-size-sm);
            margin-top: var(--wk-spacing-3);
            line-height: var(--wk-line-height-sm);
        }

        .code-block {
            background: var(--wk-color-gray-900);
            color: var(--wk-color-gray-200);
            padding: var(--wk-spacing-3);
            border-radius: var(--wk-radius-md);
            margin-top: var(--wk-spacing-3);
            font-family: 'Monaco', 'Menlo', monospace;
            font-size: var(--wk-font-size-sm);
            overflow-x: auto;
        }

        .demo-form {
            background: white;
            border-radius: var(--wk-radius-md);
            padding: var(--wk-spacing-4);
            border: 1px solid var(--wk-color-gray-200);
            margin-top: var(--wk-spacing-4);
        }

        .demo-form h3 {
            font-size: 1.125rem;
            font-weight: var(--wk-font-weight-medium);
            margin-bottom: var(--wk-spacing-3);
            color: var(--wk-color-gray-900);
        }

        .input-group {
            margin-bottom: var(--wk-spacing-3);
        }

        .input-group:last-child {
            margin-bottom: 0;
        }

        .form-row {
            display: grid;
            gap: var(--wk-spacing-3);
            margin-bottom: var(--wk-spacing-3);
        }

        .form-actions {
            display: flex;
            gap: var(--wk-spacing-3);
            margin-top: var(--wk-spacing-4);
            padding-top: var(--wk-spacing-3);
            border-top: 1px solid var(--wk-color-gray-200);
        }

        .btn {
            padding: var(--wk-spacing-2) var(--wk-spacing-4);
            border-radius: var(--wk-radius-md);
            font-weight: var(--wk-font-weight-medium);
            cursor: pointer;
            transition: var(--wk-transition-default);
            border: none;
            font-size: var(--wk-font-size-base);
        }

        .btn-primary {
            background: var(--wk-color-blue-500);
            color: white;
        }

        .btn-primary:hover {
            background: #2563eb;
        }

        .btn-secondary {
            background: white;
            color: var(--wk-color-gray-700);
            border: 1px solid var(--wk-color-gray-300);
        }

        .btn-secondary:hover {
            background: var(--wk-color-gray-50);
            border-color: var(--wk-color-gray-400);
        }

        @media (max-width: 640px) {
            body {
                padding: var(--wk-spacing-2);
            }

            .container {
                border-radius: var(--wk-radius-md);
            }

            .header {
                padding: var(--wk-spacing-3);
            }

            .content {
                padding: var(--wk-spacing-3);
            }

            .example-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Input Component</h1>
        <p>Примеры использования компонента Input</p>
    </div>

    <div class="content">
        <div class="section">
            <h2 class="section-title">Базовые поля</h2>

            <div class="example-grid">
                <!-- Пример 1: Чистый Input -->
                <div class="example-box">
                    <div class="example-title">1. Чистый Input (без обёртки)</div>
                    <?php
                    $simpleInput = new Input(
                        name: 'search',
                        placeholder: 'Поиск...',
                    );
                    echo $simpleInput;
                    ?>
                    <div class="example-description">
                        Минимальный вариант без метки и ID
                    </div>
                    <div class="code-block">
                        new Input(<br>
                        &nbsp;&nbsp;name: 'search',<br>
                        &nbsp;&nbsp;placeholder: 'Поиск...'<br>
                        )
                    </div>
                </div>

                <!-- Пример 2: Field с Input внутри -->
                <div class="example-box">
                    <div class="example-title">2. Field с меткой и Input</div>
                    <?php
                    $field = new Field(
                        input: new Input(
                            name: 'username',
                            placeholder: 'Введите имя пользователя',
                            required: true,
                            id: 'username-field',
                        ),
                        label: 'Имя пользователя',
                        helpText: 'Обязательное поле',
                    );
                    echo $field;
                    ?>
                    <div class="example-description">
                        Поле с обёрткой, меткой и подсказкой
                    </div>
                    <div class="code-block">
                        new Field(<br>
                        &nbsp;&nbsp;input: new Input(<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;name: 'username',<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;placeholder: 'Введите имя пользователя',<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;required: true,<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;id: 'username-field'<br>
                        &nbsp;&nbsp;),<br>
                        &nbsp;&nbsp;label: 'Имя пользователя',<br>
                        &nbsp;&nbsp;helpText: 'Обязательное поле'<br>
                        )
                    </div>
                </div>
            </div>
        </div>

        <!-- Секция: Валидация и ошибки -->
        <div class="section">
            <h2 class="section-title">Валидация и ошибки</h2>

            <div class="example-grid">
                <!-- Пример 3: Field с ошибкой -->
                <div class="example-box">
                    <div class="example-title">3. Field с ошибкой</div>
                    <?php
                    $emailField = new Field(
                        input: new Input(
                            name: 'email',
                            value: 'неправильный email',
                            type: 'email',
                            id: 'email-input',
                        ),
                        label: 'Email',
                        error: 'Введите корректный email',
                    );
                    echo $emailField;
                    ?>
                    <div class="example-description">
                        Поле типа email с сообщением об ошибке
                    </div>
                    <div class="code-block">
                        new Field(<br>
                        &nbsp;&nbsp;input: new Input(<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;name: 'email',<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;value: 'неправильный email',<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;type: 'email',<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;id: 'email-input'<br>
                        &nbsp;&nbsp;),<br>
                        &nbsp;&nbsp;label: 'Email',<br>
                        &nbsp;&nbsp;error: 'Введите корректный email'<br>
                        )
                    </div>
                </div>

                <!-- Пример 4: Field с паролем -->
                <div class="example-box">
                    <div class="example-title">4. Field пароля с toggle</div>
                    <?php
                    $passwordField = new Field(
                        input: new Input(
                            name: 'password',
                            type: 'password',
                            id: 'password-input',
                        ),
                        label: 'Пароль',
                        helpText: 'Минимум 8 символов',
                        withPasswordToggle: true,
                    );
                    echo $passwordField;
                    ?>
                    <div class="example-description">
                        Поле пароля с кнопкой показа/скрытия
                    </div>
                    <div class="code-block">
                        new Field(<br>
                        &nbsp;&nbsp;input: new Input(<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;name: 'password',<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;type: 'password',<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;id: 'password-input'<br>
                        &nbsp;&nbsp;),<br>
                        &nbsp;&nbsp;label: 'Пароль',<br>
                        &nbsp;&nbsp;helpText: 'Минимум 8 символов',<br>
                        &nbsp;&nbsp;withPasswordToggle: true<br>
                        )
                    </div>
                </div>
            </div>
        </div>

        <!-- Секция: Разные типы -->
        <div class="section">
            <h2 class="section-title">Разные типы полей</h2>

            <div class="example-grid">
                <!-- Пример 5: Field без toggle -->
                <div class="example-box">
                    <div class="example-title">5. Field пароля без toggle</div>
                    <?php
                    $passwordFieldNoToggle = new Field(
                        input: new Input(
                            name: 'password2',
                            type: 'password',
                            id: 'password-input2',
                        ),
                        label: 'Пароль2',
                        helpText: 'Минимум 8 символов',
                        withPasswordToggle: false,
                    );
                    echo $passwordFieldNoToggle;
                    ?>
                    <div class="example-description">
                        Поле пароля без кнопки показа/скрытия
                    </div>
                    <div class="code-block">
                        new Field(<br>
                        &nbsp;&nbsp;input: new Input(<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;name: 'password2',<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;type: 'password',<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;id: 'password-input2'<br>
                        &nbsp;&nbsp;),<br>
                        &nbsp;&nbsp;label: 'Пароль2',<br>
                        &nbsp;&nbsp;helpText: 'Минимум 8 символов',<br>
                        &nbsp;&nbsp;withPasswordToggle: false<br>
                        )
                    </div>
                </div>

                <!-- Пример 6: Дополнительные типы -->
                <div class="example-box">
                    <div class="example-title">6. Разные типы полей</div>
                    <?php
                    // Телефон
                    $phoneField = new Field(
                        input: new Input(
                            name: 'phone',
                            type: 'tel',
                            placeholder: '+7 (___) ___-__-__',
                            id: 'phone-input',
                        ),
                        label: 'Телефон',
                    );
                    echo $phoneField;
                    echo '<div style="margin-top: var(--wk-spacing-3);"></div>';

                    // Дата
                    $dateField = new Field(
                        input: new Input(
                            name: 'birthday',
                            type: 'date',
                            id: 'date-input',
                        ),
                        label: 'Дата рождения',
                    );
                    echo $dateField;
                    ?>
                    <div class="example-description">
                        Примеры разных типов полей: телефон и дата
                    </div>
                </div>
            </div>
        </div>


        <!-- Секция: Демо форма -->
        <div class="section">
            <h2 class="section-title">Демо форма</h2>

            <div class="demo-form">
                <h3>Пример формы регистрации</h3>

                <?php
                // Демо форма с разными типами полей
                $formFields = [
                    new Field(
                        input: new Input(
                            name: 'full_name',
                            placeholder: 'Иванов Иван Иванович',
                            required: true,
                            id: 'full-name',
                        ),
                        label: 'ФИО',
                    ),
                    new Field(
                        input: new Input(
                            name: 'user_email',
                            placeholder: 'example@domain.com',
                            type: 'email',
                            required: true,
                            id: 'user-email',
                        ),
                        label: 'Email',
                    ),
                    new Field(
                        input: new Input(
                            name: 'user_phone',
                            placeholder: '+7 (999) 123-45-67',
                            type: 'tel',
                            id: 'user-phone',
                        ),
                        label: 'Телефон',
                    ),
                    new Field(
                        input: new Input(
                            name: 'user_password',
                            type: 'password',
                            required: true,
                            id: 'user-password',
                        ),
                        label: 'Пароль',
                        helpText: 'Минимум 8 символов',
                    ),
                ];

                foreach ($formFields as $field) {
                    echo '<div class="input-group">'.$field.'</div>';
                }
                ?>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
                    <button type="reset" class="btn btn-secondary">Очистить форму</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Подключение стилей и скриптов компонента -->
<?php
echo BrickManager::getInstance()->renderAssets(); ?>

<script>
    // Демо отправки формы
    document.querySelector('.btn-primary').addEventListener('click', function (e) {
        e.preventDefault();
        alert('Форма отправлена! (Это демо)');
    });
</script>
</body>
</html>