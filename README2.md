# Полный учебник по проекту CRM «Авто» на Laravel и PHP

Этот документ — **очень подробное объяснение**: что за чем стоит, **каждая функция** в **нашем коде** (папка `app`, `routes`, `database/seeders`, важные шаблоны). Код фреймворка из папки `vendor` здесь почти не разбираем — его ты изучишь по документации Laravel.

**Как пользоваться:** держи открытым нужный файл в редакторе и читай соответствующий раздел ниже.

---

## Оглавление

1. [Словарь: объясняем слова](#1-словарь-объясняем-слова)
2. [Как браузер доходит до твоего кода](#2-как-браузер-доходит-до-твоего-кода)
3. [`bootstrap/app.php` — старт приложения](#3-bootstrapappphp--старт-приложения)
4. [`routes/web.php` — каждая строка маршрутов](#4-routeswebphp--каждая-строка-маршрутов)
5. [`AbstractEntityController` — все методы](#5-abstractentitycontroller--все-методы)
6. [Контроллеры разделов (наследники)](#6-контроллеры-разделов-наследники)
7. [`UserController` — отдельный CRUD сотрудников](#7-usercontroller--отдельный-crud-сотрудников)
8. [`DashboardController`](#8-dashboardcontroller)
9. [`WalletPageController`](#9-walletpagecontroller)
10. [`LoginController` и `RegisterController`](#10-logincontroller-и-registercontroller)
11. [`Controller` и `AppServiceProvider`](#11-controller-и-appserviceprovider)
12. [Модели в `app/Models` — поле за полем, метод за методом](#12-модели-в-appmodels--поле-за-полем-метод-за-методом)
13. [Сидеры](#13-сидеры)
14. [Шаблон пагинации `pagination/crm.blade.php`](#14-шаблон-пагинации-paginationcrmbladephp)
15. [Представления `resources/views` — что за что отвечает](#15-представления-resourcesviews--что-за-что-отвечает)
16. [Миграции — зачем и что за таблицы](#16-миграции--зачем-и-что-за-таблицы)
17. [Валидация: что означают правила](#17-валидация-что-означают-правила)

---

## 1. Словарь: объясняем слова

| Слово | Простыми словами |
|--------|------------------|
| **PHP** | Язык программирования, в котором написан серверный код. |
| **Фреймворк (Laravel)** | Набор готовых правил и инструментов: маршруты, ORM, шаблоны, сессии — чтобы не писать всё с нуля. |
| **HTTP GET** | «Просто открой страницу по ссылке», данные формы не отправляются. |
| **HTTP POST** | «Отправь форму» (кнопка «Сохранить», «Войти»). Тело запроса содержит поля. |
| **Класс** | Чертёж. В нём живут **свойства** (данные) и **методы** (действия). |
| **Метод / функция** в классе | Кусок кода с именем, который можно вызвать: `index()`, `store()`. |
| **Наследование** | Класс-дочка берёт готовое поведение родителя и дописывает своё. |
| **`abstract`** | «Этот класс нельзя создать сам по себе»; от него только наследуются. |
| **`static`** | Вызов через имя класса, без объекта: `ClientController::class`. |
| **Маршрут** | Запись «URL → какой метод какого класса выполнить». |
| **Контроллер** | Класс, методы которого отвечают на запросы пользователя. |
| **Модель (Eloquent)** | Класс «одна таблица БД» + удобные методы читать/писать строки. |
| **Миграция** | Файл-инструкция «создай/измени таблицу». |
| **Сидер** | Скрипт, который наполняет БД стартовыми данными. |
| **Blade** | Шаблонизатор: HTML + вставки `{{ }}`, `@if`. |
| **Middleware** | Прослойка «до контроллера»: проверить, вошёл ли пользователь. |
| **`Request`** | Объект с данными запроса (поля формы, файлы). |
| **Редирект** | Ответ «иди на другой адрес» (после сохранения формы обычно на список). |

---

## 2. Как браузер доходит до твоего кода

1. Пользователь открывает, например, `http://сайт/clients`.
2. Laravel смотрит [`routes/web.php`](routes/web.php).
3. Находит правило для `GET /clients` (часть группы `Route::resource`).
4. Вызывает метод **`index`** класса **`ClientController`**.
5. Метод читает БД через модель **`Client`**, собирает данные и возвращает **представление** `crud.index`.
6. Blade превращает шаблон в **HTML** и отдаёт браузеру.

---

## 3. `bootstrap/app.php` — старт приложения

Файл: [`bootstrap/app.php`](bootstrap/app.php)

| Строка / блок | Что делает |
|---------------|------------|
| `Application::configure(basePath: ...)` | Создаёт «приложение» Laravel и знает корневую папку проекта. |
| `->withRouting(web: ... routes/web.php)` | Подключает **веб-маршруты** из этого файла. |
| `commands: ... routes/console.php` | Консольные команды Artisan (`php artisan ...`). |
| `health: '/up'` | Служебный URL для проверки «жив ли сайт» (для балансировщиков). |
| `->withMiddleware(...)` | Настройка middleware. |
| `$middleware->redirectGuestsTo(fn () => route('login'))` | Если пользователь **не вошёл**, а лезет в защищённый URL, Laravel **перенаправит на именованный маршрут `login`**. `fn () =>` — короткая анонимная **функция** без имени. |
| `->withExceptions(...)` | Настройка обработки ошибок (пусто `//` — значит по умолчанию). |
| `->create()` | Собрать и вернуть готовое приложение. |

---

## 4. `routes/web.php` — каждая строка маршрутов

Файл: [`routes/web.php`](routes/web.php)

**Импорты вверху** — подключают классы контроллеров и фасад `Route`, чтобы писать `Route::get(...)`.

### Корень сайта

```php
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});
```

- **`Route::get('/', ...)`** — при GET на `/`.
- **`function () { ... }`** — **замыкание**: маленький контроллер прямо в файле маршрутов.
- **`auth()->check()`** — вошёл ли пользователь (есть ли сессия «я тот самый юзер»).
- **`redirect()->route('dashboard')`** — ответ «перейди на именованный маршрут `dashboard`».
- Иначе — на **`login`**.

### Группа для гостей

```php
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    ...
});
```

- **`middleware('guest')`** — Laravel применит правило: **залогиненных** отправить подальше (обычно на главную), чтобы не показывать форму входа дважды.
- **`[LoginController::class, 'showLoginForm']`** — массив из **класса** и **имени метода** (PHP 8+ стиль).
- **`->name('login')`** — имя маршрута для `route('login')` и для редиректа из `bootstrap/app.php`.

Аналогично: POST логина → `login`, GET/POST регистрации → `RegisterController`.

### Выход

```php
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');
```

- Только **POST** — чтобы выход нельзя было случайно дернуть ссылкой GET.
- **`middleware('auth')`** — только для **вошедших**.

### Защищённая группа

```php
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    ...
});
```

Внутри — всё, что видит **только авторизованный** пользователь.

### Касса

- `GET /wallet` → `WalletPageController@index`
- `POST /wallet/deposit` → `deposit` (приход)
- `POST /wallet/withdraw` → `withdraw` (расход)

### `Route::resource(...)`

Пример:

```php
Route::resource('clients', ClientController::class)->parameters(['clients' => 'id']);
```

Laravel **автоматически** создаёт несколько маршрутов:

| HTTP | URL (упрощённо) | Метод контроллера | Назначение |
|------|-----------------|-------------------|------------|
| GET | `/clients` | `index` | Список |
| GET | `/clients/create` | `create` | Форма «добавить» |
| POST | `/clients` | `store` | Сохранить новую запись |
| GET | `/clients/{id}` | `show` | Просмотр одной |
| GET | `/clients/{id}/edit` | `edit` | Форма редактирования |
| PUT/PATCH | `/clients/{id}` | `update` | Сохранить изменения |
| DELETE | `/clients/{id}` | `destroy` | Удалить |

**`->parameters(['clients' => 'id'])`** — в адресе сегмент назовём **`{id}`**, а не дефолтное имя параметра. Тогда в методах контроллера удобно принимать **`string $id`**, и имя совпадает с тем, что ожидает Laravel при связывании маршрута с методом.

Так сделано для всех ресурсов: `vehicles`, `supplies`, `sales`, `service_orders`, `spare_parts`, `service_items`, `body_repair_orders`, `paint_materials`, `users`.

---

## 5. `AbstractEntityController` — все методы

Файл: [`app/Http/Controllers/AbstractEntityController.php`](app/Http/Controllers/AbstractEntityController.php)

Базовый **абстрактный** класс. Сам по себе не создаётся; от него наследуются `ClientController`, `VehicleController` и т.д.

### Абстрактные методы (обязаны быть у наследника)

| Метод | Зачем |
|-------|--------|
| **`modelClass(): string`** | Возвращает строку с именем класса модели (`Client::class`). Нужно, чтобы писать универсально: `Client::query()` или любая другая модель. |
| **`entityKey(): string`** | Короткий ключ раздела: `clients`, `sales`. Нужен для имён мarshрутов (`clients.index`), для правил статуса в БД (`entity_key` в таблице `statuses`), для Blade (`$entityKey`). |
| **`cfg(): array`** | Конфигурация подписей: **`label`** — заголовок раздела; **`index`** — какие колонки в таблице списка; **`fields`** — какие поля в форме и на странице просмотра. |
| **`validationRules(): array`** | Массив **правил валидации** Laravel для полей при `store` и `update`. |

### `indexWith(): array`

```php
protected static function indexWith(): array
{
    return ['status'];
}
```

- **`with([...])`** в запросе Eloquent — **жадная подгрузка** связей одним мини-запросом, чтобы в списке не было «N+1» запросов.
- По умолчанию только **`status`** (чтобы показать название статуса).
- Наследники могут вернуть, например, `['status', 'client', 'vehicle']`.

### `index()`

1. Берёт класс модели: `$class = static::modelClass()`.
2. Строит запрос: `with(indexWith())`, сортировка по `id` вниз, **`paginate(20)`** — по 20 строк и ссылки «следующая страница».
3. Возвращает **`view('crud.index', [...])`** — шаблон списка с `items`, `cfg`, `entityKey`.

### `create()`

1. Создаёт **пустой** объект модели `new $class` — привязка к форме «новая запись».
2. Открывает **`crud.form`** с этим пустым `$item` (у него `exists == false` в логике Blade).

### `store(Request $request)`

1. **`$request->validate($this->validationRules())`** — если данные плохие, Laravel **сам** вернёт назад с ошибками (исключение валидации).
2. Если ок — **`$class::query()->create($data)`** — одна новая строка в таблице (только поля из `$fillable` модели).
3. **`redirect()->route(...)->with('ok', '...')`** — на список и **флеш-сообщение** в сессии на один показ.

### `show(string $id)`

1. **`findOrFail($id)`** — найти по первичному ключу или **404**.
2. **`with('status')`** — подгрузить связь статуса для карточки.
3. Показать **`crud.show`**.

### `edit(string $id)`

Как `show`, но модель для **формы** правки (можно без `with`, поля подтянутся при сохранении).

### `update(Request $request, string $id)`

1. Найти модель, **`validate`**, **`$item->update($data)`** — обновить строку.
2. Редирект на список с сообщением.

### `destroy(string $id)`

**`whereKey($id)->delete()`** — удалить строку. Редирект на список.

### `statusIdRule(): array`

Возвращает правило: поле **`status_id`** обязательно и должно существовать в таблице **`statuses`**, причём с **`entity_key`** ровно как у текущего раздела (`clients`, `sales`, …). Так нельзя выбрать «статус продажи» в форме клиента.

Внутри используется **`Rule::exists(...)->where(...)`** — встроенный способ описать сложное правило.

---

## 6. Контроллеры разделов (наследники)

У каждого одинаковая идея: **только** описывают модель, ключ, `cfg` и `validationRules`. Остальное — **родитель**.

Файлы:

- [`ClientController.php`](app/Http/Controllers/ClientController.php)
- [`VehicleController.php`](app/Http/Controllers/VehicleController.php)
- [`SupplyController.php`](app/Http/Controllers/SupplyController.php)
- [`SaleController.php`](app/Http/Controllers/SaleController.php)
- [`ServiceOrderController.php`](app/Http/Controllers/ServiceOrderController.php)
- [`SparePartController.php`](app/Http/Controllers/SparePartController.php)
- [`ServiceItemController.php`](app/Http/Controllers/ServiceItemController.php)
- [`PaintMaterialController.php`](app/Http/Controllers/PaintMaterialController.php)
- [`BodyRepairOrderController.php`](app/Http/Controllers/BodyRepairOrderController.php)

У **каждого** три-четыре «своих» метода (все `protected static` или `protected`, кроме явно переопределённого `show`):

| Метод наследника | Что делает |
|------------------|------------|
| **`modelClass()`** | Имя класса модели раздела. |
| **`entityKey()`** | Имя для маршрутов и статусов. |
| **`cfg()`** | Названия колонок и полей для универсальных шаблонов. |
| **`validationRules()`** | Ограничения на поля (обязательность, тип, существование внешнего ключа). |
| **`indexWith()`** (не у всех) | Дополнительные связи для списка. |
| **`show($id)`** (не у всех) | Свой `show` с `with([...])`, если в карточке нужны связи, которых нет в родительском `show`. |

**`VehicleController`**: `indexWith` = `status`, `client` (в списке показать клиента); свой **`show`** с `status` и `client`.

**`SaleController`**, **`ServiceOrderController`**, **`BodyRepairOrderController`**: в списке и в карточке нужны **машина и клиент** — поэтому `indexWith` и `show` расширены.

**`SupplyController`**, **`SparePartController`**, **`ServiceItemController`**, **`PaintMaterialController`**: достаточно родительских `index`/`show` (только `status` в `show`).

---

## 7. `UserController` — отдельный CRUD сотрудников

Файл: [`app/Http/Controllers/UserController.php`](app/Http/Controllers/UserController.php)

Почему не через `AbstractEntityController`: отдельная логика **пароля** (при создании обязателен и **confirmed**, при правке может быть пустым), отдельные правила **email unique**.

| Метод | Что делает |
|-------|------------|
| **`cfg()`** | То же по смыслу, что в абстрактном классе: подписи для универсальных шаблонов `crud.*`. |
| **`index()`** | Список `User` с `status`, пагинация 20. |
| **`create()`** | Форма нового пользователя. |
| **`store()`** | Валидация: уникальный email, роль, пароль с подтверждением, статус из `statuses` для `entity_key = users`. **`User::create`** — пароль попадёт в модель и **`casts` с `hashed`** сделает bcrypt автоматически. |
| **`show($id)`** | Карточка с `status`. |
| **`edit($id)`** | Форма правки. |
| **`update()`** | Правила: email unique **кроме текущего** (`ignore($item->id)`). Пароль в правила добавляется **только если** поле не пустое. После валидации, если пароль пустой — **`unset($data['password'])`**, чтобы не затереть старый хэш пустотой. |
| **`destroy($id)`** | Если **`auth()->id()`** совпадает с удаляемым id — редирект с ошибкой (нельзя удалить себя). Иначе удаление. |

---

## 8. `DashboardController`

Файл: [`app/Http/Controllers/DashboardController.php`](app/Http/Controllers/DashboardController.php)

### Метод `index()`

| Шаг | Код по смыслу | Зачем |
|-----|----------------|-------|
| Касса | `Treasury::first()` | Первая запись кассы, если есть. |
| Баланс | `$treasury->balanceFloat` или `'0'` | Свойство из пакета кошелька; если кассы нет — ноль строкой. |
| Сервис «в работе» | `ServiceOrder::whereHas('status', fn ($q) => $q->where('slug', 'in_progress'))->count()` | Считает заказы, у которых связанный статус с **slug** `in_progress`. |
| Кузов «в работе» | То же для `BodyRepairOrder`. | |
| Авто на территории | `Vehicle::whereHas('status', fn ($q) => $q->whereIn('slug', ['in_service', 'in_paint']))->count()` | Машины в нужных статусах. |
| Последние продажи | `Sale::with(...)->orderByDesc('sold_at')->limit(10)->get()` | 10 строк без пагинации для виджета. |
| Ответ | `view('dashboard', [...])` | Передаёт все переменные в шаблон. |

**`whereHas`** — «выбери модели, у которых **связь** удовлетворяет условию» (SQL с подзапросом или join — делает Eloquent).

---

## 9. `WalletPageController`

Файл: [`app/Http/Controllers/WalletPageController.php`](app/Http/Controllers/WalletPageController.php)

| Метод | Что делает |
|-------|------------|
| **`index()`** | `Treasury::firstOrCreate(['name' => 'main'])` — гарантирует запись кассы. Берёт **`transactions()`** (связь из трейта кошелька), сортирует, **`paginate(25)`**. Отдаёт `wallet.index`. |
| **`deposit(Request $request)`** | Проверяет сумму и комментарий. **`depositFloat`** на модели Treasury — деньги **приходят** на кошёлёк (пакет пишет транзакцию в БД). Редирект с сообщением. |
| **`withdraw`** | То же, но **`withdrawFloat`** — **списание**, если денег хватает (иначе пакет выбросит исключение → страница с ошибкой). |

---

## 10. `LoginController` и `RegisterController`

### [`LoginController`](app/Http/Controllers/Auth/LoginController.php)

| Метод | Что делает |
|-------|------------|
| **`showLoginForm()`** | Просто показать шаблон `auth.login`. |
| **`login(Request $request)`** | Валидация email/password. **`Auth::attempt($credentials, $remember)`** — Laravel сверяет email и **проверяет хэш пароля**. Успех → **`session()->regenerate()`** (защита от фиксации сессии) → редирект на `dashboard` или на «предполагаемый» URL **`intended`**. Неуспех → назад с ошибкой. |
| **`logout`** | `Auth::logout()`, очистка и регенерация сессии/токена CSRF, редирект на `login`. |

### [`RegisterController`](app/Http/Controllers/Auth/RegisterController.php)

| Метод | Что делает |
|-------|------------|
| **`showForm()`** | Шаблон `auth.register`. |
| **`register()`** | Валидация. Ищет id статуса **`users` + active**. **`User::create([...])`** с подстановкой **`status_id`**. **`Auth::login($user)`** — сразу залогинить. Редирект на панель. |

---

## 11. `Controller` и `AppServiceProvider`

### [`Controller.php`](app/Http/Controllers/Controller.php)

Пустой базовый класс для всех контроллеров — точка для общих трейтов/политик в больших проектах.

### [`AppServiceProvider.php`](app/Providers/AppServiceProvider.php)

| Метод | Что делает |
|-------|------------|
| **`register()`** | Регистрация сервисов в контейнере (у нас пусто). |
| **`boot()`** | При старте приложения: **`Paginator::defaultView('pagination.crm')`** — все **`->links()`** в проекте используют наш шаблон [`resources/views/pagination/crm.blade.php`](resources/views/pagination/crm.blade.php). |

---

## 12. Модели в `app/Models` — поле за полем, метод за методом

### Общие понятия

- **`$fillable`** — белый список: **какие колонки можно массово присвоить** через `create()` / `update()` из массива. Защита от перезаписи лишних полей запросом.
- **`casts()`** — как превратить «сырое» значение из БД в тип PHP (дата, decimal) и обратно; у `User` для пароля — **`hashed`** (при записи — хэш).
- **`belongsTo(Status::class)`** — «у этой строки есть **внешний ключ** `status_id` → одна запись в `statuses`».
- **`hasMany(Vehicle::class)`** — «у клиента много машин по полю `client_id` у машин».

### [`Status`](app/Models/Status.php)

| Часть | Назначение |
|-------|------------|
| `$fillable` | `entity_key`, `slug`, `title` — справочник статусов по разделам. |
| **`forEntityKey($entityKey): Builder`** | Статический метод: вернуть **запрос** (ещё не выполненный) всех статусов данного раздела, по возрастанию `id`. В Blade вызывают **`->get()`**. |

### [`Client`](app/Models/Client.php)

| Метод | Назначение |
|-------|------------|
| **`status()`** | К какому статусу привязан клиент. |
| **`vehicles()`** | Все машины этого клиента. |

### [`Vehicle`](app/Models/Vehicle.php)

| Часть | Назначение |
|-------|------------|
| `casts` | `year` как целое. |
| **`status()`**, **`client()`** | Статус и владелец. |
| **`getShortLabelAttribute()`** | **Аксессор**: в Blade пишем `$vehicle->short_label`, Laravel вызовет этот метод и соберёт короткую строку «марка модель [номер]». |

### [`Supply`](app/Models/Supply.php)

`casts`: деньги и дата. **`status()`**.

### [`Sale`](app/Models/Sale.php)

`casts`: цена, **`sold_at`** как datetime. Связи: **`status`, `vehicle`, `client`**.

### [`ServiceOrder`](app/Models/ServiceOrder.php)

`opened_at` как date. Связи: **status, vehicle, client**.

### [`SparePart`](app/Models/SparePart.php)

Цена decimal, **`stock_qty`** integer. **`status()`**.

### [`ServiceItem`](app/Models/ServiceItem.php)

Цена и длительность как decimal. **`status()`**.

### [`BodyRepairOrder`](app/Models/BodyRepairOrder.php)

`started_at` date. **status, vehicle, client**.

### [`PaintMaterial`](app/Models/PaintMaterial.php)

Остаток и цена за единицу как decimal. **`status()`**.

### [`Treasury`](app/Models/Treasury.php)

Реализует интерфейсы пакета кошелька, трейт **`HasWalletFloat`**. **`$fillable`** только `name`. Остальное (баланс кошелька, транзакции) добавляет пакет своими таблицами.

### [`User`](app/Models/User.php)

Наследует **`Authenticatable`** — готовность к **`Auth::attempt`**.

| Часть | Назначение |
|-------|------------|
| **`#[Fillable(...)]`**, **`#[Hidden(...)]`** | Современный синтаксис атрибутов PHP вместо `$fillable` массива — то же по смыслу. **Hidden** — не отдавать пароль в JSON. |
| **`HasFactory`**, **`Notifiable`** | Фабрики для тестов; уведомления (почта и т.д.) — пригодится при расширении. |
| **`casts`** | `password` → **hashed**; `status_id` int; дата верификации почты. |
| **`status()`** | Статус сотрудника из справочника `users`. |

---

## 13. Сидеры

### [`StatusSeeder`](database/seeders/StatusSeeder.php)

| Часть | Назначение |
|-------|------------|
| **`run()`** | Один большой массив: ключ — **`entity_key`** раздела, значение — список статусов_slug + человекочитаемый **title**. |
| Двойной **`foreach`** | Обойти все разделы и все статусы. |
| **`Status::updateOrCreate([ключ уникальности], [поля])`** | Если такой `entity_key + slug` уже есть — **обновить title**; нет — **создать**. Повторный запуск сидера безопасен. |

### [`DatabaseSeeder`](database/seeders/DatabaseSeeder.php)

| Строка | Назначение |
|--------|------------|
| **`$this->call(StatusSeeder::class)`** | Сначала статусы (нужны для `status_id` пользователя). |
| Запрос **`Status::...->value('id')`** | Взять один id статуса «active» для сущности `users`. |
| **`User::updateOrCreate([email], [...])`** | Админ: создать или **обновить** пароль и поля при каждом сидировании. |
| **`Treasury::firstOrCreate(['name' => 'main'])`** | Одна запись кассы в таблице `treasuries`. |

---

## 14. Шаблон пагинации `pagination/crm.blade.php`

Файл: [`resources/views/pagination/crm.blade.php`](resources/views/pagination/crm.blade.php)

| Конструкция | Назначение |
|-------------|------------|
| **`@if ($paginator->hasPages())`** | Если страница одна — блок страниц не рисуем. |
| **`$paginator->firstItem()`, `lastItem()`, `total()`** | «С 5 по 20 из 100» для пользователя. |
| **`onFirstPage()`, `previousPageUrl()`** | Кнопка «назад». |
| **`@foreach ($elements as $element)`** | Laravel отдаёт **массив кусков**: строка `"..."` или массив **номер страница → URL**. |
| **`is_string` / `is_array`** | Разделить «троеточие» и обычные номера страниц. |
| **`$page == $paginator->currentPage()`** | Текущая страница — не ссылка, а выделение. |
| **`hasMorePages()`, `nextPageUrl()`** | Вперёд. |

Переменные **`$paginator`**, **`$elements`** создаёт Laravel при рендере вида пагинации.

---

## 15. Представления `resources/views` — что за что отвечает

| Файл | Роль |
|------|------|
| [`layouts/app.blade.php`](resources/views/layouts/app.blade.php) | Оболочка: меню, выход, всплывающие сообщения `session('ok')` и ошибки, **`@yield('content')`**. |
| [`dashboard.blade.php`](resources/views/dashboard.blade.php) | Панель: карточки цифр, таблица последних продаж. |
| [`crud/index.blade.php`](resources/views/crud/index.blade.php) | Универсальная **таблица**: цикл по `$cfg['index']`, ссылки show/edit/delete. |
| [`crud/form.blade.php`](resources/views/crud/form.blade.php) | Универсальная **форма**: по полям из `$cfg['fields']` рисуется input/select/textarea; особые случаи для `users` и пароля. |
| [`crud/show.blade.php`](resources/views/crud/show.blade.php) | Просмотр одной записи по полям `cfg['fields']` без пароля. |
| [`wallet/index.blade.php`](resources/views/wallet/index.blade.php) | Баланс, две формы приход/расход, таблица транзакций, **`$transactions->links()`**. |
| [`auth/login.blade.php`](resources/views/auth/login.blade.php), [`register.blade.php`](resources/views/auth/register.blade.php) | Страницы без общего layout (своя вёрстка). |

Директивы Blade: **`@extends`**, **`@section`**, **`@csrf`** (скрытое поле токена для POST), **`@method('PUT')`** — симуляция PUT из формы.

---

## 16. Миграции — зачем и что за таблицы

**Зачем:** чтобы любой разработчик или сервер мог **одной командой** получить такую же структуру БД.

Папка: [`database/migrations/`](database/migrations/)

| Файл (логика) | Таблица / действие |
|---------------|---------------------|
| `0001_*_users` | Пользователи, сессии, токены сброса пароля (стандарт Laravel). |
| `0001_*_cache`, `jobs` | Кэш и очереди (стандарт). |
| `2018–2024_*` (из пакета wallet) | Таблицы **laravel-wallet** (кошельки, транзакции). |
| `2026_*_statuses` | Справочник статусов. |
| `2026_*_add_role...users` | Поля `role`, `status_id` у пользователей. |
| `clients`, `vehicles`, `supplies`, `sales`, `service_orders`, `spare_parts`, `service_items`, `body_repair_orders`, `paint_materials` | Сущности CRM. |
| `treasuries` | Строка кассы (связь с кошельком пакета). |

В каждой миграции метод **`up()`** — применить, **`down()`** — откатить (удалить таблицу).

---

## 17. Валидация: что означают правила

Примеры из контроллеров:

| Правило | Смысл |
|---------|--------|
| **`required`** | Поле обязательно. |
| **`nullable`** | Можно пустым. |
| **`string`**, **`integer`**, **`numeric`** | Тип. |
| **`max:255`** | Не длиннее N символов. |
| **`min:0`** | Для числа — не отрицательное. |
| **`email`** | Похоже на email. |
| **`unique:users,email`** | Такого email ещё нет в `users`. |
| **`exists:clients,id`** | Значение должно быть **существующим id** в таблице `clients`. |
| **`confirmed`** | Должно быть поле **`password_confirmation`** такое же, как `password`. |
| **`in:manager,master,...`** | Только одно из перечисленных значений. |
| **`date`** | Корректная дата/время. |

---

## Итог

Ты можешь **проговорить преподавателю цепочку**: запрос → **`web.php`** → **middleware** → **метод контроллера** → **валидация** → **модель/БД** → **Blade**. В этом проекте повторяющийся CRUD спрятан в **`AbstractEntityController`**, а отличия разделов — только **конфиг** и **правила**. Это нормальный учебный приём **не копировать** десять почти одинаковых контроллеров целиком.

Если понадобится **отдельный PDF по одному файлу** (например только маршруты + только модель `Sale`) — можно вырезать нужные разделы из этого документа.
