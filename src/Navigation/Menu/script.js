/**
 * JavaScript для компонента Menu
 * Только для сворачиваемого меню на мобильных устройствах
 */

document.addEventListener('DOMContentLoaded', function() {
  // Инициализация сворачиваемых меню
  document.querySelectorAll('.wallkit-menu--collapsible').forEach(menu => {
    // Создаём кнопку переключения
    const toggleButton = document.createElement('button');
    toggleButton.className = 'wallkit-menu__toggle';
    toggleButton.innerHTML = '☰';
    toggleButton.setAttribute('aria-label', 'Переключить меню');
    toggleButton.setAttribute('aria-expanded', 'false');

    toggleButton.addEventListener('click', function() {
      const isExpanded = menu.classList.toggle('wallkit-menu--expanded');
      toggleButton.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');

      // Эмитим событие
      const event = new CustomEvent('wallkit:menu:toggle', {
        bubbles: true,
        detail: {
          menu: menu,
          expanded: isExpanded
        }
      });
      menu.dispatchEvent(event);
    });

    // Добавляем кнопку в меню
    menu.appendChild(toggleButton);
  });

  // Закрытие меню при клике вне на мобильных
  document.addEventListener('click', function(e) {
    if (window.innerWidth > 768) return;

    document.querySelectorAll('.wallkit-menu--collapsible.wallkit-menu--expanded').forEach(menu => {
      if (!menu.contains(e.target) && !e.target.classList.contains('wallkit-menu__toggle')) {
        menu.classList.remove('wallkit-menu--expanded');
        const toggleBtn = menu.querySelector('.wallkit-menu__toggle');
        if (toggleBtn) {
          toggleBtn.setAttribute('aria-expanded', 'false');
        }
      }
    });
  });

  // Закрытие меню при изменении размера окна
  window.addEventListener('resize', function() {
    if (window.innerWidth > 768) {
      document.querySelectorAll('.wallkit-menu--collapsible').forEach(menu => {
        menu.classList.remove('wallkit-menu--expanded');
        const toggleBtn = menu.querySelector('.wallkit-menu__toggle');
        if (toggleBtn) {
          toggleBtn.setAttribute('aria-expanded', 'false');
        }
      });
    }
  });
});