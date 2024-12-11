# Project Title

Application for parsing data from the site of ads

## Table of Contents

- [Installation](#installation)
- [Usage](#usage)
- [Configuration](#configuration)



## Installation

1. Clone the repository:

    ```bash
    git clone https://github.com/Mihail-kh/ad-watcher.git
    cd ad-watcher
    ```

2. Install dependencies:

    ```bash
    composer install
    ```

3. Configure environment variables:

   Copy `.env.example` to `.env` and update the necessary values:

    ```bash
    cp .env.example .env
    ```

4. Run the setup script:

    ```bash
    docker compose up --build -d
   
    docker compose exec app php artisan key:generate
   
    docker compose exec app php artisan migrate
    ```

---

## Usage

1. Run docker containers:
    ```bash
    docker compose up -d
    ```
2. When you first start the application, run the command
    ```bash
    docker compose exec app php artisan queue:restart
    ```
3. Available endpoints:
    ```
    http://localhost/docs/api#/
    ```

---

## Configuration

- **Environment Variables**:
    - `MAIL_MAILER`
    - `MAIL_HOST`
    - `MAIL_PORT`
    - `MAIL_USERNAME`
    - `MAIL_PASSWORD`
---

