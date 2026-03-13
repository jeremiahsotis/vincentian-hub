# Tests

Minimum required coverage:
- targeting resolver
- capability map
- route security
- ICS feed visibility
- contract-key enforcement

Current foundation scaffold:
- `tests/bootstrap.php` provides minimal WordPress stubs for registration-focused PHPUnit tests
- `tests/unit/` covers capability matrix, content registration, and meta registration
- `tests/integration/` covers bootstrap wiring and runtime table naming
