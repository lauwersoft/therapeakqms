---
id: "RPT-002"
title: "Risk Management Report"
type: "RPT"
category: "technical"
version: "1.0"
status: "draft"
effective_date: "2026-04-01"
author: "Sarp Derinsu"
iso_refs:
  - "7.1"
mdr_refs:
  - "Annex I"
  - "Article 10(2)"
---

# Risk Management Report

## 1. Purpose

This report summarizes the results of the risk management process for the Therapeak medical device software version 1.0, as required by ISO 14971:2019 Clause 9. It provides evidence that the risk management plan ([[PLN-001]]) has been implemented, documents the completeness and effectiveness of risk control measures, and states the conclusion on overall residual risk acceptability.

**Related documents:** [[PLN-001]] Risk Management Plan, [[RA-001]] Risk Management File, [[SOP-002]] Risk Management Procedure

## 2. Device Identification

| Item | Detail |
|---|---|
| Device name | Therapeak |
| Manufacturer | Therapeak B.V. |
| Classification | Class IIa (MDR Annex VIII, Rule 11) |
| Software version | 1.0 |
| Intended purpose | Patient-specific supportive conversational guidance to help users self-manage mild to moderate mental health symptoms at home |
| Software safety classification | Class B (IEC 62304) |

## 3. Risk Management Plan Implementation

### 3.1 Plan Scope and Applicability

The risk management plan ([[PLN-001]]) was executed for software version 1.0. All lifecycle stages within scope (design, development, verification) have been completed. Post-market monitoring activities are defined but will commence upon market placement.

### 3.2 Risk Analysis Method

Risk analysis was performed using Failure Mode and Effects Analysis (FMEA) methodology as defined in [[PLN-001]] Section 4.2 and [[SOP-002]]. The analysis addressed:

- AI output quality and behavioral hazards
- System reliability and availability hazards
- Data privacy and security hazards
- User interaction and misuse hazards
- Infrastructure and third-party dependency hazards

### 3.3 Risk Estimation Framework

Risk was estimated using qualitative scales:

- **Severity:** S1 (Negligible) to S5 (Catastrophic)
- **Probability:** P1 (Rare, <1 in 100,000 sessions) to P5 (Frequent, >1 in 100 sessions)
- **Risk acceptability:** 3-tier (Acceptable, ALARP, Unacceptable) per risk matrix in [[PLN-001]]

For software failure modes, the probability of software failure was assumed to be 1 (per ISO 14971 guidance for software); risk estimation focused on the probability of the surrounding conditions and events that lead from the failure to harm.

### 3.4 Risk Control Priority

Risk controls were applied in priority order per EU MDR 2017/745 Annex I, Section 4:

1. Inherently safe design (prompt engineering, age gating, safety instructions)
2. Protective measures (automated monitoring, content moderation, crisis delegation)
3. Information for safety (disclaimers, IFU, contraindications, homepage messaging)

## 4. Risk Analysis Results Summary

### 4.1 Hazards Identified

15 hazards were identified across 5 categories:

| Category | Hazards | IDs |
|---|---|---|
| AI Output Quality and Behavioral | AI validates self-harm, inappropriate advice, role confusion, harmful advice | H-001, H-002, H-003, H-004 |
| Clinical Interpretation | Diagnostic misinterpretation, over-dependency | H-005, H-006 |
| Crisis and Safety | Crisis mishandling, minor access | H-007, H-008 |
| Data and Privacy | Data breach, API key compromise, unencrypted email content | H-009, H-010, H-012 |
| System and Technical | Model change degradation, prompt injection, service outage, toxic behavior reinforcement | H-010, H-011, H-014, H-015 |

### 4.2 Initial Risk Levels (Before Controls)

| Risk Level | Count | Hazard IDs |
|---|---|---|
| Unacceptable | 6 | H-001, H-003, H-007, H-008, H-009, H-011 |
| ALARP | 7 | H-002, H-004, H-005, H-006, H-010, H-012, H-013 |
| Acceptable | 2 | H-014, H-015 |

### 4.3 Risk Controls Implemented

A total of 55 risk control measures were defined and implemented. Distribution by type:

| Control Type | Count | Examples |
|---|---|---|
| Inherently safe design | 22 | System prompt safety instructions, age gate, crisis delegation to Claude, role enforcement |
| Protective measures | 18 | ChatDebugFlag monitoring, content moderation, retry/fallback mechanisms, DPA agreements |
| Information for safety | 15 | Homepage emergency disclaimer, report disclaimers, IFU contraindications, therapist profile disclaimers |

### 4.4 Residual Risk Levels (After Controls)

| Risk Level | Count | Hazard IDs |
|---|---|---|
| Unacceptable | 0 | -- |
| ALARP | 11 | H-001, H-002, H-005, H-006, H-007, H-008, H-010, H-011, H-012, H-013, H-015 |
| Acceptable | 4 | H-003, H-004, H-009, H-014 |

All initially unacceptable risks have been reduced to ALARP or Acceptable through the implementation of risk control measures.

## 5. Risk Control Verification

### 5.1 Verification of Implementation

Each risk control measure was verified as implemented through the method specified in [[RA-001]]:

| Verification Method | Controls Verified |
|---|---|
| Code review / Inspection | System prompt contents, age gate logic, API configuration, queue retry settings |
| UI review | Disclaimer text presence, emergency messaging, therapist profile disclaimers |
| Configuration review | DEVICE_MODE switching, OpenRouter data sharing settings, DPA status |
| Testing | Crisis scenario testing, role confusion detection, age gate blocking, SSL verification |
| Documentation review | IFU content, contraindications, DPA agreements |

### 5.2 Verification of Effectiveness

Risk control effectiveness is verified through:

- **Pre-market testing:** Representative conversation scenarios tested for crisis handling, role enforcement, content restrictions
- **Automated monitoring:** ChatDebugFlag system provides continuous effectiveness data for role confusion and non-response controls
- **Pre-market experience data:** The wellness version (same codebase, same AI models) has operated with no reported adverse events, no harm, and no safety-related complaints (documented in [[RPT-001]])

### 5.3 New Hazards from Risk Controls

The risk control measures were reviewed for potential introduction of new hazards. No new hazards were identified:

- Automated monitoring (ChatDebugFlags) operates on session transcripts after the session and does not interfere with therapy
- Content moderation explicitly excludes therapy conversations (SF-032) to avoid blocking legitimate therapeutic content
- Crisis delegation to Claude's built-in safety does not introduce latency or block non-crisis conversations

## 6. Completeness of Risk Management

### 6.1 Risk Management Plan Execution

| Plan Element | Status |
|---|---|
| Risk analysis activities performed per PLN-001 | Complete |
| All identified hazards documented in RA-001 | Complete |
| Risk evaluation performed per acceptability criteria | Complete |
| Risk controls defined for all unacceptable and ALARP risks | Complete |
| Risk control implementation verified | Complete |
| Risk control effectiveness verified | Complete (pre-market) |
| Residual risk evaluated for all hazards | Complete |
| Benefit-risk analysis for ALARP residual risks | Complete |
| Overall residual risk assessment | Complete |
| Post-market monitoring activities defined | Complete (will activate upon market placement) |

### 6.2 Traceability

Full traceability is maintained in [[RA-001]] between:
- Hazards and hazardous situations
- Hazardous situations and harms
- Hazardous sequences (hazard → hazardous situation → harm)
- Risk controls and the hazards they address
- Risk controls and their implementation verification
- Risk controls and their effectiveness verification
- Safety requirements in [[SPE-001]] and the risk controls they implement

### 6.3 Standards Compliance

| Standard | Relevant Clauses | Status |
|---|---|---|
| ISO 14971:2019 | Clauses 4-9 | Implemented |
| ISO/TR 24971:2020 | Guidance applied | Applied |
| IEC 62304:2006+AMD1 | Clause 7 (risk management in software) | Implemented |
| IEC 81001-5-1:2021 | Security risk management | Implemented (SOP-016) |
| EU MDR 2017/745 | Annex I, Sections 1-4 (GSPR on risk) | Implemented |

## 7. Benefit-Risk Analysis Summary

