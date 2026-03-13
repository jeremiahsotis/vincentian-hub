# Vincentian Hub 10-Minute Implementation Roadmap

This roadmap assumes the repo uses only the consolidated authoritative docs:

- `specs/contracts-spec.md`
- `specs/architecture/architecture.md`
- `specs/architecture/targeting-engine-rules.md`
- `specs/architecture/wordpress-data-model-map.md`
- `specs/architecture/system-diagram.md`
- `specs/issues/SPEC.md`

## Step 1 — Bootstrap Discovery A

Goal: read the contracts and architecture only.

Action:
- Give Codex the **Bootstrap Discovery A** prompt from `specs/issues/bootstrap-prompts.md`
- Require a summary only
- Do not allow code or edits

Expected output:
- hard contract summary
- canonical object registry
- shared targeting keys
- role/profile distinction
- route/security model
- drift risks
- remaining ambiguities

## Step 2 — Bootstrap Discovery B

Goal: read the repo structure and supporting docs only.

Action:
- Give Codex the **Bootstrap Discovery B** prompt from `specs/issues/bootstrap-prompts.md`
- Require a summary only
- Do not allow code or edits

Expected output:
- module boundaries
- file responsibility map
- dependency order
- missing scaffolding/setup gaps
- PR-slice suggestions

## Step 3 — `/speckit.plan`

Goal: produce the implementation plan and PR boundaries.

Action:
- Run `/speckit.plan` using the plan prompt/context in `specs/issues/bootstrap-prompts.md`

Required output from plan:
- phased implementation roadmap
- module order
- PR slice boundaries
- commit checkpoints
- branch naming suggestions
- risk/validation points

## Step 4 — `/speckit.tasks.md`

Goal: convert the plan into execution tasks grouped by PR slice.

Action:
- Run `/speckit.tasks.md` using the tasks prompt/context in `specs/issues/bootstrap-prompts.md`

Required output from tasks:
- grouped by PR boundary
- dependencies
- files/modules affected
- acceptance criteria
- contract/risk checks

## Step 5 — `/speckit.analyze`

Goal: find drift, gaps, or oversized slices before code is written.

Action:
- Run `/speckit.analyze` using the analyze prompt/context in `specs/issues/bootstrap-prompts.md`

Check for:
- schema drift
- resolver duplication
- capability leakage
- route/security gaps
- role/profile confusion
- oversized PR slices

## Step 6 — `/speckit.implement`

Goal: implement exactly one approved slice.

Action:
- Run `/speckit.implement` using the implement prompt/context in `specs/issues/bootstrap-prompts.md`
- Require it to stop at the PR boundary

Rules:
- one slice only
- no stepping into the next slice
- report contract compliance before stopping

## Step 7 — Commit / Push / PR / Merge / New Branch

Goal: keep review boundaries clean and prevent drift.

Action:
1. confirm slice acceptance criteria
2. run relevant checks/tests
3. commit
4. push
5. open PR
6. verify against `.github/pull_request_template.md`
7. merge after review
8. create a new branch for the next slice

Never continue implementation on the same branch after reaching a clean PR boundary.

## Recommended Early Slice Order

1. Foundation
   - rename/replace the plugin entrypoint so the canonical main file is `vincentian-hub.php`
   - plugin bootstrap
   - post types
   - taxonomies
   - meta registration
   - roles/capabilities
   - user meta
   - directory table

   Why first:
   - the plugin entrypoint name is contract-locked
   - all downstream work depends on stable bootstrap and registration boundaries

2. Targeting Resolver
   - normalized user context
   - resolver helpers
   - resolver tests

3. Auth + Onboarding
   - Google auth
   - approval/account scope handling
   - onboarding restrictions

4. Conferences
   - conference model
   - linked page mapping
   - conference flags
   - shortcode context

5. Dashboard Shell + Items
   - dashboard query
   - dashboard rendering
   - route behaviors

6. Announcements

7. Documents

8. Events + Calendar Export

9. Settings + Admin Polish
