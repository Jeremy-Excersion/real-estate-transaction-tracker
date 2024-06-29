# Real Estate Sales Tracker

This application enables real estate teams to manage transactions effectively, assisting with bookkeeping, sales tracking, commission calculations, and various financial data.

## Features

-   Track real estate transactions
-   Manage sales and commissions
-   Generate financial reports
-   User authentication and role management

## Technology Stack

-   **Backend:** Laravel
-   **Frontend:** Vue.js
-   **Styling:** Tailwind CSS
-   **Containerization:** Docker (Laravel Sail)

## Getting Started

Follow these steps to set up the application in your local environment.

### Prerequisites

-   Docker installed on your machine
-   Basic knowledge of Laravel and Vue.js

### Installation

1. **Clone the repository:**

    ```bash
    git clone <repository-url>
    cd <repository-directory>
    ```

2. **Set up environment variables:**

    Copy the `.env.example` file to `.env`:

    ```bash
    cp .env.example .env
    ```

3. **Install dependencies:**

    Use the following command to install PHP and Composer dependencies:

    ```bash
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v "$(pwd):/var/www/html" \
        -w /var/www/html \
        laravelsail/php83-composer:latest \
        composer install --ignore-platform-reqs
    ```

4. **Start the Sail container:**

    ```bash
    vendor/bin/sail up -d
    ```

5. **Install additional dependencies:**

    ```bash
    vendor/bin/sail composer install
    vendor/bin/sail npm install
    ```

6. **Run database migrations:**

    ```bash
    vendor/bin/sail artisan migrate
    ```

7. **Start the frontend environment:**

    ```bash
    vendor/bin/sail npm run dev
    ```

    The application will be available at [http://localhost](http://localhost).

## Seeding the Database with Test Data

To populate the database with test data, use the following commands while Sail is running:

1. **Create default users:**

    This command will create two users:

    - **Admin:** admin@realestateagentassistant.com
    - **Agent:** agent@realestateagentassistant.com

    Both users will have the password: `password`.

    ```bash
    vendor/bin/sail artisan db:seed --class=DefaultUserSeeder
    ```

2. **Seed sales data:**

    This command will populate the Sales table with 100 test sales along with related data such as fees and sources. **Note:** This will wipe existing sales data and replace it with test data, so it should not be run in production.

    ```bash
    vendor/bin/sail artisan db:seed --class=SaleSeeder
    ```

## Access the Demo Environment

For demonstration purposes, this application is currently live at [sales.realestateagentassistant.com](https://sales.realestateagentassistant.com/sales).

Explore the features and functionality of the Real Estate Sales Tracker in this live demo environment.
