---
id: "TRC-001"
title: "Software Traceability Matrix"
type: "TRC"
category: "technical"
version: "1.0"
status: "draft"
effective_date: "2026-04-01"
author: "Sarp Derinsu"
iso_refs:
  - "7.3.3"
  - "7.3.6"
mdr_refs:
  - "Annex II"
---

# Software Traceability Matrix

## 1. Purpose

This document provides the bidirectional traceability matrices for the Therapeak medical device software version 1.0, demonstrating that all use requirements are implemented, verified, and validated, and that all risk controls are traceable to their implementation and verification. This is required by EU MDR Annex II, IEC 62304, and IEC 82304-1.

**Related documents:** [[SPE-003]] Use Requirements Specification, [[SPE-001]] Software Requirements Specification, [[RA-001]] Risk Management File, [[TST-001]] Software Verification Test Specifications

## 2. Matrix 1: Use Requirements → Software Requirements

This matrix traces each use requirement to the software requirement(s) that implement it.

| Use Requirement | Description | Software Requirements | Coverage |
|---|---|---|---|
| UR-001 | AI conversational therapy sessions | FR-030 to FR-036, AI-001 to AI-004 | Full |
| UR-002 | Onboarding questionnaire | FR-010 to FR-013 | Full |
| UR-003 | Personalized AI therapist | FR-020 to FR-025 | Full |
| UR-004 | Timed therapy sessions | FR-030 to FR-033 | Full |
| UR-005 | Session continuity via summaries | FR-040, FR-041 | Full |
| UR-006 | Progress reports | FR-042 to FR-046 | Full |
| UR-007 | Mood tracking | FR-050 to FR-053 | Full |
| UR-008 | Multi-language support | PF-003 | Full |
| UR-009 | Subscription management | FR-060 to FR-063 | Full |
| UR-010 | Web-based access | UX-001 | Full |
| UR-011 | Accessibility | UX-002 | Full |
| UR-012 | Real-time AI responses | FR-036, PF-002 | Full |
| UR-013 | Disclaimers and transparency | UX-004, SF-002, RG-003 | Full |
| UR-014 | Self-service management | UX-003, UX-005, UX-006 | Full |
| UR-015 | AI model integration | AI-001 to AI-003, AI-007 | Full |
| UR-016 | Payment processing | FR-060 to FR-063 | Full |
| UR-017 | Email delivery | (Infrastructure — not software requirement) | Full (operational) |
| UR-018 | Image generation | FR-023 | Full |
| UR-019 | Crisis handling | SF-001 to SF-003 | Full |
| UR-020 | Age restriction | FR-014, FR-015 | Full |
| UR-021 | Role enforcement | SF-010 to SF-012 | Full |
| UR-022 | Session quality monitoring | SF-020 to SF-022, AI-006 | Full |
| UR-023 | Contraindication display | UX-004, SF-002 | Full |
| UR-024 | Content restrictions | SF-040, SF-041 | Full |
| UR-025 | No diagnosis/triage | FR-044, FR-045 | Full |
| UR-026 | Data encryption in transit | SC-001 | Full |
| UR-027 | Secure authentication | FR-001 to FR-003, SC-006 | Full |
| UR-028 | Health data protection | SC-003 | Full |
| UR-029 | Account deletion / GDPR erasure | SC-004, SC-005 | Full |
| UR-030 | Abuse prevention | SC-008 | Full |
| UR-031 | Administrative access control | SC-002, SC-006, SC-007 | Full |
| UR-032 | No third-party training | AI-007 | Full |
| UR-033 | No local installation | UX-001 | Full |
| UR-034 | Transparent updates | (Operational — server-side deployment) | Full (operational) |
| UR-035 | User decommissioning | SC-004, SC-005 | Full |
| UR-036 | AI service availability | PF-001, AI-001, AI-002, AI-003 | Full |
| UR-037 | Dual operating mode | RG-001 | Full |
| UR-038 | Medical mode terminology | RG-001 | Full |
| UR-039 | GDPR compliance | SC-003, SC-004, SC-005 | Full |
| UR-040 | Instructions for use | (Documentation — [[LBL-001]]) | Full (documentation) |
| UR-041 | Device labeling | RG-002, RG-003 | Full |

**Gap analysis:** All 41 use requirements are covered by at least one software requirement or operational/documentation control. No gaps identified.

## 3. Matrix 2: Software Requirements → Verification Test Specifications

This matrix traces each software requirement to its verification test specification.

