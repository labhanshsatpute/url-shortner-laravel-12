## Setup

Add the variable to `.env` file to create the default admin user

```dotenv
DEFAULT_ADMIN_NAME=Admin
DEFAULT_ADMIN_EMAIL=labhansh25@gmail.com
DEFAULT_ADMIN_PASSWORD=12345678
```

Then run the migrations and seed the database:

```bash
php artisan migrate
php artisan db:seed
```