# Vincentian Hub Contract Patch (v1.0.1)

This patch supplements `contracts-spec.md` to close gaps identified during architectural review.
All items here are contract-locked unless versioned to v2.0.

## Capability Registry
svdp_view_portal_admin
svdp_manage_settings
svdp_manage_conferences
svdp_manage_user_profiles
svdp_approve_users
svdp_edit_dashboard_items
svdp_edit_others_dashboard_items
svdp_publish_dashboard_items
svdp_delete_dashboard_items
svdp_edit_announcements
svdp_edit_others_announcements
svdp_publish_announcements
svdp_delete_announcements
svdp_edit_documents
svdp_edit_others_documents
svdp_publish_documents
svdp_delete_documents
svdp_edit_events
svdp_edit_others_events
svdp_publish_events
svdp_delete_events
svdp_manage_drive_imports

## WordPress Roles
svdp_member
svdp_district_staff
svdp_district_announcements_editor
svdp_district_editor
svdp_district_admin

## Role Semantics
svdp_member
- No administrative portal capabilities

svdp_district_staff
- Access to district dashboard
- No publishing privileges by default

svdp_district_announcements_editor
- May create and publish announcements only

svdp_district_editor
- May manage dashboard items, announcements, documents, and events

svdp_district_admin
- Full portal administrative capabilities

## svdp_approval_status values
approved
pending
disabled

## svdp_account_scope values
conference
district

## Canonical Routes
/district-resources/<conference-name>/
/district-resources/district/
/resource-library/<doc-slug>/
/events/<event-slug>/
/portal-calendar/feed/<token>/
/portal-calendar/event/<event_id>/download/

## Security
All protected routes must:
- construct normalized user context
- call the targeting resolver
- enforce server-side permission checks

Client-side filtering is insufficient.

## Resolver Step 7 wording
conference/district targeting match