| Software Requirement | Description | Test Spec | Verification Method |
|---|---|---|---|
| FR-001 | Email/social login registration | TS-001 | Test |
| FR-002 | Email verification (4-digit code) | TS-001 | Test |
| FR-003 | Authentication (Sanctum/Passport) | TS-001 | Test |
| FR-010 | Onboarding questionnaire (20 questions) | TS-002 | Test |
| FR-011 | Depression screening section | TS-002 | Test |
| FR-012 | Suicidal ideation item replacement | TS-002 | Test |
| FR-013 | Demographic/preference collection | TS-002 | Test |
| FR-014 | Age gate (≤18 blocked) | TS-003 | Test |
| FR-015 | Age dropdown (12-100) | TS-003 | Test |
| FR-020 | AI therapist generation | TS-004 | Test |
| FR-021 | 17 personality traits | TS-004 | Test |
| FR-022 | Name, bio, backstory, avatar | TS-004 | Test |
| FR-023 | Fal.ai avatar + fallback | TS-004 | Test |
| FR-024 | Therapist switching | TS-004 | Test |
| FR-025 | Conversation isolation on switch | TS-004 | Test |
| FR-030 | Timed sessions with minute deduction | TS-005 | Test |
| FR-031 | 30 min/day, 45 min max | TS-005 | Test |
| FR-032 | 10 free trial minutes | TS-005 | Test |
| FR-033 | AI session wrap-up | TS-005 | Test |
| FR-034 | Message queue dispatch | TS-005 | Test |
| FR-035 | System prompt construction | TS-005 | Inspection |
| FR-036 | Real-time WebSocket delivery | TS-005 | Test |
| FR-040 | Session summary generation | TS-006 | Test |
| FR-041 | Summary as session context | TS-006 | Inspection |
| FR-042 | User report generation | TS-006 | Test |
| FR-043 | Report sections | TS-006 | Inspection |
| FR-044 | "Not a medical document" disclaimer | TS-006 | Inspection |
| FR-045 | No medication advice | TS-006 | Inspection |
| FR-046 | PDF export | TS-006 | Test |
| FR-050 | Self-reported mood rating | TS-007 | Test |
| FR-051 | AI session mood rating | TS-007 | Test |
| FR-052 | Mood tracking toggle | TS-007 | Test |
| FR-053 | Mood graphs | TS-007 | Test |
| FR-060 | Stripe subscriptions | TS-008 | Test |
| FR-061 | Payment methods | TS-008 | Test |
| FR-062 | Additional minute purchases | TS-008 | Test |
| FR-063 | NL/TR checkout blocking | TS-008 | Test |
| PF-001 | 99.9% availability | TS-009 | Analysis |
| PF-002 | ≤30s response time | TS-010 | Test |
| PF-003 | 20+ locales | TS-011 | Test |
| PF-004 | Token limits (400/500) | TS-020 | Inspection |
| PF-005 | Concurrent user handling | TS-010 | Test |
| SF-001 | Crisis delegation to Claude | TS-012 | Test |
| SF-002 | Homepage emergency disclaimer | TS-012 | Inspection |
| SF-003 | Severe screening warning | TS-012 | Test |
| SF-010 | Role enforcement (10+ statements) | TS-013 | Inspection |
| SF-011 | 160-200+ static instructions | TS-013 | Inspection |
| SF-012 | No role-play/games/referrals | TS-013 | Test |
| SF-020 | SwitchedRoles detection | TS-014 | Test |
| SF-021 | DidNotRespond detection | TS-014 | Test |
| SF-022 | ChatDebugFlag recording | TS-014 | Test |
| SF-030 | Platform content moderation | TS-015 | Test |
| SF-031 | Prohibited content rejection | TS-015 | Test |
| SF-032 | Therapy exempt from moderation | TS-015 | Test |
| SF-040 | Relationship protection | TS-015 | Test |
| SF-041 | Conversational formatting only | TS-015 | Inspection |
| SC-001 | TLS encryption | TS-016 | Test |
| SC-002 | SSH access restriction | TS-016 | Inspection |
| SC-003 | DPA with processors | TS-016 | Review |
| SC-004 | Account deletion (soft + wipe) | TS-017 | Test |
| SC-005 | GDPR erasure within 30 days | TS-017 | Test |
| SC-006 | 2FA on infrastructure accounts | TS-016 | Inspection |
| SC-007 | Secrets not in git | TS-016 | Inspection |
| SC-008 | Anti-abuse measures | TS-018 | Test |
| AI-001 | Primary model + fallback chain | TS-019 | Test |
| AI-002 | OpenRouter gateway routing | TS-019 | Inspection |
| AI-003 | Retry logic (3x, 1s) | TS-019 | Test |
| AI-004 | Safety instruction sections | TS-020 | Inspection |
| AI-005 | GPT-4o for summaries/reports | TS-020 | Inspection |
| AI-006 | Quality check monitoring | TS-020 | Test |
| AI-007 | Data sharing disabled | TS-019 | Inspection |
| UX-001 | Browser compatibility | TS-021 | Test |
| UX-002 | ARIA labels | TS-022 | Inspection |
| UX-003 | FAQ popup | TS-022 | Test |
| UX-004 | Disclaimers at touchpoints | TS-022 | Inspection |
| UX-005 | Self-service features | TS-022 | Test |
| UX-006 | Support channels | TS-022 | Test |
| RG-001 | Dual mode switching | TS-023 | Test |
| RG-002 | Version 1.0 labeling | TS-023 | Inspection |
| RG-003 | AI profile disclaimer | TS-023 | Inspection |

