# Vincentian Hub Contracts Specification
Version: v1.1-consolidated

This is the single authoritative contracts document for Vincentian Hub.
It replaces patch-on-patch guidance and is intended to be the only active contract source for development, planning, and review.

## 1. Contract Scope

The following are hard contracts for MVP and require deliberate versioning and migration planning before any change:

- Plugin identity
- Custom post type names
- Taxonomy names
- Custom table naming convention
- User meta keys
- Canonical object meta keys
- Shared targeting block keys
- Targeting semantics
- Resolver evaluation order
- Capability names and meanings
- WordPress role names and role boundaries
- Route patterns
- Server-side security requirements
- WYSIWYG/editor constraints
- WordPress-as-system-of-record boundary
- Header logo contract

If code, comments, configuration, ACF field names, UI labels, generated tasks, or AI outputs conflict with this document, this document wins.

## 2. Plugin Identity Lock

- Plugin Name: `Vincentian Hub`
- Plugin Slug: `vincentian-hub`
- Code Prefix: `vincentian_hub_`
- Schema / Meta Prefix: `svdp_`
- PHP Namespace: `VincentianHub`
- Main Plugin File: `vincentian-hub.php`
- Text Domain: `vincentian-hub`
- Admin Menu Label: `Vincentian Hub`
- Volunteer-Facing Name: `Vincentian Hub`

### Main Plugin File Enforcement

The canonical plugin entrypoint file for MVP is:

- `vincentian-hub.php`

If the repository currently contains a different main plugin file name, including:

- `vincentian-hub-portal.php`

that file must be renamed or replaced in the **Foundation slice** before feature implementation proceeds.

This is a contract conformance requirement, not an optional cleanup.

## 3. Canonical Object Registry

### Custom Post Types
- `svdp_conf`
- `svdp_dash_item`
- `svdp_announcement`
- `svdp_doc`
- `svdp_event`

### Taxonomy
- `svdp_doc_cat`

### Custom Table
Logical identifier:
- `svdp_directory`

Canonical runtime physical table name:
- `{$wpdb->prefix}svdp_directory`

The string `wp_svdp_directory` is not canonical and must not be hardcoded. It is only an example that assumes the default WordPress table prefix.

## 4. Required User Meta Keys

- `svdp_account_scope`
- `svdp_approval_status`
- `svdp_conference_id`
- `svdp_role_profiles`
- `svdp_phone`
- `svdp_google_sub`
- `svdp_directory_source`
- `svdp_last_login`
- `svdp_onboarding_completed`
- `svdp_can_self_change_conference`
- `svdp_calendar_feed_token`
- `svdp_calendar_feed_token_rotated_at`
- `svdp_admin_notes`

### `svdp_directory_source` Allowed Values

This field indicates the origin of the user's directory entry.

Allowed values:

- `trusted_directory`
- `manual_admin_entry`
- `oauth_self_registration`
- `imported`

Semantics:

- `trusted_directory`
  user record originated from the trusted directory table

- `manual_admin_entry`
  user was created manually by an administrator

- `oauth_self_registration`
  user first appeared through OAuth authentication

- `imported`
  user record originated from an external import process

These values are informational and must not be used for authorization decisions.

## 5. Shared Targeting Block

The following keys are immutable and must be reused identically across:
- `svdp_dash_item`
- `svdp_announcement`
- `svdp_doc`
- `svdp_event`

### Canonical Keys
- `svdp_scope`
- `svdp_audience_profiles`
- `svdp_target_conference_mode`
- `svdp_target_conference_ids`
- `svdp_target_group_flags`
- `svdp_is_active`
- `svdp_publish_start`
- `svdp_publish_end`

### Prohibited MVP Aliases
- `svdp_conference_mode`
- `svdp_conference_ids`
- `svdp_group_flags`
- `svdp_active`
- `svdp_start_at`
- `svdp_end_at`

These keys are the resolver interface. They are not editorial conveniences.

## 6. Front-End Visibility Profiles vs WordPress Roles

These are not the same thing.

### A. WordPress Roles
WordPress roles are for backend/admin authorization.

