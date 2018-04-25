# Yii2 SEO Анализ сайтов

> **Внимание:** Приложение на стадии начальной разработки.
 Все может поменяться. Используйте на свой страх и риск.

1. Инструмент предназначен для владельцев сайтов, которые доверяют продвижение SEO-специалистам,
но не знают как контролировать работу.
2. Инструмент предназначен для SEO-специалистов, которые хотят показать количество работы над сайтом,
предоставить отчетность о проделанной работе.
3. Инструмент можно использовать как дополнительный к вашим основным.
4. По функционалу схож с инструментами [http://pr-cy.ru/](http://pr-cy.ru/),
 [http://www.megaindex.ru/](http://www.megaindex.ru/), Google Insight

> **ВНИМАНИЕ:** Приглашаю к разработке seo-специалистов и программистов.

Приложение основано на шаблоне [akiraz2/yii2-app](https://github.com/akiraz2/yii2-app), поэтому 
разделено на 3 части `frontend`(пока не сделан), `backend` (личный кабинет) и `console` (запуск задач парсинга и пинга).

Заходить сразу в личный кабинет `backend.site.com` (login `adminus`, password `adminus`)

## Особенности
* можно создавать много проектов (название, адрес сайта, настройки) `SiteProject`
* в каждом проекте можно делать много снимков сайта `SiteSnapshot`
* парсится весь сайта, все сохраняется в базу данных. `SitePage`
* в ходе парсинга могут возникать ошибки - они сохраняются в базу. `SiteLog`
* необходимо сделать снимок сайт до начала работ, затем с периодичностью (2-4 недели) делать снимки 
для сравнения, что и какие изменения произошли на сайте

### Парсинг сайта
* процесс запускается с помощью очереди заданий (`cron php /your-site/yii queue/run` как пример)
* robots.txt - обычно небольшие файлы. сохраняется в базу. `SiteSnapshot`
* sitemap.xml - бывают громадные файлы. сохраняется **на диск** с зашифрованным именем
 (путь и соль хэша конфигурируется `common/config/params.php`)
* база SEO - title, meta desc, meta keywords
* **Opengraph** - записывается информация о количестве использованных Базовых Тегов (title, type, image, url)
 и Дополнительных (description, site_name, locale, video/audio).
* если есть ошибки *404/500/300*, об этом в `SiteLog` будет инфа, а так же количество ошибок в `SiteSnapshot`
* в базе сохраняется время парсинга каждой страницы (в мсек)

### Пинг сайта
* доступность сайта очень важна
* скорость очень важна
* у хостера бывают проблемы. это важно заметить и при необходимости исправить/сменить (поставить свой VPS)
* в проекте указывается пинг 0-отключить, целое число большее 60 - с какой периодичностью в секундах пинговать
сайт
* добавить в Cron каждые 60 секунд (ежеминутно) `cron php /your-path/yii site-check`

### Стресс тест (mini-dDOS)
* важно чтобы хостинг выдерживал нагрузки
* если злоупотреблять стресс тестом, хостинг может забанить ваш IP сервера
* используется инструмент Apache `abs -n xxx -c xxx site.com`

### Аналитика
* еще не готово (самая важная часть функционала)
* можно смотреть только ошибки
* нужно сделать сравнение снимков сайта и в удобной форме сделать отчет
* проверить sitemap.xml - вдруг есть битые ссылки
* проверить robots.txt - все директивы правильные или нет
* пинг сайта - сделать график скорости сайта и отказов, дать рекомендации
* к страницам привязать поисковые запросы и проверять позиции сайта в яндексе и google по заданному региону
* проверить разметку Schema.org, Opengraph
* проверить seo теги, дать рекомендации
* да и вообще посмотреть похожие инструменты, перенести их функционал в это open-source решение 


## Установка
Приложение предпочтительно устанавливать с помощью [Composer](https://getcomposer.org/download/)

```
composer create-project --prefer-dist akiraz2/yii2-seo-analysis my-site
```
После установки запустить команду `init`

### Миграции (таблицы БД)

> **Внимание:** Перед началом миграций необходимо задать имя базы данных, пользователя и пароля
 в файле `common/config/main-local.php`
 
```
php yii migrate/up --migrationPath=@vendor/dektrium/yii2-user/migrations
php yii migrate --migrationPath=@yii/log/migrations/
php yii migrate --migrationPath=vendor/ignatenkovnikita/yii2-queuemanager/migrations/
php yii migrate/up
```



## Досталось от yii2-app**
Все что ниже - как напоминание разработчикам о фичах шаблона
* Admin template: [yiister/yii2-gentelella](https://github.com/yiister/yii2-gentelella), [Demo](https://colorlib.com/polygon/gentelella/)
* Yii2 User: [dektrium/yii2-user](https://github.com/dektrium/yii2-user) (login `adminus`, password `adminus`)
* Frontend and Backend User Controllers are filtered (by `dektrium/yii2-user`)
* Redis cache
* Yii2 queue (DB table `queue`)
* Log DB Target with backend (`/log/index`)
* **UrlManagerFrontend** for backend app (all url rules in file `frontend/config/urls.php`, hostInfo in `common/config/params.php`)
* i18n translations in `common/messages` with config
* ContactForm in frontend app is improved: [himiklab/yii2-recaptcha-widget](https://github.com/himiklab/yii2-recaptcha-widget),
 all email are saved to DB (`common/models/EmailForm` Model), optionally send message to Viber messenger via bot
  (install requirements and config, uncomment code in Model)
* **postcss** config


## Available modules
These modules can be easy installed to Yii2-App using Composer:

* Yii2 Super Blog Module (semantic, seo): [akiraz2/yii2-blog](https://github.com/akiraz2/yii2-blog)
* Yii2 many web-statictic counters *(yandex, google, own db-counter)*: [akiraz2/yii2-stat](https://github.com/akiraz2/yii2-stat)
* yii2 opengraph component: [dragonjet/yii2-opengraph](https://github.com/dragonjet/yii2-opengraph)
* yii2 settings (db+cache): [yii2mod/yii2-settings](https://github.com/yii2mod/yii2-settings)
* etc...




## Development

### Messages
Change in `common/config/main.php`
```
'language' => 'ru-RU',
'sourceLanguage' => 'en-US',
```
In shell 
```
php yii message/extract common/messages/config.php
```

**POSTCSS**

```
webstorm file-watcher

scope file[mites-site]:frontend/web/src/pcss//*.css

program C:\Users\user4957\AppData\Roaming\npm\postcss.cmd

arguments $ContentRoot$\frontend\web\css\style.css --config $ContentRoot$\post.config.js
```

## Support

If you have any questions or problems with Yii2-App you can ask them directly
 by using following email address: `akiraz@bk.ru`.


## Contributing

If you'd like to contribute, please fork the repository and use a feature branch. Pull requests are warmly welcome.
+PSR-2 style coding.

I can apply patch, PR in 2-3 days! If not, please write me `akiraz@bk.ru`

## Licensing

Yii2-App is released under the BSD License. See the bundled [LICENSE.md](LICENSE.md)
for details. 
