# Data Model: Vincentian Hub Implementation Planning Baseline

This document restates the binding Vincentian Hub data model as the implementation baseline for planning and PR slicing.

## Core Runtime Entities

### Conference

- Backing type: `svdp_conf`
- Purpose: Canonical conference record used for volunteer routing, conference assignment, linked-page mapping, and conference flag derivation.
- Key fields:
  - `svdp_conf_code`
  - `svdp_conf_page_slug`
  - `svdp_conf_linked_page_id`
  - `svdp_conf_city`
  - `svdp_conf_county`
  - `svdp_conf_is_urban`
  - `svdp_conf_is_rural`
  - `svdp_conf_is_new_haven`
  - `svdp_conf_is_allen_county`
  - `svdp_conf_active`
- Relationships:
  - one conference may be assigned to many conference-scope users
  - one conference contributes derived `conference_flags` to normalized user context
  - one conference may map to one linked page slug/context route
- Validation rules:
  - `svdp_conf_page_slug` must be unique and stable once published
  - only active conferences contribute targeting flags

### User Access State

- Backing type: WordPress user plus required `svdp_` user meta
- Purpose: Stores approval state, account scope, conference assignment, role profiles, and calendar token data.
- Required fields:
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
- State transitions:
  - approval: `pending` -> `approved` or `disabled`
  - onboarding: incomplete -> completed
  - scope: `conference` or `district`
- Validation rules:
  - conference-scope users require exactly one `svdp_conference_id`
  - district-scope users do not use `svdp_conference_id` for targeting
  - `svdp_role_profiles` is stored as array user meta, not CSV text

### Normalized User Context

- Backing type: computed runtime structure owned by the targeting resolver
- Purpose: The only user-context shape used for front-end visibility evaluation.
- Fields:
  - `user_id`
  - `approval_status`
  - `account_scope`
  - `conference_id`
  - `role_profiles`
  - `conference_flags`
  - `calendar_feed_token`
- Relationships:
  - derived from WordPress user meta plus active conference metadata
- Validation rules:
  - all front-end visibility checks must reuse this shape
  - resolver evaluation must not query conference meta ad hoc once context is built

### Shared Targeting Block

- Backing type: registered object meta reused across content CPTs
- Purpose: Common resolver interface for content visibility.
- Fields:
  - `svdp_scope`
  - `svdp_audience_profiles`
  - `svdp_target_conference_mode`
  - `svdp_target_conference_ids`
  - `svdp_target_group_flags`
  - `svdp_is_active`
  - `svdp_publish_start`
  - `svdp_publish_end`
- Applies to:
  - `svdp_dash_item`
  - `svdp_announcement`
  - `svdp_doc`
  - `svdp_event`
- Validation rules:
  - no aliases or alternate key names
  - district-only shared-targeting objects use `svdp_scope = district` with `svdp_target_conference_mode = district_only`

### Dashboard Item

- Backing type: `svdp_dash_item`
- Purpose: Dashboard tools, links, document references, event shortcuts, or shortcode-backed utilities.
- Additional fields:
  - `svdp_item_type`
  - `svdp_item_url`
  - `svdp_item_shortcode`
  - `svdp_item_document_id`
  - `svdp_item_linked_item_ids`
  - `svdp_item_open_mode`
  - `svdp_section_key`
  - `svdp_priority`
  - `svdp_display_style`
  - `svdp_sort_order`
  - `svdp_auto_inject_conference_context`
  - `svdp_featured`

### Announcement

- Backing type: `svdp_announcement`
- Purpose: Time-sensitive district or conference communications rendered in dashboard and announcement contexts.
- Additional fields:
  - `svdp_announcement_type`
  - `svdp_priority`
  - `svdp_display_placement`
  - `svdp_cta_label`
  - `svdp_cta_url`
  - `svdp_created_by_profile`
  - `svdp_internal_notes`
  - `svdp_featured`

### Document

- Backing type: `svdp_doc`
- Purpose: Protected resource-library record with preview/download behavior.
- Additional fields:
  - `svdp_doc_source`
  - `svdp_drive_file_id`
  - `svdp_drive_parent_ref`
  - `svdp_drive_mime_type`
  - `svdp_drive_modified_time`
  - `svdp_drive_version`
  - `svdp_doc_preview_type`
  - `svdp_doc_local_cache_path`
  - `svdp_doc_thumbnail_path`
  - `svdp_doc_search_weight`
  - `svdp_doc_featured`
  - `svdp_doc_plain_language_title`
  - `svdp_doc_help_text`
  - `svdp_doc_is_recently_updated`
  - `svdp_doc_force_download`
  - `svdp_doc_available_for_meeting_packets`
- Relationships:
  - many documents may belong to `svdp_doc_cat`
  - events may reference related documents and meeting packet documents

### Event

- Backing type: `svdp_event`
- Purpose: Protected event detail pages plus calendar/export behavior.
- Additional fields:
  - `svdp_event_start`
  - `svdp_event_end`
  - `svdp_event_all_day`
  - `svdp_event_timezone`
  - `svdp_event_location_name`
  - `svdp_event_location_address`
  - `svdp_event_virtual_url`
  - `svdp_event_registration_url`
  - `svdp_event_type`
  - `svdp_event_status`
  - `svdp_show_on_dashboard`
  - `svdp_show_in_calendar`
  - `svdp_show_in_whats_new`
  - `svdp_featured`
  - `svdp_priority`
  - `svdp_sort_order`
  - `svdp_related_document_ids`
  - `svdp_meeting_packet_document_ids`
  - `svdp_related_announcement_ids`
  - `svdp_event_uid`
  - `svdp_event_last_modified_utc`
  - `svdp_event_calendar_export_enabled`
  - `svdp_event_single_add_enabled`

### Trusted Directory Entry

- Backing type: `{$wpdb->prefix}svdp_directory`
- Purpose: Registration/import bootstrap source for identity resolution and default values.
- Fields:
  - `id`
  - `first_name`
  - `last_name`
  - `email`
  - `phone`
  - `conference_id`
  - `account_scope`
  - `default_profiles`
  - `auto_approve`
  - `source_label`
  - `updated_at`
  - `created_at`
- Validation rules:
  - runtime code must not hardcode `wp_`
  - after user creation, runtime authority moves to WordPress user meta and normalized context

### Branding Settings

- Backing type: WordPress option / settings layer
- Purpose: Holds plugin-level branding configuration.
- Key field:
  - `vincentian_hub_logo_attachment_id`

## Authorization and Visibility Boundaries

- Backend/admin authorization:
  - WordPress roles
  - WordPress capabilities
  - admin menu access rules
- Front-end visibility:
  - normalized user context
  - targeting resolver
- Planning implication:
  - no slice may blur these two models
