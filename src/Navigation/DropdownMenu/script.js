/**
 * JavaScript для компонента DropdownMenu
 * Управляет открытием/закрытием выпадающего меню
 */

document.addEventListener('DOMContentLoaded', function() {
  // Инициализация всех выпадающих меню
  document.querySelectorAll('.wallkit-dropdown-menu').forEach(menu => {
    const triggerId = menu.getAttribute('aria-labelledby');
    if (!triggerId) return;

    const trigger = document.getElementById(triggerId);
    if (!trigger) return;

    const triggerType = menu.dataset.trigger || 'click';
    const position = menu.dataset.position || 'bottom';
    const closeOnClick = menu.dataset.closeOnClick !== 'false';

    // Инициализация в зависимости от типа триггера
    if (triggerType === 'hover') {
      initHoverDropdown(trigger, menu);
    } else {
      initClickDropdown(trigger, menu, closeOnClick);
    }

    // Позиционирование
    positionDropdown(trigger, menu, position);

    // Обновление позиции при изменении размера окна
    window.addEventListener('resize', () => {
      positionDropdown(trigger, menu, position);
    });
  });

  // Закрытие всех выпадающих меню при клике вне
  document.addEventListener('click', function(e) {
    document.querySelectorAll('.wallkit-dropdown-menu.wallkit-dropdown-menu--visible').forEach(menu => {
      const triggerId = menu.getAttribute('aria-labelledby');
      const trigger = triggerId ? document.getElementById(triggerId) : null;

      if (!menu.contains(e.target) && (!trigger || !trigger.contains(e.target))) {
        closeDropdown(menu);
      }
    });
  });

  // Закрытие по ESC
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      document.querySelectorAll('.wallkit-dropdown-menu.wallkit-dropdown-menu--visible').forEach(closeDropdown);
    }
  });
});

/**
 * Инициализация dropdown по клику
 */
function initClickDropdown(trigger, menu, closeOnClick) {
  trigger.addEventListener('click', function(e) {
    e.preventDefault();
    e.stopPropagation();

    const isVisible = menu.classList.contains('wallkit-dropdown-menu--visible');

    // Закрываем все другие открытые меню
    document.querySelectorAll('.wallkit-dropdown-menu.wallkit-dropdown-menu--visible').forEach(otherMenu => {
      if (otherMenu !== menu) {
        closeDropdown(otherMenu);
      }
    });

    if (isVisible) {
      closeDropdown(menu);
    } else {
      openDropdown(trigger, menu);
    }
  });

  // Закрытие при клике на пункт меню
  if (closeOnClick) {
    menu.addEventListener('click', function(e) {
      if (e.target.closest('.wallkit-item')) {
        setTimeout(() => closeDropdown(menu), 100);
      }
    });
  }
}

/**
 * Инициализация dropdown по наведению
 */
function initHoverDropdown(trigger, menu) {
  let closeTimeout;

  trigger.addEventListener('mouseenter', function() {
    clearTimeout(closeTimeout);
    openDropdown(trigger, menu);
  });

  trigger.addEventListener('mouseleave', function() {
    closeTimeout = setTimeout(() => {
      if (!menu.matches(':hover')) {
        closeDropdown(menu);
      }
    }, 200);
  });

  menu.addEventListener('mouseenter', function() {
    clearTimeout(closeTimeout);
  });

  menu.addEventListener('mouseleave', function() {
    closeTimeout = setTimeout(() => closeDropdown(menu), 200);
  });
}

/**
 * Открытие dropdown
 */
function openDropdown(trigger, menu) {
  menu.classList.remove('wallkit-dropdown-menu--hidden');
  menu.classList.add('wallkit-dropdown-menu--visible');
  trigger.setAttribute('aria-expanded', 'true');

  // Эмитим событие
  const event = new CustomEvent('wallkit:dropdown:open', {
    bubbles: true,
    detail: { trigger, menu }
  });
  menu.dispatchEvent(event);
}

/**
 * Закрытие dropdown
 */
function closeDropdown(menu) {
  const triggerId = menu.getAttribute('aria-labelledby');
  const trigger = triggerId ? document.getElementById(triggerId) : null;

  menu.classList.remove('wallkit-dropdown-menu--visible');
  menu.classList.add('wallkit-dropdown-menu--hidden');

  if (trigger) {
    trigger.setAttribute('aria-expanded', 'false');
  }

  // Эмитим событие
  const event = new CustomEvent('wallkit:dropdown:close', {
    bubbles: true,
    detail: { menu }
  });
  menu.dispatchEvent(event);
}

/**
 * Позиционирование dropdown
 */
function positionDropdown(trigger, menu, position) {
  const triggerRect = trigger.getBoundingClientRect();
  const menuRect = menu.getBoundingClientRect();
  const viewportWidth = window.innerWidth;
  const viewportHeight = window.innerHeight;

  let top, left;

  switch (position) {
    case 'top':
      top = triggerRect.top - menuRect.height - 8;
      left = triggerRect.left;
      break;
    case 'left':
      top = triggerRect.top;
      left = triggerRect.left - menuRect.width - 8;
      break;
    case 'right':
      top = triggerRect.top;
      left = triggerRect.right + 8;
      break;
    case 'bottom':
    default:
      top = triggerRect.bottom + 8;
      left = triggerRect.left;
      break;
  }

  // Корректировка для вьюпорта
  if (left + menuRect.width > viewportWidth) {
    left = viewportWidth - menuRect.width - 8;
  }
  if (left < 0) {
    left = 8;
  }
  if (top + menuRect.height > viewportHeight) {
    top = viewportHeight - menuRect.height - 8;
  }
  if (top < 0) {
    top = 8;
  }

  menu.style.top = `${top}px`;
  menu.style.left = `${left}px`;
}