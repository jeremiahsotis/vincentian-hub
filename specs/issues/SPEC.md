# Vincentian Hub Product Specification

This specification is the entry point for Spec Kit.

## Binding and Non-Negotiable Constraints

The following documents are binding system constraints and must be treated as non-negotiable:

- `/specs/contracts-spec.md`
- `/specs/contracts-spec-patch.md`
- `/specs/architecture/architecture.md`
- `/specs/architecture/system-diagram.md`
- `/specs/architecture/targeting-engine-rules.md`
- `/specs/architecture/wordpress-data-model-map.md`

These documents exist specifically to prevent:
- schema drift
- targeting drift
- inconsistent capability interpretation
- WordPress data-model confusion
- duplicated visibility logic

## Required PR-First Workflow

Implementation must follow a PR-first slice model.

Each slice must:
1. implement one coherent behavior boundary
2. validate against all binding documents above
3. stop at the PR boundary
4. commit
5. push
6. open a pull request
7. pass the Vincentian Hub PR template
8. merge before the next slice begins on a new branch

Long-running branches are prohibited.

## Plan output requirement

Any generated plan must explicitly include:
- module order
- dependencies
- PR slice boundaries
- commit checkpoints
- validation gates against the binding documents

## Security model

All front-end visibility decisions must use:
- normalized user context
- the targeting resolver

Admin CRUD authorization must use:
- WordPress roles
- capabilities
