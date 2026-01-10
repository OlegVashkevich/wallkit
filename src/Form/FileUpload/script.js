/**
 * JavaScript для компонента FileUpload
 *
 * Добавляет:
 * 1. Валидацию размера файлов
 * 2. Валидацию количества файлов
 * 3. Валидацию размеров изображений
 * 4. Поддержку drag & drop
 * 5. Отображение выбранных файлов
 *
 * Использует прогрессивное улучшение.
 * Все данные передаются через data-атрибуты.
 *
 * @version 1.0.0
 */

(function() {
  'use strict';

  class WallKitFileUpload {
    constructor(input) {
      this.input = input;
      this.wrapper = input.closest('.wallkit-fileupload__wrapper');
      this.placeholder = this.wrapper?.querySelector('.wallkit-fileupload__placeholder');

      // Параметры валидации из data-атрибутов
      this.maxSize = input.dataset.maxSize ? parseInt(input.dataset.maxSize) : null;
      this.maxFiles = input.dataset.maxFiles ? parseInt(input.dataset.maxFiles) : null;
      this.maxWidth = input.dataset.maxWidth ? parseInt(input.dataset.maxWidth) : null;
      this.maxHeight = input.dataset.maxHeight ? parseInt(input.dataset.maxHeight) : null;

      this.init();
    }

    init() {
      // Основные события
      this.input.addEventListener('change', this.handleFileSelect.bind(this));

      // Drag & drop
      if (this.wrapper) {
        this.wrapper.addEventListener('dragover', this.handleDragOver.bind(this));
        this.wrapper.addEventListener('dragleave', this.handleDragLeave.bind(this));
        this.wrapper.addEventListener('drop', this.handleDrop.bind(this));
      }
    }

    handleFileSelect(event) {
      const files = Array.from(event.target.files);

      // Валидация количества файлов
      if (this.maxFiles && files.length > this.maxFiles) {
        this.showError(`Максимальное количество файлов: ${this.maxFiles}`);
        event.target.value = '';
        return;
      }

      // Валидация каждого файла
      for (const file of files) {
        if (!this.validateFile(file)) {
          event.target.value = '';
          return;
        }
      }

      // Обновление плейсхолдера
      if (this.placeholder && files.length > 0) {
        const names = files.map(f => f.name).join(', ');
        this.placeholder.textContent = `${files.length} файл(ов) выбрано: ${names}`;
      }

      this.clearError();
    }

    validateFile(file) {
      // Размер файла
      if (this.maxSize && file.size > this.maxSize) {
        const maxSizeMB = (this.maxSize / 1024 / 1024).toFixed(1);
        this.showError(`Файл "${file.name}" превышает максимальный размер (${maxSizeMB} MB)`);
        return false;
      }

      // Для изображений - проверка размеров
      if (file.type.startsWith('image/') && (this.maxWidth || this.maxHeight)) {
        return this.validateImageDimensions(file);
      }

      return true;
    }

    validateImageDimensions(file) {
      return new Promise((resolve) => {
        const img = new Image();
        img.onload = () => {
          if (this.maxWidth && img.width > this.maxWidth) {
            this.showError(`Изображение "${file.name}" превышает максимальную ширину: ${this.maxWidth}px`);
            resolve(false);
          } else if (this.maxHeight && img.height > this.maxHeight) {
            this.showError(`Изображение "${file.name}" превышает максимальную высоту: ${this.maxHeight}px`);
            resolve(false);
          } else {
            resolve(true);
          }
        };
        img.onerror = () => resolve(true); // Если не удалось загрузить, пропускаем
        img.src = URL.createObjectURL(file);
      });
    }

    // Drag & drop
    handleDragOver(event) {
      event.preventDefault();
      event.stopPropagation();
      this.input.setAttribute('data-drag-over', 'true');
    }

    handleDragLeave(event) {
      event.preventDefault();
      event.stopPropagation();
      this.input.setAttribute('data-drag-over', 'false');
    }

    handleDrop(event) {
      event.preventDefault();
      event.stopPropagation();
      this.input.setAttribute('data-drag-over', 'false');

      //const files = event.dataTransfer.files;
      this.input.files = event.dataTransfer.files;

      // Триггерим событие change для валидации
      const changeEvent = new Event('change', { bubbles: true });
      this.input.dispatchEvent(changeEvent);
    }

    // Утилиты
    showError(message) {
      // Удаляем старую ошибку
      this.clearError();

      // Создаём элемент ошибки
      const errorEl = document.createElement('div');
      errorEl.className = 'wallkit-fileupload__error';
      errorEl.textContent = message;

      // Вставляем после wrapper
      this.wrapper?.parentNode?.insertBefore(errorEl, this.wrapper.nextSibling);

      // Добавляем класс ошибки к инпуту
      this.input.classList.add('wallkit-fileupload__field--error');
    }

    clearError() {
      const existingError = this.input.closest('.wallkit-fileupload')?.querySelector('.wallkit-fileupload__error');
      if (existingError) {
        existingError.remove();
      }
      this.input.classList.remove('wallkit-fileupload__field--error');
    }
  }

  // Инициализация всех компонентов на странице
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.wallkit-fileupload__field').forEach(input => {
      new WallKitFileUpload(input);
    });
  });

  // Экспорт для использования в других скриптах
  if (typeof window !== 'undefined') {
    window.WallKitFileUpload = WallKitFileUpload;
  }
})();