🧪 Проверка доменов
Простое приложение на Laravel + Vue.js для проверки доменов: свободен или занят.
Тестовый логин: test@example.com / password

Проверка доменов (Laravel + Vue.js)
Требования
PHP 8.1+

Composer

Node.js 18+

npm

MySQL

Git

Установка и запуск
1. Клонируем проект и переходим в папку
git clone https://github.com/your-username/domain-check.git
cd domain-check/domain-app

3. Laravel (бэкенд)
composer install
cp .env.example .env

Отредактируй
.env:
APP_URL=http://localhost:8000
DB_DATABASE=domain_check
DB_USERNAME=root
DB_PASSWORD= # поставь свой пароль
FRONTEND_URL=http://localhost:5173
SANCTUM_STATEFUL_DOMAINS=localhost:5173
SESSION_DOMAIN=localhost

Сгенерируй ключ:
php artisan key:generate
Создай базу:

CREATE DATABASE domain_check;
Запусти миграции и сидеры:

php artisan migrate:fresh --seed
Запускай сервер Laravel:


php artisan serve
3. Vue.js (фронтенд)
В отдельной вкладке терминала:

cd ../frontend
npm install
npm run dev
Тестовый вход
Чтобы получить токен:

curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"password"}'
В браузере открой http://localhost:5173

Открой консоль (F12 → Console) и вставь токен так:

localStorage.setItem('auth_token', 'ТВОЙ_ТОКЕН_ЗДЕСЬ');
Проверка доменов
Введи домены (по одному на строку) например:
=
arduino
free.net
available.org
example.com
Нажми кнопку "Проверить"

Результат
Домен	Статус	Истекает
free.net	Доступен	-
available.org	Доступен	-
example.com	Занят	2026-05-23

Тестовые данные
Email: test@example.com

Пароль: password

Свободные: free.net, available.org
Занятые: example.com, test.org
