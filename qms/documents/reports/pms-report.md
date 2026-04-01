---
id: "RPT-001"
title: "Post-Market Surveillance Report"
type: "RPT"
category: "technical"
version: "1.0"
status: "approved"
effective_date: "2026-03-01"
author: "Sarp Derinsu"
iso_refs:
  - "8.2.1"
mdr_refs:
  - "Article 85"
---

# Post-Market Surveillance Report

## 1. Purpose

This is the initial Post-Market Surveillance (PMS) Report for the Therapeak medical device, prepared in accordance with EU MDR 2017/745 Article 85 and ISO 13485:2016 Clause 8.2.1. As a Class IIa device, Therapeak is required to maintain a PMS Report (updated at least annually) rather than a Periodic Safety Update Report (PSUR).

**Related documents:** [[SOP-009]] PMS Procedure, [[PLN-004]] PMS Plan, [[PLN-003]] PMCF Plan, [[SOP-013]] Vigilance Procedure

## 2. Device Identification

| Item | Detail |
|------|--------|
| Device name | Therapeak |
| Manufacturer | Therapeak B.V. |
| Classification | Class IIa (MDR Annex VIII, Rule 11) |
| Intended purpose | Patient-specific supportive conversational guidance to help users self-manage mild to moderate mental health symptoms at home |
| Target population | Adults (19+) with mild to moderate mental health conditions |
| EMDN code | V92 |
| MDA code | MDA 0315 |
| Software version | 1.0 (to be assigned upon CE-marked release) |

## 3. Report Scope and Period

| Item | Detail |
|------|--------|
| Report type | Initial PMS Report (pre-market baseline) |
| Reporting period | Pre-market experience data up to 2026-03-01 |
| Market status | Not yet on the EU market as a medical device |
| Next report due | Within 1 year of placing the medical device on the market |

The Therapeak medical device has not yet been placed on the market. This initial PMS Report documents the baseline data derived from pre-market experience with the wellness version of the software. The wellness version operates on the same codebase and uses the same AI models, user interface, and infrastructure. It provides relevant pre-market experience data that establishes the safety and performance baseline for post-market monitoring.

## 4. Pre-Market Experience: Wellness Version

### 4.1 Overview

The wellness version of Therapeak has been operating commercially prior to CE marking. While it is not a medical device, it provides meaningful pre-market experience data because the medical device will be built on the same codebase with the same core AI therapy functionality. This data is classified as pre-market experience from the wellness version and cannot be considered post-market clinical data for the medical device.

| Metric | Value |
|--------|-------|
| Approximate subscribers | A few hundred |
| First-month user retention | 65–75% |
| Reported serious adverse events | None |
| Reported harm | None |
| Contact volume | Approximately 1–2 messages per day |
| Complaint nature | Predominantly feature requests, not safety-related |

### 4.2 Complaint and Feedback Analysis

#### 4.2.1 Contact Messages

User contact messages are received via info@therapeak.com and the in-app contact form. Sarp Derinsu personally handles all support, typically responding within 5–10 minutes and never exceeding 24 hours.

The introduction of an FAQ popup on the contact page significantly reduced contact volume by addressing common questions (subscription cancellation, billing, account management) before users need to submit a message. Current contact volume is approximately 1–2 messages per day.

The vast majority of contacts are feature requests, billing inquiries, or general feedback. No contacts have reported harm, adverse events, or safety concerns attributable to the AI therapy sessions.

Complaints requiring technical fixes are labelled "Needs-fix" in email and addressed during the next development cycle.

#### 4.2.2 Trustpilot Reviews

Trustpilot is used as a user feedback source. Review request emails are sent to users automatically. Negative reviews are actively addressed by Sarp, who contacts users personally to resolve issues. No Trustpilot reviews have reported harm, adverse events, or safety concerns related to the AI therapy output.

#### 4.2.3 Complaint Categorization

| Category | Approximate Volume | Safety Relevance |
|----------|-------------------|-----------------|
| Feature requests | Majority of contacts | None |
| Billing / subscription inquiries | Common | None |
| Usability issues | Occasional | None directly; addressed iteratively |
| AI response quality | Rare | Monitored — see Section 5 |
| Reported harm or adverse events | None | N/A |

### 4.3 Refund and Cancellation Data

Therapeak operates a lenient refund policy. Refunds are processed via Stripe when requested and are sometimes offered proactively to dissatisfied users. Refund requests have not been associated with safety concerns. Only one user has been banned from the platform (for chargeback abuse), and this was unrelated to device safety.

## 5. Session Quality Monitoring

### 5.1 Automated Quality Flags

Two automated monitoring jobs analyze therapy session quality using GPT-4o:

| Flag | Description | Detection Method |
|------|-------------|-----------------|
| FLAG_SWITCHED_ROLES | AI responded as the patient instead of the therapist | GPT-4o transcript analysis (CheckSessionForSwitchedRolesJob) |
| FLAG_DID_NOT_RESPOND | AI failed to respond, with user explicitly asking "hello?" or "are you there?" after >30 seconds | GPT-4o transcript analysis (CheckSessionForDidNotRespondJob) |

