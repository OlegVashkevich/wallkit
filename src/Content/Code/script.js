class WallKitCodeHighlighter {
    static loadedLanguages = new Set();
    static prismLoading = null; // Promise для отслеживания загрузки
    static pluginsLoading = null;

    static async highlight(element) {
        const language = Array.from(element.classList)
            .find(cls => cls.startsWith('language-'))
            ?.replace('language-', '');

        if (!language || language === 'plaintext') return;

        // Ждём загрузки Prism (если она уже идёт, используем тот же Promise)
        await this.ensurePrismLoaded(language);

        // Теперь безопасно вызываем highlight
        if (window.Prism) {
            Prism.highlightElement(element);
        }
    }

    static async ensurePrismLoaded(language) {
        // Если Prism уже загружен
        if (window.Prism) return;

        // Если загрузка уже идёт, ждём её завершения
        if (this.prismLoading) {
            return await this.prismLoading;
        }

        // Создаём новый Promise для загрузки
        this.prismLoading = this.loadPrismCore(language);
        return this.prismLoading;
    }

    static async loadPrismCore(language) {
        try {
            // Загружаем основной Prism
            await this.loadScript('https://cdnjs.cloudflare.com/ajax/libs/prism/1.30.0/prism.min.js');

            // Загружаем обязательные зависимости
            await this.loadScript('https://cdnjs.cloudflare.com/ajax/libs/prism/1.30.0/components/prism-markup-templating.min.js');

            // Загружаем плагины (если ещё не загружены)
            await this.loadPlugins();

            // Загружаем нужный языковой плагин
            await this.loadLanguage(language);

            //console.log('WallKit: Prism.js loaded successfully');
        } catch (error) {
            console.error('WallKit: Failed to load Prism.js', error);
            // Сбрасываем флаг при ошибке
            this.prismLoading = null;
            throw error;
        }
    }

    static async loadPlugins() {
        // Если плагины уже загружаются, ждём
        if (this.pluginsLoading) {
            return await this.pluginsLoading;
        }

        this.pluginsLoading = (async () => {
            // Загружаем CSS для плагинов
            await this.loadCss('https://cdnjs.cloudflare.com/ajax/libs/prism/1.30.0/themes/prism-tomorrow.min.css');
            await this.loadCss('https://cdnjs.cloudflare.com/ajax/libs/prism/1.30.0/plugins/line-numbers/prism-line-numbers.min.css');
            await this.loadCss('https://cdnjs.cloudflare.com/ajax/libs/prism/1.30.0/plugins/toolbar/prism-toolbar.min.css');

            // Загружаем JS плагины
            await this.loadScript('https://cdnjs.cloudflare.com/ajax/libs/prism/1.30.0/plugins/line-numbers/prism-line-numbers.min.js');
            await this.loadScript('https://cdnjs.cloudflare.com/ajax/libs/prism/1.30.0/plugins/toolbar/prism-toolbar.min.js');
            await this.loadScript('https://cdnjs.cloudflare.com/ajax/libs/prism/1.30.0/plugins/copy-to-clipboard/prism-copy-to-clipboard.min.js');
        })();

        return this.pluginsLoading;
    }

    static async loadLanguage(lang) {
        if (this.loadedLanguages.has(lang)) return;

        const supported = {
            php: 'prism-php.min.js',
            javascript: 'prism-javascript.min.js',
            js: 'prism-javascript.min.js',
            typescript: 'prism-typescript.min.js',
            ts: 'prism-typescript.min.js',
            html: 'prism-markup.min.js',
            css: 'prism-css.min.js',
            scss: 'prism-scss.min.js',
            python: 'prism-python.min.js',
            py: 'prism-python.min.js',
            sql: 'prism-sql.min.js',
            json: 'prism-json.min.js',
            yaml: 'prism-yaml.min.js',
            bash: 'prism-bash.min.js',
        };

        if (supported[lang]) {
            this.loadedLanguages.add(lang);
            await this.loadScript(
                `https://cdnjs.cloudflare.com/ajax/libs/prism/1.30.0/components/${supported[lang]}`
            );
        }
    }

    static loadScript(src) {
        return new Promise((resolve, reject) => {
            const script = document.createElement('script');
            script.src = src;
            script.onload = () => resolve();
            script.onerror = () => reject(new Error(`Failed to load script: ${src}`));
            document.head.appendChild(script);
        });
    }

    static loadCss(href) {
        return new Promise((resolve, reject) => {
            // Проверяем, не загружена ли уже эта CSS
            const existingLinks = document.querySelectorAll(`link[href="${href}"]`);
            if (existingLinks.length > 0) {
                resolve();
                return;
            }

            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = href;
            link.onload = () => resolve();
            link.onerror = () => reject(new Error(`Failed to load CSS: ${href}`));
            document.head.appendChild(link);
        });
    }
}

// Автоматическое применение с защитой от дублирования
document.addEventListener('DOMContentLoaded', () => {
    // Находим все блоки кода, которые нужно подсветить
    const codeBlocks = document.querySelectorAll('[class*="language-"]');

    if (codeBlocks.length === 0) return;

    // Собираем уникальные языки
    const languages = new Set();
    codeBlocks.forEach(block => {
        const lang = Array.from(block.classList)
            .find(cls => cls.startsWith('language-'))
            ?.replace('language-', '');
        if (lang && lang !== 'plaintext') {
            languages.add(lang);
        }
    });

    //console.log(`WallKit: Found ${codeBlocks.length} code blocks in ${languages.size} languages`);

    // Подсвечиваем каждый блок
    codeBlocks.forEach(code => {
        WallKitCodeHighlighter.highlight(code).catch(error => {
            console.error('WallKit: Failed to highlight code block', error, code);
        });
    });
});