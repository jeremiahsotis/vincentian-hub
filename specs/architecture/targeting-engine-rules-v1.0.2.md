# Targeting Engine Rules (v1.0.2 clarified)

This document is binding.

## 1. Canonical Targeting Keys

- `svdp_scope`
- `svdp_audience_profiles`
- `svdp_target_conference_mode`
- `svdp_target_conference_ids`
- `svdp_target_group_flags`
- `svdp_is_active`
- `svdp_publish_start`
- `svdp_publish_end`

These keys are the resolver interface.

## 2. Canonical `svdp_role_profiles` values

### Conference
- `member`
- `president`
- `executive_leadership`
- `spiritual_advisor`

### District
- `assistance_line`
- `district_staff`
- `district_announcements_editor`
- `district_editor`
- `district_admin`

## 3. Scope values

- `conference`
- `district`
- `both`

## 4. Conference targeting modes

- `all`
- `selected`
- `group_flags`
- `district_only`
- `none`

## 5. Resolver order

1. user exists
2. user approved
3. object active
4. publish window
5. scope match
6. audience intersection
7. conference/district targeting match

This wording is canonical.

## 6. Authorization boundary

The targeting resolver is the single visibility authority for:
- dashboards
- announcements
- documents
- events
- front-end detail pages
- downloads
- feeds
- export endpoints

It is not the authorization mechanism for WordPress admin CRUD screens.
Admin CRUD uses capabilities.
