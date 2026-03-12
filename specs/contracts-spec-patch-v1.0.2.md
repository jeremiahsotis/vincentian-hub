# Vincentian Hub Contract Patch (v1.0.2)

This patch closes remaining ambiguities identified during architecture discovery.
It supplements:
- `specs/contracts-spec.md`
- `specs/contracts-spec-patch.md`

If there is a conflict between earlier wording and this patch, this patch wins.

---

## 1. Canonical distinction: WordPress Roles vs `svdp_role_profiles`

These are **not the same thing**.

### A. WordPress Roles
WordPress roles are for **backend authorization and admin/editor permissions**.

Canonical WordPress roles:
- `svdp_member`
- `svdp_district_staff`
- `svdp_district_announcements_editor`
- `svdp_district_editor`
- `svdp_district_admin`

### B. `svdp_role_profiles`
`svdp_role_profiles` is a **user meta array used for front-end visibility and targeting**.

Canonical allowed `svdp_role_profiles` values for MVP:

#### Conference-side profiles
- `member`
- `president`
- `executive_leadership`
- `spiritual_advisor`

#### District-side profiles
- `assistance_line`
- `district_staff`
- `district_announcements_editor`
- `district_editor`
- `district_admin`

### Contract rule
- Backend authorization uses WordPress roles + capabilities.
- Front-end visibility uses normalized user context derived from user meta, especially `svdp_role_profiles`.
- Do not use WordPress role names directly as front-end audience targets unless they exactly match canonical `svdp_role_profiles` values.

---

## 2. Capability-to-Role Matrix (Authoritative)

This matrix is authoritative for MVP.

| Capability | svdp_member | svdp_district_staff | svdp_district_announcements_editor | svdp_district_editor | svdp_district_admin |
|---|---|---:|---:|---:|---:|
| `svdp_view_portal_admin` | no | optional read-only later, not required in MVP | yes | yes | yes |
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
| `svdp_manage_drive_imports` | no | no | no | optional by explicit grant only | yes |

### Notes
- `svdp_district_staff` has no publishing rights by default in MVP.
- `svdp_manage_drive_imports` belongs to `svdp_district_admin` by default. Grant to `svdp_district_editor` only if explicitly decided later.
- Admin/editor CRUD screens are controlled by capabilities, not the targeting resolver.

---

## 3. Canonical Resolver Wording

Resolver step 7 is canonically:

**7. conference/district targeting match**

All previous variant phrasings are superseded.

---

## 4. Canonical User Meta Enums

### `svdp_approval_status`
Allowed values:
- `approved`
- `pending`
- `disabled`

### `svdp_account_scope`
Allowed values:
- `conference`
- `district`

### Additional contract rule
- `conference` users must have exactly one `svdp_conference_id`
- `district` users must not rely on `svdp_conference_id` for targeting

---

## 5. Canonical Table Naming Clarification

The logical custom table identifier is:

- `svdp_directory`

The canonical runtime physical table name is:

- `{$wpdb->prefix}svdp_directory`

### Clarification
Any previous reference to `wp_svdp_directory` should be understood as an example using the default WordPress prefix.
The implementation must not hardcode `wp_`.

---

## 6. Route and Security Clarification

Canonical routes remain:

- `/district-resources/<conference-name>/`
- `/district-resources/district/`
- `/resource-library/<doc-slug>/`
- `/events/<event-slug>/`
- `/portal-calendar/feed/<token>/`
- `/portal-calendar/event/<event_id>/download/`

### Security contract
These front-end and export routes must:
- construct normalized user context
- call the targeting resolver
- enforce server-side permission checks

### Admin CRUD clarification
WordPress admin/editor CRUD screens are governed by:
- WordPress roles
- capabilities
- admin menu access rules

They do **not** use the front-end targeting resolver for authorization.

---

## 7. WYSIWYG Storage and Sanitization Contract

WYSIWYG is contract-locked for MVP for:
- Dashboard Items full description
- Announcements full body
- Documents body/description
- Events full description

### Storage
- Store rich text in standard WordPress post content or equivalent registered field storage for the relevant CPT.
- Do not invent separate incompatible storage formats per object type.

### Sanitization
- Use WordPress-safe sanitization consistent with restricted-editor behavior.
- `wp_kses_post()` or equivalent WordPress-safe sanitization is the expected baseline.
- Raw script execution, arbitrary embeds, unsafe iframes, and unrestricted HTML are prohibited for normal editors.

### Output
- Rich text must be rendered through a normalized content container with controlled typography and spacing.
- Output must not expose unsafe markup.

---

## 8. Canonical Object Registry (Restated)

### CPTs
- `svdp_conf`
- `svdp_dash_item`
- `svdp_announcement`
- `svdp_doc`
- `svdp_event`

### Taxonomy
- `svdp_doc_cat`

### Custom table
- `{$wpdb->prefix}svdp_directory`

### Required user meta keys
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

### Shared targeting keys
- `svdp_scope`
- `svdp_audience_profiles`
- `svdp_target_conference_mode`
- `svdp_target_conference_ids`
- `svdp_target_group_flags`
- `svdp_is_active`
- `svdp_publish_start`
- `svdp_publish_end`

### Prohibited MVP aliases
- `svdp_conference_mode`
- `svdp_conference_ids`
- `svdp_group_flags`
- `svdp_active`
- `svdp_start_at`
- `svdp_end_at`
