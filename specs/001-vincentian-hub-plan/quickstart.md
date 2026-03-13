# Quickstart: Vincentian Hub Planning Slice

## Purpose

This planning slice establishes the implementation roadmap and PR boundaries for the Vincentian Hub plugin before feature coding begins.

## Binding Inputs

Validate every planning or implementation step against:

- `/Users/jeremiahotis/projects/vincentian-hub/specs/contracts-spec.md`
- `/Users/jeremiahotis/projects/vincentian-hub/specs/architecture/architecture.md`
- `/Users/jeremiahotis/projects/vincentian-hub/specs/architecture/system-diagram.md`
- `/Users/jeremiahotis/projects/vincentian-hub/specs/architecture/targeting-engine-rules.md`
- `/Users/jeremiahotis/projects/vincentian-hub/specs/architecture/wordpress-data-model-map.md`
- `/Users/jeremiahotis/projects/vincentian-hub/specs/issues/SPEC.md`

## Planning Workflow

1. Work from a numbered feature branch.
2. Keep the current slice limited to one coherent ownership boundary.
3. Before implementation, confirm the slice does not rename canonical identifiers, split resolver logic, or alter route patterns.
4. When the slice is complete, validate it against the PR template.
5. Commit, push, open a PR, merge, then start the next slice on a new branch.

## Planned Slice Sequence

1. Foundation
2. Targeting resolver
3. Auth / onboarding
4. Conferences / linked-page context
5. Dashboard shell and routing
6. Announcements
7. Documents
8. Events / calendar export
9. Settings / admin polish and remaining UI refinement

## Minimum Validation Per Slice

- Contract check against canonical names and enums
- Security check for server-side protected-content enforcement
- Role/capability versus `svdp_role_profiles` boundary check
- Route ownership check when any front-end endpoint is touched
- Tests or manual validation appropriate to the slice scope

## Immediate Repo Observations

- `includes/` contains the canonical modules but they are all stubs except the bootstrap loader.
- `templates/` are presentation placeholders only.
- `tests/` currently defines required coverage areas but no actual test files.
- `vincentian-hub.php` is the correct plugin entrypoint name already in place.
