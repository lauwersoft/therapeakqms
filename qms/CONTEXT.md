# QMS Project Context

## Company
- Building a Quality Management System (QMS) for EU medical device certification
- Target market: EU only

## Device
- Type: AI-based therapy software (Software as a Medical Device / SaMD)
- Classification: Class IIa (EU MDR)
- Conformity assessment route: Annex IX (likely)
- Key considerations: AI model validation, retraining/updates as design changes, clinical evidence for therapy effectiveness, cybersecurity, GDPR (health data)

## Regulatory Framework
- **ISO 13485:2016** — QMS standard for medical devices
- **EU MDR 2017/745** — European Medical Device Regulation
- **ISO 14971** — Risk management
- Both ISO 13485 and EU MDR must be satisfied simultaneously
- IEC 62304 (software lifecycle) and IEC 62366-1 (usability) may be needed later for technical documentation phase, but not required for initial QMS setup per legal advice

## Timeline
- NB (Notified Body) audit engagement starts: April 7, 2026
- QMS must be established and documented before NB engagement
- The NB will audit the QMS against both ISO 13485 and EU MDR

## Reference Documents in this Directory
- `iso_13485_2016.txt` — Full ISO 13485:2016 standard text
- `iso_14971_2019.txt` — Full ISO 14971:2019 standard text
- `eu_mdr_2017_745.txt` — Full EU MDR 2017/745 regulation text (Annexes I–XVI)
- `mdcg/` — MDCG guidance documents (free, from EU Commission)

## Key MDR Sections for QMS
- **Article 10** — General obligations of manufacturers
- **Annex I** — General Safety and Performance Requirements (GSPR)
- **Annex II** — Technical documentation structure
- **Annex VIII** — Classification rules (Class IIa justification)
- **Annex IX** — Conformity assessment (quality management system + technical documentation assessment)
- **Annex XIV** — Clinical evaluation and post-market clinical follow-up

## Key ISO 13485 Clauses for QMS
- **Clause 4** — Quality management system (documentation, quality manual, device files)
- **Clause 5** — Management responsibility
- **Clause 6** — Resource management
- **Clause 7** — Product realization (design controls, purchasing, production, traceability)
- **Clause 8** — Measurement, analysis and improvement (CAPA, complaints, audits)

## Approach
- QMS documents are being built using Claude Code
- All reference standards/regulations are stored in `/qms` as plain text for AI consumption
- Documents should cross-reference both ISO 13485 clauses and MDR requirements
- Documents are stored as markdown files in `qms/documents/`
- Only `qms/documents/` is shown on the website — everything else is for AI/reference only

---

## Document System Guide

### File Location
All QMS documents live in `qms/documents/`. Subdirectories are used to organize by category (e.g., `procedures/`, `forms/`, `plans/`).

### Filenames
- Use kebab-case, all lowercase: `document-control.md`, `risk-management.md`
- Always `.md` extension
- The display name comes from the frontmatter `title` field, not the filename

### Frontmatter (YAML)
Every document MUST have frontmatter at the top. This is the metadata that the system uses for document IDs, versioning, status tracking, and cross-referencing.

```markdown
---
id: "SOP-001"
title: "Document Control Procedure"
type: "SOP"
version: "0.1"
status: "draft"
author: "Sarp Derinsu"
effective_date: "2026-04-01"
iso_refs:
  - "4.2.4"
  - "4.2.5"
mdr_refs:
  - "Annex I, Section 3"
---

# Document Control Procedure

Content goes here...
```

### Frontmatter Fields

| Field | Required | Description |
|-------|----------|-------------|
| `id` | Yes | Unique document ID. Format: `TYPE-NNN` (e.g., `SOP-001`). Auto-generated when creating via the UI. |
| `title` | Yes | Human-readable document title. Shown in the sidebar and document header. |
| `type` | Yes | Document type prefix. Must match one of the types below. |
| `version` | Yes | Version string (e.g., `"0.1"`, `"1.0"`). Always quoted in YAML. |
| `status` | Yes | One of: `draft`, `in_review`, `approved`, `obsolete` |
| `author` | Yes | Name of the document author/owner. |
| `effective_date` | No | Date the document becomes effective. Format: `YYYY-MM-DD`. |
| `iso_refs` | No | List of ISO 13485 clause references (e.g., `["4.2.4", "7.1"]`). |
| `mdr_refs` | No | List of EU MDR references (e.g., `["Annex I, Section 3"]`). |

### Document Types and ID Prefixes

| Prefix | Type | Use For |
|--------|------|---------|
| `QM` | Quality Manual | The top-level QMS document |
| `POL` | Policy | Quality policy, data privacy policy, etc. |
| `SOP` | Standard Operating Procedure | Step-by-step procedures (CAPA, complaints, audits, etc.) |
| `WI` | Work Instruction | Detailed instructions for specific tasks |
| `FM` | Form | Fillable forms (audit checklists, CAPA forms, deviation forms, etc.) |
| `TMP` | Template | Document templates (report templates, meeting minutes templates, etc.) |
| `PLN` | Plan | Plans (risk management plan, clinical evaluation plan, PMCF plan, etc.) |
| `REC` | Record | Records (training records, calibration records, etc.) |
| `RPT` | Report | Reports (management review, audit reports, clinical evaluation report, etc.) |
| `LOG` | Log | Logs (training log, supplier log, change log, etc.) |
| `LST` | List / Register | Lists and registers (approved supplier list, equipment list, etc.) |
| `SPE` | Specification | Specifications (product specs, software requirements, etc.) |
| `DWG` | Drawing / Diagram | Drawings, diagrams, flowcharts, process maps |
| `AGR` | Agreement | Agreements (quality agreements with suppliers, NDAs, etc.) |
| `CER` | Certificate | Certificates (ISO certificates, calibration certificates, etc.) |
| `LBL` | Label / IFU | Labels and instructions for use |
| `RA` | Risk Assessment | Risk assessments and risk management files |
| `CE` | Clinical Evaluation | Clinical evaluation reports and related documents |
| `MAN` | Manual / Guide | User manuals, training guides, reference guides |

### Document Linking
To link to another document, use double-bracket syntax with the document ID:
- Write `[[SOP-001]]` in the markdown body
- It renders as a clickable link pointing to that document
- If the ID doesn't exist, it shows as red "(not found)" text
- Links work regardless of which directory the target document is in

### Status Workflow
Documents follow this lifecycle:
1. **Draft** — Initial creation, work in progress
2. **In Review** — Ready for review and approval
3. **Approved** — Formally approved, effective for use
4. **Obsolete** — Superseded or withdrawn

### Version Numbering
- Start at `0.1` for initial drafts
- Increment minor version for small changes: `0.1` → `0.2`
- Use `1.0` for first approved version
- Increment major version for significant changes: `1.0` → `2.0`

### Git/Publishing
- All changes are saved to disk immediately but NOT committed to git
- Changes accumulate until someone clicks "Publish" on the changes page
- Publishing creates one git commit with all changes and pushes to GitHub
- Every change is tracked with user attribution
- The changes page shows diffs, property changes, and activity log

### When Creating Documents with Claude Code
1. Always include complete frontmatter with all required fields
2. Use `DocumentMetadata::nextId('SOP', base_path('qms/documents'))` to get the next available ID
3. Use double-quoted values in frontmatter for consistency
4. Reference ISO 13485 clauses and MDR articles in the `iso_refs` and `mdr_refs` fields
5. Use `[[DOC-ID]]` syntax to cross-reference other documents
6. Follow the existing document structure: Purpose → Scope → Responsibilities → Procedure → Records
