# BSC
## Batyukov Studio Commerce package.

Пакет электронной коммерции объединяет в себе расширенную функциональность интернет магазина для быстрого развертывания
и кастомизации.

### Инструкция по установке
- Установка пакета `composer require cvaize/bsc`
- Миграция таблиц `php artisan bsc:migrate`

### Темы, views
После установки вы можете опубликовать настройки темы и изменить их под себя `config/themes.php` 
командой 

`php artisan vendor:publish --provider="BSC\Providers\ThemesServiceProvider"`
`php artisan vendor:publish --provider="BSC\Providers\DefineServiceProvider"`

Для использования тем в вашем приложении вам необходимо использовать namespace `theme` 
в названии view.

Например: `view('theme::welcome')`.

View Factory сначала проверит есть ли view по пути `resources/views/themes/{theme}` 
(`theme` вы указываете в `config/themes.php`) если он есть то будет использован этот view,
если его нет то будет использован view по пути `vendor/cvaize/bsc/src/resources/views/welcome.blade.php`.

Чтобы использовать создать свою тему, вам достаточно скопировать содержание папки `vendor/cvaize/bsc/src/resources/views`
в директорию `resources/views/themes/{theme}` и указать `theme` в `config/themes.php`.
