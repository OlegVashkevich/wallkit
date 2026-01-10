/**
 * Обработчик формы для WallKit
 *
 * Минимальная JS логика для обработки отправки форм.
 * Использует прогрессивное улучшение - форма работает и без JS.
 */

document.addEventListener('DOMContentLoaded', function() {
  const forms = document.querySelectorAll('.wallkit-form');

  forms.forEach(form => {
    // Добавляем обработчик только если нужна AJAX отправка
    if (form.hasAttribute('data-ajax')) {
      form.addEventListener('submit', handleFormSubmit);
    }
  });

  async function handleFormSubmit(e) {
    e.preventDefault();
    const form = e.target;
    const submitButton = form.querySelector('button[type="submit"]');

    // Показываем состояние отправки
    form.classList.add('wallkit-form--submitting');
    if (submitButton) {
      submitButton.disabled = true;
    }

    try {
      const formData = new FormData(form);
      const response = await fetch(form.action, {
        method: form.method,
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      });

      const messagesContainer = form.querySelector('.wallkit-form__messages');

      if (messagesContainer) {
        if (response.ok) {
          showMessage(messagesContainer, 'success', 'Форма успешно отправлена');
        } else {
          showMessage(messagesContainer, 'error', 'Ошибка при отправке формы');
        }
      }
    } catch (error) {
      console.error('Form submission error:', error);
    } finally {
      // Восстанавливаем состояние
      form.classList.remove('wallkit-form--submitting');
      if (submitButton) {
        submitButton.disabled = false;
      }
    }
  }

  function showMessage(container, type, text) {
    const message = document.createElement('div');
    message.className = `wallkit-form__message wallkit-form__message--${type}`;
    message.textContent = text;

    container.innerHTML = '';
    container.appendChild(message);

    // Убираем сообщение через 5 секунд
    setTimeout(() => {
      message.remove();
    }, 5000);
  }
});