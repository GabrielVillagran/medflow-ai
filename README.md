# MedFlow AI

MedFlow AI is a healthcare and wellness platform built with
PHP and Laravel.

The project is designed as a production-oriented learning platform
covering backend architecture, e-commerce, subscriptions,
telehealth workflows, queues, integrations, observability,
testing, and deployment.

## Initial Features

- Authentication and authorization
- Product catalog
- Shopping cart
- Orders and checkout
- Payments and subscriptions
- Telehealth appointments
- Notifications
- Administrative workflows
- Audit logs
- Monitoring

## Technology Stack

- PHP
- Laravel
- MySQL
- Redis
- Laravel Sail
- Pest
- Laravel Pint
- Docker
- GitHub Actions

## Local Development

Start the application:

```bash
./vendor/bin/sail up -d

```

##  Run database migrations:

./vendor/bin/sail artisan migrate

## Run tests:

./vendor/bin/sail artisan test

## Format the code:

./vendor/bin/sail pint