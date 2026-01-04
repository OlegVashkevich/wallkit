/**
 * WallKitEvents - система событий с обязательной регистрацией
 *
 * @module WallKitEvents
 * @description Централизованная система событий для WallKit компонентов.
 * Все события должны быть зарегистрированы перед использованием в строгом режиме.
 *
 * @example
 * // 1. Регистрация события
 * WallKitEvents.register('wallkit:button:click');
 *
 * // 2. Отправка события
 * WallKitEvents.emit('wallkit:button:click', { id: 'btn-1' });
 *
 * // 3. Подписка
 * const subId = WallKitEvents.on('wallkit:button:click', (data) => {
 *   console.log('Кнопка нажата:', data.id);
 * });
 *
 * // 4. Отписка
 * WallKitEvents.off(subId);
 */
let WallKitEvents = (() => {
    // Приватное хранилище зарегистрированных событий
    const events = new Set();

    // Приватное хранилище активных подписок
    const subscriptions = new Map();

    // Счетчик для генерации уникальных ID подписок
    let nextId = 0;

    // Флаг строгого режима (true = только зарегистрированные события)
    let strictMode = true;

    /**
     * Проверяет корректность имени события
     * @private
     * @param {string} name - Имя события
     * @returns {boolean} - true если имя валидно
     */
    const isValidEventName = (name) => name.startsWith('wallkit:');

    /**
     * Генерирует уникальный ID для подписки
     * @private
     * @returns {string} - Уникальный ID
     */
    const generateId = () => `wk-${Date.now()}-${++nextId}`;

    // Публичное API
    return {
        /**
         * Регистрирует новое событие в системе
         *
         * @param {string} eventName - Имя события (должно начинаться с 'wallkit:')
         * @returns {boolean} - true если регистрация успешна, false если событие уже зарегистрировано
         * @throws {Error} - Если имя события не начинается с 'wallkit:'
         *
         * @example
         * // Успешная регистрация
         * WallKitEvents.register('wallkit:button:click'); // → true
         *
         * // Повторная регистрация
         * WallKitEvents.register('wallkit:button:click'); // → false (уже зарегистрировано)
         *
         * // Некорректное имя
         * WallKitEvents.register('button:click'); // → Error
         */
        register(eventName) {
            if (!isValidEventName(eventName)) {
                throw new Error('WallKitEvents: имя события должно начинаться с "wallkit:"');
            }

            if (events.has(eventName)) {
                console.warn(`WallKitEvents: событие "${eventName}" уже зарегистрировано`);
                return false;
            }

            events.add(eventName);
            return true;
        },

        /**
         * Проверяет, зарегистрировано ли событие
         *
         * @param {string} eventName - Имя события для проверки
         * @returns {boolean} - true если событие зарегистрировано
         *
         * @example
         * WallKitEvents.register('wallkit:modal:open');
         * WallKitEvents.isRegistered('wallkit:modal:open'); // → true
         * WallKitEvents.isRegistered('wallkit:modal:close'); // → false
         */
        isRegistered(eventName) {
            return events.has(eventName);
        },

        /**
         * Возвращает список всех зарегистрированных событий
         *
         * @returns {string[]} - Отсортированный массив имён событий
         *
         * @example
         * WallKitEvents.register('wallkit:button:click');
         * WallKitEvents.register('wallkit:modal:open');
         * WallKitEvents.getRegisteredEvents(); // → ['wallkit:button:click', 'wallkit:modal:open']
         */
        getRegisteredEvents() {
            return Array.from(events).sort();
        },

        /**
         * Включает или выключает строгий режим
         *
         * @param {boolean} enabled - true для включения строгого режима
         * @description В строгом режиме можно отправлять только зарегистрированные события
         *
         * @example
         * // Включить строгий режим (по умолчанию)
         * WallKitEvents.setStrictMode(true);
         *
         * // Выключить для разработки
         * WallKitEvents.setStrictMode(false);
         * WallKitEvents.emit('wallkit:test:event'); // Работает без регистрации
         */
        setStrictMode(enabled) {
            strictMode = enabled;
        },

        /**
         * Отправляет событие с данными
         *
         * @param {string} eventName - Имя события для отправки
         * @param {Object} [data={}] - Данные события
         * @returns {CustomEvent|null} - Объект события или null при ошибке
         *
         * @example
         * // Зарегистрировать и отправить
         * WallKitEvents.register('wallkit:user:login');
         * WallKitEvents.emit('wallkit:user:login', {
         *   userId: 123,
         *   username: 'john_doe'
         * });
         *
         * // В строгом режиме без регистрации
         * WallKitEvents.emit('wallkit:unregistered:event'); // → null, ошибка в консоли
         */
        emit(eventName, data = {}) {
            if (!isValidEventName(eventName)) {
                console.error('WallKitEvents: имя события должно начинаться с "wallkit:"');
                return null;
            }

            if (strictMode && !events.has(eventName)) {
                console.error(`WallKitEvents: событие "${eventName}" не зарегистрировано. Сначала вызовите register().`);
                return null;
            }

            const event = new CustomEvent(eventName, {
                detail: data,
                bubbles: true,
                cancelable: true
            });

            window.dispatchEvent(event);
            return event;
        },

        /**
         * Подписывает обработчик на событие
         *
         * @param {string} eventName - Имя события для подписки
         * @param {Function} handler - Функция-обработчик, получает (data, event)
         * @returns {string} - ID подписки для управления
         *
         * @example
         * // Подписка на событие
         * const subscriptionId = WallKitEvents.on('wallkit:modal:open', (data, event) => {
         *   console.log('Модалка открыта:', data.modalId);
         *   console.log('Объект события:', event);
         * });
         *
         * // Можно подписаться на незарегистрированные события
         * WallKitEvents.on('wallkit:custom:event', handler); // Работает всегда
         */
        on(eventName, handler) {
            const id = generateId();
            const wrappedHandler = (e) => handler(e.detail, e);

            window.addEventListener(eventName, wrappedHandler);

            subscriptions.set(id, {
                id,
                eventName,
                handler: wrappedHandler,
                originalHandler: handler
            });

            return id;
        },

        /**
         * Подписывает обработчик на одноразовое выполнение
         *
         * @param {string} eventName - Имя события
         * @param {Function} handler - Функция-обработчик
         * @returns {string} - ID подписки
         *
         * @example
         * // Сработает только один раз
         * WallKitEvents.once('wallkit:user:login', (userData) => {
         *   showWelcomeMessage(userData.username);
         * });
         *
         * // При следующем emit обработчик не выполнится
         * WallKitEvents.emit('wallkit:user:login', user1); // Сработает
         * WallKitEvents.emit('wallkit:user:login', user2); // Не сработает
         */
        once(eventName, handler) {
            const id = generateId();
            const wrappedHandler = (e) => {
                this.off(id);
                handler(e.detail, e);
            };

            window.addEventListener(eventName, wrappedHandler);

            subscriptions.set(id, {
                id,
                eventName,
                handler: wrappedHandler,
                originalHandler: handler,
                once: true
            });

            return id;
        },

        /**
         * Отписывает обработчик по ID подписки
         *
         * @param {string} id - ID подписки, полученный от on() или once()
         * @returns {boolean} - true если отписка успешна
         *
         * @example
         * const subId = WallKitEvents.on('wallkit:button:click', handler);
         *
         * // Позже отписываемся
         * WallKitEvents.off(subId); // → true
         *
         * // Повторная отписка
         * WallKitEvents.off(subId); // → false (подписка уже удалена)
         */
        off(id) {
            const subscription = subscriptions.get(id);
            if (!subscription) return false;

            window.removeEventListener(subscription.eventName, subscription.handler);
            subscriptions.delete(id);
            return true;
        },

        /**
         * Удаляет все подписки
         *
         * @param {null} [eventName] - Опционально: имя события для удаления только его подписок
         *
         * @example
         * // Удалить все подписки на конкретное событие
         * WallKitEvents.destroy('wallkit:modal:open');
         *
         * // Удалить все подписки полностью
         * WallKitEvents.destroy();
         *
         * // При размонтировании компонента
         * class MyComponent {
         *   destroy() {
         *     WallKitEvents.destroy('wallkit:component:custom:event');
         *   }
         * }
         */
        destroy(eventName = null) {
            if (eventName) {
                // Удаляем подписки только для указанного события
                for (const [id, sub] of subscriptions) {
                    if (sub.eventName === eventName) {
                        window.removeEventListener(sub.eventName, sub.handler);
                        subscriptions.delete(id);
                    }
                }
            } else {
                // Удаляем все подписки
                for (const [id, sub] of subscriptions) {
                    window.removeEventListener(sub.eventName, sub.handler);
                }
                subscriptions.clear();
            }
        },

        /**
         * Возвращает статистику системы событий
         *
         * @returns {Object} - Объект со статистикой
         * @property {number} totalSubscriptions - Всего активных подписок
         * @property {Object} byEvent - Количество подписок по событиям
         * @property {number} registeredEvents - Количество зарегистрированных событий
         *
         * @example
         * const stats = WallKitEvents.stats();
         * // {
         * //   totalSubscriptions: 5,
         * //   byEvent: {
         * //     'wallkit:modal:open': 2,
         * //     'wallkit:button:click': 3
         * //   },
         * //   registeredEvents: 10
         * // }
         */
        stats() {
            const byEvent = {};

            // Считаем подписки по событиям
            for (const sub of subscriptions.values()) {
                byEvent[sub.eventName] = (byEvent[sub.eventName] || 0) + 1;
            }

            return {
                totalSubscriptions: subscriptions.size,
                byEvent,
                registeredEvents: events.size
            };
        }
    };
})();

// Экспорт в глобальную область видимости
if (typeof window !== 'undefined') {
    window.WallKitEvents = WallKitEvents;
}

// Экспорт для модульных систем
if (typeof module !== 'undefined' && module.exports) {
    module.exports = WallKitEvents;
}