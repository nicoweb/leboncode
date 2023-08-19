# LeBonCode API

![PHP](https://img.shields.io/badge/php-8.2-blue)
![Symfony](https://img.shields.io/badge/symfony-6.3-green)
![Build](https://github.com/nicoweb/leboncode/actions/workflows/ci.yml/badge.svg)
[![Documentation](https://img.shields.io/badge/docs-swagger-green)](http://localhost:8000/docs)

A robust API for a product sales platform, inspired by LeBonCoin, with a focus on adhering to best software engineering practices.

## 🚧 Status: In Active Development 🚧

This project is currently in active development. While its foundational principles and structures are in place, features and functionality may still be in flux.

## Overview

The primary goal is to implement a product sales platform starting from a Symfony skeleton and a given `docker-compose` setup. This project employs methodologies such as:

- **Outside-In TDD**
- **Hexagonal Architecture** to isolate the core of the application from external needs.
- **Domain-Driven Design (DDD)** to focus on the core domain logic.
- **CQRS** for a clear separation of command and query responsibilities.

Quality assurance tools integrated:

- **PHP-CS-Fixer**: For code style consistency.
- **PHPStan**: For static analysis ensuring bug-free code.
- **Rector**: Automated code refactoring.
- **Deptrac**: Enforces rules to maintain architecture boundaries.

## Features

### Advert Management
- Create, retrieve, update, and delete product advertisements.
- Search adverts based on title, price range.
- Fetch advertisement details by ID.

### User Management
- Register and login user functionalities.
- JWT authentication.

## Getting Started

### Dependencies

- PHP 8.2
- Symfony 6.3
- Docker

### Installation

1. Clone the repository:
```bash
git clone git@github.com:nicoweb/leboncode.git
```
2. Navigate to the project directory:
```bash
cd leboncode
```
3. Build and start the project using:
```bash
make start
```
### Running Tests

Use the following command:
```bash
make test
```

### Documentation

Access the Swagger documentation at:
[http://localhost:8000/docs](http://localhost:8000/docs)

## Project Structure

The project is modularized, e.g., `app/Module/User`. Each module can house multiple use-cases, such as "Register User", and these use-cases are categorized as:

- **Application**: Includes query/command, handlers, mappers, etc.
- **Domain**: Houses the core business logic and domain entities.
- **Infrastructure**: Contains details of implementation, like repositories.
- **Presentation**: Manages HTTP requests/responses – controllers, etc.

### Layer Dependencies:

- **Domain**: No dependencies.
- **Application**: Only Domain.
- **Infrastructure**: Vendors and Domain.
- **Presentation**: Vendors and Domain.
