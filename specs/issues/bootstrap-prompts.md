# Vincentian Hub Spec Kit Bootstrap Prompts

Use only the consolidated authoritative docs as binding context:

- `specs/contracts-spec.md`
- `specs/architecture/architecture.md`
- `specs/architecture/targeting-engine-rules.md`
- `specs/architecture/wordpress-data-model-map.md`
- `specs/architecture/system-diagram.md`
- `specs/issues/SPEC.md`

Do not reference superseded patch files.

## Bootstrap Discovery A

```text
You are in discovery mode only.

Do not write code.
Do not modify files.
Do not create a plan.
Do not create tasks.
Do not suggest implementation yet.

Your only job is to read and summarize the binding constraints for Vincentian Hub.

Read these files carefully:

- specs/contracts-spec.md
- specs/architecture/architecture.md
- specs/architecture/system-diagram.md
- specs/architecture/targeting-engine-rules.md
- specs/architecture/wordpress-data-model-map.md

What I need back from you:

1. A concise summary of the hard contracts
2. The canonical CPTs, taxonomy, runtime custom table naming convention, and required user meta keys
3. The canonical shared targeting keys
4. The canonical `svdp_role_profiles` values
5. The WordPress roles and the capability-to-role boundaries
6. The approval and account scope enums
7. The route/security model
8. The biggest implementation drift risks
9. Anything still ambiguous or contradictory

Important constraints for this step:
- Do not write code
- Do not edit files
- Do not propose implementation
- Only gather information and report what you learned
```

## Bootstrap Discovery B

```text
You are still in discovery mode only.

Do not write code.
Do not modify files.
Do not create a plan.
Do not create tasks.
Do not suggest implementation yet.

Your only job is to read the supporting docs and inspect the repository structure for Vincentian Hub.

Read these files:

- specs/issues/SPEC.md
- specs/architecture/architecture.md
- specs/architecture/system-diagram.md
- specs/architecture/wordpress-data-model-map.md
- specs/architecture/targeting-engine-rules.md
- specs/architecture/most-used-tools.md
- docs/01_Vincentian_Hub_Technical_Handoff.docx
- docs/02_Vincentian_Hub_Build_Backlog_and_Sprint_Plan.docx
- docs/03_Vincentian_Hub_Developer_Kickoff_Checklist_and_Acceptance_Criteria.docx

Inspect this repository structure carefully:

- assets/
- includes/
- templates/
- tests/
- specs/

What I need back from you:

1. The intended module boundaries in includes/
2. Which files likely own which responsibilities
3. Module dependencies and safest implementation order
4. Missing scaffolding or setup gaps
5. Risks of WordPress-specific drift based on the repo structure
6. Suggested implementation slices that map cleanly to PRs

Important constraints for this step:
- Do not write code
- Do not edit files
- Do not create implementation tasks yet
- Only gather information and report what you learned
```

## `/speckit.plan` Prompt / Context

```text
Use the Vincentian Hub spec and all binding architecture documents to generate a realistic implementation plan.

This project is a WordPress plugin named Vincentian Hub.

Binding and non-negotiable documents:
- specs/contracts-spec.md
- specs/architecture/architecture.md
- specs/architecture/system-diagram.md
- specs/architecture/targeting-engine-rules.md
- specs/architecture/wordpress-data-model-map.md
- specs/issues/SPEC.md

Planning rules:
1. Do not violate the contracts spec
2. Do not invent alternate schema names
3. Do not duplicate targeting logic outside the resolver
4. Do not change route patterns
5. Do not redesign the UI beyond mobile-first structure using the current theme baseline
6. Keep WordPress as the system of record
7. Treat the WordPress Data Model Map as an anti-drift artifact
8. Distinguish clearly between:
   - WordPress roles/capabilities for backend authorization
   - `svdp_role_profiles` for front-end visibility

Implementation slicing rules:
- Break work into reasonable vertical slices
- Each slice must be small enough for one focused PR
- Foundation and resolver work must come before user-facing rendering
- Do not combine unrelated systems in one slice unless dependency requires it

Git workflow rules:
- After each completed slice, commit
- Push the branch
- Open a PR
- Validate against the Vincentian Hub PR template
- Merge before beginning the next slice on a new branch
- The plan must explicitly identify PR boundaries and commit checkpoints

What I need in the plan:
1. phased implementation roadmap
2. dependency-aware module order
3. recommended PR slices
4. risks and validation points per slice
5. suggested branch naming convention
6. suggested commit checkpoints
```

## `/speckit.tasks.md` Prompt / Context

```text
Generate implementation tasks from the approved Vincentian Hub plan.

Requirements:
- Group tasks by implementation slice / PR boundary
- Include dependencies
- Include files likely to be touched
- Include acceptance criteria
- Include validation against:
  - specs/contracts-spec.md
  - specs/architecture/targeting-engine-rules.md
  - specs/architecture/wordpress-data-model-map.md
- Keep tasks small enough for a focused PR

Output should make it obvious:
- what gets built first
- what gets tested first
- what ends each PR slice
- when a slice is complete enough to commit, push, and open a PR

Each task should include:
1. title
2. description
3. files/modules affected
4. dependencies
5. acceptance criteria
6. PR slice grouping
7. contract/risk checks
```

## `/speckit.analyze` Prompt / Context

```text
Analyze the Vincentian Hub plan and tasks for architecture drift, dependency mistakes, and WordPress implementation risk.

Check specifically for:
- schema drift risk
- resolver duplication risk
- route/security gaps
- capability leakage
- CPT/meta registration mismatches
- implementation order problems
- slices that are too large for safe PR review
- missing test checkpoints
- missing contract validation points
- confusion between WordPress roles/capabilities and `svdp_role_profiles`

Return:
1. risks
2. corrections
3. missing tasks
4. suggested PR boundary adjustments
5. any plan/task output that conflicts with:
   - specs/contracts-spec.md
   - specs/architecture/targeting-engine-rules.md
   - specs/architecture/wordpress-data-model-map.md
```

## `/speckit.implement` Prompt / Context

```text
Implement Vincentian Hub one approved slice at a time.

Rules:
- Follow the contracts spec exactly
- Follow targeting-engine-rules exactly
- Follow wordpress-data-model-map exactly
- Do not rename canonical keys
- Do not create alternate targeting logic
- Keep changes limited to the current slice
- Stop at the PR boundary for the slice
- Report what changed, what remains, and any contract concerns before proceeding

Before finishing the slice, verify:
1. no contract drift
2. no duplicated resolver logic
3. no unauthorized access paths introduced
4. files changed match the current slice only
5. backend authorization uses capabilities
6. front-end visibility uses normalized user context + resolver

Do not continue into the next slice.
Stop once the current slice is PR-ready.
```
