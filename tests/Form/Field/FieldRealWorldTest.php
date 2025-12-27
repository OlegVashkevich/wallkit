<?php

declare(strict_types=1);

namespace OlegV\WallKit\Tests\Form\Field;

use OlegV\WallKit\Form\Field\Field;
use OlegV\WallKit\Form\Input\Input;
use PHPUnit\Framework\TestCase;

class FieldRealWorldTest extends TestCase
{
    public function testLoginFormScenario(): void
    {
        $usernameField = new Field(
            input: new Input(
                name: 'username',
                placeholder: 'Введите ваш логин',
                required: true,
                id: 'username-field',
                autoFocus: true,
                autocomplete: 'username',
            ),
            label: 'Имя пользователя',
        );

        $passwordField = new Field(
            input: new Input(
                name: 'password',
                type: 'password',
                required: true,
                id: 'password-field',
                autocomplete: 'current-password',
            ),
            label: 'Пароль',
            helpText: 'Минимум 8 символов',
        );

        $usernameHtml = (string)$usernameField;
        $passwordHtml = (string)$passwordField;

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
        $this->assertStringContainsString('wallkit-field__toggle-password', $passwordHtml);
    }

    public function testErrorHandlingScenario(): void
    {
        // Симуляция отправки формы с ошибками
        $emailField = new Field(
            input: new Input(
                name: 'email',
                value: 'invalid-email',
                type: 'email',
                id: 'email-field',
            ),
            label: 'Email',
            error: 'Введите корректный email адрес',
        );

        $passwordField = new Field(
            input: new Input(
                name: 'password',
                value: '123',
                type: 'password',
                id: 'password-field',
                minLength: 8,
            ),
            label: 'Пароль',
            error: 'Пароль должен содержать минимум 8 символов',
        );

        // Проверяем email поле с ошибкой
        $emailHtml = (string)$emailField;
        $this->assertStringContainsString('wallkit-field--error', $emailHtml);
        $this->assertStringContainsString('Введите корректный email адрес', $emailHtml);
        $this->assertStringContainsString('value="invalid-email"', $emailHtml);

        // Проверяем password поле с ошибкой
        $passwordHtml = (string)$passwordField;
        $this->assertStringContainsString('wallkit-field--error', $passwordHtml);
        $this->assertStringContainsString('Пароль должен содержать минимум 8 символов', $passwordHtml);
        $this->assertStringContainsString('value="123"', $passwordHtml);
        $this->assertStringContainsString('minlength="8"', $passwordHtml);
    }
}