/**
 * JavaScript для компонента ContextMenu
 * Управляет контекстными меню (правый клик)
 */

class ContextMenuManager {
  constructor() {
    this.currentMenu = null;
    this.init();
  }

  init() {
    // Инициализация всех контекстных меню
    document.querySelectorAll('.wallkit-context-menu').forEach(menu => {
      const target = menu.dataset.target || 'body';
      const preventDefault = menu.dataset.preventDefault !== 'false';

      this.setupContextMenu(menu, target, preventDefault);
    });

    // Закрытие при клике вне
    document.addEventListener('click', (e) => {
      this.closeAllMenus(e);
    });

    // Закрытие по ESC
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        this.closeAllMenus();
      }
    });

    // Закрытие при изменении размера окна
    window.addEventListener('resize', () => {
      this.closeAllMenus();
    });
  }

  setupContextMenu(menu, targetSelector, preventDefault) {
    const targets = document.querySelectorAll(targetSelector);

    targets.forEach(target => {
      // Обработка правого клика
      target.addEventListener('contextmenu', (e) => {
        e.preventDefault();

        // Закрываем предыдущее меню
        this.closeAllMenus();

        // Открываем новое
        this.openContextMenu(menu, e.clientX, e.clientY);

        // Запоминаем целевой элемент
        menu.dataset.contextTarget = e.target.id || '';
        menu.dataset.contextTargetClass = e.target.className;

        // Эмитим событие
        const event = new CustomEvent('wallkit:contextmenu:open', {
          bubbles: true,
          detail: {
            menu: menu,
            target: e.target,
            x: e.clientX,
            y: e.clientY
          }
        });
        target.dispatchEvent(event);
      });

      // Блокировка стандартного меню
      if (preventDefault) {
        target.addEventListener('contextmenu', (e) => {
          e.preventDefault();
        }, { capture: true });
      }
    });

    // Закрытие при клике на пункт меню
    menu.addEventListener('click', (e) => {
      if (e.target.closest('.wallkit-item')) {
        setTimeout(() => this.closeAllMenus(), 100);

        // Эмитим событие выбора
        const event = new CustomEvent('wallkit:contextmenu:select', {
          bubbles: true,
          detail: {
            menu: menu,
            item: e.target.closest('.wallkit-item'),
            action: e.target.closest('.wallkit-item')?.dataset?.action
          }
        });
        menu.dispatchEvent(event);
      }
    });
  }

  openContextMenu(menu, x, y) {
    // Показываем меню
    menu.classList.remove('wallkit-context-menu--hidden');
    menu.classList.add('wallkit-context-menu--visible');
    menu.setAttribute('aria-hidden', 'false');

    // Позиционируем относительно курсора
    this.positionContextMenu(menu, x, y);

    // Запоминаем текущее меню
    this.currentMenu = menu;

    // Эмитим событие
    const event = new CustomEvent('wallkit:contextmenu:opened', {
      bubbles: true,
      detail: { menu: menu }
    });
    menu.dispatchEvent(event);
  }

  positionContextMenu(menu, x, y) {
    const viewportWidth = window.innerWidth;
    const viewportHeight = window.innerHeight;
    const menuRect = menu.getBoundingClientRect();

    // Корректировка позиции чтобы меню не выходило за пределы экрана
    let adjustedX = x;
    let adjustedY = y;

    if (x + menuRect.width > viewportWidth) {
      adjustedX = viewportWidth - menuRect.width - 8;
    }
    if (y + menuRect.height > viewportHeight) {
      adjustedY = viewportHeight - menuRect.height - 8;
    }

    menu.style.left = `${adjustedX}px`;
    menu.style.top = `${adjustedY}px`;
  }

  closeAllMenus(exceptEvent = null) {
    document.querySelectorAll('.wallkit-context-menu--visible').forEach(menu => {
      // Не закрываем если клик был внутри меню
      if (exceptEvent && menu.contains(exceptEvent.target)) {
        return;
      }

      menu.classList.remove('wallkit-context-menu--visible');
      menu.classList.add('wallkit-context-menu--hidden');
      menu.setAttribute('aria-hidden', 'true');

      // Эмитим событие
      const event = new CustomEvent('wallkit:contextmenu:closed', {
        bubbles: true,
        detail: { menu: menu }
      });
      menu.dispatchEvent(event);
    });

    this.currentMenu = null;
  }
}

// Инициализация менеджера при загрузке документа
document.addEventListener('DOMContentLoaded', () => {
  window.wallkitContextMenuManager = new ContextMenuManager();
});