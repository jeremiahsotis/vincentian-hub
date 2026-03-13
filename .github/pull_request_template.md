# Vincentian Hub PR

## Summary

What does this PR do?

## Implementation Slice

Which planned slice does this PR complete?

- [ ] Foundation
- [ ] Auth / onboarding
- [ ] Conferences
- [ ] Targeting resolver
- [ ] Dashboard items
- [ ] Announcements
- [ ] Documents
- [ ] Events / calendar export
- [ ] UI / mobile polish
- [ ] Other: __________

### Slice Ownership Rule

Only choose slices that have an explicit ownership boundary in the binding architecture documents.

If a proposed slice does not map to a defined architectural owner or module, it must be treated as:
- out of scope for the current PR, or
- a future architecture decision

Do not use the PR template to introduce new architectural slices implicitly.

## Why this slice is separate

Why is this a coherent reviewable unit?

## Files / Modules Touched

List the major files or modules changed.

## Binding Docs Check

Confirm this PR was checked against the following:

- [ ] `specs/contracts-spec.md`
- [ ] `specs/architecture/architecture.md`
- [ ] `specs/architecture/targeting-engine-rules.md`
- [ ] `specs/architecture/wordpress-data-model-map.md`
- [ ] `specs/architecture/system-diagram.md`
- [ ] `specs/issues/SPEC.md`

## Drift Prevention Check

Confirm the following are true:

- [ ] No canonical field/meta names were changed
- [ ] No targeting semantics were changed
- [ ] No capability meanings were changed
- [ ] No route patterns were changed
- [ ] No duplicate visibility logic was introduced outside the resolver
- [ ] No client-side-only access control was introduced
- [ ] No WordPress role / `svdp_role_profiles` confusion was introduced

## WordPress Data Model Check

- [ ] CPT usage matches the data model map
- [ ] User meta usage matches the data model map
- [ ] Object relationships match the data model map
- [ ] Resolver inputs still match the shared targeting block
- [ ] Runtime table naming uses `{$wpdb->prefix}svdp_directory` semantics and does not hardcode `wp_`

## Security Check

- [ ] Server-side permission checks were added or preserved where required
- [ ] No unauthorized document/event/feed access paths were introduced
- [ ] Gated content remains gated
- [ ] Admin CRUD authorization still uses roles/capabilities, not the resolver

## UI / Theme Check

- [ ] Uses current theme baseline
- [ ] Mobile-first layout respected
- [ ] Large tap targets preserved where relevant
- [ ] No unnecessary custom design drift introduced

## Tests / Validation

What did you run or verify?

- [ ] Unit tests
- [ ] Integration tests
- [ ] Manual auth check
- [ ] Manual resolver check
- [ ] Manual document access check
- [ ] Manual event access check
- [ ] Manual mobile viewport check
- [ ] Other: __________

## Acceptance Criteria Met

List the acceptance criteria from the plan/tasks that are satisfied by this PR.

## Risks / Open Questions

Anything the reviewer should pay special attention to?

## Out of Scope

What was intentionally not included in this PR?

## Ready for Merge Checklist

- [ ] Slice is complete enough to review safely
- [ ] No unrelated work included
- [ ] Next slice will happen in a new branch after merge
