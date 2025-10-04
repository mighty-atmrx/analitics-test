# 🚀 WB Analytics Sync Project

Проект для синхронизации данных из wb-api в базу данных MySQL.

## 📋 Описание

Система автоматически выгружает данные по заказам, продажам, поставкам и остаткам из wb-api и сохраняет их в локальную базу данных для последующего анализа.

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

### 5. Настройка базы данных

Отредактируйте `.env` файл:

```env
# Database Configuration
DB_CONNECTION=mysql
DB_HOST=shinkansen.proxy.rlwy.net
DB_PORT=53407
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=NrgcgZwUeXlKlNlbPhfeIQkEcDIIugPs

# wb-api Configuration
WB_API_KEY=E6kUTYrYwZq2tN4QEtyzsbEBk3ie
WB_API_URL=http://109.73.206.144:6969
```

### 6. Запуск миграций

```bash
php artisan migrate
```

## 🚀 Использование

```bash
php artisan sync:data
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
