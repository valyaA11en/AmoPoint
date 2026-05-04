# AmoPoint Test Task

Laravel-проект для тестового задания на позицию PHP-разработчика.

## Что реализовано

### Основное задание

- Консольная команда `jokes:fetch`
- Получение данных из внешнего API каждые 5 минут
- Сохранение данных в таблицу `jokes`
- JSON endpoint `/api/jokes`
- JS-файл для фильтрации полей по выбранному типу

### Дополнительное задание

- JS-счетчик посещений страницы
- Backend API для сохранения посещений
- Сохранение IP, города, устройства, URL страницы, referrer, языка, timezone и размера экрана
- Страница статистики `/admin/visits`
- Авторизация для просмотра статистики
- График уникальных посещений по часам
- Круговая диаграмма посещений по городам

---

## Требования

- PHP 8.3+
- Composer
- Node.js / npm
- SQLite / MySQL / PostgreSQL

---

## Установка

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm run build
```
