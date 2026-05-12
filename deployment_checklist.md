# 🚀 Multi-School Deployment Checklist

Follow these steps to go live for a new school using the `main` branch.

## 1. Server Setup (cPanel/VPS)
- [ ] Create a new Database and Database User.
- [ ] Point the domain/subdomain to the server's `public` directory.
- [ ] Upload the code or `git clone` from the `main` branch.

## 2. Configuration (.env)
Copy `.env.example` to `.env` and configure:
- [ ] `APP_NAME`: The school's name.
- [ ] `APP_URL`: The live website URL.
- [ ] `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.
- [ ] `FILESYSTEM_DISK`: Set to `r2` or `google` if using cloud storage.
- [ ] `R2_BUCKET`, `R2_ACCESS_KEY_ID`, etc. (if applicable).

## 3. Deployment Commands
Run these commands in the project root:
```bash
# Install dependencies
composer install --no-dev

# Generate app key
php artisan key:generate

# Run migrations
php artisan migrate

# Initialize school defaults (CLEANS DEMO DATA)
php artisan app:school-init

# Link storage
php artisan storage:link

# Optimize for production
php artisan optimize
```

## 4. Final Customization (Admin Panel)
- [ ] Login to `/admin` (Default: admin@gmail.com / password).
- [ ] Go to **Site Management > Settings**.
- [ ] Upload the School Logo and Favicon.
- [ ] Set Theme Colors (Primary/Secondary).
- [ ] Add Sliders and Notices.

## 5. Maintenance
When you push new updates to the `main` branch on GitHub:
```bash
git pull origin main
php artisan migrate
php artisan optimize:clear
```
