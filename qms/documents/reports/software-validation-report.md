---
id: "RPT-003"
title: "Software Validation Report"
type: "RPT"
category: "technical"
version: "1.0"
status: "draft"
effective_date: "2026-04-01"
author: "Sarp Derinsu"
iso_refs:
  - "7.3.7"
mdr_refs:
  - "Annex I, 17.1"
---

# Software Validation Report

## 1. Purpose

This report documents the results of validation activities for the Therapeak medical device software version 1.0. Validation confirms that the finished product meets its use requirements ([[SPE-003]]) when used in the intended use environment by the intended user population.

Validation is distinct from verification: verification confirms software requirements ([[SPE-001]]) are correctly implemented; validation confirms the product fulfils its intended purpose for the user.

**Related documents:** [[SPE-003]] Use Requirements Specification, [[TRC-001]] Software Traceability Matrix (Matrix 4), [[PLN-005]] Software Development Plan, [[PLN-006]] Usability Engineering Plan

**Applicable standards:**
- IEC 82304-1:2016 Sections 6.1, 6.2, 6.3 — Software validation
- IEC 62304:2006+AMD1:2015 Section 5.8 — Software release
- IEC 62366-1:2015 — Usability engineering

## 2. Validation Scope

### 2.1 Device Under Validation

| Item | Detail |
|---|---|
| Device name | Therapeak |
| Software version | 1.0 |
| Configuration | `DEVICE_MODE=medical` |
| Environment | Production-equivalent staging environment (Hetzner VPS, Nginx, PHP 8.3, MariaDB 10) |
| Browser | Chrome (latest), Safari (latest, iOS 15+), Firefox (latest) |

### 2.2 Validation Personnel

| Role | Person | Independence |
|---|---|---|
| Validation executor | Sarp Derinsu | As sole developer and quality manager, full independence from development is not achievable. Compensating measures: structured validation protocol with predefined acceptance criteria, documented results, and regulatory consultant review. This is proportionate for a single-person manufacturer per ISO 13485:2016 Clause 6.2 note. |

### 2.3 Use Requirements in Scope

All 41 use requirements from [[SPE-003]] (UR-001 to UR-041) are in scope for validation. Traceability from use requirements to validation activities is maintained in [[TRC-001]] Matrix 4.

## 3. Validation Results

### 3.1 Intended Purpose and Functionality (UR-001 to UR-009)

| VAL ID | Use Req | Description | Method | Result | Notes |
|---|---|---|---|---|---|
| VAL-001 | UR-001 | AI conversational therapy sessions | User testing | **PENDING** | |
| VAL-002 | UR-002 | Onboarding questionnaire | User testing | **PENDING** | |
| VAL-003 | UR-003 | Personalized AI therapist | User testing | **PENDING** | |
| VAL-004 | UR-004 | Timed therapy sessions | User testing | **PENDING** | |
| VAL-005 | UR-005 | Session continuity via summaries | User testing | **PENDING** | |
| VAL-006 | UR-006 | Progress reports | Review | **PENDING** | |
| VAL-007 | UR-007 | Mood tracking | User testing | **PENDING** | |
| VAL-008 | UR-008 | Multi-language support | User testing | **PENDING** | |
| VAL-009 | UR-009 | Subscription management | User testing | **PENDING** | |

### 3.2 User Interface (UR-010 to UR-014)

| VAL ID | Use Req | Description | Method | Result | Notes |
|---|---|---|---|---|---|
| VAL-010 | UR-010 | Web-based access | User testing | **PENDING** | |
| VAL-011 | UR-011 | Accessibility | Inspection | **PENDING** | |
| VAL-012 | UR-012 | Real-time interaction | User testing | **PENDING** | |
| VAL-013 | UR-013 | Disclaimers and transparency | Inspection | **PENDING** | |
| VAL-014 | UR-014 | Self-service management | User testing | **PENDING** | |

### 3.3 External Interfaces (UR-015 to UR-018)

| VAL ID | Use Req | Description | Method | Result | Notes |
|---|---|---|---|---|---|
| VAL-015 | UR-015 | AI model integration | User testing | **PENDING** | |
| VAL-016 | UR-016 | Payment processing | User testing | **PENDING** | |
| VAL-017 | UR-017 | Email delivery | User testing | **PENDING** | |
| VAL-018 | UR-018 | Image generation | User testing | **PENDING** | |

### 3.4 Safety (UR-019 to UR-025)

