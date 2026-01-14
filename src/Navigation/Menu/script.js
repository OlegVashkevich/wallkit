/**
 * JavaScript для компонента Menu
 * Обеспечивает интерактивность для различных типов меню
 */

document.addEventListener('DOMContentLoaded', function() {
  // Обработка триггеров
  document.querySelectorAll('.wallkit-menu').forEach(menu => {
    const trigger = menu.dataset.trigger;
    const variant = menu.dataset.variant;

    // Для dropdown и context меню
    if (variant === 'dropdown' || variant === 'context') {
      setupDropdownMenu(menu, trigger);
    }

    // Для сворачиваемого меню
    if (menu.classList.contains('wallkit-menu--collapsible')) {
      setupCollapsibleMenu(menu);
    }

    // Для поиска в меню
    const searchInput = menu.querySelector('.wallkit-menu__search-input');
    if (searchInput) {
      setupMenuSearch(menu, searchInput);
    }
  });

  // Закрытие выпадающих меню при клике вне
  document.addEventListener('click', function(e) {
    document.querySelectorAll('.wallkit-menu--dropdown, .wallkit-menu--context').forEach(menu => {
      if (!menu.contains(e.target) && !e.target.closest('[data-menu-target]')) {
        menu.style.display = 'none';
      }
    });
  });
});

/**
 * Настройка выпадающего меню
 */
function setupDropdownMenu(menu, trigger) {
  const triggerElement = document.querySelector(`[data-menu-target="${menu.id}"]`);
  if (!triggerElement) return;

  if (trigger === 'click') {
    triggerElement.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();

      const isVisible = menu.style.display === 'block';
      menu.style.display = isVisible ? 'none' : 'block';

      // Позиционирование
      positionDropdown(menu, triggerElement);
    });
  } else if (trigger === 'hover') {
    triggerElement.addEventListener('mouseenter', function() {
      menu.style.display = 'block';
      positionDropdown(menu, triggerElement);
    });

    triggerElement.addEventListener('mouseleave', function() {
      if (!menu.matches(':hover')) {
        menu.style.display = 'none';
      }
    });

    menu.addEventListener('mouseleave', function() {
      menu.style.display = 'none';
    });
  }
}

/**
 * Позиционирование выпадающего меню
 */
function positionDropdown(menu, trigger) {
  const rect = trigger.getBoundingClientRect();
  const position = menu.dataset.position || 'bottom';

  switch (position) {
    case 'top':
      menu.style.top = (rect.top - menu.offsetHeight) + 'px';
      menu.style.left = rect.left + 'px';
      break;
    case 'left':
      menu.style.top = rect.top + 'px';
      menu.style.left = (rect.left - menu.offsetWidth) + 'px';
      break;
    case 'right':
      menu.style.top = rect.top + 'px';
      menu.style.left = (rect.right) + 'px';
      break;
    case 'bottom':
    default:
      menu.style.top = rect.bottom + 'px';
      menu.style.left = rect.left + 'px';
      break;
  }
}

/**
 * Настройка сворачиваемого меню
 */
function setupCollapsibleMenu(menu) {
  const toggleButton = document.createElement('button');
  toggleButton.className = 'wallkit-menu__toggle';
  toggleButton.innerHTML = '☰';
  toggleButton.setAttribute('aria-label', 'Переключить меню');

  toggleButton.addEventListener('click', function() {
    menu.classList.toggle('wallkit-menu--expanded');
    toggleButton.setAttribute('aria-expanded',
      menu.classList.contains('wallkit-menu--expanded'));
  });

  menu.insertBefore(toggleButton, menu.firstChild);
}

/**
 * Настройка поиска в меню
 */
function setupMenuSearch(menu, searchInput) {
  searchInput.addEventListener('input', function() {
    const query = this.value.toLowerCase();
    const items = menu.querySelectorAll('.wallkit-menu__item');

    items.forEach(item => {
      const label = item.textContent.toLowerCase();
      if (label.includes(query)) {
        item.style.display = '';
      } else {
        item.style.display = 'none';
      }
    });
  });
}