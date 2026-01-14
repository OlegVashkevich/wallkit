/**
 * JavaScript для компонента Item
 * Обеспечивает интерактивность для элементов с дочерними элементами
 */

document.addEventListener('DOMContentLoaded', function() {
  // Обработка кликов по элементам с детьми
  document.addEventListener('click', function(e) {
    const itemWrapper = e.target.closest('.wallkit-item__wrapper[data-has-children="true"]');
    if (!itemWrapper) return;

    const item = itemWrapper.querySelector('.wallkit-item');
    if (!item) return;

    // Проверяем, что кликнули именно по элементу, а не по его детям
    if (item.contains(e.target)) {
      e.preventDefault();
      e.stopPropagation();

      // Переключаем класс expanded
      itemWrapper.classList.toggle('wallkit-item__wrapper--expanded');

      // Эмитим событие
      const event = new CustomEvent('wallkit:item:toggle', {
        bubbles: true,
        detail: {
          item: item,
          expanded: itemWrapper.classList.contains('wallkit-item__wrapper--expanded'),
          id: item.id || null
        }
      });
      itemWrapper.dispatchEvent(event);
    }
  });

  // Обработка нажатия клавиш для доступности
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' || e.key === ' ') {
      const activeElement = document.activeElement;
      if (activeElement.classList.contains('wallkit-item') &&
        activeElement.parentElement.dataset.hasChildren === 'true') {
        e.preventDefault();
        activeElement.click();
      }
    }
  });

  // Автоматическое сворачивание при клике вне элемента
  document.addEventListener('click', function(e) {
    const expandedWrappers = document.querySelectorAll('.wallkit-item__wrapper--expanded');
    expandedWrappers.forEach(wrapper => {
      if (!wrapper.contains(e.target)) {
        wrapper.classList.remove('wallkit-item__wrapper--expanded');
      }
    });
  });
});