Canonical WordPress roles:
- `svdp_member`
- `svdp_district_staff`
- `svdp_district_announcements_editor`
- `svdp_district_editor`
- `svdp_district_admin`

### B. `svdp_role_profiles`
`svdp_role_profiles` is user meta for front-end visibility targeting.

Canonical allowed values for `svdp_role_profiles`:

#### Conference-side
- `member`
- `president`
- `executive_leadership`
- `spiritual_advisor`

#### District-side
- `assistance_line`
- `district_staff`
- `district_announcements_editor`
- `district_editor`
- `district_admin`

### Clarification: `svdp_district_staff`

`svdp_district_staff` is intentionally defined as a canonical WordPress role in MVP even though it has no publishing or administrative capabilities.

Purpose of this role:

- identify district-affiliated backend users who are not editors or administrators
- support future capability grants without redefining role identity
- maintain a clean separation between backend authorization and front-end visibility targeting

Contract rule:

A WordPress role must **never be assumed to imply a matching targeting profile**.

If a district staff user should see district-targeted content, that must come from the `svdp_role_profiles` user meta field.

### Storage Contract for `svdp_role_profiles`
- Store as standard WordPress user meta
- Canonical semantic shape: array of strings
- Do not store as comma-delimited text
- Do not change storage semantics without versioning

### Role Name Overlap Clarification

The string `svdp_district_staff` appears in two separate systems:

1. WordPress Role
   - `svdp_district_staff`

2. Front-End Targeting Profile
   - `district_staff`

These are intentionally separate.

Rules:

- WordPress roles control **backend/admin authorization**
- `svdp_role_profiles` control **front-end visibility targeting**

Developers must never assume that a WordPress role automatically implies a matching targeting profile.

If a district staff user should see district-targeted content, the user must have the `district_staff` value inside `svdp_role_profiles`.

## 7. Approval and Scope Enums

### `svdp_approval_status`
Allowed values:
- `approved`
- `pending`
- `disabled`

Semantics:
- `approved`: eligible for resolver checks
- `pending`: authenticated but no portal access
- `disabled`: blocked from portal access

### `svdp_account_scope`
Allowed values:
- `conference`
- `district`

Semantics:
- `conference`: exactly one `svdp_conference_id` required
- `district`: `svdp_conference_id` is not used for targeting

## 8. Capability Registry

### Portal/System
- `svdp_view_portal_admin`
- `svdp_manage_settings`
- `svdp_manage_conferences`
- `svdp_manage_user_profiles`
- `svdp_approve_users`

### Dashboard Items
- `svdp_edit_dashboard_items`
- `svdp_edit_others_dashboard_items`
- `svdp_publish_dashboard_items`
- `svdp_delete_dashboard_items`

### Announcements
- `svdp_edit_announcements`
- `svdp_edit_others_announcements`
- `svdp_publish_announcements`
- `svdp_delete_announcements`

### Documents
- `svdp_edit_documents`
- `svdp_edit_others_documents`
- `svdp_publish_documents`
- `svdp_delete_documents`

### Events
- `svdp_edit_events`
- `svdp_edit_others_events`
- `svdp_publish_events`
- `svdp_delete_events`

### Imports
- `svdp_manage_drive_imports`

## 9. Capability-to-Role Matrix

This matrix is authoritative for MVP.

| Capability | svdp_member | svdp_district_staff | svdp_district_announcements_editor | svdp_district_editor | svdp_district_admin |
|---|---|---:|---:|---:|---:|
| `svdp_view_portal_admin` | no | no | yes | yes | yes |
| `svdp_manage_settings` | no | no | no | no | yes |
| `svdp_manage_conferences` | no | no | no | no | yes |
| `svdp_manage_user_profiles` | no | no | no | no | yes |
| `svdp_approve_users` | no | no | no | no | yes |
| `svdp_edit_dashboard_items` | no | no | no | yes | yes |
| `svdp_edit_others_dashboard_items` | no | no | no | yes | yes |
| `svdp_publish_dashboard_items` | no | no | no | yes | yes |
| `svdp_delete_dashboard_items` | no | no | no | yes | yes |
| `svdp_edit_announcements` | no | no | yes | yes | yes |
| `svdp_edit_others_announcements` | no | no | yes | yes | yes |
| `svdp_publish_announcements` | no | no | yes | yes | yes |
| `svdp_delete_announcements` | no | no | yes | yes | yes |
| `svdp_edit_documents` | no | no | no | yes | yes |
| `svdp_edit_others_documents` | no | no | no | yes | yes |
| `svdp_publish_documents` | no | no | no | yes | yes |
| `svdp_delete_documents` | no | no | no | yes | yes |
| `svdp_edit_events` | no | no | no | yes | yes |
| `svdp_edit_others_events` | no | no | no | yes | yes |
| `svdp_publish_events` | no | no | no | yes | yes |
| `svdp_delete_events` | no | no | no | yes | yes |
| `svdp_manage_drive_imports` | no | no | no | no | yes |