**Gap analysis:** All 77 software requirements are covered by at least one test specification. No gaps identified.

## 4. Matrix 3: Risk Controls → Software Requirements → Verification

This matrix traces risk controls from [[RA-001]] to the software requirements that implement them and the test specifications that verify them.

| Risk Control | Hazard | Type | Software Req | Test Spec |
|---|---|---|---|---|
| C-001a | H-001 (AI validates self-harm) | Inherent safety | AI-004, SF-010, SF-011 | TS-013, TS-020 |
| C-001b | H-001 | Protection | SF-020 (ChatDebugFlags) | TS-014 |
| C-001c | H-001 | Information | UX-004 (disclaimers) | TS-022 |
| C-002a | H-002 (Inappropriate advice) | Inherent safety | AI-004, SF-040, SF-041 | TS-015, TS-020 |
| C-002b | H-002 | Information | FR-044, FR-045 (report disclaimers) | TS-006 |
| C-003a | H-003 (Role confusion) | Inherent safety | SF-010, SF-011 | TS-013 |
| C-003b | H-003 | Inherent safety | AI-004 (reasoning tokens) | TS-020 |
| C-003c | H-003 | Protection | SF-020 (SwitchedRoles flag) | TS-014 |
| C-004a | H-004 (Harmful advice) | Inherent safety | SF-040, FR-045 | TS-015, TS-006 |
| C-005a | H-005 (Diagnostic misinterpretation) | Information | FR-044 (not a diagnosis) | TS-006 |
| C-006a | H-006 (Over-dependency) | Inherent safety | FR-031 (daily limits) | TS-005 |
| C-006b | H-006 | Information | UX-004 (IFU disclaimers) | TS-022 |
| C-007a | H-007 (Crisis mishandling) | Inherent safety | SF-001 (Claude safety) | TS-012 |
| C-007b | H-007 | Information | UX-004 (contraindications) | TS-022 |
| C-007c | H-007 | Information | SF-002 (homepage emergency) | TS-012 |
| C-007d | H-007 | Information | SF-003 (severe screening warning) | TS-012 |
| C-007e | H-007 | Inherent safety | FR-012 (item 9 replacement) | TS-002 |
| C-008a | H-008 (Minor access) | Inherent safety | FR-014, FR-015 (age gate) | TS-003 |
| C-009a | H-009 (Data breach) | Protection | SC-001 (TLS) | TS-016 |
| C-009b | H-009 | Protection | SC-002 (SSH restriction) | TS-016 |
| C-009c | H-009 | Protection | SC-006 (2FA) | TS-016 |
| C-009d | H-009 | Protection | SC-007 (secrets management) | TS-016 |
| C-010a | H-010 (Model change) | Protection | AI-001 (fallback chain) | TS-019 |
| C-010b | H-010 | Protection | AI-003 (retry logic) | TS-019 |
| C-011a | H-011 (Prompt injection) | Inherent safety | SF-010, SF-011 (role enforcement) | TS-013 |
| C-011b | H-011 | Protection | SF-020 (monitoring) | TS-014 |
| C-012a | H-012 (Unencrypted email) | Protection | SC-001 (TLS) | TS-016 |
| C-013a | H-013 (Minor access via circumvention) | Inherent safety | FR-014 (age gate) | TS-003 |
| C-014a | H-014 (Service outage) | Protection | PF-001, AI-001, AI-002, AI-003 | TS-009, TS-019 |
| C-015a | H-015 (Toxic behavior) | Inherent safety | SF-040 (relationship protection) | TS-015 |

**Gap analysis:** All risk controls that require software implementation are traced to software requirements and verification test specifications. No gaps identified.

## 5. Matrix 4: Use Requirements → Validation Activities

This matrix traces use requirements to their validation activities. Validation confirms the product meets use requirements in the intended use environment (distinct from verification, which confirms software requirements are implemented correctly).

