---
id: "SPE-003"
title: "Use Requirements Specification"
type: "SPE"
category: "technical"
version: "1.0"
status: "draft"
effective_date: "2026-04-01"
author: "Sarp Derinsu"
iso_refs:
  - "7.3.3"
mdr_refs:
  - "Annex I, 17.1"
  - "Annex II"
---

# Use Requirements Specification

## 1. Purpose

This document defines the product-level use requirements for the Therapeak medical device software (version 1.0). Use requirements describe WHAT the product must do from the perspective of users, regulators, and the operating environment, independent of implementation details.

These use requirements are the top-level requirements from which all software requirements ([[SPE-001]]) are derived. They serve as the basis for software validation per IEC 82304-1 Section 6.

**Related documents:** [[SPE-001]] Software Requirements Specification, [[PLN-005]] Software Development Plan, [[PLN-006]] Usability Engineering Plan, [[RA-001]] Risk Management File

**Applicable standards:**
- IEC 82304-1:2016 Section 4.2 — Use requirements
- IEC 62304:2006+AMD1:2015 — Software lifecycle processes
- IEC 62366-1:2015 — Usability engineering
- EU MDR 2017/745 Annex I — General Safety and Performance Requirements

## 2. Scope

This specification covers the Therapeak AI therapy platform in its medical device configuration (`DEVICE_MODE=medical`). Use requirements are organized per IEC 82304-1 Section 4.2 categories:

1. Intended purpose and functionality
2. User interface
3. External interfaces
4. Safety
5. Security and data privacy
6. Installation, operation, and lifecycle
7. Regulatory and compliance
8. Labeling and accompanying documentation

## 3. Intended Purpose and Functionality

| ID | Requirement | Source |
|---|---|---|
| UR-001 | The product shall provide AI-based conversational therapy sessions to support users in self-managing mild to moderate mental health symptoms at home. | Intended purpose |
| UR-002 | The product shall collect user demographic, clinical, and preference information through a structured onboarding questionnaire to personalize the therapeutic experience. | Intended purpose |
| UR-003 | The product shall provide personalized AI therapist profiles matched to user needs and preferences based on onboarding questionnaire responses. | Intended purpose |
| UR-004 | The product shall provide timed, text-based therapy sessions with defined daily session limits. | Intended purpose |
| UR-005 | The product shall maintain therapeutic continuity across sessions through automated session summaries that carry context forward. | Intended purpose |
| UR-006 | The product shall generate structured progress reports to support user self-management, clearly stating that reports are not medical documents. | Intended purpose |
| UR-007 | The product shall enable users to track and visualize their mood over time using self-reported and AI-assessed ratings. | Intended purpose |
| UR-008 | The product shall support therapy in multiple languages (20+ locales). | Intended purpose / market |
| UR-009 | The product shall manage user subscriptions with localized pricing and additional session minute purchases. | Business / operational |

## 4. User Interface

| ID | Requirement | Source |
|---|---|---|
| UR-010 | The product shall be accessible as a web application via modern browsers (Chrome, Firefox, Edge, Safari) on desktop and mobile devices without requiring local software installation. | Use environment / IEC 82304-1 |
| UR-011 | The product shall provide accessible interfaces supporting screen readers and assistive technologies (ARIA labels on interactive elements). | IEC 62366-1 / MDR Annex I 22.1 |
| UR-012 | The product shall deliver AI therapist responses in real time during therapy sessions. | Intended purpose / usability |
| UR-013 | The product shall display clear disclaimers about the nature, limitations, and contraindications of the product at relevant touchpoints (homepage, therapist profiles, reports). | MDR Annex I 23.1 |
| UR-014 | The product shall enable users to independently manage their account, subscription, therapist preferences, mood tracking, and data. | Usability / GDPR |

## 5. External Interfaces

| ID | Requirement | Source |
|---|---|---|
| UR-015 | The product shall interface with an AI model provider (Anthropic Claude via OpenRouter API gateway) for therapy conversation generation, with infrastructure-level redundancy. | Technical architecture |
| UR-016 | The product shall interface with a payment processor (Stripe) for subscription management and payment handling, without storing payment card data locally. | Business / PCI DSS |
| UR-017 | The product shall interface with an email delivery service (AWS SES, EU region) for transactional emails including session summaries. | Business / operational |
| UR-018 | The product shall interface with an AI image generation service (Fal.ai) for therapist avatar creation, with pre-generated avatars as fallback. | Technical architecture (non-critical) |

