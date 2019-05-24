## Installation

```bash
composer install
cp .env.example .env
```

Edit file .env with your favourite editor and set correct data for:
* `APP_URL`
* Access to database
* Access to email server

Remember to set in production server:
* `APP_ENV=production` 
* `APP_DEBUG=false`

Next to:
```bash
php artisan migrate
php artisan db:seed
php artisan passport:keys
php artisan passport:client --personal
```

