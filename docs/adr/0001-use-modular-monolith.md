# ADR 0001: Use a Modular Monolith

## Status

Accepted

## Context

MedFlow AI will include authentication, product catalog, carts,
orders, payments, subscriptions, appointments, notifications,
and administrative workflows.

The product and domain are still evolving, and the project is
currently maintained by a small development team.

## Decision

We will begin with a modular monolith built with Laravel.

The application will remain a single deployable unit, while
business capabilities will be separated into clear modules.

## Reasons

- Simpler local development and deployment.
- Easier database transactions.
- Lower operational overhead.
- Faster feature delivery.
- Clear module boundaries.
- Possibility of extracting services later when justified.

## Alternatives Considered

### Traditional unstructured monolith

Rejected because business logic could become tightly coupled.

### Microservices

Rejected for the initial version because they introduce additional
deployment, networking, monitoring, consistency, and operational
complexity.

## Consequences

### Positive

- Faster development.
- Easier testing.
- Simpler deployment.
- Local transactions are available.

### Negative

- Modules still share one application process.
- Poor boundaries could create tight coupling.
- The database may become a shared dependency.

## Review Conditions

We may reconsider this decision when:

- One module requires independent scaling.
- Teams need independent deployments.
- A module requires a different availability model.
- The shared application significantly slows delivery.