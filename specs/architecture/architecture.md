# Vincentian Hub Architecture

This document defines the implementation structure of Vincentian Hub.

## Binding Architecture Companions

The following documents are required companions to this architecture and must be treated as anti-drift artifacts:

- `/specs/contracts-spec.md`
- `/specs/architecture/targeting-engine-rules.md`
- `/specs/architecture/system-diagram.md`
- `/specs/architecture/wordpress-data-model-map.md`

### Drift-prevention rule

Developers must consult the WordPress Data Model Map before changing:

- CPT registration
- meta registration
- user meta handling
- resolver inputs
- object relationships
- document/event relationship fields

Its purpose is to prevent WordPress schema drift, inconsistent targeting inputs, and duplicated visibility logic.

## Core Principle

WordPress is the system of record.

Google Drive is an upstream document source, not the user interface.

## Layers

### 1. Authentication Layer
Google OAuth login and onboarding

### 2. User Context Layer
Approved user context, role profiles, conference assignment, and conference flags

### 3. Targeting Engine
Central resolver that determines visibility for all targeted objects

### 4. Content Layer
Custom Post Types:
- Dashboard Items
- Announcements
- Documents
- Events

### 5. Presentation Layer
Dashboard rendering, detail pages, and current-theme-based mobile-first UI

### 6. Integration Layer
Google Drive imports, ICS feeds, and external system links

## Security Model

Server-side permission checks are required for:

- dashboard content queries
- document preview
- document download
- event detail pages
- calendar feeds
- export endpoints

Client-side filtering is not sufficient.

## Theme Integration Constraint

The portal must adopt the basics of the current WordPress theme.

Do not spend time reinventing visual branding.  
Design effort should focus on:

- mobile-first layout
- readable typography
- spacing
- large tap targets
- calm predictable hierarchy

## Header Logo Constraint

A rectangular header logo area must be reserved and support district-admin upload behavior as defined in the contracts specification.
