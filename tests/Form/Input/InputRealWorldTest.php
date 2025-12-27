<?php

declare(strict_types=1);

namespace OlegV\WallKit\Tests\Form\Input;

use OlegV\WallKit\Form\Input\Input;
use PHPUnit\Framework\TestCase;

/**
 * Тесты для проверки совместимости с реальными сценариями использования
 */
class InputRealWorldTest extends TestCase
{
    // ==================== ТЕСТЫ РЕАЛЬНЫХ СЦЕНАРИЕВ ====================

    public function testLoginFormScenario(): void
    {
        $usernameInput = new Input(
            name: 'username',
            label: 'Имя пользователя',
            placeholder: 'Введите ваш логин',
            required: true,
            autoFocus: true,
            autocomplete: 'username',
        );

        $passwordInput = new Input(
            name: 'password',
            label: 'Пароль',
            type: 'password',
            required: true,
            helpText: 'Минимум 8 символов',
            autocomplete: 'current-password',
        );

        $usernameHtml = (string)$usernameInput;
        $passwordHtml = (string)$passwordInput;

        // Проверяем username поле
        $this->assertStringContainsString('name="username"', $usernameHtml);
        $this->assertStringContainsString('Имя пользователя', $usernameHtml);
        $this->assertStringContainsString('required', $usernameHtml);
        $this->assertStringContainsString('autofocus', $usernameHtml);
        $this->assertStringContainsString('autocomplete="username"', $usernameHtml);

        // Проверяем password поле
        $this->assertStringContainsString('type="password"', $passwordHtml);
        $this->assertStringContainsString('name="password"', $passwordHtml);
        $this->assertStringContainsString('Пароль', $passwordHtml);
        $this->assertStringContainsString('Минимум 8 символов', $passwordHtml);
        $this->assertStringContainsString('autocomplete="current-password"', $passwordHtml);
        $this->assertStringContainsString('wallkit-input__toggle-password', $passwordHtml);
    }

    public function testRegistrationFormScenario(): void
    {
        $inputs = [
            new Input(
                name: 'email',
                label: 'Email',
                placeholder: 'example@domain.com',
                type: 'email',
                required: true,
                helpText: 'На этот email придет письмо для подтверждения',
                autocomplete: 'email',
            ),
            new Input(
                name: 'phone',
                label: 'Телефон',
                placeholder: '+7 (___) ___-__-__',
                type: 'tel',
                helpText: 'В формате +7 (XXX) XXX-XX-XX',
                pattern: '^\+7\s?\(?\d{3}\)?\s?\d{3}-\d{2}-\d{2}$',
            ),
            new Input(
                name: 'birth_date',
                label: 'Дата рождения',
                type: 'date',
                helpText: 'Вы должны быть старше 18 лет',
                max: date('Y-m-d'),
            ),
        ];

        foreach ($inputs as $input) {
            $html = (string)$input;

            $this->assertNotEmpty($html);
            $this->assertStringContainsString('wallkit-input', $html);
            $this->assertStringContainsString('wallkit-input__field', $html);

            if ($input->helpText) {
                $this->assertStringContainsString('wallkit-input__help', $html);
                $this->assertStringContainsString($input->helpText, $html);
            }
        }
    }

    public function testSearchFormScenario(): void
    {
        $searchInput = new Input(
            name: 'q',
            placeholder: 'Поиск...',
            type: 'search',
            attributes: [
                'aria-label' => 'Поиск по сайту',
                'role' => 'searchbox',
            ],
            autoFocus: true,
            spellcheck: true,
        );

        $html = (string)$searchInput;

        $this->assertStringContainsString('type="search"', $html);
        $this->assertStringContainsString('placeholder="Поиск..."', $html);
        $this->assertStringContainsString('autofocus', $html);
        $this->assertStringContainsString('spellcheck="true"', $html);
        $this->assertStringContainsString('aria-label="Поиск по сайту"', $html);
        $this->assertStringContainsString('role="searchbox"', $html);
        $this->assertStringNotContainsString('wallkit-input__label', $html);
    }

    public function testErrorHandlingScenario(): void
    {
        // Симуляция отправки формы с ошибками
        $submittedData = [
            'email' => 'invalid-email',
            'password' => '123',
        ];

        $errors = [
            'email' => 'Введите корректный email адрес',
            'password' => 'Пароль должен содержать минимум 8 символов',
        ];

        $emailInput = new Input(
            name: 'email',
            label: 'Email',
            value: $submittedData['email'],
            type: 'email',
            required: true,
            error: $errors['email'],
        );

        $passwordInput = new Input(
            name: 'password',
            label: 'Пароль',
            value: $submittedData['password'],
            type: 'password',
            required: true,
            error: $errors['password'],
            minLength: 8,
        );

        // Проверяем email поле с ошибкой
        $emailHtml = (string)$emailInput;
        $this->assertStringContainsString('wallkit-input--error', $emailHtml);
        $this->assertStringContainsString($errors['email'], $emailHtml);
        $this->assertStringContainsString('value="invalid-email"', $emailHtml);
        $this->assertStringNotContainsString('wallkit-input__help', $emailHtml);

        // Проверяем password поле с ошибкой
        $passwordHtml = (string)$passwordInput;
        $this->assertStringContainsString('wallkit-input--error', $passwordHtml);
        $this->assertStringContainsString($errors['password'], $passwordHtml);
        $this->assertStringContainsString('value="123"', $passwordHtml);
        $this->assertStringContainsString('minlength="8"', $passwordHtml);
    }

    public function testMultiLanguageSupport(): void
    {
        $translations = [
            'en' => [
                'label' => 'Username',
                'placeholder' => 'Enter your username',
                'help' => 'Minimum 3 characters',
                //'error' => 'This field is required',
            ],
            'ru' => [
                'label' => 'Имя пользователя',
                'placeholder' => 'Введите имя пользователя',
                'help' => 'Минимум 3 символа',
                //'error' => 'Это поле обязательно',
            ],
        ];

        foreach ($translations as $texts) {
            $input = new Input(
                name: 'username',
                label: $texts['label'],
                placeholder: $texts['placeholder'],
                required: true,
                helpText: $texts['help'],
                error: $texts['error'],
            );

            $html = (string)$input;

            $this->assertStringContainsString($texts['label'], $html);
            $this->assertStringContainsString($texts['placeholder'], $html);

            if ($texts['help']) {
                $this->assertStringContainsString($texts['help'], $html);
            }

            if ($texts['error']) {
                $this->assertStringContainsString($texts['error'], $html);
            }
        }
    }

    public function testAccessibilityFeatures(): void
    {
        $input = new Input(
            name: 'username',
            label: 'Имя пользователя',
            required: true,
            id: 'username-field',
            attributes: [
                'aria-describedby' => 'username-help',
                'aria-invalid' => 'false',
                'aria-required' => 'true',
            ],
            helpText: 'Обязательное поле',
        );

        $html = (string)$input;

        // Проверяем accessibility атрибуты
        $this->assertStringContainsString('id="username-field"', $html);
        $this->assertStringContainsString('for="username-field"', $html);
        $this->assertStringContainsString('aria-describedby', $html);
        $this->assertStringContainsString('aria-invalid', $html);
        $this->assertStringContainsString('aria-required', $html);

        // Проверяем семантику required
        $this->assertStringContainsString('required', $html);
        $this->assertStringContainsString('wallkit-input__required', $html);
        $this->assertStringContainsString('*', $html);
    }
}