🧪 Проверка доменов
Простое приложение на Laravel + Vue.js для проверки доменов: свободен или занят.
Тестовый логин: test@example.com / password

🧾 Требования
PHP 8.1+

Composer

Node.js 18+

NPM

MySQL

Git

🚀 Установка
1. Клонируй репозиторий
bash
Копировать
Редактировать
git clone https://github.com/your-username/domain-check.git
cd domain-check
2. Настрой бэкенд (Laravel)
bash
Копировать
Редактировать
cd domain-app
composer install
cp .env.example .env
Настрой .env:
env
Копировать
Редактировать
APP_URL=http://localhost:8000
DB_DATABASE=domain_check
DB_USERNAME=root
DB_PASSWORD=
FRONTEND_URL=http://localhost:5173
SANCTUM_STATEFUL_DOMAINS=localhost:5173
SESSION_DOMAIN=localhost
Замените DB_USERNAME и DB_PASSWORD на свои.

Далее:
bash
Копировать
Редактировать
php artisan key:generate
Создай базу данных:

bash
Копировать
Редактировать
mysql -u root -p
CREATE DATABASE domain_check;
Запусти миграции с сидерами:

bash
Копировать
Редактировать
php artisan migrate:fresh --seed
php artisan serve
3. Настрой фронтенд (Vue.js)
bash
Копировать
Редактировать
cd ../frontend
npm install
npm run dev
🔐 Тестовый вход
Получи токен:
bash
Копировать
Редактировать
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"password"}'
Сохрани токен в браузере:
Открой в браузере: http://localhost:5173
В консоли (F12 → Console):

js
Копировать
Редактировать
localStorage.setItem('auth_token', 'ВАШ_ТОКЕН_ЗДЕСЬ');
🔍 Как проверить домены
Перейди на http://localhost:5173

Введи список доменов, например:

arduino
Копировать
Редактировать
free.net
available.org
example.com
Нажми "Проверить"

✅ Результат:

Домен	Статус	Истекает
free.net	Доступен	-
available.org	Доступен	-
example.com	Занят	2026-05-23

📦 Тестовые данные
Пользователь: test@example.com / password

Свободные домены: free.net, available.org

Занятые домены: example.com, test.org