## 6. Safety

| ID | Requirement | Source |
|---|---|---|
| UR-019 | The product shall direct users in crisis situations to emergency services and crisis helplines. The product is contraindicated for crisis or emergency use. | [[RA-001]] H-007 / MDR Annex I 1 |
| UR-020 | The product shall restrict platform access for users under the age of 19 to protect minors from unsupervised AI therapy. | [[RA-001]] H-008 / Contraindication |
| UR-021 | The product shall enforce AI therapeutic role boundaries to prevent the AI from deviating from its therapist role (e.g., responding as the patient). | [[RA-001]] H-003 |
| UR-022 | The product shall automatically monitor therapy sessions for safety-relevant quality issues. | [[RA-001]] / [[SOP-009]] |
| UR-023 | The product shall clearly communicate contraindications, including: not for crisis use, not for users under 19, not for severe mental illness, not a replacement for professional treatment. | MDR Annex I 23.1 / [[LBL-001]] |
| UR-024 | The product shall enforce content restrictions to prevent harmful therapeutic output (e.g., medication advice, encouraging relationship termination, labeling individuals). | [[RA-001]] H-002 / H-004 |
| UR-025 | The product shall not diagnose, triage, or select treatments. Reports and summaries shall include explicit disclaimers. | Intended purpose boundaries |

## 7. Security and Data Privacy

| ID | Requirement | Source |
|---|---|---|
| UR-026 | The product shall encrypt all data in transit using TLS. | MDR Annex I 17.2 / GDPR Art. 32 |
| UR-027 | The product shall provide secure user authentication with email verification. | IEC 81001-5-1 / MDR Annex I 17.2 |
| UR-028 | The product shall protect user health data in compliance with GDPR, with data processing agreements maintained for all processors handling health data. | GDPR Art. 28 / MDR Annex I 17.2 |
| UR-029 | The product shall support user-initiated account deletion and GDPR erasure requests with complete data removal per defined retention periods. | GDPR Art. 17 |
| UR-030 | The product shall implement measures to prevent platform abuse (temporary email blocking, rate limiting, IP/country blocking, user banning). | Operational / [[SOP-016]] |
| UR-031 | The product shall restrict production server and administrative access to authorized personnel only. | IEC 81001-5-1 / MDR Annex I 17.2 |
| UR-032 | The product shall prevent therapy conversation data from being used for third-party AI model training. | Data privacy / DPA terms |

## 8. Installation, Operation, and Lifecycle

| ID | Requirement | Source |
|---|---|---|
| UR-033 | The product shall operate as a SaaS web application requiring no local software installation by the user. | Use environment / IEC 82304-1 4.2 |
| UR-034 | Software updates shall be deployed server-side without requiring user action. Active therapy sessions shall not be disrupted by deployments. | IEC 82304-1 Section 8 |
| UR-035 | The product shall support user-initiated account decommissioning with data retention and deletion per the defined retention policy (soft delete immediately, permanent wipe after 180 days). | IEC 82304-1 / GDPR |
| UR-036 | The product shall maintain AI therapy service availability through infrastructure redundancy (multi-provider routing) and AI model fallback mechanisms. | Performance / operational |

## 9. Regulatory and Compliance

| ID | Requirement | Source |
|---|---|---|
| UR-037 | The product shall operate in two modes (wellness and medical device) controlled by a configuration setting (`DEVICE_MODE`), to separate the CE-marked medical device from the wellness product within a single codebase. | EU MDR 2017/745 |
| UR-038 | The medical device mode shall use medical-specific terminology and labeling from dedicated translation files. | EU MDR 2017/745 / IFU |
| UR-039 | The product shall comply with GDPR requirements for processing special category data (health data, Article 9). | GDPR |

## 10. Labeling and Accompanying Documentation

| ID | Requirement | Source |
|---|---|---|
| UR-040 | The product shall provide electronic Instructions for Use (eIFU) accessible within the application per EU MDR Annex I Section 23 and IEC 82304-1 Section 7.2.2. | EU MDR Annex I 23 / IEC 82304-1 7.2 |
| UR-041 | The product shall display device labeling information (manufacturer, intended purpose, version, UDI, contraindications) within the application per EU MDR requirements. | EU MDR Annex I 23 / [[SOP-014]] |

## 11. Traceability

