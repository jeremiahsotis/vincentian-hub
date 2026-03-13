# Vincentian Hub Product Specification

This specification is the entry point for Spec Kit and project planning.

## Binding and Non-Negotiable Constraints

The following documents are the only binding architecture set for active development:

- `/specs/contracts-spec.md`
- `/specs/architecture/architecture.md`
- `/specs/architecture/targeting-engine-rules.md`
- `/specs/architecture/wordpress-data-model-map.md`
- `/specs/architecture/system-diagram.md`

These documents exist specifically to prevent:
- schema drift
- targeting drift
- capability drift
- route/security drift
- WordPress data-model confusion
- duplicated visibility logic

Superseded patch documents must not be used as active planning inputs once these consolidated documents are in place.

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

The following workflow artifacts are part of the active implementation process:

- `specs/issues/bootstrap-prompts.md`
- `specs/issues/implementation-roadmap.md`
- `.github/pull_request_template.md`

These are operationally binding for planning and implementation workflow.

They do not override architecture contracts, but they do define:
- how discovery is performed
- how plan/tasks/analyze/implement are run
- how PR boundaries are enforced
- how contract compliance is checked before merge

## Plan Output Requirement

Any generated plan must explicitly include:
- module order
- dependencies
- PR slice boundaries
- commit checkpoints
- validation gates against the binding documents

### Current Architecture Boundary Note

Search is not currently defined as a first-class architectural module in the binding architecture set.

Until a dedicated search ownership boundary is added to:
- `specs/architecture/architecture.md`
- `specs/architecture/wordpress-data-model-map.md`

Search must not be treated as its own implementation slice by default.

If search work is later added, it must first be architecturally defined before being promoted into plans, tasks, or PR slices.

## Product Purpose

Vincentian Hub is the internal WordPress-based portal for Vincentian volunteers and district staff to access tools, documents, announcements, and events.

## Security Model

All front-end visibility decisions must use:
- normalized user context
- the targeting resolver

Admin CRUD authorization must use:
- WordPress roles
- capabilities

## UI Constraints

Use the existing WordPress theme baseline.
Focus on:
- mobile-first layout
- large tap targets
- clear typography
- minimal visual clutter
- calm predictable hierarchy
