# 🚀 WB Analytics Sync Project

Проект для синхронизации данных из wb-api в базу данных MySQL.

## 📋 Описание

Система автоматически выгружает данные по заказам, продажам, поставкам и остаткам из wb-api и сохраняет их в локальную базу данных для последующего анализа.

## ⚠️ Важное примечание о синхронизации данных

**Проблемы с облачными базами данных:**
В процессе разработки были протестированы различные бесплатные облачные БД, однако выявлены серьезные ограничения:

- 🔄 **Постоянные падения** - соединение обрывается каждые 5-10 минут (MySQL server has gone away)
- 🐌 **Очень низкая скорость** - обработка 1000 записей занимает 10-20+ минут из-за ограничений пропускной способности
- ⏰ **Таймауты** - операции не успевают завершиться из-за лимитов на время выполнения
- 🚫 **Малое кол-во памяти** - для комфортной работы требуется 200MB+ свободного места в БД, а предоставляется 5-100MB...

**Решение:**
Для демонстрации работоспособности системы предоставлен готовый дамп базы данных (`dump.sql`), который содержит синхронизированные данные и может быть быстро развернут в локальной среде.
**Инструкция ниже👇**

## 💇️ Архитектура

Проект построен по принципам **DDD (Domain-Driven Design)** и **SOLID**:

### Структура проекта:

```bash
app/
├── Console/Commands/SyncDataBase.php # Artisan команда
├── Http/
│   ├── Exceptions/                       # Исключения
│   │   ├── DtoNotFoundException.php
│   │   └── HandlerNotFoundException.php
│   │
│   └── Services/                         # Сервисы
│       ├── SyncService.php               # Основной сервис синхронизации
│       └── ApiClientService.php          # Клиент для WB API
├── Handlers/ # Обработчики для разных типов данных
│ ├── BaseHandler.php
│ ├── OrderSyncHandler.php
│ ├── SaleSyncHandler.php
│ ├── IncomeSyncHandler.php
│ └── StockSyncHandler.php
├── Dto/ # Data Transfer Objects
│ ├── BaseDto.php
│ ├── OrderDto.php
│ ├── SaleDto.php
│ ├── IncomeDto.php
│ └── StockDto.php
├── Enum/
│ └── SyncEndpointEnum.php # Enum для типов данных
├── Providers/
| └── AppServiceProvider.php # Регистрация зависимостей
└── Models/ 
  ├── Order.php
  ├── Sale.php
  ├── Income.php
  └── Stock.php
```

## 🛠️ Установка и настройка

### 1. Клонирование репозитория
```bash
git clone https://github.com/mighty-atmrx/analytics-test

cd analitics-test
```


### 2. Установка зависимостей

```bash
composer install
```

### 3. Настройка окружения

Скопируйте файл окружения:

```bash
cp .env.example .env
```

### 4. 🐳 Работа с Docker

🔹 Запуск контейнеров

```bash
docker-compose up -d --build
```

Это запустит:
•	backend — Laravel PHP контейнер
•	nginx — веб-сервер
•	db — MySQL база данных

### 5. Настройка базы данных

Отредактируйте `.env` файл:

```env
# Database Configuration
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=analytics
DB_USERNAME=analytic
DB_PASSWORD=analytic1235678

# wb-api Configuration
WB_API_KEY=E6kUTYrYwZq2tN4QEtyzsbEBk3ie
WB_API_URL=http://109.73.206.144:6969
```

### 6. Запуск генерации ключа приложения

```bash
docker-compose exec backend php artisan key:generate
```

### 7. Запуск миграций

```bash
docker-compose exec backend php artisan migrate
```

# Импортируйте данные
📊 Загрузка демо-данных
В корне проекта есть файл dump.sql с готовыми демо-данными.
**Вариант 1: Автоматическая загрузка**
```bash
# Загрузка дампа в запущенный контейнер
docker-compose exec db mysql -u analytic -panalytic1235678 analytics < dump.sql
```

**Вариант 2: Ручная загрузка**
```bash
# Скопируйте дамп в контейнер
docker cp dump.sql analytics-test-db-1:/tmp/dump.sql

# Импортируйте данные
docker-compose exec db mysql -u analytic -panalytic1235678 analytics -e "source /tmp/dump.sql"
```

## 🚀 Использование команды синхронизации данных
```bash
docker-compose exec backend php artisan sync:data
```

Команда последовательно выгружает данные по заказам, продажам, поставкам и остаткам.

## 🔧 Логирование

Все операции логируются в `storage/logs/laravel.log` с детальной информацией (статусы HTTP, кол-во записей, использование памяти и др.).

## ⚙️ Особенности

* Пагинация по 500 записей на страницу
* Retry механизм для HTTP запросов
* Чанкование данных по 1000 записей
* Мониторинг использования памяти

## 📊 Мониторинг

В логах отслеживается процесс загрузки, подгрузки страниц и использование памяти.

🗃️ Данные в демо-дампе
Файл dump.sql содержит готовые данные для демонстрации:

✅ Orders: 117,203 записей

✅ Sales: 108,501 записей

✅ Incomes: 2,533 записей

✅ Stocks: 3,496 записей

📅 Данные за период: 2000-01-01 - 2025-10-05
