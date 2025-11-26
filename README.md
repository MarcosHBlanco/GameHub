Live at: https://gamehub-production-a4fa.up.railway.app/index.php

CPSC 2030 Final — Game Hub (PHP)

Game Hub is a small PHP web application that allows users to search games via the RAWG API, save their personal game list, mark favorites, track played status, and filter/search their own library.

The app is built with PHP, PDO, CSS Grid, vanilla JS/jQuery, and deployed on Railway.

🚀 Features

Home page with search + filter

Infinite scroll for RAWG API results

Add game (full form with validation)

Edit game

My Games (search/filter your saved games)

Delete game

CSRF protection

Prepared statements everywhere

Mobile menu (jQuery)

Custom layout using CSS Grid, Flexbox, and animations

RAWG API integration (client-side & server-side)

🧰 Tech Stack

PHP 8

PDO

MySQL (local)

SQLite (production fallback on Railway)

CSS Grid + Flexbox

RAWG API

JavaScript / jQuery

📦 Project Setup

1. Clone the repository
   git clone https://github.com/MarcosHBlanco/GameHub.git
   cd GameHub

🛠 Local Development (MySQL)

1. Create the local database
   CREATE DATABASE gamehub CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

2. Import schema.sql

Use phpMyAdmin (XAMPP) or your MySQL client.

3. Update credentials in config.php

Your local settings should match:

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'gamehub');
define('DB_USER', 'root');
define('DB_PASS', '');

4. Start PHP development server
   php -S localhost:8000

Open in browser:
👉 http://localhost:8000

☁️ Production Deployment (Railway)

Railway uses PHP via Nixpacks.
Since Railway’s default PHP image does not include pdo_mysql, the app automatically falls back to SQLite in production.

This behavior is handled inside inc/db.php.

✔ No setup required on Railway

When deployed:

Railway provides env vars for RAWG API

SQLite DB file is created automatically

games table is auto-created on first load

No MySQL service required on Railway

Required Railway environment variable:
Variable Description
RAWG_API_KEY Your RAWG API key

Add via the Railway Dashboard → Variables.

📁 File Structure
/GameHub
│
├─ inc/
│ ├─ db.php # Database layer (MySQL locally, SQLite on Railway)
│ ├─ functions.php # CSRF, helpers, validation
│ ├─ header.php
│ └─ footer.php
│
├─ api/ # RAWG API endpoints
│
├─ css/
├─ assets/
│
├─ index.php
├─ mygames.php
├─ add.php
├─ edit.php
├─ schema.sql
└─ README.md

🔐 Security

CSRF tokens on all POST requests

PDO prepared statements (no raw SQL)

Escaped output everywhere (e() helper)

Server-side and client-side validation

📝 Future Improvements

User accounts & authentication

Multiple lists per user

Game categories / genres

Upload custom covers

Improved UI animations
