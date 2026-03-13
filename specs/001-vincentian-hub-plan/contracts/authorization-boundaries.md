# Authorization Boundary Contract

## Backend/Admin Authorization

Backend/admin access is governed by:

- WordPress roles
- WordPress capabilities
- admin menu access rules

Canonical WordPress roles:

- `svdp_member`
- `svdp_district_staff`
- `svdp_district_announcements_editor`
- `svdp_district_editor`
- `svdp_district_admin`

## Front-End Visibility

Front-end visibility is governed by:

- normalized user context
- targeting resolver

Canonical `svdp_role_profiles` values:

- `member`
- `president`
- `executive_leadership`
- `spiritual_advisor`
- `assistance_line`
- `district_staff`
- `district_announcements_editor`
- `district_editor`
- `district_admin`

## Contract Rules

- WordPress roles must never be treated as automatic front-end targeting profiles
- `svdp_role_profiles` must never be used as a substitute for backend CRUD authorization
- all protected dashboard, document, event, feed, export, shortcode, and detail behaviors must route through the targeting resolver

## Planning Implications

- role and capability registration belongs in the foundation slice
- resolver/context logic belongs in the resolver slice
- any slice touching both admin CRUD and front-end delivery must preserve the boundary explicitly
