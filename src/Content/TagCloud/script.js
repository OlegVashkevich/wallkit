/**
 * TagCloud JavaScript
 *
 * Этот файл добавляет:
 * 1. Регистрацию событий WallKitEvents
 * 2. Фильтрацию по data-tag для hash-ссылок (#php, #js)
 * 3. Множественный выбор фильтров (И-логика)
 *
 * @example
 * <a href="#php" class="wallkit-tagcloud__tag">PHP</a>
 * <div data-tag="php database">Контент про PHP</div>
 */
document.addEventListener('DOMContentLoaded', function() {
    // Регистрация событий
    if (window.WallKitEvents) {
        WallKitEvents.register('wallkit:tagcloud:tag:click');
        WallKitEvents.register('wallkit:tagcloud:filter');
    }

    // Инициализация всех облаков тегов на странице
    document.querySelectorAll('.wallkit-tagcloud').forEach(initTagCloud);
});

/**
 * Инициализирует одно облако тегов
 */
function initTagCloud(cloud) {
    const activeFilters = new Set();
    const tags = cloud.querySelectorAll('.wallkit-tagcloud__tag[href]');

    tags.forEach(tag => {
        tag.addEventListener('click', function(e) {
            const isHashLink = this.hash; // Есть ли # в ссылке

            // Всегда отправляем событие клика
            if (window.WallKitEvents) {
                WallKitEvents.emit('wallkit:tagcloud:tag:click', {
                    label: this.textContent.trim(),
                    href: this.href,
                    cloudId: cloud.id || 'unnamed',
                    isHashLink: !!isHashLink,
                });
            }

            // Если это hash-ссылка - обрабатываем фильтрацию
            if (isHashLink) {
                e.preventDefault();
                handleHashFilter(this, cloud, activeFilters);
            }
        });
    });
}

/**
 * Обрабатывает фильтрацию по hash-ссылкам
 */
function handleHashFilter(clickedTag, cloud, activeFilters) {
    const filterValue = clickedTag.hash.slice(1); // Убираем #

    // Сброс фильтров (все или пустой hash)
    if (!filterValue || filterValue === 'all') {
        resetFilters(cloud, activeFilters);
        return;
    }

    // Переключаем фильтр
    toggleFilter(filterValue, clickedTag, activeFilters);

    // Применяем фильтры к элементам
    applyFilters(cloud, activeFilters);
}

/**
 * Сбрасывает все фильтры
 */
function resetFilters(cloud, activeFilters) {
    activeFilters.clear();

    // Показываем все элементы
    document.querySelectorAll('[data-tag]').forEach(el => {
        el.hidden = false;
    });

    // Снимаем активность со всех тегов
    cloud.querySelectorAll('.wallkit-tagcloud__tag').forEach(t => {
        t.classList.remove('wallkit-tagcloud__tag--active');
    });

    emitFilterEvent(cloud, []);
}

/**
 * Переключает состояние фильтра
 */
function toggleFilter(filterValue, clickedTag, activeFilters) {
    if (activeFilters.has(filterValue)) {
        activeFilters.delete(filterValue);
        clickedTag.classList.remove('wallkit-tagcloud__tag--active');
    } else {
        activeFilters.add(filterValue);
        clickedTag.classList.add('wallkit-tagcloud__tag--active');
    }
}

/**
 * Применяет активные фильтры к элементам
 */
function applyFilters(cloud, activeFilters) {
    // Если нет активных фильтров - показываем всё
    if (activeFilters.size === 0) {
        document.querySelectorAll('[data-tag]').forEach(el => {
            el.hidden = false;
        });
        emitFilterEvent(cloud, []);
        return;
    }

    // Фильтруем по логике И (все теги должны совпадать)
    document.querySelectorAll('[data-tag]').forEach(el => {
        const elementTags = el.dataset.tag.split(' ');
        const hasAllTags = Array.from(activeFilters).every(f => elementTags.includes(f));
        el.hidden = !hasAllTags;
    });

    emitFilterEvent(cloud, Array.from(activeFilters));
}

/**
 * Отправляет событие фильтрации
 */
function emitFilterEvent(cloud, activeFilters) {
    if (!window.WallKitEvents) return;

    WallKitEvents.emit('wallkit:tagcloud:filter', {
        filters: activeFilters,
        cloudId: cloud.id || 'unnamed',
        activeCount: activeFilters.length,
        timestamp: Date.now(),
    });
}