Each use requirement traces forward to:
- **Software requirements** in [[SPE-001]] (implementation)
- **Validation activities** in the Software Validation Report (confirmation that the product meets use requirements)

The complete traceability is maintained in the Software Traceability Matrix.

| Use Requirement | Software Requirements | Validation |
|---|---|---|
| UR-001 | FR-030 to FR-036, AI-001 to AI-004 | VAL-001 |
| UR-002 | FR-010 to FR-013 | VAL-002 |
| UR-003 | FR-020 to FR-025 | VAL-003 |
| UR-004 | FR-030 to FR-033 | VAL-004 |
| UR-005 | FR-040, FR-041 | VAL-005 |
| UR-006 | FR-042 to FR-046 | VAL-006 |
| UR-007 | FR-050 to FR-053 | VAL-007 |
| UR-008 | PF-003 | VAL-008 |
| UR-009 | FR-060 to FR-063 | VAL-009 |
| UR-010 | UX-001 | VAL-010 |
| UR-011 | UX-002 | VAL-011 |
| UR-012 | FR-036, PF-002 | VAL-012 |
| UR-013 | UX-004, SF-003 | VAL-013 |
| UR-014 | UX-003, UX-005, UX-006 | VAL-014 |
| UR-015 | AI-001 to AI-003, AI-007 | VAL-015 |
| UR-016 | FR-060 to FR-063 | VAL-016 |
| UR-017 | -- (infrastructure) | VAL-017 |
| UR-018 | FR-023 | VAL-018 |
| UR-019 | SF-001 to SF-003 | VAL-019 |
| UR-020 | FR-014, FR-015 | VAL-020 |
| UR-021 | SF-010 to SF-012 | VAL-021 |
| UR-022 | SF-020 to SF-022 | VAL-022 |
| UR-023 | UX-004, SF-002 | VAL-023 |
| UR-024 | SF-040, SF-041 | VAL-024 |
| UR-025 | FR-044, FR-045 | VAL-025 |
| UR-026 | SC-001 | VAL-026 |
| UR-027 | FR-001 to FR-003, SC-006 | VAL-027 |
| UR-028 | SC-003 | VAL-028 |
| UR-029 | SC-004, SC-005 | VAL-029 |
| UR-030 | SC-008 | VAL-030 |
| UR-031 | SC-002, SC-006 | VAL-031 |
| UR-032 | AI-007 | VAL-032 |
| UR-033 | UX-001 | VAL-033 |
| UR-034 | -- (operational) | VAL-034 |
| UR-035 | SC-004, SC-005 | VAL-035 |
| UR-036 | PF-001, AI-001, AI-002 | VAL-036 |
| UR-037 | RG-001 | VAL-037 |
| UR-038 | RG-001 | VAL-038 |
| UR-039 | SC-003, SC-004, SC-005 | VAL-039 |
| UR-040 | -- (documentation) | VAL-040 |
| UR-041 | RG-002, RG-003 | VAL-041 |

## 12. Requirements Review

| Criterion | Status |
|---|---|
| Completeness: all use requirement categories per IEC 82304-1 Section 4.2 are addressed | Confirmed |
| Accuracy: requirements reflect the intended purpose and use environment | Confirmed |
| Feasibility: requirements are technically achievable within the current architecture | Confirmed |
| Verifiability: each requirement can be validated through testing, analysis, or inspection | Confirmed |
| Consistency: no contradictions between requirements | Confirmed |
| Traceability: all requirements trace forward to software requirements and/or validation | Confirmed |

| Role | Name | Date |
|---|---|---|
| Author / Reviewer | Sarp Derinsu | 2026-04-01 |

## 13. Change History

| Version | Date | Description |
|---|---|---|
| 1.0 | 2026-04-01 | Initial release |

## 14. References

- [[SPE-001]] Software Requirements Specification
- [[SPE-002]] Product Specification
- [[PLN-005]] Software Development Plan
- [[PLN-006]] Usability Engineering Plan
- [[RA-001]] Risk Management File
- [[LBL-001]] Device Labeling
- [[SOP-014]] Product Identification and Traceability
- IEC 82304-1:2016 — Health software — Product safety
- IEC 62304:2006+AMD1:2015 — Medical device software — Software lifecycle processes
- IEC 62366-1:2015 — Medical devices — Usability engineering
- IEC 81001-5-1:2021 — Health software — Security
- EU MDR 2017/745 Annex I — General Safety and Performance Requirements
- EU MDR 2017/745 Annex II — Technical Documentation