Role boundaries:
- `svdp_member`: no admin portal capabilities
- `svdp_district_staff`: no publishing rights by default in MVP
- `svdp_district_announcements_editor`: announcements only
- `svdp_district_editor`: dashboard items, announcements, documents, and events
- `svdp_district_admin`: full portal administrative capabilities

### Clarification: `svdp_district_staff`

`svdp_district_staff` is a valid canonical WordPress role in MVP even though it has no publishing or administrative management capabilities by default.

Purpose of this role:
- identify district-affiliated backend users who are not editors or administrators
- support future capability grants without redefining role identity
- keep backend/admin authorization distinct from front-end visibility targeting

Contract rule:
- `svdp_district_staff` must not be interpreted as a general admin/editor role in MVP
- if a district staff user needs front-end district visibility, that must come from `svdp_role_profiles`, not from the WordPress role alone

## 10. Targeting Semantics

### `svdp_scope`
Allowed values:
- `conference`
- `district`
- `both`

Semantics:
- `conference`: approved conference users only
- `district`: approved district users only
- `both`: approved conference or district users, subject to all other checks

### Audience intersection
Visibility requires intersection between:
- object `svdp_audience_profiles`
- normalized user `role_profiles`

### Behavior When `svdp_audience_profiles` Is Empty

If `svdp_audience_profiles` is empty or not defined on an object, the object is considered visible to **all role profiles within the allowed scope**.

This means:

- Scope filtering still applies (`svdp_scope`)
- Conference targeting still applies (`svdp_target_conference_mode`)
- Publish window and active checks still apply

Only the **audience intersection requirement** is skipped.

This behavior exists to support content intended for all volunteers or all district users without requiring enumeration of every profile.

### Conference targeting modes
Allowed values:
- `all`
- `selected`
- `group_flags`
- `district_only`
- `none`

### Group flags
Allowed values:
- `urban`
- `rural`
- `allen_county`
- `new_haven`

## 11. Canonical Resolver Order

The targeting resolver must evaluate in this order:

1. user exists
2. user approved
3. object active
4. publish window
5. scope match
6. audience intersection
7. conference/district targeting match

The wording of step 7 is canonical and must not vary.

## 12. Route and Security Contract

Canonical routes:
- `/district-resources/<conference-name>/`
- `/district-resources/district/`
- `/resource-library/<doc-slug>/`
- `/events/<event-slug>/`
- `/portal-calendar/feed/<token>/`
- `/portal-calendar/event/<event_id>/download/`

### Conference Route Slug Source

The `<conference-name>` token used in:

`/district-resources/<conference-name>/`

must be derived from the conference meta field:

- `svdp_conf_page_slug`

Contract rules:

- The slug must be unique across all conferences.
- The slug must remain stable once published.
- Routes must resolve by querying `svdp_conf` where:

  `svdp_conf_page_slug = <conference-name>`

- Routes must **not** be generated from the WordPress post slug unless that slug is explicitly synchronized with `svdp_conf_page_slug`.

### Front-end and export route security
These routes must:
- construct normalized user context
- call the targeting resolver
- enforce server-side permission checks

Client-side filtering is never sufficient.

### Conference Route Token Naming Clarification

The URL token name `<conference-name>` is a human-readable route placeholder only.

It does **not** mean:
- WordPress post title
- WordPress post slug
- arbitrary conference display name

For MVP, the canonical route token source is:

- `svdp_conf_page_slug`

