## Описание системы предоставления информации о "Продаже запасных частей"

### 1. Общее Описание Системы

Данная система представляет собой веб-приложение на базе Symfony PHP Framework, предназначенное для предоставления информации о продажах, возвратах и остатках запасных частей клиентами дистрибутора (далее дилерами) через API, а также для управления этими данными через административную панель.

**Ключевые технологии:**

*   **Symfony:** PHP фреймворк для разработки веб-приложений.
*   **PHP:** Язык программирования (Версия выше 8.2).
*   **Docker и Docker Compose:** Для контейнеризации и управления средой разработки/развертывания.
*   **PostgreSQL:** Система управления реляционными базами данных.
*   **Doctrine ORM:** Для работы с базой данных (маппинг объектов на таблицы).
*   **EasyAdminBundle:** Для быстрого создания административных интерфейсов.
*   **FOSUserBundle:** Для управления пользователями и аутентификации.
*   **Zenstruck Foundry:**  для генерации тестовых данных.
*   **API:** RESTful интерфейс для получения данных.

### 2. Docker-конфигурация и Развертывание

Система использует Docker для создания консистентной среды разработки и упрощения развертывания.

#### 2.1. Файл `docker-compose.yml`

Описывает сервисы, необходимые для работы приложения:

*   **`php`**:
    *   Собирается из `docker/php/Dockerfile`.
    *   Содержит PHP-FPM для обработки PHP скриптов.
    *   Том ` ./:/var/www/html ` монтирует текущий каталог проекта в контейнер.
    *   Зависит от сервиса `postgres`.
    *   Переменные окружения:
        *   `APP_ENV=dev`: Устанавливает окружение Symfony в режим разработки.
        *   `DATABASE_URL=postgresql://${POSTGRES_USER}:${POSTGRES_PASSWORD}@${POSTGRES_HOST}:5432/${POSTGRES_DB}?serverVersion=15&charset=utf8`: Строка подключения к базе данных PostgreSQL. Значения `${POSTGRES_USER}`, `${POSTGRES_PASSWORD}`, `${POSTGRES_HOST}` и `${POSTGRES_DB}` обычно задаются в файле `.env`.

*   **`nginx`**:
    *   Использует образ `nginx:alpine`.
    *   Веб-сервер, проксирующий запросы к `php` сервису.
    *   Порт `80` хоста маппится на порт `80` контейнера.
    *   Тома:
        *   `./:/var/www/html`: Доступ к файлам проекта.
        *   `./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf`: Пользовательская конфигурация Nginx.
    *   Зависит от сервиса `php`.

*   **`postgres`**:
    *   Использует образ `postgres:15-alpine`.
    *   Сервер базы данных PostgreSQL.
    *   Переменные окружения (обычно из `.env`):
        *   `POSTGRES_DB`: Имя базы данных.
        *   `POSTGRES_USER`: Пользователь базы данных.
        *   `POSTGRES_PASSWORD`: Пароль пользователя базы данных.
    *   Порт `5432` хоста маппится на порт `5432` контейнера.
    *   Том `postgres_data:/var/lib/postgresql/data`: Сохранение данных PostgreSQL между перезапусками контейнеров.

*   **`mailer`**:
    *   Использует образ `axllent/mailpit`.
    *   Локальный SMTP-сервер для тестирования отправки email (например, при регистрации пользователя или сбросе пароля).
    *   Порты:
        *   `1025` (SMTP)
        *   `8025` (веб-интерфейс Mailpit для просмотра писем).

#### 2.2. Процесс развертывания (локально)

Следуя инструкциям из `README.md` и общим практикам:

1.  **Клонирование репозитория:**
    ```bash
    git clone <URL_РЕПОЗИТОРИЯ>
    cd <ИМЯ_КАТАЛОГА_ПРОЕКТА>
    ```
2.  **Настройка окружения:**
    *   Создайте файл `.env.local` (если его нет) путем копирования из `.env` (если он есть и содержит значения по умолчанию) или `.env.dist`.
    *   Заполните необходимые переменные окружения в `.env.local`, особенно `POSTGRES_USER`, `POSTGRES_PASSWORD`, `POSTGRES_DB`, `POSTGRES_HOST` (обычно `postgres`). Например:
        ```env
        POSTGRES_USER=symfony
        POSTGRES_PASSWORD=password
        POSTGRES_DB=symfony_db
        POSTGRES_HOST=postgres
        DATABASE_URL="postgresql://${POSTGRES_USER}:${POSTGRES_PASSWORD}@${POSTGRES_HOST}:5432/${POSTGRES_DB}?serverVersion=15&charset=utf8"
        APP_SECRET=your_strong_secret_key # Сгенерируйте случайный ключ
        ```
