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
            placeholder: 'Введите ваш логин',
            required: true,
            id: 'username-field',
            autoFocus: true,
            autocomplete: 'username',
        );

        $passwordInput = new Input(
            name: 'password',
            type: 'password',
            required: true,
            id: 'password-field',
            minLength: 8,
            autocomplete: 'current-password',
        );

        $usernameHtml = (string) $usernameInput;
        $passwordHtml = (string) $passwordInput;

        // Проверяем username поле
        $this->assertStringContainsString('name="username"', $usernameHtml);
        $this->assertStringContainsString('placeholder="Введите ваш логин"', $usernameHtml);
        $this->assertStringContainsString('required', $usernameHtml);
        $this->assertStringContainsString('autofocus', $usernameHtml);
        $this->assertStringContainsString('autocomplete="username"', $usernameHtml);
        $this->assertStringContainsString('id="username-field"', $usernameHtml);
        $this->assertStringContainsString('wallkit-input__field', $usernameHtml);

        // Проверяем password поле
        $this->assertStringContainsString('type="password"', $passwordHtml);
        $this->assertStringContainsString('name="password"', $passwordHtml);
        $this->assertStringContainsString('required', $passwordHtml);
        $this->assertStringContainsString('autocomplete="current-password"', $passwordHtml);
        $this->assertStringContainsString('id="password-field"', $passwordHtml);
        $this->assertStringContainsString('minlength="8"', $passwordHtml);
        $this->assertStringContainsString('wallkit-input__field', $passwordHtml);
    }

    public function testRegistrationFormScenario(): void
    {
        $inputs = [
            new Input(
                name: 'email',
                placeholder: 'example@domain.com',
                type: 'email',
                required: true,
                id: 'email-field',
                autocomplete: 'email',
            ),
            new Input(
                name: 'phone',
                placeholder: '+7 (___) ___-__-__',
                type: 'tel',
                id: 'phone-field',
                pattern: '^\+7\s?\(?\d{3}\)?\s?\d{3}-\d{2}-\d{2}$',
            ),
            new Input(
                name: 'birth_date',
                type: 'date',
                id: 'birth-date-field',
                max: date('Y-m-d'),
            ),
        ];

        foreach ($inputs as $input) {
            $html = (string) $input;

            $this->assertNotEmpty($html);
            $this->assertStringContainsString('wallkit-input__field', $html);
            $this->assertStringContainsString('name="' . $input->name . '"', $html);
            $this->assertStringContainsString('type="' . $input->type . '"', $html);

            if ($input->placeholder) {
                $this->assertStringContainsString('placeholder="' . $input->placeholder . '"', $html);
            }

            if ($input->required) {
                $this->assertStringContainsString('required', $html);
            }
        }
    }

    public function testSearchFormScenario(): void
    {
        $searchInput = new Input(
            name: 'q',
            placeholder: 'Поиск...',
            type: 'search',
            id: 'search-field',
            attributes: [
                'aria-label' => 'Поиск по сайту',
                'role' => 'searchbox',
                'data-search' => 'true',
            ],
            autoFocus: true,
            spellcheck: true,
        );

        $html = (string) $searchInput;

        $this->assertStringContainsString('type="search"', $html);
        $this->assertStringContainsString('name="q"', $html);
        $this->assertStringContainsString('placeholder="Поиск..."', $html);
        $this->assertStringContainsString('autofocus', $html);
        $this->assertStringContainsString('spellcheck="true"', $html);
        $this->assertStringContainsString('aria-label="Поиск по сайту"', $html);
        $this->assertStringContainsString('role="searchbox"', $html);
        $this->assertStringContainsString('data-search="true"', $html);
        $this->assertStringContainsString('id="search-field"', $html);
        $this->assertStringContainsString('wallkit-input__field', $html);
    }

    public function testContactFormScenario(): void
    {
        $contactInputs = [
            new Input(
                name: 'name',
                placeholder: 'Ваше имя',
                required: true,
                id: 'name-field',
                maxLength: 100,
            ),
            new Input(
                name: 'email',
                placeholder: 'Ваш email',
                type: 'email',
                required: true,
                id: 'email-field',
            ),
            new Input(
                name: 'subject',
                placeholder: 'Тема сообщения',
                id: 'subject-field',
                maxLength: 200,
            ),
            new Input(
                name: 'phone',
                placeholder: 'Телефон для связи',
                type: 'tel',
                id: 'phone-field',
                pattern: '^\+?[0-9\s\-\(\)]+$',
            ),
        ];

        foreach ($contactInputs as $input) {
            $html = (string) $input;

            $this->assertStringContainsString('name="' . $input->name . '"', $html);
            $this->assertStringContainsString('placeholder="' . $input->placeholder . '"', $html);
            $this->assertStringContainsString('id="' . $input->id . '"', $html);
            $this->assertStringContainsString('wallkit-input__field', $html);

            if ($input->required) {
                $this->assertStringContainsString('required', $html);
            }

            if ($input->type !== 'text') {
                $this->assertStringContainsString('type="' . $input->type . '"', $html);
            }
        }
    }

    public function testSettingsFormScenario(): void
    {
        $settingsInputs = [
            // Настройки профиля
            new Input(
                name: 'display_name',
                value: 'John Doe',
                id: 'display-name-field',
                maxLength: 50,
            ),
            new Input(
                name: 'website',
                placeholder: 'https://example.com',
                type: 'url',
                id: 'website-field',
            ),
            new Input(
                name: 'location',
                placeholder: 'Город, страна',
                id: 'location-field',
            ),
            new Input(
                name: 'twitter',
                placeholder: '@username',
                id: 'twitter-field',
                pattern: '^@[A-Za-z0-9_]{1,15}$',
            ),
            // Настройки безопасности
            new Input(
                name: 'current_password',
                type: 'password',
                required: true,
                id: 'current-password-field',
                autocomplete: 'current-password',
            ),
            new Input(
                name: 'new_password',
                type: 'password',
                required: true,
                id: 'new-password-field',
                minLength: 12,
                autocomplete: 'new-password',
            ),
            new Input(
                name: 'confirm_password',
                type: 'password',
                required: true,
                id: 'confirm-password-field',
                autocomplete: 'new-password',
            ),
            // Настройки уведомлений
            new Input(
                name: 'notification_email',
                value: 'user@example.com',
                type: 'email',
                required: true,
                id: 'notification-email-field',
            ),
        ];

        foreach ($settingsInputs as $input) {
            $html = (string) $input;

            $this->assertStringContainsString('name="' . $input->name . '"', $html);
            $this->assertStringContainsString('wallkit-input__field', $html);

            // Проверяем специфичные атрибуты для каждого типа
            if ($input->value) {
                $this->assertStringContainsString('value="' . $input->value . '"', $html);
            }

            if ($input->type === 'password') {
                $this->assertStringContainsString('autocomplete="' . $input->autocomplete . '"', $html);
            }

            if ($input->minLength) {
                $this->assertStringContainsString('minlength="' . $input->minLength . '"', $html);
            }
        }
    }

    public function testEcommerceFormScenario(): void
    {
        $checkoutInputs = [
            // Информация о покупателе
            new Input(
                name: 'first_name',
                placeholder: 'Имя',
                required: true,
                id: 'first-name-field',
            ),
            new Input(
                name: 'last_name',
                placeholder: 'Фамилия',
                required: true,
                id: 'last-name-field',
            ),
            new Input(
                name: 'email',
                placeholder: 'email@example.com',
                type: 'email',
                required: true,
                id: 'email-field',
            ),
            new Input(
                name: 'phone',
                placeholder: '+7 (999) 123-45-67',
                type: 'tel',
                required: true,
                id: 'phone-field',
            ),
            // Адрес доставки
            new Input(
                name: 'address',
                placeholder: 'Улица, дом, квартира',
                required: true,
                id: 'address-field',
            ),
            new Input(
                name: 'city',
                placeholder: 'Город',
                required: true,
                id: 'city-field',
            ),
            new Input(
                name: 'postal_code',
                placeholder: 'Почтовый индекс',
                id: 'postal-code-field',
                pattern: '^\d{6}$',
            ),
            // Платежная информация
            new Input(
                name: 'card_number',
                placeholder: 'Номер карты',
                id: 'card-number-field',
                pattern: '^\d{16}$',
                maxLength: 16,
            ),
            new Input(
                name: 'card_expiry',
                placeholder: 'ММ/ГГ',
                id: 'card-expiry-field',
                pattern: '^(0[1-9]|1[0-2])\/\d{2}$',
            ),
            new Input(
                name: 'card_cvc',
                placeholder: 'CVC',
                type: 'password',
                id: 'card-cvc-field',
                pattern: '^\d{3,4}$',
                maxLength: 4,
            ),
        ];

        foreach ($checkoutInputs as $input) {
            $html = (string) $input;

            $this->assertStringContainsString('name="' . $input->name . '"', $html);
            $this->assertStringContainsString('wallkit-input__field', $html);

            // Проверяем, что все обязательные поля имеют required
            if ($input->required) {
                $this->assertStringContainsString('required', $html);
            }

            // Проверяем паттерны для валидации
            if ($input->pattern) {
                $this->assertStringContainsString('pattern="' . $input->pattern . '"', $html);
            }

            // Специальные проверки для конфиденциальных полей
            if ($input->name === 'card_cvc') {
                $this->assertStringContainsString('type="password"', $html);
            }
        }
    }

    public function testAccessibilityFeatures(): void
    {
        $accessibleInput = new Input(
            name: 'username',
            placeholder: 'Введите имя пользователя',
            required: true,
            id: 'username-field',
            attributes: [
                'aria-describedby' => 'username-help',
                'aria-invalid' => 'false',
                'aria-required' => 'true',
                'aria-label' => 'Поле для ввода имени пользователя',
                'title' => 'Обязательное поле для входа в систему',
            ],
        );

        $html = (string) $accessibleInput;

        // Проверяем базовые accessibility атрибуты
        $this->assertStringContainsString('id="username-field"', $html);
        $this->assertStringContainsString('name="username"', $html);
        $this->assertStringContainsString('required', $html);

        // Проверяем ARIA атрибуты
        $this->assertStringContainsString('aria-describedby="username-help"', $html);
        $this->assertStringContainsString('aria-invalid="false"', $html);
        $this->assertStringContainsString('aria-required="true"', $html);
        $this->assertStringContainsString('aria-label="Поле для ввода имени пользователя"', $html);

        // Проверяем title для дополнительной информации
        $this->assertStringContainsString('title="Обязательное поле для входа в систему"', $html);

        // Проверяем CSS класс
        $this->assertStringContainsString('wallkit-input__field', $html);
    }

    public function testMultilingualFormScenario(): void
    {
        $multilingualInputs = [
            // Русская версия
            new Input(
                name: 'имя',
                placeholder: 'Введите ваше имя',
                required: true,
                id: 'имя-поле',
                attributes: [
                    'lang' => 'ru',
                    'dir' => 'ltr',
                ],
            ),
            // Английская версия
            new Input(
                name: 'name',
                placeholder: 'Enter your name',
                required: true,
                id: 'name-field',
                attributes: [
                    'lang' => 'en',
                    'dir' => 'ltr',
                ],
            ),
            // Арабская версия (RTL)
            new Input(
                name: 'اسم',
                placeholder: 'أدخل اسمك',
                required: true,
                id: 'اسم-حقل',
                attributes: [
                    'lang' => 'ar',
                    'dir' => 'rtl',
                ],
            ),
        ];

        foreach ($multilingualInputs as $input) {
            $html = (string) $input;

            $this->assertStringContainsString('name="' . $input->name . '"', $html);
            $this->assertStringContainsString('placeholder="' . $input->placeholder . '"', $html);
            $this->assertStringContainsString('id="' . $input->id . '"', $html);
            $this->assertStringContainsString('required', $html);
            $this->assertStringContainsString('lang="' . $input->attributes['lang'] . '"', $html);
            $this->assertStringContainsString('dir="' . $input->attributes['dir'] . '"', $html);
            $this->assertStringContainsString('wallkit-input__field', $html);
        }
    }
}