All route resolution logic must treat `<conference-name>` as a stand-in for `svdp_conf_page_slug`.

### Admin CRUD authorization boundary
WordPress admin/editor CRUD screens are governed by:
- WordPress roles
- capabilities
- admin menu access rules

They do not use the front-end targeting resolver.

## 12A. Canonical Object-Specific Enum Values

The following object-specific fields are canonical and their allowed values are contract-locked for MVP.

### Dashboard Items

#### `svdp_item_type`
Allowed values:
- `tool`
- `document`
- `form`
- `grant`
- `event_link`
- `shortcut_collection`
- `external_link`
- `shortcode`

#### `svdp_item_open_mode`
Allowed values:
- `internal`
- `preview`
- `download`
- `new_tab`

#### `svdp_priority`
Allowed values:
- `normal`
- `important`
- `urgent`

#### `svdp_display_style`
Allowed values:
- `tile`
- `card`
- `list`
- `banner`
- `hidden_searchable`

### Announcements

#### `svdp_announcement_type`
Allowed values:
- `update`
- `alert`
- `reminder`
- `event`
- `grant`

#### `svdp_display_placement`
Allowed values:
- `top_banner`
- `dashboard_card`
- `whats_new`
- `announcements_page`

### Documents

#### `svdp_doc_source`
Allowed values:
- `google_drive`
- `local_upload`
- `external`

#### `svdp_doc_preview_type`
Allowed values:
- `pdf`
- `office_embed`
- `download_only`
- `html_summary`

### Events

#### `svdp_event_type`
Allowed values:
- `meeting`
- `training`
- `formation`
- `deadline`
- `volunteer`
- `district_event`
- `conference_event`

#### `svdp_event_status`
Allowed values:
- `scheduled`
- `cancelled`
- `postponed`
- `completed`

### Anti-drift rule

No alternate enum values may be introduced for these fields in MVP without contract versioning.

## 13. WYSIWYG Contract

WYSIWYG is required in MVP for:
- Dashboard Items full description
- Announcements full body
- Documents body/description
- Events full description

### Allowed editor behavior
- paragraphs
- H2/H3
- bold
- italic
- bulleted lists
- numbered lists
- links
- remove formatting
- tables only if explicitly enabled

### Prohibited behavior
- arbitrary HTML for normal editors
- free-form font sizing
- font-family changes
- uncontrolled color formatting
- scripts
- unsafe embeds
- page-builder layout behavior in rich text

### Storage and sanitization
- Store rich text in standard WordPress post content or equivalent registered field storage

### WYSIWYG Storage Constraint

For contract-locked content types (`svdp_dash_item`, `svdp_announcement`, `svdp_doc`, `svdp_event`):

- rich text content should use standard WordPress `post_content` unless a documented exception requires a registered custom field.

Rules:

- WYSIWYG storage must use WordPress editor-compatible storage.
- Arbitrary page-builder storage formats must not be used.
- Sanitization must follow `wp_kses_post()` or an equivalent WordPress-safe sanitization baseline.

This constraint prevents parallel or incompatible rich text storage models across portal objects.

- `wp_kses_post()` or demonstrably equivalent WordPress-safe sanitization is the expected baseline
- Output must be rendered inside a normalized content container with controlled typography and spacing
- Unsafe markup must not be exposed

## 14. Header Logo Contract

Setting key:
- `vincentian_hub_logo_attachment_id`

Location:
- `Vincentian Hub → Settings → Branding`

Logo rules:
- rectangular logo area reserved
- maintain aspect ratio
- do not crop
- use contain behavior
- district admin uploadable
- text fallback if no logo uploaded

Recommended containers:
- desktop: max 240px × 64px
- mobile: max 180px × 48px

## 15. WordPress as System of Record

WordPress is the system of record for:
- conferences
- dashboard items
- announcements
- documents
- events
- user access state
- conference assignment
- role profiles
- event calendar exports

Google Drive is an upstream document source only. It is not the user interface and not the active source of truth for portal content or user state.

## 16. Change Control

Breaking changes to any hard contract require:
- version increment
- migration note
- backwards compatibility strategy or explicit cutover plan

This document is the authoritative contract surface for the active project.