3.  **Сборка и запуск контейнеров:**
    ```bash
    docker compose up -d --build
    ```
4.  **Установка зависимостей PHP (Composer):**
    ```bash
    docker compose exec php composer install
    ```
5.  **Выполнение миграций базы данных:**
    ```bash
    docker compose exec php php bin/console doctrine:migrations:migrate
    ```
    При первом запуске может потребоваться создать базу данных:
    ```bash
    docker compose exec php php bin/console doctrine:database:create --if-not-exists
    ```
6.  **Загрузка начальных/тестовых данных:**
    *   Для загрузки данных из JSON-файлов (как определено в `ImportMockDataCommand`):
        ```bash
        docker compose exec php php bin/console app:import-mock-data
        ```
    *   Или, если используются стандартные фикстуры Doctrine:
        ```bash
        docker compose exec php php bin/console doctrine:fixtures:load
        ```
7.  **Создание администратора:**
    Если используется `FOSUserBundle` напрямую или ваша команда `app:create-admin`:
    ```bash
    docker compose exec php php bin/console app:create-admin
    ```
    Или стандартная команда FOSUserBundle:
    ```bash
    docker compose exec php php bin/console fos:user:create admin_username admin_email@example.com admin_password --super-admin
    ```
8.  **Доступ к приложению:**
    *   Веб-сайт: `http://localhost`
    *   Админ-панель: `http://localhost/admin`
    *   Mailpit (почта): `http://localhost:8025`

#### 2.3. Процесс деплоя (общие соображения для production)

Развертывание на production требует дополнительных шагов:

*   **Переменные окружения:** Использовать реальные значения для `APP_ENV=prod`, `APP_SECRET`, `DATABASE_URL` и других чувствительных данных. Хранить их безопасно (например, в секретах CI/CD или платформы хостинга).
*   **Nginx:** Настроить HTTPS с использованием SSL-сертификатов (например, Let's Encrypt).
*   **Symfony:**
    *   Установить зависимости без dev-пакетов: `composer install --no-dev --optimize-autoloader`.
    *   Очистить и прогреть кеш для production: `php bin/console cache:clear --env=prod`.
    *   Проверить конфигурацию логирования.
*   **База данных:** Настроить регулярное резервное копирование.
*   **CI/CD:** Автоматизировать процессы сборки, тестирования и развертывания.
*   **Сервер Context7 (MCP):** Если "MCP сервер Context7" — это ваша целевая платформа, то деплой будет включать специфичные для этой платформы шаги. Это может включать настройку окружения на сервере, конфигурацию доступа, использование специфичных инструментов для деплоя, предоставляемых Context7. Необходимо обратиться к документации Context7 для получения точных инструкций.

### 3. Структура и Описание Кода Системы

#### 3.1. Сущности (Entities - `src/Entity/`)

Сущности представляют собой PHP-классы, которые Doctrine ORM отображает на таблицы в базе данных.

*   **`Sale.php`**:
    *   Таблица: `sales`.
    *   Поля: `id`, `taxId` (ИНН), `salesDate` (дата продажи, должна быть `date`, а не `salesDate` в ORM\Index), `brand` (бренд), `sku` (артикул), `quantity` (количество).
    *   Индексы по: `taxId`, `brand`, `sku`, `salesDate`.
    *   ```php
      <?php
      // src/Entity/Sale.php
      namespace App\Entity;
      use Doctrine\ORM\Mapping as ORM;
      use App\Repository\SaleRepository; // Убедитесь, что это добавлено

      #[ORM\Entity(repositoryClass: SaleRepository::class)] // Используйте одинарные кавычки или ::class с импортом
      #[ORM\Table(name: 'sales')]
      #[ORM\Index(fields: ['taxId'])]
      #[ORM\Index(fields: ['brand'])]
      #[ORM\Index(fields: ['sku'])]
      #[ORM\Index(fields: ['salesDate'], name: 'idx_sales_date')] // Добавлено имя для индекса даты
      class Sale
      {
          #[ORM\Id]
          #[ORM\GeneratedValue]
          #[ORM\Column(type: 'integer')]
          private ?int $id = null;

          #[ORM\Column(type: 'string', length: 15, nullable: true)]
          private ?string $taxId = null;

          #[ORM\Column(type: 'datetime')] // Рекомендуется использовать 'datetime_immutable' или 'date_immutable' если время не важно
          private ?\DateTimeInterface $salesDate = null; // Используйте DateTimeInterface

          #[ORM\Column(type: 'string', length: 100, nullable: false)] // nullable: true в коде, но в запросе было false
          private string $brand;

          #[ORM\Column(type: 'string', length: 100, nullable: false)]
          private string $sku; // Был ?string $sku, но nullable: false

          #[ORM\Column(type: 'integer', nullable: false, options: ['default' => 1])]
          private int $quantity; // Был ?int $quantity
          
          // ... getters and setters ...
          public function getDate(): ?\DateTimeInterface { return $this->salesDate; }
          public function setDate(?\DateTimeInterface $salesDate): self { $this->salesDate = $salesDate; return $this; }
          // Остальные геттеры и сеттеры...
      }
      ```

*   **`ReturnData.php`**:
    *   Таблица: `returns`.
    *   Поля: `id`, `taxId`, `brand`, `sku`, `salesDate` (дата продажи), `returnDate` (дата возврата), `quantity`.
    *   Индексы по: `taxId`, `brand`, `sku`, `salesDate`, `returnDate`.
    *   Содержит метод `__toString`, возвращающий JSON-представление объекта (это не стандартная практика для `__toString`, обычно он возвращает простое строковое представление).

*   **`Stock.php`**:
    *   Таблица: `stock`.
    *   Поля: `id`, `brand`, `sku`, `stockDate` (дата обновления остатка), `quantity`.
    *   Индексы по: `brand`, `sku`, `stockDate`.
    *   Также содержит `__toString` с JSON.

*   **`User.php`**:
    *   Таблица: `fos_user` (стандарт для FOSUserBundle).
    *   Наследуется от `FOS\UserBundle\Model\User as BaseUser`.
    *   Определяет `id` как первичный ключ.
    *   Используется для аутентификации и авторизации.

*   **`Setting.php`**:
    *   Таблица: `settings`.
    *   Поля: `id`, `code` (уникальный код настройки), `value` (значение), `comment` (комментарий).
    *   Индекс по `code`.
    *   Аннотация `#[ApiResource]` указывает на интеграцию с API Platform (если она установлена и настроена).

#### 3.2. Репозитории (Repositories - `src/Repository/`)

Репозитории содержат логику для извлечения данных из базы. Каждый репозиторий связан с одной сущностью.

*   `SaleRepository.php`, `ReturnDataRepository.php`, `StockRepository.php`: Предоставляют стандартные методы (`find`, `findAll`, `findBy`, etc.) и могут содержать кастомные методы для более сложных запросов (например, `findAllFormatted` в вашем `SalesDataController`).

#### 3.3. Фабрики (Factories - `src/Factory/`)

Используются для создания экземпляров сущностей с фейковыми данными, обычно для тестирования или наполнения базы данных (seeding). Основаны на Zenstruck Foundry.

*   `ReturnDataFactory.php`, `SaleFactory.php`, `StockFactory.php`: Определяют, как генерировать случайные, но валидные данные для полей соответствующих сущностей. Используют `self::faker()` для доступа к генератору FakerPHP.

#### 3.4. API Контроллеры (`src/Controller/Api/`)

*   **`SalesDataController.php`**:
    *   Отвечает за обработку запросов к API, связанным с данными о продажах.
    *   Методы:
        *   `sales()`: Маршрут `/api/salesdata/sales` (согласно `config/routes.yaml`). Возвращает данные о продажах, используя `SaleRepository->findAllFormatted()`.
        *   `returns()`: Маршрут `/api/salesdata/returns`. Возвращает данные о возвратах из `ReturnDataRepository->findAllFormatted()`.
        *   `stocks()`: Маршрут `/api/salesdata/stocks`. Возвращает данные об остатках из `StockRepository->findAllFormatted()`.
    *   Все методы возвращают `JsonResponse`.

#### 3.5. Контроллеры Админ-панели (`src/Controller/Admin/`)

Используются EasyAdminBundle для создания интерфейса администратора.

*   **`DashboardController.php`**:
    *   Конфигурирует главную страницу админ-панели (`/admin`).
    *   Метод `configureMenuItems()` определяет пункты меню: "Dashboard", "Users", "Sales", "Return Data", "Stock", "Settings". Каждый пункт ссылается на соответствующий CRUD-контроллер.
    *   Доступ ограничен ролью `ROLE_ADMIN` через атрибут `#[IsGranted('ROLE_ADMIN')]`.

*   **`SaleCrudController.php`, `ReturnDataCrudController.php`, `StockCrudController.php`, `UserCrudController.php` (предположительно существует или должен быть создан), `SettingCrudController.php` (предположительно существует):**
    *   Каждый из этих контроллеров наследуется от `AbstractCrudController` EasyAdmin.
    *   Метод `getEntityFqcn()` возвращает полное имя класса сущности, которой управляет контроллер.
    *   Опционально могут переопределять методы `configureFields()`, `configureActions()`, `configureCrud()` для настройки отображения и поведения CRUD-операций.

*   **`SecurityController.php`**:
    *   Обрабатывает логику входа в админ-панель.
    *   Маршрут `/admin/login` (имя `admin_login`) отображает форму входа.
    *   Маршрут `/admin/login/check` (имя `admin_login_check`) обрабатывается фаерволом Symfony.
    *   Маршрут `/admin/logout` (имя `admin_logout`) обрабатывается фаерволом Symfony.

#### 3.6. Команды (`src/Command/`)

Консольные команды для выполнения различных задач.

*   **`ImportMockDataCommand.php` (`app:import-mock-data`):**
    *   Импортирует данные из JSON-файлов (`returns_mocking.json`, `sales_mocking.json`, `stocks_mocking.json`) из каталога `templates/dataexamples/` в соответствующие таблицы базы данных.
    *   Для каждой записи из JSON создается новый объект сущности, заполняются его поля и он сохраняется через `EntityManagerInterface`.
    *   Структура JSON-файлов: объект, где ключи - это числовые индексы, а значения - массивы данных для полей сущности в определенном порядке.
        *   `returns_mocking.json`: `[taxId, brand, sku, salesDate, returnDate, quantity]`
        *   `sales_mocking.json`: `[taxId, brand, sku, salesDate, quantity]`
        *   `stocks_mocking.json`: `[brand, sku, stockDate, quantity]`

*   **`CreateAdminCommand.php` (`app:create-admin`):**
    *   Позволяет создать пользователя с ролью `ROLE_ADMIN` через командную строку, запрашивая имя пользователя (которое, вероятно, используется как email), email и пароль.

#### 3.7. Конфигурационные файлы (`config/`)

*   **`routes.yaml`**:
    *   `homepage`: Маршрут `/` ведет на `HomepageController::index`.
    *   `admin`: Загружает маршруты для контроллеров в `src/Controller/Admin/` с префиксом `/admin`.
    *   `controllers`: Загружает основные маршруты для контроллеров в `src/Controller/`.
    *   `api_platform`: (Если используется) Загружает маршруты API Platform с префиксом `/api`.
    *   `fos_user`: Загружает стандартные маршруты FOSUserBundle (регистрация, вход и т.д., но они могут быть перекрыты вашим `admin` фаерволом).
    *   `api_salesdata`: Загружает маршруты для контроллеров в `src/Controller/Api/` с префиксом `/api/salesdata`.

*   **`security.yaml`**:
    *   `password_hashers`: Определяет алгоритмы хеширования паролей для `UserInterface` и `FOS\UserBundle\Model\UserInterface`.
    *   `providers`: `fos_userbundle` использует стандартный провайдер пользователей FOSUserBundle.
    *   `firewalls`:
        *   `dev`: Отключает безопасность для служебных маршрутов Symfony в dev-режиме.
        *   `admin`: Защищает пути, начинающиеся с `/admin`. Использует `form_login` с путями `admin_login` и `admin_login_check`. Перенаправляет на `admin_dashboard` после успешного входа. Настроен выход (`admin_logout`) и "запомнить меня".
        *   `main`: Охватывает все остальные пути (`^/`). **Безопасность для этого фаервола отключена (`security: false`)**, что означает, что все пути, не попадающие под `dev` или `admin`, будут общедоступны.
    *   `role_hierarchy`: Определяет иерархию ролей (`ROLE_ADMIN` включает `ROLE_USER`).
    *   `access_control`:
        *   `^/$`: Доступен всем (`PUBLIC_ACCESS`).
        *   `^/admin/login$`: Доступен всем (`PUBLIC_ACCESS`).
        *   `^/admin`: Требует роль `ROLE_ADMIN`.

*   **`doctrine.yaml`** (предполагаемое содержимое):
    *   Настройка подключения к базе данных (`dbal.url` из переменной окружения).
    *   Конфигурация ORM, включая автогенерацию прокси, стратегию именования, и маппинг для сущностей в `src/Entity` с использованием атрибутов.
    *   Определение кастомных типов Doctrine, если есть (например, `json`).

*   **`fos_user.yaml`** (предполагаемое содержимое):
    *   `db_driver: orm`
    *   `firewall_name: main` (хотя у вас основной фаервол для FOSUserBundle скорее `admin`)
    *   `user_class: App\Entity\User`
    *   `from_email`: Email-адрес и имя отправителя для писем.
    *   `service.mailer: fos_user.mailer.noop` (или другой настроенный mailer).
    *   Могут быть настройки для регистрации, сброса пароля и т.д.

*   **`easy_admin.yaml`** (предполагаемое содержимое):
    *   Может содержать глобальные настройки EasyAdmin, такие как `site_name`. Основная конфигурация сущностей и меню происходит в `DashboardController.php`.

#### 3.8. Mock-данные (`templates/dataexamples/`)

*   `returns_mocking.json`, `sales_mocking.json`, `stocks_mocking.json`: JSON файлы, содержащие массивы данных. Каждый элемент верхнеуровневого объекта — это массив, представляющий одну запись, где значения идут в определенном порядке, соответствующем полям сущностей, как это обрабатывается в `ImportMockDataCommand`.

### 4. Использование API

API предоставляет три основных эндпоинта (точки входа) для получения данных:

*   **`GET http://localhost/api/salesdata/sales`**:
    *   Возвращает список всех продаж. Данные берутся из базы данных через `SaleRepository`.
    *   Ответ в формате JSON.
*   **`GET http://localhost/api/salesdata/returns`**:
    *   Возвращает список всех возвратов. Данные берутся из базы данных через `ReturnDataRepository`.
    *   Ответ в формате JSON.
*   **`GET http://localhost/api/salesdata/stocks`**:
    *   Возвращает список всех остатков на складе. Данные берутся из базы данных через `StockRepository`.
    *   Ответ в формате JSON.

Все API эндпоинты требуют аутентификации через query-параметр `?secret=`, значение которого должно быть MD5-хешем секретного ключа. Секретный ключ можно изменить через административную панель в разделе Settings. Например: `?secret=098f6bcd4621d373cade4e832627b4f6`.

Методы `findAllFormatted()` в репозиториях подготавливают данные в нужном для API формате и поддерживают пагинацию через параметры `?page=` и `?per_page=`. По умолчанию `per_page=10`. Пример запроса: `/api/salesdata/sales?page=1&per_page=10&secret=098f6bcd4621d373cade4e832627b4f6`.

Если API Platform интегрирована для сущностей (`Sale`, `ReturnData`, `Stock`), то для них также будут доступны стандартные CRUD эндпоинты API Platform с такой же аутентификацией и пагинацией.

### 5. Административная Панель (EasyAdmin)

*   **Доступ:** `http://localhost/admin`
*   **Аутентификация:** При попытке доступа к `/admin` неаутентифицированный пользователь будет перенаправлен на `/admin/login`. Вход осуществляется с использованием учетных данных пользователя с ролью `ROLE_ADMIN`.
*   **Возможности:**
    *   **Dashboard:** Главная страница.
    *   **Users:** Управление пользователями системы (создание, редактирование, удаление).
    *   **Sales:** Управление данными о продажах.
    *   **Return Data:** Управление данными о возвратах.
    *   **Stock:** Управление данными об остатках.
    *   **Settings:** Управление настройками приложения.

### 6. Дополнительные Компоненты

*   **FOSUserBundle:** Обеспечивает базовую функциональность управления пользователями. В данной конфигурации, в основном, используется для аутентификации администраторов.
*   **EasyAdminBundle:** Позволяет быстро создавать полнофункциональные CRUD-интерфейсы для Doctrine-сущностей.

### 7. Файл `README.md`

Файл `README.md` содержит краткую инструкцию по запуску проекта для локальной разработки:
```
Distribution Example Application realized by Symfony PHP Framework 

For local development simple start command in console: 

docker compose up -d --build 
```
Эта команда собирает образы (если они изменились или отсутствуют) и запускает все сервисы, описанные в `docker-compose.yml`, в фоновом режиме (`-d`).
