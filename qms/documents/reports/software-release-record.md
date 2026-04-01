---
id: "RPT-005"
title: "Software Release Record"
type: "RPT"
category: "technical"
version: "1.0"
status: "draft"
effective_date: "2026-04-01"
author: "Sarp Derinsu"
iso_refs:
  - "7.3.4"
mdr_refs:
  - "Annex I, 17.1"
---

# Software Release Record — Version 1.0

## 1. Purpose

This record documents the release decision for Therapeak medical device software version 1.0. It confirms that all prerequisite activities have been completed and provides the formal authorization for release.

**Related documents:** [[PLN-005]] Software Development Plan, [[SOP-011]] Software Lifecycle Management, [[SOP-014]] Product Identification and Traceability

**Applicable standards:**
- IEC 62304:2006+AMD1:2015 Section 5.8 — Software release
- IEC 82304-1:2016 Section 5.4 — Release

## 2. Release Identification

| Item | Detail |
|---|---|
| Software name | Therapeak |
| Version | 1.0 |
| Configuration | `DEVICE_MODE=medical` |
| Git tag | *To be assigned: v1.0* |
| Release date | *To be determined — upon successful conformity assessment* |
| Release type | Initial release (medical device) |

## 3. Pre-Release Checklist

### 3.1 Requirements

| Item | Status | Evidence |
|---|---|---|
| Use requirements defined and reviewed | Complete | [[SPE-003]] |
| Software requirements defined and reviewed | Complete | [[SPE-001]] |
| All use requirements traced to software requirements | Complete | [[TRC-001]] Matrix 1 |
| All software requirements have acceptance criteria | Complete | [[SPE-001]] |

### 3.2 Design and Architecture

| Item | Status | Evidence |
|---|---|---|
| Software architecture documented | Complete | [[PLN-005]] |
| SOUP items identified and documented | Complete | [[PLN-005]] Section 10, [[SOP-011]] Section 4.8 |
| Design reviews completed | Complete | FM-007 records |

### 3.3 Risk Management

| Item | Status | Evidence |
|---|---|---|
| Risk management plan defined | Complete | [[PLN-001]] |
| Risk analysis complete (all hazards identified) | Complete | [[RA-001]] |
| Risk controls implemented and verified | Complete | [[RA-001]], [[RPT-002]] |
| Residual risk evaluation complete | Complete | [[RA-001]] Section 7, [[RPT-002]] |
| Risk management report complete | Complete | [[RPT-002]] |
| Overall residual risk acceptable | Complete | [[RPT-002]] Section 8 |
| Risk controls traced to software requirements | Complete | [[TRC-001]] Matrix 3 |

### 3.4 Verification

| Item | Status | Evidence |
|---|---|---|
| Verification test specifications defined | Complete | [[TST-001]] |
| All software requirements traced to test specifications | Complete | [[TRC-001]] Matrix 2 |
| Verification tests executed | **PENDING** | [[RPT-006]] — fill in results |
| All verification tests passed | **PENDING** | [[RPT-006]] — awaiting execution |
| Anomalies resolved or documented | **PENDING** | [[RPT-006]] — awaiting execution |

### 3.5 Validation

| Item | Status | Evidence |
|---|---|---|
| Validation activities defined | Complete | [[RPT-003]], [[TRC-001]] Matrix 4 |
| Validation executed | **PENDING** | [[RPT-003]] — execution pending |
| All validation activities passed | **PENDING** | *Awaiting validation execution* |
| Usability evaluation completed | Complete (formative + summative of hazard scenarios) | [[RPT-004]] |

### 3.6 Clinical and Regulatory

| Item | Status | Evidence |
|---|---|---|
| Clinical evaluation complete | Complete | [[CE-001]] |
| GSPR checklist complete | Complete | [[CHK-001]] |
| Labeling and IFU complete | Complete | [[LBL-001]] |
| Declaration of Conformity drafted | Complete (draft) | [[DOC-001]] |
| PMS plan defined | Complete | [[PLN-004]] |
| PMCF plan defined | Complete | [[PLN-003]] |
| Cybersecurity assessment complete | Complete | [[SOP-016]] |

### 3.7 Configuration and Deployment

| Item | Status | Evidence |
|---|---|---|
| `DEVICE_MODE=medical` configuration verified | **PENDING** | *To be verified before release* |
| Medical-specific translations activated | **PENDING** | *Translation files to be activated* |
| Git tag created (v1.0) | **PENDING** | *To be created at release* |
| Production deployment verified | **PENDING** | *To be verified at release* |

## 4. Known Issues at Release

| Issue | Severity | Justification for Release | Mitigation |
|---|---|---|---|
| No automated testing / CI/CD | Low | Compensating controls documented in SOP-011 (local testing, Telescope monitoring, rapid rollback) | Manual testing before significant changes |
| Database not encrypted at rest | Low | Compensating controls documented in SOP-016 (SSH-only, localhost-only DB, DPA, physical security) | Planned evaluation of MariaDB TDE |
| Session summaries in transactional emails | Low | Documented as H-012 in RA-001; accepted via benefit-risk analysis | Opportunistic TLS, EU-based infrastructure |
| Formal summative usability evaluation with external participants not yet completed | Low | Formative evaluation + hazard scenario evaluation documented in RPT-004; formal summative planned | Planned per PLN-006 Section 8 |

## 5. Release Decision

| Question | Answer |
|---|---|
| Are all requirements implemented and verified? | **PENDING** — verification test execution required |
| Are all validation activities completed? | **PENDING** — validation execution required |
| Is the risk management process complete? | Yes — [[RPT-002]] concludes overall residual risk is acceptable |
| Are all known issues acceptable for release? | Yes — all known issues have documented mitigations |
| Is the technical documentation complete? | Yes — all required documents are in place |

### Release Authorization

| Role | Name | Decision | Date |
|---|---|---|---|
| Release authority | Sarp Derinsu | **PENDING** | *To be completed* |

**Release is authorized when all PENDING items in Section 3 are resolved and the release authority signs off.**

## 6. Change History

| Version | Date | Description |
|---|---|---|
| 1.0 | 2026-04-01 | Initial release record — pre-release checklist defined, execution items pending |

## 7. References

- [[PLN-005]] Software Development Plan
- [[SOP-011]] Software Lifecycle Management Procedure
- [[SOP-014]] Product Identification and Traceability
- [[SPE-001]] Software Requirements Specification
- [[SPE-003]] Use Requirements Specification
- [[RA-001]] Risk Management File
- [[RPT-002]] Risk Management Report
- [[RPT-003]] Software Validation Report
- [[RPT-004]] Usability Engineering Summative Evaluation Report
- [[TST-001]] Software Verification Test Specifications
- [[TRC-001]] Software Traceability Matrix
- [[CHK-001]] GSPR Checklist
- [[DOC-001]] Declaration of Conformity
- [[CE-001]] Clinical Evaluation Report
- [[LBL-001]] Device Labeling
- IEC 62304:2006+AMD1:2015 Section 5.8
- IEC 82304-1:2016 Section 5.4
