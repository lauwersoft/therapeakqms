---
id: "RPT-004"
title: "Usability Engineering Summative Evaluation Report"
type: "RPT"
category: "technical"
version: "1.0"
status: "draft"
effective_date: "2026-04-01"
author: "Sarp Derinsu"
iso_refs:
  - "7.3.7"
mdr_refs:
  - "Annex I, Section 5"
---

# Usability Engineering Summative Evaluation Report

## 1. Purpose

This report documents the results of usability evaluation activities for the Therapeak medical device software version 1.0. It covers both the formative evaluation conducted during the wellness product operation and the summative evaluation of hazard-related use scenarios.

**Related documents:** [[PLN-006]] Usability Engineering Plan, [[SPE-003]] Use Requirements Specification, [[RA-001]] Risk Management File

**Applicable standards:**
- IEC 62366-1:2015 — Application of usability engineering to medical devices
- IEC 62366-2:2016 — Guidance on the application of usability engineering (informative)

## 2. Device Under Evaluation

| Item | Detail |
|---|---|
| Device name | Therapeak |
| Software version | 1.0 |
| Configuration | `DEVICE_MODE=medical` (evaluated in medical mode where applicable; formative data from wellness mode) |
| Intended users | Adults aged 19+ with mild to moderate mental health symptoms |
| Use environment | Home use, unsupervised, via web browser on desktop or mobile |

## 3. Formative Evaluation Summary

### 3.1 Data Sources

Formative evaluation data was collected from the wellness version of Therapeak (same codebase, same AI models, same user interface). This data represents pre-market experience that informs the usability assessment for the medical device version.

| Source | Period | Volume |
|---|---|---|
| User contact messages (info@therapeak.com + in-app contact form) | Ongoing since product launch | ~1-2 messages per day |
| Trustpilot reviews | Ongoing since product launch | Multiple reviews |
| Support interactions | Ongoing | Continuous |
| Session observations (manual review) | Ongoing | 1-2 sessions per week |

### 3.2 Key Formative Findings

| Finding | Source | Action Taken | Status |
|---|---|---|---|
| Screen reader could not activate the "Send" button in therapy chat | User with visual impairment (German) via contact form | Added ARIA label to the send button | Resolved |
| Users frequently contacted support to ask how to cancel subscription | Multiple support messages | Implemented FAQ popup on contact page that proactively answers cancellation and billing questions | Resolved — contact volume significantly reduced |
| Users occasionally confused by therapy session ending when time runs out | Support messages | Session timer is visible; AI wraps up session near the end; no further action needed | Monitored |

### 3.3 Formative Evaluation Conclusions

- **No safety-related usability issues were identified** during the entire wellness product operation period
- **Contact patterns are dominated by feature requests and billing questions**, not usability difficulties
- **The FAQ popup intervention successfully reduced repetitive support contacts**, demonstrating that common usability friction points can be addressed through proactive information design
- **Accessibility was validated by a real user** with visual impairment using voice control — the issue was promptly resolved

## 4. Hazard-Related Use Scenario Evaluation

The following hazard-related use scenarios were identified in [[PLN-006]] Section 5. Each was evaluated for the medical device version.

### 4.1 User Misinterprets AI Output as Professional Diagnosis

| Aspect | Detail |
|---|---|
| Scenario | User reads session report or user report and interprets clinical-style language as formal diagnosis |
| Severity | Serious (S3) |
| Evaluation method | Inspection of report content and disclaimer visibility |
| Findings | Reports contain "this is not a medical document" and "not a diagnosis" disclaimers. Reports never advise about medication. Disclaimers are present in the report body text, not hidden in footnotes. |
| Result | **PASS** — Disclaimers are prominent and present in every generated report |
| Residual risk | Acceptable with current mitigations. Users may still misinterpret despite disclaimers, but this is inherent to any health information tool and mitigated by the explicit language. |

### 4.2 User Unable to Find Crisis Resources

| Aspect | Detail |
|---|---|
| Scenario | User experiencing crisis cannot locate emergency resources |
| Severity | Critical (S4) to Catastrophic (S5) |
| Evaluation method | Inspection of homepage, chat interface, and onboarding flow |
| Findings | Homepage displays emergency messaging prominently. Device is contraindicated for crisis use (stated in IFU and on homepage). Claude's built-in safety handles crisis detection within conversation by directing users to emergency services. |
| Result | **PASS** — Crisis resources visible on homepage without scrolling; Claude safety handles in-conversation crises |
| Residual risk | ALARP. Cannot guarantee all crisis users will see homepage messaging before entering chat. Claude's safety layer provides secondary protection. |