| VAL ID | Use Req | Description | Method | Result | Notes |
|---|---|---|---|---|---|
| VAL-019 | UR-019 | Crisis handling | User testing | **PENDING** | |
| VAL-020 | UR-020 | Age restriction | User testing | **PENDING** | |
| VAL-021 | UR-021 | Role enforcement | User testing | **PENDING** | |
| VAL-022 | UR-022 | Session quality monitoring | Review | **PENDING** | |
| VAL-023 | UR-023 | Contraindication display | Inspection | **PENDING** | |
| VAL-024 | UR-024 | Content restrictions | User testing | **PENDING** | |
| VAL-025 | UR-025 | No diagnosis/triage | Review | **PENDING** | |

### 3.5 Security and Data Privacy (UR-026 to UR-032)

| VAL ID | Use Req | Description | Method | Result | Notes |
|---|---|---|---|---|---|
| VAL-026 | UR-026 | Data encryption in transit | Analysis | **PENDING** | |
| VAL-027 | UR-027 | Secure authentication | User testing | **PENDING** | |
| VAL-028 | UR-028 | Health data protection | Review | **PENDING** | |
| VAL-029 | UR-029 | Account deletion / GDPR erasure | User testing | **PENDING** | |
| VAL-030 | UR-030 | Abuse prevention | User testing | **PENDING** | |
| VAL-031 | UR-031 | Administrative access control | Inspection | **PENDING** | |
| VAL-032 | UR-032 | No third-party training | Inspection | **PENDING** | |

### 3.6 Installation, Operation, and Lifecycle (UR-033 to UR-036)

| VAL ID | Use Req | Description | Method | Result | Notes |
|---|---|---|---|---|---|
| VAL-033 | UR-033 | No local installation | User testing | **PENDING** | |
| VAL-034 | UR-034 | Transparent updates | Analysis | **PENDING** | |
| VAL-035 | UR-035 | User decommissioning | User testing | **PENDING** | |
| VAL-036 | UR-036 | AI service availability | Analysis | **PENDING** | |

### 3.7 Regulatory and Compliance (UR-037 to UR-039)

| VAL ID | Use Req | Description | Method | Result | Notes |
|---|---|---|---|---|---|
| VAL-037 | UR-037 | Dual operating mode | User testing | **PENDING** | |
| VAL-038 | UR-038 | Medical mode terminology | Inspection | **PENDING** | |
| VAL-039 | UR-039 | GDPR compliance | Review | **PENDING** | |

### 3.8 Labeling and Accompanying Documentation (UR-040 to UR-041)

| VAL ID | Use Req | Description | Method | Result | Notes |
|---|---|---|---|---|---|
| VAL-040 | UR-040 | Instructions for use | Review | **PENDING** | |
| VAL-041 | UR-041 | Device labeling | Review | **PENDING** | |

## 4. Validation Summary

| Category | Total | Pass | Fail | Pending |
|---|---|---|---|---|
| Intended Purpose (UR-001 to UR-009) | 9 | -- | -- | 9 |
| User Interface (UR-010 to UR-014) | 5 | -- | -- | 5 |
| External Interfaces (UR-015 to UR-018) | 4 | -- | -- | 4 |
| Safety (UR-019 to UR-025) | 7 | -- | -- | 7 |
| Security (UR-026 to UR-032) | 7 | -- | -- | 7 |
| Lifecycle (UR-033 to UR-036) | 4 | -- | -- | 4 |
| Regulatory (UR-037 to UR-039) | 3 | -- | -- | 3 |
| Labeling (UR-040 to UR-041) | 2 | -- | -- | 2 |
| **Total** | **41** | **--** | **--** | **41** |

## 5. Anomalies and Deviations

*To be completed after validation execution.*

## 6. Residual Risk Assessment

*To be completed after validation execution. Will reference [[RA-001]] and [[RPT-002]] for any new risks identified during validation.*

## 7. Conclusion

*To be completed after validation execution.*

Expected conclusion format:
- Summary of validation results (pass/fail/conditional)
- Statement on whether the product meets its use requirements
- Statement on whether residual risks remain acceptable
- Recommendation for release or required corrective actions
- Statement on whether findings support inclusion in the Declaration of Conformity

## 8. Approval

| Role | Name | Date |
|---|---|---|
| Validation executor | Sarp Derinsu | *To be completed* |

## 9. Change History

| Version | Date | Description |
|---|---|---|
| 1.0 | 2026-04-01 | Initial release — validation protocol defined, execution pending |

## 10. References

- [[SPE-003]] Use Requirements Specification
- [[SPE-001]] Software Requirements Specification
- [[TRC-001]] Software Traceability Matrix
- [[TST-001]] Software Verification Test Specifications
- [[PLN-005]] Software Development Plan
- [[PLN-006]] Usability Engineering Plan
- [[RA-001]] Risk Management File
- [[RPT-002]] Risk Management Report
- IEC 82304-1:2016 Sections 6.1, 6.2, 6.3
- IEC 62304:2006+AMD1:2015 Section 5.8
- IEC 62366-1:2015
