# Supabase Database Setup for Yohannes Finance

## Local Configuration

Update your `.env` file with these Supabase credentials:

```
DB_CONNECTION=pgsql
DB_HOST=db.qmgcnzweewqlbsgyyutn.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=DAhvz2bBksMnex5Z
```

## Vercel Configuration

When deploying to Vercel, add these environment variables:

```
DB_CONNECTION=pgsql
DB_HOST=db.qmgcnzweewqlbsgyyutn.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=DAhvz2bBksMnex5Z
```

### Database URL Format

For some frameworks, you may need to use the database URL format:

```
DATABASE_URL=postgres://postgres:DAhvz2bBksMnex5Z@db.qmgcnzweewqlbsgyyutn.supabase.co:5432/postgres?sslmode=require
```

## Running Migrations

After deployment to Vercel, run migrations with:

```bash
vercel run -- php artisan migrate --force
```

Or locally test the connection with:

```bash
php artisan migrate --force
```

## Security Note

The credentials in this file are sensitive. Do not commit this file to your public repository. Rotate your database password if you've already shared these credentials publicly. 