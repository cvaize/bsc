# BSC
## Batyukov Studio Commerce package.

Пакет создан для Batyukov Studio Commerce package. Пакет нужен для работы с темами и хранит в себе
шаблоны по умолчанию.

### Инструкция по установке
- Установка производится командой `composer require cvaize/bsc-themes`
- После установки вы можете опубликовать настройки и изменить их под себя `config/themes.php` 
командой `php artisan vendor:publish --provider="BSC\Themes\ThemesServiceProvider"`

### Инструкция по использованию
Для использования тем в вашем приложении вам необходимо использовать namespace `theme` 
в названии view.

Например: `view('theme::welcome')`.

View Factory сначала проверит есть ли view по пути `resources/views/themes/{theme}` 
(`theme` вы указываете в `config/themes.php`) если он есть то будет использован этот view,
если его нет то будет использован view по пути `vendor/cvaize/bsc-themes/resources/views/welcome.blade.php`.

Чтобы использовать создать свою тему, вам достаточно скопировать содержание папки `vendor/cvaize/bsc-themes/resources/views`
в директорию `resources/views/themes/{theme}` и указать `theme` в `config/themes.php`.