For each ALARP residual risk, a benefit-risk analysis was performed (documented in [[RA-001]] Section 7.2). The key findings:

- **Highest residual risks (S5/P1 ALARP):** H-001 (AI validates self-harm) and H-007 (crisis mishandling) — both have catastrophic potential severity but rare probability after controls. The clinical benefit of accessible mental health support for the target population outweighs these risks given: (a) the device is contraindicated for crisis use, (b) multiple independent safety layers exist, and (c) eliminating these risks would require removing the therapeutic function entirely.

- **Data breach risk (S4/P1 ALARP):** H-008 — critical severity but rare probability given security controls. The benefit of maintaining therapy data for continuity of care outweighs the risk.

- **All other ALARP risks (S3/P2):** Serious severity with unlikely probability. Benefits of accessible therapy, progress reports, and engagement features outweigh the respective risks, particularly for the target population (mild to moderate symptoms, home use).

**Conclusion:** For all ALARP residual risks, the medical benefits outweigh the residual risks.

## 8. Overall Residual Risk Conclusion

**The overall residual risk of the Therapeak medical device version 1.0 is ACCEPTABLE.**

This conclusion is based on:

1. **No unacceptable residual risks remain.** All 6 initially unacceptable risks have been reduced to ALARP or Acceptable.
2. **Benefit-risk balance is favorable for all ALARP risks.** Documented in [[RA-001]] Section 7.2.
3. **Pre-market experience data supports the risk assessment.** The wellness version (same codebase, same AI models, several hundred subscribers) has reported zero adverse events and zero safety-related complaints ([[RPT-001]]).
4. **Defense in depth is achieved.** Multiple independent safety layers (Anthropic model safety, prompt engineering, automated monitoring, user-facing information) provide robust protection.
5. **The target population (mild to moderate symptoms, home use) represents a lower-acuity use context** where the consequences of AI limitations are generally less severe than in acute or clinical settings.
6. **Post-market monitoring is planned.** [[SOP-009]], [[PLN-004]], and [[SOP-013]] define ongoing risk monitoring that will commence upon market placement.

## 9. Post-Market Risk Monitoring

Risk management is a continuous process. Upon market placement, the following activities will maintain the risk management file:

| Activity | Frequency | Reference |
|---|---|---|
| Session quality monitoring (ChatDebugFlags) | Continuous (automated) | [[SOP-009]] |
| Manual session review | Regular | [[SOP-009]] |
| Complaint analysis | Per complaint, trends quarterly | [[SOP-004]] |
| PMS reporting | Annually | [[SOP-009]], [[PLN-004]] |
| Vigilance reporting | Per event | [[SOP-013]] |
| Risk management file review | At least annually and before each release | [[PLN-001]] |
| Literature and EUDAMED monitoring | Quarterly | [[SOP-009]], [[PLN-004]] |

When post-market data indicates a new hazard or changed risk level, the risk management file will be updated per [[SOP-002]] and [[SOP-017]].

## 10. Approval

| Role | Name | Date |
|---|---|---|
| Author / Risk Manager | Sarp Derinsu | 2026-04-01 |

## 11. Change History

| Version | Date | Description |
|---|---|---|
| 1.0 | 2026-04-01 | Initial release for software version 1.0 |

## 12. References

- [[PLN-001]] Risk Management Plan
- [[RA-001]] Risk Management File
- [[SOP-002]] Risk Management Procedure
- [[RPT-001]] Post-Market Surveillance Report
- [[SPE-001]] Software Requirements Specification
- [[SOP-004]] Complaint Handling Procedure
- [[SOP-009]] Post-Market Surveillance Procedure
- [[SOP-013]] Vigilance and Field Safety Procedure
- [[SOP-016]] Cybersecurity Management Procedure
- [[SOP-017]] Change Management Procedure
- [[PLN-004]] PMS Plan
- ISO 14971:2019 — Medical devices — Application of risk management
- ISO/TR 24971:2020 — Guidance on the application of ISO 14971
- IEC 62304:2006+AMD1:2015 — Medical device software — Software lifecycle processes
- EU MDR 2017/745 Annex I — General Safety and Performance Requirements
