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
            font-family: var(--wk-font-family);
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
        <?php
        require __DIR__.'/../../vendor/autoload.php';

        use OlegV\BrickManager;
        use OlegV\WallKit\Form\Input\Input;

        // Секция: Основные примеры
        ?>
        <div class="section">
            <h2 class="section-title">Базовые поля</h2>

            <div class="example-grid">
                <!-- Пример 1: Простое поле -->
                <div class="example-box">
                    <div class="example-title">1. Простой поиск</div>
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

                <!-- Пример 2: Поле с меткой -->
                <div class="example-box">
                    <div class="example-title">2. Имя пользователя</div>
                    <?php
                    $username = new Input(
                        name: 'username',
                        label: 'Имя пользователя',
                        required: true,
                        id: 'username-field',
                    );
                    echo $username;
                    ?>
                    <div class="example-description">
                        Обязательное поле с меткой и ID
                    </div>
                    <div class="code-block">
                        new Input(<br>
                        &nbsp;&nbsp;name: 'username',<br>
                        &nbsp;&nbsp;label: 'Имя пользователя',<br>
                        &nbsp;&nbsp;required: true,<br>
                        &nbsp;&nbsp;id: 'username-field'<br>
                        )
                    </div>
                </div>
            </div>
        </div>

        <!-- Секция: Валидация и ошибки -->
        <div class="section">
            <h2 class="section-title">Валидация и ошибки</h2>

            <div class="example-grid">
                <!-- Пример 3: Поле с ошибкой -->
                <div class="example-box">
                    <div class="example-title">3. Email с ошибкой</div>
                    <?php
                    $email = new Input(
                        name: 'email',
                        label: 'Email',
                        value: 'неправильный email',
                        type: 'email',
                        id: 'email-input',
                        error: 'Введите корректный email',
                    );
                    echo $email;
                    ?>
                    <div class="example-description">
                        Поле типа email с сообщением об ошибке
                    </div>
                    <div class="code-block">
                        new Input(<br>
                        &nbsp;&nbsp;name: 'email',<br>
                        &nbsp;&nbsp;label: 'Email',<br>
                        &nbsp;&nbsp;value: 'неправильный email',<br>
                        &nbsp;&nbsp;type: 'email',<br>
                        &nbsp;&nbsp;id: 'email-input',<br>
                        &nbsp;&nbsp;error: 'Введите корректный email'<br>
                        )
                    </div>
                </div>

                <!-- Пример 4: Поле пароля -->
                <div class="example-box">
                    <div class="example-title">4. Поле пароля</div>
                    <?php
                    $password = new Input(
                        name: 'password',
                        label: 'Пароль',
                        type: 'password',
                        id: 'password-input',
                        helpText: 'Минимум 8 символов',
                    );
                    echo $password;
                    ?>
                    <div class="example-description">
                        Поле пароля с подсказкой и кнопкой показа/скрытия
                    </div>
                    <div class="code-block">
                        new Input(<br>
                        &nbsp;&nbsp;name: 'password',<br>
                        &nbsp;&nbsp;label: 'Пароль',<br>
                        &nbsp;&nbsp;type: 'password',<br>
                        &nbsp;&nbsp;id: 'password-input',<br>
                        &nbsp;&nbsp;helpText: 'Минимум 8 символов'<br>
                        )
                    </div>
                </div>
            </div>
        </div>

        <!-- Секция: Разные типы -->
        <div class="section">
            <h2 class="section-title">Разные типы полей</h2>

            <div class="example-grid">
                <!-- Пример 5: Поле пароля без toggle -->
                <div class="example-box">
                    <div class="example-title">5. Пароль без переключения</div>
                    <?php
                    $password2 = new Input(
                        name: 'password2',
                        label: 'Пароль2',
                        type: 'password',
                        id: 'password-input2',
                        helpText: 'Минимум 8 символов',
                        withPasswordToggle: false,
                    );
                    echo $password2;
                    ?>
                    <div class="example-description">
                        Поле пароля без кнопки показа/скрытия
                    </div>
                    <div class="code-block">
                        new Input(<br>
                        &nbsp;&nbsp;name: 'password2',<br>
                        &nbsp;&nbsp;label: 'Пароль2',<br>
                        &nbsp;&nbsp;type: 'password',<br>
                        &nbsp;&nbsp;id: 'password-input2',<br>
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
                    $phone = new Input(
                        name: 'phone',
                        label: 'Телефон',
                        type: 'tel',
                        placeholder: '+7 (___) ___-__-__',
                        id: 'phone-input',
                    );
                    echo $phone;
                    echo '<div style="margin-top: var(--wk-spacing-3);"></div>';

                    // Дата
                    $date = new Input(
                        name: 'birthday',
                        label: 'Дата рождения',
                        type: 'date',
                        id: 'date-input',
                    );
                    echo $date;
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
                    new Input(
                        name: 'full_name',
                        label: 'ФИО',
                        placeholder: 'Иванов Иван Иванович',
                        required: true,
                        id: 'full-name',
                    ),
                    new Input(
                        name: 'user_email',
                        label: 'Email',
                        type: 'email',
                        placeholder: 'example@domain.com',
                        required: true,
                        id: 'user-email',
                    ),
                    new Input(
                        name: 'user_phone',
                        label: 'Телефон',
                        type: 'tel',
                        placeholder: '+7 (999) 123-45-67',
                        id: 'user-phone',
                    ),
                    new Input(
                        name: 'user_password',
                        label: 'Пароль',
                        type: 'password',
                        required: true,
                        id: 'user-password',
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