# Vincentian Hub Product Specification

This spec is the entry point for Spec Kit.

## Binding Architecture Contracts
specs/contracts-spec.md
specs/contracts-spec-patch.md
specs/architecture/architecture.md
specs/architecture/system-diagram.md
specs/architecture/targeting-engine-rules.md
specs/architecture/wordpress-data-model-map.md

These must never be violated.

## Required Development Workflow

Implementation must follow a PR-first slice model.

Each slice must:
1. implement a coherent behavior boundary
2. pass contract checks
3. be committed and pushed
4. open a pull request
5. pass PR template verification
6. merge before the next slice begins

Long-running branches are prohibited.

## Implementation Phases
Foundation
Resolver
Content systems
Dashboard rendering
Events and calendar
Admin and polish

## Security
All visibility decisions must use the normalized user context and targeting resolver.
