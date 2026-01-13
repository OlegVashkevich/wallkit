/**
 * Интерактивность для компонента Menu
 *
 * Обеспечивает работу выпадающих меню, вложенных элементов и адаптивность.
 * Чистый JavaScript без зависимостей.
 *
 * @version 1.0.0
 */

(function() {
  'use strict';

  // Конфигурация
  const CONFIG = {
    selectors: {
      menu: '.wallkit-menu',
      link: '.wallkit-menu__link',
      itemHasChildren: '.wallkit-menu__item--has-children',
      submenu: '.wallkit-menu__submenu',
      toggler: '[data-menu-toggle]',
      search: '.wallkit-menu__search-input'
    },
    classes: {
      expanded: 'wallkit-menu--expanded',
      itemExpanded: 'wallkit-menu__item--expanded',
      open: 'wallkit-menu--open'
    },
    events: {
      click: 'click',
      mouseenter: 'mouseenter',
      mouseleave: 'mouseleave',
      keydown: 'keydown',
      focusin: 'focusin',
      focusout: 'focusout'
    },
    keys: {
      escape: 'Escape',
      enter: 'Enter',
      space: ' ',
      arrowUp: 'ArrowUp',
      arrowDown: 'ArrowDown',
      arrowLeft: 'ArrowLeft',
      arrowRight: 'ArrowRight'
    }
  };

  /**
   * Инициализация всех меню на странице
   */
  function init() {
    document.addEventListener('DOMContentLoaded', function() {
      const menus = document.querySelectorAll(CONFIG.selectors.menu);

      menus.forEach(function(menu) {
        initMenu(menu);
      });

      // Закрытие меню при клике вне
      document.addEventListener('click', function(event) {
        menus.forEach(function(menu) {
          if (!menu.contains(event.target)) {
            closeAllSubmenus(menu);
            if (menu.classList.contains(CONFIG.classes.expanded) &&
              !event.target.closest(CONFIG.selectors.toggler)) {
              menu.classList.remove(CONFIG.classes.expanded);
            }
          }
        });
      });

      // Закрытие по ESC
      document.addEventListener('keydown', function(event) {
        if (event.key === CONFIG.keys.escape) {
          menus.forEach(function(menu) {
            closeAllSubmenus(menu);
            menu.classList.remove(CONFIG.classes.expanded);
          });
        }
      });
    });
  }

  /**
   * Инициализация конкретного меню
   */
  function initMenu(menu) {
    const trigger = menu.getAttribute('data-trigger') || 'always';
    const variant = menu.getAttribute('data-variant') || 'navbar';

    // Инициализация элементов
    initMenuItems(menu);

    // Инициализация тогглера для collapsible
    const toggler = menu.querySelector(CONFIG.selectors.toggler);
    if (toggler) {
      toggler.addEventListener('click', function(event) {
        event.stopPropagation();
        menu.classList.toggle(CONFIG.classes.expanded);
        updateTogglerIcon(toggler, menu.classList.contains(CONFIG.classes.expanded));
      });
    }

    // Настройка поведения по trigger
    switch (trigger) {
      case 'hover':
        setupHoverTrigger(menu);
        break;
      case 'click':
        setupClickTrigger(menu);
        break;
      case 'context':
        setupContextTrigger(menu);
        break;
      // 'always' - ничего не настраиваем
    }

    // Инициализация клавиатурной навигации
    initKeyboardNavigation(menu);
  }

  /**
   * Инициализация элементов меню
   */
  function initMenuItems(menu) {
    const itemsWithChildren = menu.querySelectorAll(CONFIG.selectors.itemHasChildren);

    itemsWithChildren.forEach(function(item) {
      const link = item.querySelector(CONFIG.selectors.link);
      const submenu = item.querySelector(CONFIG.selectors.submenu);

      if (!link || !submenu) return;

      // Установка начальных ARIA атрибутов
      link.setAttribute('aria-haspopup', 'true');
      link.setAttribute('aria-expanded', 'false');
      submenu.setAttribute('aria-hidden', 'true');
    });
  }

  /**
   * Настройка hover триггера
   */
  function setupHoverTrigger(menu) {
    const items = menu.querySelectorAll(CONFIG.selectors.itemHasChildren);

    items.forEach(function(item) {
      const link = item.querySelector(CONFIG.selectors.link);
      const submenu = item.querySelector(CONFIG.selectors.submenu);

      if (!link || !submenu) return;

      item.addEventListener(CONFIG.events.mouseenter, function() {
        openSubmenu(item, link, submenu);
      });

      item.addEventListener(CONFIG.events.mouseleave, function(event) {
        // Проверяем, что курсор не перешёл в подменю
        if (!item.contains(event.relatedTarget)) {
          closeSubmenu(item, link, submenu);
        }
      });

      submenu.addEventListener(CONFIG.events.mouseleave, function(event) {
        if (!submenu.contains(event.relatedTarget)) {
          closeSubmenu(item, link, submenu);
        }
      });
    });
  }

  /**
   * Настройка click триггера
   */
  function setupClickTrigger(menu) {
    const items = menu.querySelectorAll(CONFIG.selectors.itemHasChildren);

    items.forEach(function(item) {
      const link = item.querySelector(CONFIG.selectors.link);
      const submenu = item.querySelector(CONFIG.selectors.submenu);

      if (!link || !submenu) return;

      link.addEventListener('click', function(event) {
        event.preventDefault();
        event.stopPropagation();

        const isExpanded = link.getAttribute('aria-expanded') === 'true';

        // Закрываем все остальные подменю
        closeAllSubmenus(menu, item);

        if (!isExpanded) {
          openSubmenu(item, link, submenu);
        }
      });
    });

    // Закрытие при клике на другой элемент меню
    menu.querySelectorAll(CONFIG.selectors.link).forEach(function(link) {
      link.addEventListener('click', function(event) {
        if (!link.closest(CONFIG.selectors.itemHasChildren)) {
          closeAllSubmenus(menu);
        }
      });
    });
  }

  /**
   * Настройка context триггера (правая кнопка мыши)
   */
  function setupContextTrigger(menu) {
    document.addEventListener('contextmenu', function(event) {
      event.preventDefault();

      // Позиционируем меню
      menu.style.left = event.pageX + 'px';
      menu.style.top = event.pageY + 'px';
      menu.classList.add(CONFIG.classes.open);

      // Закрытие при клике
      const closeHandler = function(e) {
        if (!menu.contains(e.target)) {
          menu.classList.remove(CONFIG.classes.open);
          document.removeEventListener('click', closeHandler);
        }
      };

      setTimeout(function() {
        document.addEventListener('click', closeHandler);
      }, 0);
    });
  }

  /**
   * Открытие подменю
   */
  function openSubmenu(item, link, submenu) {
    item.classList.add(CONFIG.classes.itemExpanded);
    link.setAttribute('aria-expanded', 'true');
    submenu.setAttribute('aria-hidden', 'false');

    // Позиционирование для горизонтального меню
    if (item.closest('.wallkit-menu--horizontal')) {
      positionSubmenu(submenu, item);
    }

    // Событие
    dispatchMenuEvent(item, 'wallkit:menu:submenu:open', { item, submenu });
  }

  /**
   * Закрытие подменю
   */
  function closeSubmenu(item, link, submenu) {
    item.classList.remove(CONFIG.classes.itemExpanded);
    link.setAttribute('aria-expanded', 'false');
    submenu.setAttribute('aria-hidden', 'true');

    // Событие
    dispatchMenuEvent(item, 'wallkit:menu:submenu:close', { item, submenu });
  }

  /**
   * Закрытие всех подменю в меню
   */
  function closeAllSubmenus(menu, exceptItem = null) {
    const items = menu.querySelectorAll(CONFIG.selectors.itemHasChildren);

    items.forEach(function(item) {
      if (item === exceptItem) return;

      const link = item.querySelector(CONFIG.selectors.link);
      const submenu = item.querySelector(CONFIG.selectors.submenu);

      if (link && submenu) {
        closeSubmenu(item, link, submenu);
      }
    });
  }

  /**
   * Позиционирование подменю
   */
  function positionSubmenu(submenu, parentItem) {
    const rect = parentItem.getBoundingClientRect();
    const submenuRect = submenu.getBoundingClientRect();
    const viewportWidth = window.innerWidth;

    // Проверка на переполнение справа
    if (rect.left + submenuRect.width > viewportWidth) {
      submenu.style.left = 'auto';
      submenu.style.right = '0';
    } else {
      submenu.style.left = '0';
      submenu.style.right = 'auto';
    }

    // Проверка на переполнение снизу (для многоуровневых)
    const viewportHeight = window.innerHeight;
    if (rect.bottom + submenuRect.height > viewportHeight) {
      submenu.style.top = 'auto';
      submenu.style.bottom = '0';
    }
  }

  /**
   * Обновление иконки тогглера
   */
  function updateTogglerIcon(toggler, isExpanded) {
    const icon = toggler.querySelector('.wallkit-menu__toggler-icon');
    if (!icon) return;

    if (isExpanded) {
      icon.style.backgroundColor = 'transparent';
      icon.style.transform = 'rotate(45deg)';

      icon.style.before = icon.style.before || {};
      icon.style.before.transform = 'rotate(90deg) translate(6px, 0)';

      icon.style.after = icon.style.after || {};
      icon.style.after.transform = 'rotate(-90deg) translate(-6px, 0)';
    } else {
      icon.style.backgroundColor = '';
      icon.style.transform = '';

      if (icon.style.before) icon.style.before.transform = '';
      if (icon.style.after) icon.style.after.transform = '';
    }
  }

  /**
   * Инициализация клавиатурной навигации
   */
  function initKeyboardNavigation(menu) {
    menu.addEventListener(CONFIG.events.keydown, function(event) {
      const items = Array.from(menu.querySelectorAll(CONFIG.selectors.link));
      const currentItem = event.target.closest(CONFIG.selectors.link);
      const currentIndex = items.indexOf(currentItem);

      if (currentIndex === -1) return;

      let nextIndex = currentIndex;

      switch (event.key) {
        case CONFIG.keys.arrowDown:
        case CONFIG.keys.arrowRight:
          event.preventDefault();
          nextIndex = (currentIndex + 1) % items.length;
          break;

        case CONFIG.keys.arrowUp:
        case CONFIG.keys.arrowLeft:
          event.preventDefault();
          nextIndex = (currentIndex - 1 + items.length) % items.length;
          break;

        case CONFIG.keys.enter:
        case CONFIG.keys.space:
          event.preventDefault();
          currentItem.click();
          break;

        case CONFIG.keys.escape:
          closeAllSubmenus(menu);
          break;
      }

      if (nextIndex !== currentIndex) {
        items[nextIndex].focus();
      }
    });
  }

  /**
   * Диспетчеризация событий
   */
  function dispatchMenuEvent(element, eventName, detail = {}) {
    const event = new CustomEvent(eventName, {
      bubbles: true,
      detail: detail
    });
    element.dispatchEvent(event);
  }

  // Экспорт API
  window.WallKitMenu = {
    init: init,
    initMenu: initMenu,
    openSubmenu: function(menu, itemSelector) {
      const menuEl = typeof menu === 'string' ? document.querySelector(menu) : menu;
      const item = menuEl.querySelector(itemSelector);
      if (item) {
        const link = item.querySelector(CONFIG.selectors.link);
        const submenu = item.querySelector(CONFIG.selectors.submenu);
        if (link && submenu) openSubmenu(item, link, submenu);
      }
    },
    closeSubmenu: function(menu, itemSelector) {
      const menuEl = typeof menu === 'string' ? document.querySelector(menu) : menu;
      const item = menuEl.querySelector(itemSelector);
      if (item) {
        const link = item.querySelector(CONFIG.selectors.link);
        const submenu = item.querySelector(CONFIG.selectors.submenu);
        if (link && submenu) closeSubmenu(item, link, submenu);
      }
    },
    toggleMenu: function(menuSelector) {
      const menu = document.querySelector(menuSelector);
      if (menu) {
        menu.classList.toggle(CONFIG.classes.expanded);
      }
    }
  };

  // Автоинициализация
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();