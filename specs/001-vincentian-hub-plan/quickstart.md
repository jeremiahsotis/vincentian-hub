# Quickstart: Vincentian Hub Implementation Validation

## Purpose

This guide reflects the implemented Vincentian Hub slice order, the PR-first workflow used to deliver it, and the final validation checks required before further work or release packaging.

## Binding Inputs

Validate every implementation or review step against:

- `/Users/jeremiahotis/projects/vincentian-hub/specs/contracts-spec.md`
- `/Users/jeremiahotis/projects/vincentian-hub/specs/architecture/architecture.md`
- `/Users/jeremiahotis/projects/vincentian-hub/specs/architecture/system-diagram.md`
- `/Users/jeremiahotis/projects/vincentian-hub/specs/architecture/targeting-engine-rules.md`
- `/Users/jeremiahotis/projects/vincentian-hub/specs/architecture/wordpress-data-model-map.md`
- `/Users/jeremiahotis/projects/vincentian-hub/specs/issues/SPEC.md`
- `/Users/jeremiahotis/projects/vincentian-hub/specs/issues/bootstrap-prompts.md`
- `/Users/jeremiahotis/projects/vincentian-hub/specs/issues/implementation-roadmap.md`
- `/Users/jeremiahotis/projects/vincentian-hub/.github/pull_request_template.md`

## Implemented Slice Order

The plugin was implemented and reviewed in this order:

1. Foundation
2. Targeting resolver
3. Auth / onboarding
4. Conferences / context
5. Dashboard shell
6. Announcements
7. Documents
8. Events / calendar export
9. Settings / admin / imports
10. UI polish / validation docs

## PR-First Workflow

1. Start from `main` on a numbered branch for one approved slice only.
2. Keep file edits within the explicit slice boundary.
3. Before coding, confirm the slice does not rename canonical keys, alter route patterns, or create a second visibility model.
4. Complete the slice, run slice-local validation, then run the full PHPUnit suite.
5. Check the completed slice against `.github/pull_request_template.md`.
6. Commit, push, open one PR, review, merge, then begin the next slice from updated `main`.

## Final Validation Checklist

Run these checks before treating the implementation plan as complete:

1. Confirm canonical names remain unchanged:
   - CPT names
   - taxonomy names
   - user meta keys
   - shared targeting keys
   - capability names
   - route patterns
2. Confirm front-end visibility still uses normalized user context plus the targeting resolver only.
3. Confirm backend/admin authorization still uses WordPress capabilities only.
4. Confirm templates remain presentation-only and JavaScript is not used as an access-control layer.
5. Confirm Google Drive remains an upstream import source rather than a runtime visibility authority.
6. Confirm no slice introduced route-level authorization shortcuts or duplicated resolver checks.

## Validation Commands

Use these commands from the repo root:

```sh
php -l vincentian-hub.php
php -l includes/*.php
php -l templates/*.php
php -l tests/bootstrap.php
php -l tests/unit/*.php
php -l tests/integration/*.php
./vendor/bin/phpunit --bootstrap tests/bootstrap.php tests/unit tests/integration
```

## Review Expectations By Slice

- Foundation: plugin identity, registration, roles/capabilities, meta registration, directory table naming
- Targeting resolver: normalized user context, conference flags, resolver order, permission-helper delegation
- Auth / onboarding: approval-status gating, account-scope behavior, conference assignment requirements, presentation-only templates
- Conferences / context: `svdp_conf_page_slug` lookup, linked-page mapping, shortcode context ownership
- Dashboard shell: canonical routes, resolver-filtered datasets, presentation-only templates, theme-baseline assets
- Announcements: shared targeting reuse and dashboard-only integration
- Documents: protected detail, preview, and download enforcement through canonical owners
- Events / calendar export: protected event detail plus token-protected feed/export ownership split
- Settings / admin / imports: capability-gated branding, admin menus, and Drive import behavior without authority drift
- UI polish / validation docs: bounded CSS/JS polish only, updated validation guidance, no feature behavior changes

## Final Ready-For-Merge Criteria

The plan is complete when:

- all slice PRs are reviewable on their own ownership boundaries
- every slice passes its acceptance criteria and PHPUnit coverage
- no contract, routing, or security drift remains open
- PR descriptions use the binding-doc and drift-prevention checks from the template
- the repo is ready for future maintenance without re-deriving slice order or validation expectations
