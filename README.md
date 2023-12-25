## Wallet API

Wallet API is a Dockerized Laravel application for managing user wallets and transactions.

### Prerequisites

- [Docker](https://www.docker.com/) installed on your local machine.

### Installation and Setup

1. **Clone the repository:**

   ```bash
   git clone https://github.com/daalvand/wallet-api.git
   cd wallet-api
   ```

2. **Create a Configuration File:**

   Create a `.env` file by duplicating the provided `.env.example` file (`cp .env.example .env`). Customize the environment variables according to your preferences.

3. **Start the Docker Containers:**

   ```bash
   docker-compose up -d
   ```

   This command will launch the MySQL database and the application containers.

4. **Install Application Dependencies:**

   ```bash
   docker-compose exec app composer install
   ```

5. **Generate Application Key:**

   ```bash
   docker-compose exec app php artisan key:generate
   ```

6. **Migrate the Database and Seed Initial Data:**

   ```bash
   docker-compose exec app php artisan migrate --seed
   ```

7. **Set Up Scheduled Tasks:**

   Add the following Cron entry to your server to start the Laravel scheduler:

   ```bash
   ***** cd /project-path && docker-compose exec app php artisan schedule:run >> /path/logs/scheduled-jobs.log 2>&1
   ```

8. **Access the Application:**

   Visit [http://localhost:8000](http://localhost:8000) in your web browser to access the Wallet API application.

### Running Tests

To run the application tests, execute the following command:

```bash
touch database/database.sqlite
docker-compose exec app php artisan test
```

### Usage

The application offers two API endpoints:

- **Endpoint:** `GET /api/wallet/balance/{user}`
    - This endpoint returns the current balance of a user's wallet.
    - The `{user}` parameter should be replaced with the ID of the user.

- **Endpoint:** `POST /api/wallet/deposit/{user}`
    - This endpoint deposits money into a user's wallet and returns a transaction reference ID.
    - The `{user}` parameter should be replaced with the ID of the user.
    - The request body should be a JSON object containing an `amount` field.

- **GetTotalAmount:** To get the total amount, run this command:
   ```shell
    docker-compose exec app php artisan transactions:total
   ```
For complete documentation of the API, please refer to the [swagger](./swagger.yml).

### Continuous Integration (CI)

The project incorporates GitHub Actions for Continuous Integration. The workflow encompasses:

- Building and pushing Docker images to Docker Hub.
- Executing tests for the application.

The CI workflow triggers automatically with every push to the `main` branch.