These flags are recorded automatically in the database and provide quantitative session quality data.

### 5.2 Manual Session Review

Sarp Derinsu personally reviews 1–2 therapy sessions per week for harmful patterns. This manual review supplements the automated quality flags and provides qualitative assessment of AI output quality.

### 5.3 Session Quality Findings

No systematic safety issues have been identified through either automated flags or manual session review. The automated flags capture isolated incidents of role confusion and non-response, which are addressed through prompt engineering improvements.

## 6. Known Issues from Wellness Version

The following issues have been identified during the operation of the wellness version. These are tracked and addressed through iterative development:

### 6.1 AI Role Confusion

**Description:** In rare instances, the AI model responds as if it were the patient rather than the therapist (detected by FLAG_SWITCHED_ROLES).

**Severity:** Low. The issue is disorienting but not harmful. The user can send another message to redirect the conversation.

**Mitigation:** Role enforcement instructions are repeated extensively in every conversation job (10+ reinforcements per system prompt). The issue has been further reduced with the introduction of reasoning tokens, which give the model more capacity to maintain consistent role adherence.

### 6.2 Repetitive Responses

**Description:** The AI occasionally produces repetitive or formulaic responses, particularly in longer sessions or when the user's messages are brief.

**Severity:** Low. The issue affects user experience and perceived quality but does not pose a safety risk.

**Mitigation:** Improved significantly with the introduction of reasoning tokens (extended thinking). Ongoing prompt refinement based on session review feedback.

### 6.3 Accessibility

**Description:** One user with visual impairment using voice control reported that the "send" button was not accessible for screen readers. Sarp added the appropriate label and the user confirmed resolution.

**Severity:** Low for the general population. Addressed promptly when reported.

**Mitigation:** Resolved. Accessibility will continue to be improved based on user feedback.

## 7. Adverse Event and Vigilance Summary

| Category | Count |
|----------|-------|
| Serious adverse events reported | 0 |
| Non-serious adverse events reported | 0 |
| Deaths attributable to the device | 0 |
| Serious deterioration in health attributable to the device | 0 |
| Field safety corrective actions issued | 0 |
| Vigilance reports submitted to competent authorities | 0 |

No adverse events have been reported during the entire period of wellness version operation.

## 8. Literature and Equivalent Device Review

A systematic review of publicly available literature and regulatory databases for similar AI-based mental health software devices has not identified any field safety corrective actions or vigilance reports that would change the benefit-risk assessment for Therapeak. This review will be conducted on at least a quarterly basis once the medical device is on the market, in accordance with [[SOP-009]] and [[PLN-004]].

## 9. Risk Management Input

The pre-market experience data from the wellness version supports the risk assessment documented in the risk management file. Specifically:

- No new hazards have been identified beyond those already documented in the risk assessment
- The known issues (role confusion, repetitive responses) are consistent with the identified risks and have existing mitigations
- The absence of adverse events is consistent with the residual risk assessment

No updates to the risk management file are required based on this initial PMS data.

## 10. Conclusion

Based on the pre-market experience data from the wellness version of Therapeak:

1. **No safety concerns have been identified.** There have been zero reported adverse events, zero reported instances of harm, and zero vigilance-reportable incidents.
2. **Known issues are non-safety-related.** AI role confusion and repetitive responses are quality issues that have been improved through prompt engineering and reasoning tokens. They do not pose a risk to patient safety.
3. **Complaint patterns are benign.** The majority of user contacts are feature requests, not safety complaints. Contact volume is low (1–2 per day) relative to the subscriber base.
4. **Pre-market baseline is established.** Session quality flag rates, complaint patterns, retention metrics, and user feedback from the wellness version establish the baseline for post-market trend analysis.
5. **The benefit-risk assessment remains favorable.** No data from pre-market experience contradicts the clinical evaluation or risk assessment conclusions.

## 11. Actions and Next Steps

| Action | Timeline |
|--------|----------|
| Continue monitoring all PMS data sources per [[SOP-009]] and [[PLN-004]] | Ongoing |
| Update this PMS Report within 1 year of placing the medical device on the market | Within 1 year of CE marking |
| Implement FLAG_CRISIS automated monitoring (recommended but not yet implemented) | Before or shortly after market entry |
| Establish formal post-market mood tracking trend analysis | Upon market entry |
| Begin quarterly literature and EUDAMED database reviews | Upon market entry |

## 12. Approval

| Role | Name | Date |
|------|------|------|
| Author / PMS Owner | Sarp Derinsu | 2026-03-01 |
| Reviewer | Suzan Slijpen | 2026-03-01 |

## 13. References

- [[SOP-009]] Post-Market Surveillance Procedure
- [[PLN-004]] PMS Plan
- [[PLN-003]] PMCF Plan
- [[SOP-013]] Vigilance Procedure
- [[SOP-003]] CAPA Procedure
- [[SOP-002]] Risk Management Procedure
- [[SOP-012]] Clinical Evaluation Procedure
- ISO 13485:2016 Clause 8.2.1
- EU MDR 2017/745 Article 85
