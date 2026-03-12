# Vincentian Hub Architecture

This document defines the implementation structure of Vincentian Hub.

## Binding Architecture Companions

The following documents are required anti-drift companions and must be treated as binding:

- `/specs/contracts-spec.md`
- `/specs/contracts-spec-patch.md`
- `/specs/architecture/targeting-engine-rules.md`
- `/specs/architecture/system-diagram.md`
- `/specs/architecture/wordpress-data-model-map.md`

## Additional clarification

- WordPress roles + capabilities control backend/admin authorization.
- `svdp_role_profiles` controls front-end visibility through normalized user context and the targeting resolver.
- The resolver is not the authorization mechanism for admin CRUD.

## Core Principle

WordPress is the system of record for:
- conferences
- portal content objects
- user access state
- conference assignment
- role profiles
- event calendar exports

Google Drive is an upstream document source only.

## Theme Integration Constraint

Use the current WordPress theme as the visual baseline.
Focus design effort on:
- mobile-first layout
- readable typography
- large tap targets
- calm predictable hierarchy