| Use Requirement | Description | Validation Activity | Validation Method |
|---|---|---|---|
| UR-001 | AI conversational therapy | VAL-001: Conduct representative therapy sessions; assess therapeutic quality and safety | User testing |
| UR-002 | Onboarding questionnaire | VAL-002: Complete onboarding as intended user; assess flow and completeness | User testing |
| UR-003 | Personalized AI therapist | VAL-003: Generate and interact with therapist; assess personalization quality | User testing |
| UR-004 | Timed therapy sessions | VAL-004: Conduct full sessions; assess time management and session closure | User testing |
| UR-005 | Session continuity | VAL-005: Conduct multiple sessions; assess context continuity | User testing |
| UR-006 | Progress reports | VAL-006: Generate reports; assess content quality and disclaimer presence | Review |
| UR-007 | Mood tracking | VAL-007: Use mood tracking over multiple sessions; assess visualization | User testing |
| UR-008 | Multi-language | VAL-008: Conduct sessions in 3+ languages; assess language quality | User testing |
| UR-009 | Subscription management | VAL-009: Complete purchase flow; manage subscription | User testing |
| UR-010 | Web-based access | VAL-010: Access from multiple browsers and devices | User testing |
| UR-011 | Accessibility | VAL-011: Navigate core workflows with assistive technology | Inspection |
| UR-012 | Real-time interaction | VAL-012: Measure perceived response time during sessions | User testing |
| UR-013 | Disclaimers | VAL-013: Inspect all disclaimer touchpoints | Inspection |
| UR-014 | Self-service | VAL-014: Exercise all self-service features | User testing |
| UR-015 | AI model integration | VAL-015: Verify AI responses during therapy | User testing |
| UR-016 | Payment processing | VAL-016: Complete payment with test cards | User testing |
| UR-017 | Email delivery | VAL-017: Verify transactional emails are received | User testing |
| UR-018 | Image generation | VAL-018: Verify avatar generation | User testing |
| UR-019 | Crisis handling | VAL-019: Test crisis scenarios; verify appropriate response | User testing |
| UR-020 | Age restriction | VAL-020: Attempt access with ages ≤18 and ≥19 | User testing |
| UR-021 | Role enforcement | VAL-021: Attempt adversarial prompts; verify deflection | User testing |
| UR-022 | Session quality monitoring | VAL-022: Review monitoring output after sessions | Review |
| UR-023 | Contraindication display | VAL-023: Inspect contraindication visibility at touchpoints | Inspection |
| UR-024 | Content restrictions | VAL-024: Test restricted content scenarios | User testing |
| UR-025 | No diagnosis/triage | VAL-025: Review reports for absence of diagnosis claims | Review |
| UR-026 | Data encryption | VAL-026: Verify HTTPS and TLS configuration | Analysis |
| UR-027 | Authentication | VAL-027: Verify login security features | User testing |
| UR-028 | Health data protection | VAL-028: Verify DPA status for all processors | Review |
| UR-029 | Account deletion | VAL-029: Execute deletion workflow; verify data removal | User testing |
| UR-030 | Abuse prevention | VAL-030: Test abuse prevention measures | User testing |
| UR-031 | Access control | VAL-031: Verify server/admin access restrictions | Inspection |
| UR-032 | No third-party training | VAL-032: Verify OpenRouter data sharing is off | Inspection |
| UR-033 | No local installation | VAL-033: Access product from fresh browser | User testing |
| UR-034 | Transparent updates | VAL-034: Deploy update; verify no user disruption | Analysis |
| UR-035 | Decommissioning | VAL-035: Execute account deletion and data purge | User testing |
| UR-036 | AI availability | VAL-036: Verify fallback behavior | Analysis |
| UR-037 | Dual operating mode | VAL-037: Toggle DEVICE_MODE; verify behavior change | User testing |
| UR-038 | Medical terminology | VAL-038: Verify medical-specific translations in medical mode | Inspection |
| UR-039 | GDPR compliance | VAL-039: Review data processing practices | Review |
| UR-040 | Instructions for use | VAL-040: Review IFU for completeness per MDR | Review |
| UR-041 | Device labeling | VAL-041: Review labeling for completeness per MDR | Review |

**Gap analysis:** All 41 use requirements have defined validation activities. No gaps identified.

## 6. Traceability Gap Summary

| Matrix | Items Traced | Gaps Found |
|---|---|---|
| UR → SW Requirements | 41 use requirements | 0 |
| SW Requirements → Test Specs | 77 software requirements | 0 |
| Risk Controls → SW Req → Test Specs | 30 risk controls | 0 |
| UR → Validation Activities | 41 use requirements | 0 |

All traceability chains are complete with no gaps identified.

## 7. Change History

| Version | Date | Description |
|---|---|---|
| 1.0 | 2026-04-01 | Initial release — 4 traceability matrices |

## 8. References

- [[SPE-003]] Use Requirements Specification
- [[SPE-001]] Software Requirements Specification
- [[RA-001]] Risk Management File
- [[TST-001]] Software Verification Test Specifications
- [[PLN-005]] Software Development Plan
- [[SOP-011]] Software Lifecycle Management Procedure
- IEC 62304:2006+AMD1:2015 — Software lifecycle processes
- IEC 82304-1:2016 — Health software product safety
- EU MDR 2017/745 Annex II — Technical Documentation
