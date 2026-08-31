## Context

See proposal.md - Why.
The project currently has functioning tests, but they lack coverage for the latest feature additions (Direct Promotion via Filament UI Hooks, and the Anonymization Command). The documentation also reflects an older state of the project. We need a straightforward approach to update these without altering any core behavior.

## Goals / Non-Goals

**Goals:**
- Increase Pest test coverage for the specific new flows.
- Bring the documentation and historical logs up to date.

**Non-Goals:**
- Refactoring any application code.
- Adding new features to the web app.
- Altering existing test cases (only adding new ones).

## Decisions

- **Test Approach for Promotion:** We will add tests in `Tests\Feature\CategoryTest` or a dedicated test file to simulate the behavior of the Filament form data mutation. We need to ensure we can test the `mutateFormDataBeforeCreate` logic if it is encapsulated, or at least test the backend consequences if we simulate a request. Since the logic is inside a Filament Resource hook, testing it via Livewire/Filament testing helpers (if available) or unit testing the underlying action is preferred.
- **Test Approach for Anonymization:** We will add a test in `Tests\Feature\CommandTest` (or similar) that runs `artisan('app:anonymize-teachers-csv')` on a mock/temporary CSV file to ensure it executes successfully and modifies data.
- **Documentation Updates:** Direct markdown edits to the relevant files in `docs/`.

## Risks / Trade-offs

- **[Risk] Testing Filament Hooks** → Mitigation: If testing the Livewire component directly is too complex, we can extract the promotion logic into a service class or action in a future refactor, but for now, we will attempt to test the Filament Create page via `$livewire->fillForm(...)` or just rely on manual UI tests if Pest Filament testing is not fully set up. We'll aim for Pest Filament tests using `livewire()` helper.
