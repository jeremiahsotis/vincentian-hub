# Vincentian Hub Contracts Spec (Merged)

Vincentian Hub Contracts and Reference Spec

Authoritative MVP contract for field names, targeting semantics, capability mapping, route security, and developer reference.

1. Hard Contracts

The following are hard contracts for MVP and require deliberate versioning and migration planning before any change:

Custom post type names, taxonomy names, custom table names, and user meta keys

All canonical object meta keys, especially the shared targeting block

Targeting semantics, including resolver order

Capability names and capability meanings

Route patterns and server-side security requirements

WYSIWYG requirement and allowed editor behavior

2. Canonical Object Registry

3. Shared Targeting Keys

The following meta keys must be used identically across dashboard items, announcements, documents, and events.

Prohibited aliases for MVP: svdp_conference_mode, svdp_conference_ids, svdp_group_flags, svdp_active, svdp_start_at, svdp_end_at.

4. Targeting Semantics

Scope

conference: only approved conference users

district: only approved district users

both: approved conference or district users, subject to all other checks

Audience Profiles

Conference: member, president, executive_leadership, spiritual_advisor

District: assistance_line, district_staff, district_announcements_editor, district_admin

Visibility requires intersection between object audience profiles and user role profiles

Conference Targeting Mode

all: all conference users

selected: only listed conference IDs

group_flags: conferences whose true flags intersect the object flags

district_only: district users only

none: only when conference targeting is not relevant because scope or object behavior is effectively district-only

Resolver Order

1. user exists

2. user approved

3. object active

4. publish window

5. scope match

6. audience intersection

7. district or conference targeting match

5. Capability Mapping

6. Route and Security Contract

7. WYSIWYG Contract

WYSIWYG is required in MVP for Dashboard Items full description, Announcements full body, Documents body/description, and Events full description.

Allowed features: paragraphs, H2/H3, bold, italic, bulleted and numbered lists, links, remove formatting, and tables only if explicitly enabled.

Prohibited features: arbitrary HTML for normal editors, free-form font sizing, font-family changes, uncontrolled color formatting, scripts, arbitrary embeds, and page-builder layout behavior.

8. Additional Developer Inputs Included in This Package

Technical handoff document for Codex, Spec Kit, and GitHub Issues

Build backlog and sprint plan

Developer kickoff checklist and acceptance criteria

Plugin scaffold package with module stubs and README

UI integration constraint: adopt the basics of the current WordPress theme, and invest design energy mainly in mobile-first structure, typography, spacing, and large tap targets

9. Versioning

Spec version: v1.0-contract-locked. Additive non-breaking changes move to v1.1. Breaking contract changes move to v2.0 and require migration notes.

---

Vincentian Hub – Updated Contracts & Reference Spec

Plugin Identity Lock

Plugin Name: Vincentian Hub

Plugin Slug: vincentian-hub

GitHub Repository: vincentian-hub

Code Prefix: vincentian_hub_

Schema / Meta Prefix: svdp_

PHP Namespace: VincentianHub

Admin Menu Label: Vincentian Hub

Volunteer-Facing Name: Vincentian Hub

Main Plugin File: vincentian-hub.php

Text Domain: vincentian-hub

Header Logo Contract

Purpose:

Allow a district administrator to upload the Society of St Vincent de Paul logo (or district-approved mark) and display it in the Vincentian Hub header.

Placement:

Top-left of the header area.

Layout pattern (desktop):

[ Logo ]  Vincentian Hub

Page Heading

Layout pattern (mobile):

[Logo]

Vincentian Hub

Page Heading

Reserved Logo Container:

Desktop:

max-width: 240px

max-height: 64px

Mobile:

max-width: 180px

max-height: 48px

Rendering Rules:

- Maintain aspect ratio

- Do not crop

- Use object-fit: contain

- Logo scales responsively

Upload Location:

Vincentian Hub → Settings → Branding

Setting Key:

vincentian_hub_logo_attachment_id

Allowed File Types:

- PNG

- SVG

- JPG

Recommended Upload Size:

600×160 or larger

Image Handling:

Register image size:

add_image_size('vincentian_hub_logo', 480, 120, false);

Fallback Behavior:

If no logo uploaded:

Display text heading only: “Vincentian Hub”

Accessibility:

Default alt text:

“Society of St Vincent de Paul”

Targeting Resolver Contract

Shared targeting keys (immutable):

svdp_scope

svdp_audience_profiles

svdp_target_conference_mode

svdp_target_conference_ids

svdp_target_group_flags

svdp_is_active

svdp_publish_start

svdp_publish_end

Resolver evaluation order:

1. user exists

2. user approved

3. object active

4. publish window

5. scope match

6. audience intersection

7. conference/district targeting match

Calendar Export Contract

WordPress is the system of record for events.

Calendar export methods:

1. Personalized ICS subscription feed

2. Single-event ICS download

3. Add-to-Google-Calendar link

Feed route:

/portal-calendar/feed/<token>/

Single event route:

/portal-calendar/event/<event_id>/download/

WYSIWYG Contract

WYSIWYG editor required for:

- Dashboard Items

- Announcements

- Documents

- Events

Allowed formatting:

- Paragraph

- H2/H3

- Bold

- Italic

- Bulleted list

- Numbered list

- Links

- Table (optional)

Disallowed:

- arbitrary HTML

- script embeds

- uncontrolled color/fonts