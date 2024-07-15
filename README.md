
## Инструкция по локальному развёртыванию

Данный проект использует php 8.3 и mysql, поэтому если у вашей системы их нет, необходимо прописать

1. ```sudo apt update```
2. ```sudo apt install -y php8.3 php8.3-curl php8.3-mysql php8.3-mbstring php8.3-xml php8.3-zip php8.3-bcmath php8.3-intl php8.3-readline php8.3-redis php8.3-sqlite3 php8.3-opcache php8.3-pdo```
3. ```sudo apt install -y mysql-client```

После чего установить версию php8.3 как основную

Далее для развёртывания в докере необходимо из папки проекта прописать

1. ```composer require laravel/sail --dev```
2. ```./vendor/bin/sail up```

Обе команды займут какое-то время, чтобы получить и создать все образы

Обращения к artisan лучше делать тоже через ./vendor/bin/sail, например команда для миграций выглядит так:
```./vendor/bin/sail artisan migrate```

Повторные запуски приложения делаются тоже через ```./vendor/bin/sail up```
