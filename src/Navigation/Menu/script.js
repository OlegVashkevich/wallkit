/**
 * WallKit Menu Component
 * Минималистичная реализация функционала меню
 *
 * @version 1.0.0
 */

class WallKitMenu {
  constructor(menuElement) {
    this.menu = menuElement;
    this.variant = menuElement.dataset.variant;
    this.trigger = menuElement.dataset.trigger;
    this.init();
  }

  init() {
    // Инициализация в зависимости от варианта
    switch (this.variant) {
      case 'dropdown':
      case 'context':
        this.initDropdown();
        break;
      case 'navbar':
      case 'sidebar':
        this.initNavigation();
        break;
    }

    // Инициализация поиска если есть
    this.initSearch();

    // Инициализация сворачивания если есть
    this.initCollapsible();

    // Делегирование событий
    this.setupEventDelegation();
  }

  initDropdown() {
    if (this.trigger === 'always') return;

    const parent = this.menu.parentElement;
    if (!parent) return;

    if (this.trigger === 'click') {
      // Для клика нужен триггер-кнопка
      const trigger = parent.querySelector('[data-menu-trigger]');
      if (trigger) {
        trigger.addEventListener('click', (e) => {
          e.stopPropagation();
          this.toggle();
        });
      } else if (parent.tagName === 'BUTTON') {
        parent.addEventListener('click', (e) => {
          e.stopPropagation();
          this.toggle();
        });
      }
    } else if (this.trigger === 'hover') {
      parent.addEventListener('mouseenter', () => this.show());
      parent.addEventListener('mouseleave', () => this.hide());
    } else if (this.trigger === 'context') {
      parent.addEventListener('contextmenu', (e) => {
        e.preventDefault();
        this.showAt(e.clientX, e.clientY);
      });
    }

    // Закрытие при клике вне меню
    document.addEventListener('click', (e) => {
      if (!this.menu.contains(e.target) && !parent.contains(e.target)) {
        this.hide();
      }
    });

    // ESC для закрытия
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') this.hide();
    });
  }

  initNavigation() {
    // Активация элементов при клике
    this.menu.addEventListener('click', (e) => {
      const link = e.target.closest('.wallkit-menu__link');
      if (link && !link.hasAttribute('aria-disabled')) {
        this.deactivateAllItems();
        this.activateItem(link.parentElement);

        // Для вложенных меню - переключение
        if (link.hasAttribute('aria-haspopup')) {
          e.preventDefault();
          this.toggleSubmenu(link);
        }
      }
    });

    // Клавиатурная навигация
    this.menu.addEventListener('keydown', (e) => {
      const focused = document.activeElement;
      if (!this.menu.contains(focused)) return;

      switch (e.key) {
        case 'ArrowDown':
        case 'ArrowRight':
          e.preventDefault();
          this.focusNextItem(focused);
          break;
        case 'ArrowUp':
        case 'ArrowLeft':
          e.preventDefault();
          this.focusPrevItem(focused);
          break;
        case 'Enter':
        case ' ':
          e.preventDefault();
          if (focused.hasAttribute('aria-haspopup')) {
            this.toggleSubmenu(focused);
          } else {
            focused.click();
          }
          break;
        case 'Escape':
          this.closeAllSubmenus();
          break;
      }
    });
  }

  initSearch() {
    const searchInput = this.menu.querySelector('.wallkit-menu__search-input');
    if (!searchInput) return;

    searchInput.addEventListener('input', (e) => {
      const term = e.target.value.toLowerCase().trim();
      this.filterMenuItems(term);
    });
  }

  initCollapsible() {
    const toggler = this.menu.querySelector('[data-menu-toggle]');
    if (!toggler) return;

    toggler.addEventListener('click', () => {
      this.toggleCollapse();
    });

    // Адаптивное сворачивание на мобильных
    if (window.innerWidth <= 768) {
      this.collapse();
    }

    window.addEventListener('resize', () => {
      if (window.innerWidth <= 768) {
        this.collapse();
      } else {
        this.expand();
      }
    });
  }

  setupEventDelegation() {
    // Обработка подменю на hover (если триггер hover)
    if (this.trigger === 'hover') {
      this.menu.addEventListener('mouseenter', (e) => {
        const item = e.target.closest('.wallkit-menu__item--has-children');
        if (item) {
          this.showSubmenu(item);
        }
      }, true);

      this.menu.addEventListener('mouseleave', (e) => {
        const item = e.target.closest('.wallkit-menu__item--has-children');
        if (item && !item.matches(':hover')) {
          this.hideSubmenu(item);
        }
      }, true);
    }
  }

  // Основные методы
  show() {
    this.menu.style.display = 'block';
    this.menu.setAttribute('aria-hidden', 'false');
  }

  hide() {
    this.menu.style.display = 'none';
    this.menu.setAttribute('aria-hidden', 'true');
  }

  toggle() {
    if (this.menu.style.display === 'none') {
      this.show();
    } else {
      this.hide();
    }
  }

  showAt(x, y) {
    this.menu.style.position = 'fixed';
    this.menu.style.left = `${x}px`;
    this.menu.style.top = `${y}px`;
    this.show();
  }

  // Методы для подменю
  toggleSubmenu(link) {
    const item = link.parentElement;
    const submenu = item.querySelector('.wallkit-menu__submenu');
    if (!submenu) return;

    const isExpanded = link.getAttribute('aria-expanded') === 'true';

    // Закрываем другие подменю на том же уровне
    this.closeSiblingSubmenus(item);

    if (isExpanded) {
      this.hideSubmenu(item);
    } else {
      this.showSubmenu(item);
    }
  }

  showSubmenu(item) {
    const link = item.querySelector('.wallkit-menu__link');
    const submenu = item.querySelector('.wallkit-menu__submenu');

    if (link && submenu) {
      link.setAttribute('aria-expanded', 'true');
      submenu.style.display = 'block';

      // Позиционирование для dropdown
      if (this.variant === 'dropdown') {
        this.positionSubmenu(submenu, link);
      }
    }
  }

  hideSubmenu(item) {
    const link = item.querySelector('.wallkit-menu__link');
    const submenu = item.querySelector('.wallkit-menu__submenu');

    if (link && submenu) {
      link.setAttribute('aria-expanded', 'false');
      submenu.style.display = 'none';

      // Рекурсивно закрываем все вложенные подменю
      const nestedSubmenus = submenu.querySelectorAll('.wallkit-menu__submenu');
      nestedSubmenus.forEach(nested => {
        nested.style.display = 'none';
        const nestedLink = nested.previousElementSibling;
        if (nestedLink && nestedLink.classList.contains('wallkit-menu__link')) {
          nestedLink.setAttribute('aria-expanded', 'false');
        }
      });
    }
  }

  closeSiblingSubmenus(currentItem) {
    const siblings = currentItem.parentElement.children;
    Array.from(siblings).forEach(sibling => {
      if (sibling !== currentItem && sibling.classList.contains('wallkit-menu__item--has-children')) {
        this.hideSubmenu(sibling);
      }
    });
  }

  closeAllSubmenus() {
    const allSubmenus = this.menu.querySelectorAll('.wallkit-menu__submenu');
    allSubmenus.forEach(submenu => {
      submenu.style.display = 'none';
      const link = submenu.previousElementSibling;
      if (link && link.classList.contains('wallkit-menu__link')) {
        link.setAttribute('aria-expanded', 'false');
      }
    });
  }

  positionSubmenu(submenu, trigger) {
    const rect = trigger.getBoundingClientRect();
    const viewportHeight = window.innerHeight;

    // Позиционируем вправо по умолчанию
    submenu.style.position = 'absolute';
    submenu.style.left = '100%';
    submenu.style.top = '0';

    // Проверяем, помещается ли в viewport
    const submenuRect = submenu.getBoundingClientRect();

    if (rect.right + submenuRect.width > window.innerWidth) {
      // Не помещается справа - показываем слева
      submenu.style.left = 'auto';
      submenu.style.right = '100%';
    }

    if (rect.top + submenuRect.height > viewportHeight) {
      // Не помещается снизу - показываем сверху
      submenu.style.top = 'auto';
      submenu.style.bottom = '0';
    }
  }

  // Методы для навигации
  deactivateAllItems() {
    const activeItems = this.menu.querySelectorAll('.wallkit-menu__item--active');
    activeItems.forEach(item => item.classList.remove('wallkit-menu__item--active'));
  }

  activateItem(item) {
    item.classList.add('wallkit-menu__item--active');
  }

  focusNextItem(current) {
    const allItems = Array.from(this.menu.querySelectorAll('.wallkit-menu__link:not([aria-disabled="true"])'));
    const currentIndex = allItems.indexOf(current);
    const nextIndex = (currentIndex + 1) % allItems.length;
    allItems[nextIndex].focus();
  }

  focusPrevItem(current) {
    const allItems = Array.from(this.menu.querySelectorAll('.wallkit-menu__link:not([aria-disabled="true"])'));
    const currentIndex = allItems.indexOf(current);
    const prevIndex = (currentIndex - 1 + allItems.length) % allItems.length;
    allItems[prevIndex].focus();
  }

  // Методы для поиска
  filterMenuItems(term) {
    const items = this.menu.querySelectorAll('.wallkit-menu__item');

    items.forEach(item => {
      const label = item.querySelector('.wallkit-menu__label');
      if (!label) return;

      const text = label.textContent.toLowerCase();

      if (term === '' || text.includes(term)) {
        item.style.display = '';

        // Показываем родительские элементы если найден вложенный
        let parent = item.parentElement.closest('.wallkit-menu__item');
        while (parent) {
          parent.style.display = '';
          parent = parent.parentElement.closest('.wallkit-menu__item');
        }
      } else {
        item.style.display = 'none';
      }
    });
  }

  // Методы для collapsible
  toggleCollapse() {
    if (this.menu.classList.contains('wallkit-menu--collapsed')) {
      this.expand();
    } else {
      this.collapse();
    }
  }

  collapse() {
    this.menu.classList.add('wallkit-menu--collapsed');
    const toggler = this.menu.querySelector('[data-menu-toggle]');
    if (toggler) {
      toggler.setAttribute('aria-expanded', 'false');
    }
  }

  expand() {
    this.menu.classList.remove('wallkit-menu--collapsed');
    const toggler = this.menu.querySelector('[data-menu-toggle]');
    if (toggler) {
      toggler.setAttribute('aria-expanded', 'true');
    }
  }
}

// Автоматическая инициализация всех меню на странице
document.addEventListener('DOMContentLoaded', () => {
  const menuElements = document.querySelectorAll('.wallkit-menu');
  const menus = [];

  menuElements.forEach(element => {
    try {
      const menu = new WallKitMenu(element);
      menus.push(menu);

      // Сохраняем ссылку на экземпляр в элементе
      element.wallkitMenu = menu;
    } catch (error) {
      console.error('Failed to initialize WallKit Menu:', error);
    }
  });

  // Глобальный метод для доступа к меню
  window.WallKitMenus = menus;
});

// Экспорт для использования в модульных системах
if (typeof module !== 'undefined' && module.exports) {
  module.exports = WallKitMenu;
}