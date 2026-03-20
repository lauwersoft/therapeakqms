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
- **ISO 14971** — Risk management (to be acquired)
- Both ISO 13485 and EU MDR must be satisfied simultaneously
- IEC 62304 (software lifecycle) and IEC 62366-1 (usability) may be needed later for technical documentation phase, but not required for initial QMS setup per legal advice

## Timeline
- NB (Notified Body) audit engagement starts: April 7, 2026
- QMS must be established and documented before NB engagement
- The NB will audit the QMS against both ISO 13485 and EU MDR

## Reference Documents in this Directory
- `iso_13485_2016.txt` — Full ISO 13485:2016 standard text
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
