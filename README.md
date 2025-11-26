# CPSC 2030 Final — Game Hub (PHP/MySQL)

## Setup

1. Create DB:

```sql
CREATE DATABASE cpsc2030_final CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

2. Import `schema.sql` into that DB (phpMyAdmin).
3. Update credentials in `config.php`.
4. Run:

```bash
php -S localhost:8000
```

Open http://localhost:8000

## Pages

- Home (list/search/filter + delete)
- Add (form with client+server validation)
- Edit (update record)
- Documentation, Sources

## Notes

- PDO prepared statements everywhere.
- CSRF tokens on POST.
- CSS Grid/Flex + transitions + @keyframes.
- jQuery menu + form validation.
