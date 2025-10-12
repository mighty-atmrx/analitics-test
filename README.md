# 🚀 WB Analytics Sync Project

Проект для синхронизации данных из wb-api в базу данных MySQL.

## 📋 Описание

Система автоматически выгружает данные по заказам, продажам, поставкам и остаткам из wb-api и сохраняет их в локальную базу данных для последующего анализа.

	•	🐳 Развёртывание через Docker Compose — два сервиса: php и mysql.
	•	⚙️ Используется нестандартный порт MySQL (3307).
	•	🕒 Ежедневное обновление данных дважды в день — реализовано через планировщик Laravel Scheduler и команду sync:data.
	•	🚧 Обработка ошибок Too many requests — встроен retry-механизм с экспоненциальной задержкой.
	•	🐞 Вывод отладочной информации в консоль — через сервис DebugService и подробные логи.
	•	🏢 Структура данных в БД:
	•	Компания (Company)
	•	Аккаунты компании (Account)
	•	API сервисы (ApiService)
	•	Типы токенов (TokenType)
	•	Токены (Token)
	•	Связи сервисов и типов токенов (ApiServiceTokenType)
	•	🔐 Гибкая система токенов — поддержка bearer, api-key, login/password и других типов.
	•	⚙️ Консольные команды для добавления сущностей:
	•	make:company, make:account, make:api-service, make:token-type, make:api-service-token-type, make:token.
	•	👥 Поддержка нескольких аккаунтов — данные разделяются по account_id.
	•	⏰ Загрузка только свежих данных — по полю date без перезаписи старых записей.

## ⚠️ Важное примечание о синхронизации данных

**Проблемы с облачными базами данных:**
В процессе разработки были протестированы различные бесплатные облачные БД, однако выявлены серьезные ограничения:

- 🔄 **Постоянные падения** - соединение обрывается каждые 5-10 минут (MySQL server has gone away)
- 🐌 **Очень низкая скорость** - обработка 1000 записей занимает 10-20+ минут из-за ограничений пропускной способности
- ⏰ **Таймауты** - операции не успевают завершиться из-за лимитов на время выполнения
- 🚫 **Малое кол-во памяти** - для комфортной работы требуется 200MB+ свободного места в БД, а предоставляется 5-100MB...

**Решение:**
Для демонстрации работоспособности системы предоставлен готовый дамп базы данных (`dump.sql`), который содержит синхронизированные данные и может быть быстро развернут в локальной среде.
dump был сделан 05.10.2025
**Инструкция ниже👇**

## 💇️ Архитектура

Проект построен по принципам **DDD (Domain-Driven Design)** и **SOLID**:

### Структура проекта:

```bash
app/
app/
├── Console/
│   └── Commands/
│       ├── BaseCreateCommand.php          # Абстрактная команда
│       ├── CreateCompanyCommand.php       # Создание компании
│       ├── CreateAccountCommand.php       # Создание аккаунта
│       ├── CreateTokenTypeCommand.php     # Создание типа токена
│       ├── CreateApiServiceCommand.php    # Создание API сервиса
│       ├── CreateApiServiceTokenTypeCommand.php # Связь сервис-токен
│       ├── CreateTokenCommand.php         # Создание токена
│       └── SyncDataBase.php               # Синхронизация данных
├── Http/
│   ├── Exceptions/                        # Бизнес-исключения
│   │   ├── AccountAlreadyExistsException.php
│   │   ├── AccountNotFoundException.php
│   │   ├── ApiServiceAlreadyExistsException.php
│   │   ├── ApiServiceTokenTypeAlreadyExistsException.php
│   │   ├── CompanyNameIsTakenException.php
│   │   ├── DtoNotFoundException.php
│   │   ├── HandlerNotFoundException.php
│   │   ├── LoginPasswordRequiredException.php
│   │   ├── ServiceNotSupportTokenException.php
│   │   ├── TokenNotFoundException.php
│   │   └── TokenTypeAlreadyExistsException.php
│   ├── Requests/
│   │   └── Token/
│   │       └── StoreRequest.php           # Валидация токенов
│   └── Services/                          # Бизнес-сервисы
│       ├── BaseCreateService.php          # Базовый CRUD сервис
│       ├── CompanyService.php             # Управление компаниями
│       ├── AccountService.php             # Управление аккаунтами
│       ├── TokenTypeService.php           # Управление типами токенов
│       ├── ApiServiceManager.php          # Управление API сервисами
│       ├── TokenService.php               # Управление токенами
│       ├── SyncService.php                # Основной сервис синхронизации
│       ├── PaginatedDataFetcher.php       # Пагинация данных
│       ├── DateStrategyService.php        # Стратегии дат
│       ├── ApiHttpClientService.php       # HTTP клиент для WB API
│       └── DebugService.php               # Сервис логирования
├── Enum/                                  # Перечисления
│   ├── ApiServiceEnum.php
│   ├── SyncEndpointEnum.php
│   └── TokenTypeEnum.php
├── Dto/                                   # Data Transfer Objects
│   ├── BaseDto.php
│   ├── OrderDto.php
│   ├── SaleDto.php
│   ├── IncomeDto.php
│   ├── StockDto.php
│   ├── AccountDto.php
│   ├── ApiServiceDto.php
│   ├── ApiServiceTokenTypeDto.php
│   ├── CompanyDataDto.php
│   ├── TokenCreateDto.php
│   └── TokenTypeDto.php
├── Models/                                # Eloquent модели
│   ├── Order.php
│   ├── Stock.php
│   ├── Sale.php
│   ├── Income.php
│   ├── Account.php
│   ├── Company.php
│   ├── ApiService.php
│   ├── TokenType.php
│   ├── Token.php
│   └── ApiServiceTokenType.php
├── Repositories/                          # Репозитории данных
│   ├── AccountRepository.php
│   ├── ApiServiceRepository.php
│   ├── ApiServiceTokenTypeRepository.php
│   ├── CompanyRepository.php
│   ├── TokenRepository.php
│   └── TokenTypeRepository.php
├── Handlers/                              # Обработчики синхронизации
│   ├── BaseHandler.php
│   ├── OrderSyncHandler.php
│   ├── SaleSyncHandler.php
│   ├── IncomeSyncHandler.php
│   └── StockSyncHandler.php
└── Providers/
    └── AppServiceProvider.php             # Регистрация зависимостей
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
•	php — Laravel PHP контейнер
•	nginx — веб-сервер
•	mysql — MySQL база данных

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
docker-compose exec php php artisan migrate
```

# Импортируйте данные
📊 Загрузка демо-данных
В корне проекта есть файл dump.sql с готовыми демо-данными.
```bash
# Скопируйте дамп в контейнер
docker-compose cp dump.sql mysql:/dump.sql

docker-compose exec mysql bash

# Импортируйте данные 
mysql -u root -proot analytics < /dump.sql

mysql -u root -proot analytics

# можно проверять
# например вывести кол-во записей: 
# SELECT COUNT(id) FROM orders;
# SELECT COUNT(id) FROM sales;
# SELECT COUNT(id) FROM incomes;
# SELECT COUNT(id) FROM stocks;
```

## 🚀 Использование команды синхронизации данных
```bash
docker-compose exec php php artisan sync:data
```

Команда:
•	подгружает свежие данные по date
•	учитывает account_id
•	обрабатывает Too many requests через retry
•	логирует процесс и память

## 🧩 Консольные команды

| Команда                                                                   | Назначение                              |
|---------------------------------------------------------------------------|------------------------------------------|
| `php artisan make:company {name}`                                         | ➕ Добавить новую компанию               |
| `php artisan make:account {company_id, name}`                             | ➕ Добавить аккаунт                      |
| `php artisan make:token-type {type, code}`                                | ⚙️ Добавить тип токена                   |
| `php artisan make:api-service {name, code}`                               | 🌐 Добавить API-сервис                   |
| `php artisan make:api-service-token-type {api_service_id, token_type_id}` | 🔗 Связать сервис и тип токена           |
| `php artisan make:token {account_id, api_service_id, token_type_id}`      | 🔑 Добавить токен                        |
| `php artisan sync:data`                                                   | 🔄 Запустить синхронизацию данных        |


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