### 4.3 User Unable to End Session

| Aspect | Detail |
|---|---|
| Scenario | User wants to end a distressing therapy session but cannot |
| Severity | Minor (S2) to Serious (S3) |
| Evaluation method | User testing — attempted to end session via available controls |
| Findings | User can navigate away from the chat page at any time (browser back, close tab, navigate to another page). Session has a timer that ends automatically. |
| Result | **PASS** — Multiple methods available to end a session |
| Residual risk | Acceptable. Users may not immediately realize they can navigate away, but this is standard web application behavior. |

### 4.4 Minor Provides False Age to Bypass Age Gate

| Aspect | Detail |
|---|---|
| Scenario | User under 19 enters false age to access platform |
| Severity | Serious (S3) |
| Evaluation method | User testing — attempted onboarding with ages 17, 18, 19 |
| Findings | Ages ≤18 are blocked from trial and payment. Age 18 is included in the block as a buffer. Dropdown displays ages 12-100. Blocking message is shown immediately upon selection. |
| Result | **PASS** — Age gate functions correctly; blocking message is clear |
| Residual risk | ALARP. Determined minors can lie about their age, but this is inherent to any online age verification without ID checks. The 18-as-buffer approach provides proportionate protection. |

### 4.5 User Confused by Cancellation Process

| Aspect | Detail |
|---|---|
| Scenario | User cannot find or complete subscription cancellation |
| Severity | Minor (S2) |
| Evaluation method | Inspection of contact page and FAQ popup |
| Findings | FAQ popup proactively answers cancellation questions before user submits a contact message. Contact volume was significantly reduced after FAQ implementation. |
| Result | **PASS** — Cancellation process is adequately supported |
| Residual risk | Acceptable. Some users may still need to contact support, but the FAQ addresses the most common scenario. |

## 5. Summative Evaluation Summary

| Scenario | Severity | Result | Residual Risk |
|---|---|---|---|
| Misinterprets AI output as diagnosis | S3 | PASS | Acceptable |
| Unable to find crisis resources | S4-S5 | PASS | ALARP |
| Unable to end session | S2-S3 | PASS | Acceptable |
| Minor bypasses age gate | S3 | PASS | ALARP |
| Confused by cancellation | S2 | PASS | Acceptable |

**All hazard-related use scenarios evaluated as PASS.** No residual use risks are unacceptable.

## 6. Known Limitations

1. **Formal task-based summative evaluation with representative external participants has not yet been conducted.** The evaluation in this report is based on inspection, developer testing, and formative data from real users of the wellness version. A formal summative evaluation with 5-8 representative users is planned per [[PLN-006]] Section 8.2, to be completed before or shortly after market placement.

2. **Independence limitation.** As a single-person organization, the evaluation was performed by the developer (Sarp Derinsu). Compensating measures: structured evaluation criteria, pre-defined pass/fail criteria, and documented results.

3. **Medical mode translations are not yet active.** The evaluation was conducted with the existing UI. When `DEVICE_MODE=medical` translations are activated, a confirmatory review should verify that medical-specific terminology does not introduce usability issues.

## 7. Conclusion

Based on the formative evaluation data from the wellness product and the summative evaluation of hazard-related use scenarios:

1. **No safety-related usability issues have been identified** during the entire period of wellness product operation
2. **All hazard-related use scenarios pass** their evaluation criteria
3. **Known use problems have been resolved** (accessibility, cancellation confusion)
4. **Residual use risks are acceptable or ALARP** with documented justification

The usability evaluation supports the conclusion that Therapeak version 1.0 can be used safely and effectively by the intended user population in the intended use environment.

**Planned follow-up:** Formal task-based summative evaluation with representative users per [[PLN-006]] Section 8.2.

## 8. Approval

| Role | Name | Date |
|---|---|---|
| Author / Usability evaluator | Sarp Derinsu | 2026-04-01 |

## 9. Change History

| Version | Date | Description |
|---|---|---|
| 1.0 | 2026-04-01 | Initial release |

## 10. References

- [[PLN-006]] Usability Engineering Plan
- [[SPE-003]] Use Requirements Specification
- [[SPE-002]] Product Specification
- [[RA-001]] Risk Management File
- [[RPT-002]] Risk Management Report
- IEC 62366-1:2015 — Application of usability engineering to medical devices
- EU MDR 2017/745 Annex I, Section 5
