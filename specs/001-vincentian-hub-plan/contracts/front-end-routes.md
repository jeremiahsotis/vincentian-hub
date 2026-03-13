# Front-End Route Contract

These are the canonical public-facing Vincentian Hub routes that implementation must preserve.

## Protected Routes

- `/district-resources/<conference-name>/`
- `/district-resources/district/`
- `/resource-library/<doc-slug>/`
- `/events/<event-slug>/`
- `/portal-calendar/feed/<token>/`
- `/portal-calendar/event/<event_id>/download/`

## Resolution Rules

- `<conference-name>` resolves from `svdp_conf_page_slug`
- route entry points are declared from `includes/routes.php`
- feature modules may handle object-specific delivery after centralized route entry and security checks

## Security Rules

Every protected route must:

1. construct normalized user context
2. call the targeting resolver
3. enforce server-side permission checks before returning protected object data

Client-side filtering is never sufficient.

## Planning Implications

- route work depends on conference lookup helpers, permissions helpers, and resolver availability
- document, event, feed, and export behavior must not bypass centralized route ownership
