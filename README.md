# Сервис для проверки открытых юр. лиц у физ. лица

## Полезные команды
* Запуск phpstan (используется дл поддержания качества кода)
```
docker compose exec php composer phpstan
```
* Запуск cs-fixer (используется дл поддержания качества кода)
```
docker compose exec php composer cs-fix
```
* Генерация апи документации
```
docker compose exec php php artisan l5-swagger:generate
```

## Запуск для разработки
### Требования:
1. Установленный docker и docker compose
### Настройка
1. Скопировать файлы конфига для докера и для ларавеля
    ```
    cp .env.example .env
    ```
    ```
    cp docker/.env.example docker/.env
    ```
2. Скопировать конфиг docker compose для разработки
    ```
    cp docker/docker-compose.override.develop.yaml docker/docker-compose.override.yaml
    ```
3. Настроить параметры `CHECKO_API_KEY`, `ORGANIZATIONS_REPORT_RECIPIENT` и блок `MAIL_` для отправки писем в `.env`
4. Перейти в папку docker
    ```
    cd docker/
    ```
5. Запустить контейнеры. Процесс сборки контейнера `php` может быть долгим
    ```
    docker compose up -d
    ```
6. Установить зависимости
    ```
    docker compose exec php composer install
    ```
    ```
    docker compose exec php npm ci
    ```
7. Установить ключи приложения
    ```
    docker compose exec php php artisan key:generate
    ```
8. Запустить миграции
    ```
    docker compose exec php php artisan migrate
    ```

## Запуск для прода
### Для прода лучше использовать файл конфигурации `docker/docker-compose.override.develop.yaml` на 2-ом шаге
* В этом файле вырезан `nginx`. Тк скорее всего потребуется использовать серверный не контейнерный. 
* Прокинут 9000 порт для `php-fpm` (можно поменять в /docker/.env)
* Убран автосборщик фронта (тк на проде лучше использовать `npm run build` а не `npm run dev`)

### Дополнительно требуется после 6-ого шага сбилдить фронт
```
docker compose exec php npm run build
```

### Для прода рекомендуется поменять все пароли, тк в репозитории используются небезопасные пароли для удобства разработки
