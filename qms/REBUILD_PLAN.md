# QMS Rebuild Plan

*Created: April 1, 2026*
*Last updated: April 1, 2026*

## Status: ALL PHASES COMPLETE. Waiting on: internal audit report, verification/validation execution by Sarp.

## Why We Rebuilt

The internal auditor identified critical issues:
1. Documents written as if the medical device is already running (it's not — device_mode=wellness)
2. Overpromises unrealistic for a one-person company (daily checklists, constant monitoring, 5-10 min response times)
3. Verification claims without record mechanisms (no forms to prove reviews happened)
4. No traceability matrices (required by Scarlet and ISO 13485)
5. Cross-reference errors across multiple documents (wrong SOP numbers)
6. Missing documents that Scarlet requires (GSPR checklist, use requirements, verification reports, etc.)

## All Phases Complete

### Phase 0: Urgent Fixes ✅
- Fixed ALL cross-reference errors (SOP-006 misuse, SOP-010→SOP-009, SOP-012→SOP-005 in nonconforming product, DWG-001 labels)
- Fixed PHQ-9 language across 8 files
- Fixed "24 hours" consistency in complaint handling and quality policy

### Phase 1: Foundation Rebuilds ✅
- Created SPE-003 (Use Requirements Specification) — 41 use requirements per IEC 82304-1
- Rebuilt SPE-001 (Software Requirements Specification) — acceptance criteria, source traceability, verification method. 77 requirements.
- Created RPT-002 (Risk Management Report) — completes plan+file+report triad

### Phase 2: New Documents ✅
- Created TRC-001 (Software Traceability Matrix) — 4 matrices, zero gaps
- Created TST-001 (Software Verification Test Specifications) — 23 test specs covering 77 requirements
- Created CHK-001 (GSPR Checklist) — maps all MDR Annex I clauses to evidence

### Phase 3: Clinical/Usability/Validation ✅
- Created RPT-003 (Software Validation Report) — protocol defined, execution pending by Sarp
- Created RPT-004 (Usability Engineering Summative Evaluation Report) — formative + hazard scenario evaluation

### Phase 4: Submission ✅
- Created DOC-001 (Declaration of Conformity) — draft, to be finalized after conformity assessment
- Created CTX-001 (Submission Context Document) — maps Scarlet's expected docs to our doc IDs
- Created RPT-005 (Software Release Record) — pre-release checklist with pending items

### Overpromise Fixes ✅
- "5-10 minutes" response → "within 24 hours during normal working hours"
- "constantly monitors" → "monitors during active working hours"
- "after each deploy" → "for significant changes"
- "1-2 sessions per day/week" → "1-2 sessions per week" (including RA-001 risk controls)
- Qualified wellness data as "pre-market experience from the wellness version"
- Fixed "patients" used for wellness users

### Admin Guide ✅
- Rewritten as operational playbook with "when X → do Y → fill Z" format
- Split into Pre-CE and Post-CE sections

### Final Sweep ✅
- All cross-references verified clean
- All PHQ-9-style references eliminated
- All session review frequencies corrected
- All "24 hours" claims made consistent
- All document references validated

## Remaining Actions (for Sarp)

- [ ] **Run verification tests** — Execute all 23 test specs from TST-001 against staging/production. Document results in a test execution report.
- [ ] **Run validation activities** — Execute VAL-001 to VAL-041 from RPT-003. Fill in results.
- [ ] **Address internal audit findings** — When report arrives (~April 3), open CAPAs for any findings.
- [ ] **SOUP documentation** — Document all SOUP (Software of Unknown Provenance) items per Scarlet's software architecture requirements. Noted as PENDING in RPT-005.
- [ ] **Create medical device translations** — Activate `lang_backup` files for DEVICE_MODE=medical.
- [ ] **Activate crisis protocol** — Uncomment in conversation jobs. Add FLAG_CRISIS.
- [ ] **Implement app:purge-deleted-users** — Data deletion command.
- [ ] **Create Scarlet auditor account** — On QMS platform.
- [ ] **Sign off release** — After all pending items in RPT-005 are resolved.

## Document Inventory (Final)

### New documents created in this rebuild (10):
| Doc ID | Title | Type |
|---|---|---|
| SPE-003 | Use Requirements Specification | Specification |
| RPT-002 | Risk Management Report | Report |
| TST-001 | Software Verification Test Specifications | Test Specs |
| TRC-001 | Software Traceability Matrix | Matrix |
| CHK-001 | GSPR Checklist | Checklist |
| RPT-003 | Software Validation Report | Report |
| RPT-004 | Usability Summative Evaluation Report | Report |
| RPT-005 | Software Release Record | Report |
| DOC-001 | Declaration of Conformity (draft) | Declaration |
| CTX-001 | Submission Context Document | Context |

### Documents rebuilt or significantly modified:
| Doc ID | Changes |
|---|---|
| SPE-001 | Full rebuild — added acceptance criteria, source traceability, verification method columns |
| ADMIN_GUIDE | Complete rewrite as operational playbook |
| DWG-001 | Added Section 4.5, fixed cross-references |
| SOP-004 | Fixed response times, cross-references |
| SOP-003 | Fixed cross-references |
| SOP-015 | Fixed cross-reference (SOP-012→SOP-005) |
| SOP-016 | Fixed monitoring claims |
| PLN-005 | Fixed deploy claims, session review frequency |
| PLN-003 | Fixed PHQ-9 language |
| PLN-006 | Fixed PHQ-9 language, session review frequency |
| RPT-001 | Fixed monitoring claims, qualified wellness data |
| RA-001 | Fixed session review frequency in risk controls, PHQ-9 language |
| SPE-002 | Fixed PHQ-9 language |
| POL-001 | Fixed "24 hours" consistency |

### Total QMS document inventory:
- 45 markdown documents
- 9 form JSON files (.form.json)
- 11 records (.rec.json)
- 2 certificate PDFs
- **54 total files** (excluding records and certificates)
