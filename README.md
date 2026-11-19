# Oh My Cards

> Full-stack Symfony 7.2 web application with Doctrine ORM, EasyAdmin, Twig, and GoF design patterns.

![Context](https://img.shields.io/badge/Context-BUT%202%20(S3)-2ea44f?style=flat-square)
![Symfony 7.2](https://img.shields.io/badge/Symfony-7.2-000000?style=flat-square&logo=symfony&logoColor=white)
![PHP 8.2+](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=flat-square&logo=php&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-Compose-2496ED?style=flat-square&logo=docker&logoColor=white)

## Overview

**Oh My Cards** is a second-year Computer Science (BUT 2 — S3) full-stack e-commerce web application for collectible cards built with **Symfony 7.2**. It features user authentication, email verification, an administration back-office, file uploads, pagination, automated tests, and documented software design patterns.

## Features

- **Full Symfony 7.2 MVC web application** with Twig templates, Symfony UX Turbo & Autocomplete, and Webpack Encore.
- **Administration panel** powered by **EasyAdmin 4**.
- **User accounts & security** with `VerifyEmailBundle` and Symfony Security.
- **Media uploads & pagination** via `VichUploaderBundle` and `KnpPaginatorBundle`.
- **Software design patterns** documented in [`QualitéDeDev.md`](QualitéDeDev.md): Strategy + Factory (payment methods), Iterator, and Builder.

## Tech Stack

- **Backend**: PHP 8.2+, Symfony 7.2, Doctrine ORM / Migrations / Fixtures, EasyAdmin 4
- **Frontend**: Twig, JavaScript, Stimulus, Symfony UX Turbo, Webpack Encore
- **Testing & Tooling**: PHPUnit, Docker Compose

## Project Structure

```text
├── .env.example                        # Environment variables template
├── assets/                             # Frontend assets & Stimulus controllers
├── config/                             # Symfony packages, routes & security config
├── img/                                # Design pattern diagrams
├── public/                             # Web root & static assets
├── src/                                # Entities, controllers, forms & design patterns
├── templates/                          # Twig views
├── tests/                              # PHPUnit test suite
├── QualitéDeDev.md                     # Design patterns documentation
├── compose.yaml                        # Docker Compose configuration
└── composer.json / package.json
```

## Getting Started

1. **Create your `.env` file** from the template:
   ```bash
   cp .env.example .env
   ```
   Fill in `APP_SECRET` and your `DATABASE_URL` (SQLite works out of the box for local development).
2. **Install dependencies**:
   ```bash
   composer install
   npm install && npm run build
   ```
3. **Initialize the database & fixtures**:
   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   php bin/console doctrine:fixtures:load
   ```
4. **Start the local Symfony server**:
   ```bash
   symfony server:start
   ```

## Contributors

- Armel Cloarec
- Clément Hubert
- Léa Cortet
- Maxime Bouzonie