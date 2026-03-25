---
id: "PLN-004"
title: "Post-Market Surveillance Plan"
type: "PLN"
version: "1.0"
status: "approved"
effective_date: "2026-03-01"
author: "Sarp Derinsu"
iso_refs:
  - "8.2.1"
mdr_refs:
  - "Article 84"
  - "Annex III"
---

# Post-Market Surveillance Plan

## 1. Purpose

This plan defines the Post-Market Surveillance (PMS) system for the Therapeak AI therapy platform in accordance with EU MDR 2017/745 Article 83-86 and Annex III. The PMS system shall proactively and systematically collect, analyze, and evaluate data from the market to identify any need for immediate corrective or preventive actions, to monitor the continued safety and performance of the device, and to ensure that the benefit-risk determination remains favorable.

As a Class IIa medical device, Therapeak is required to produce periodic PMS Reports (at least every two years per Article 86; Therapeak commits to annual reporting given the novel nature of AI therapy devices).

## 2. Scope

This plan applies to the Therapeak medical device (software version 1.0 and subsequent versions, `DEVICE_MODE=medical`) from the date of market placement onward. It covers all data sources relevant to monitoring the safety, performance, and user experience of the device in real-world use.

## 3. PMS Data Sources

### 3.1 Proactive Surveillance

Proactive data sources are systematically collected without requiring user-initiated contact.

| Data Source | Description | Collection Method | Frequency |
|---|---|---|---|
| Session quality monitoring (ChatDebugFlags) | Automated detection of AI output quality issues: FLAG_SWITCHED_ROLES (role confusion), FLAG_DID_NOT_RESPOND (non-response) | GPT-4o analysis of session transcripts | Continuous (every session) |
| AI output monitoring | Sarp manually reviews 1-2 therapy sessions for harmful patterns, inappropriate advice, or quality degradation | Manual session transcript review via admin panel | Daily to weekly |
| Mood tracking data | User self-reported mood ratings and AI session-based mood assessments | Built-in mood tracking system (automated) | Continuous |
| User retention data | Subscription churn, session frequency, active usage periods | Stripe subscription data + application analytics | Continuous (automated) |
| Telescope monitoring | System errors, request failures, queue processing issues, performance anomalies | Laravel Telescope dashboard | Continuous (monitored by Sarp throughout the day) |
| Literature monitoring | New publications on AI therapy safety, efficacy, and regulatory developments | PubMed alerts + quarterly manual search | Quarterly alerts, annual comprehensive review |
| Regulatory updates | New MDCG guidance, harmonized standards, competent authority actions, FSCA notices for similar devices | MDCG website, Eudamed (when available), NB communications | Quarterly review |
| Equivalent device monitoring | Safety and performance data from Limbic, Wysa, Woebot, and other AI mental health devices | Literature, regulatory databases, company publications | Quarterly |

### 3.2 Reactive Surveillance

Reactive data sources depend on external reports or user-initiated contact.

| Data Source | Description | Collection Method | Frequency |
|---|---|---|---|
| User complaints | Reports of issues, dissatisfaction, or harm via email or contact form | info@therapeak.com + in-app contact form | As received (typical response: 5-10 minutes) |
| Trustpilot reviews | Public user feedback including positive and negative experiences | Trustpilot platform monitoring | Continuous; negative reviews actively addressed |
| User feedback (general) | Spontaneous user feedback via support channels | Email correspondence, contact form | As received |
| Competent authority communications | Notifications, queries, or actions from IGJ or other EU competent authorities | Official correspondence | As received |
| Notified body communications | Observations, findings, or requests from Scarlet | NB portal and correspondence | As received |

## 4. Data Analysis Methods

### 4.1 Trend Analysis

The following metrics shall be tracked and trended over time:

| Metric | Calculation | Reporting Period | Alert Threshold |
|---|---|---|---|
| Clinical complaint rate | Clinical complaints / 1,000 active users | Monthly, trended quarterly | Greater than 5 per 1,000 users/quarter |
| Session quality flag rate | Flagged sessions / total sessions (%) | Monthly | Greater than 5% |
| Role confusion rate | FLAG_SWITCHED_ROLES / total sessions (%) | Monthly | Greater than 2% |
| Non-response rate | FLAG_DID_NOT_RESPOND / total sessions (%) | Monthly | Greater than 3% |
| User mood improvement rate | % of users (14+ sessions) showing improvement | Quarterly | Below 30% |
| Subscription churn rate | Cancellations / active subscriptions per period | Monthly | Trending review if sustained increase |
| Serious incident rate | Serious incidents / active users | Continuous | Any occurrence triggers investigation |

### 4.2 Signal Detection

A safety signal is defined as information that suggests a new potentially causal association, or a new aspect of a known association, between the device and an adverse event that warrants further investigation.

**Signal sources:**
- Pattern of similar complaints (3 or more complaints of the same nature within a quarter)
- ChatDebugFlag rate exceeding alert threshold
- Negative trend in user mood improvement data
- Published literature identifying new risks for AI therapy devices
- Competent authority safety communications regarding similar devices

**Signal evaluation process:**
1. Signal detected (from any source above)
2. Preliminary assessment: determine clinical significance and urgency
3. If potentially serious: immediate investigation per [[SOP-009]]
4. If non-urgent: document in PMS records, include in next periodic analysis
5. Determine if corrective action is needed (CAPA per applicable SOP)
6. Update risk management file [[RA-001]] if risk profile has changed

### 4.3 Complaint Classification

All complaints shall be classified by:

| Category | Examples |
|---|---|
| Clinical / Safety | User reports harm from AI advice, AI provided inappropriate therapeutic guidance, user in crisis not properly redirected |
| AI Quality | Poor response quality, irrelevant advice, repetitive outputs, factual errors in coping strategies |
| Technical | Application errors, session interruptions, data loss, login issues |
| Usability | Difficulty navigating the interface, confusion about features, accessibility issues |
| Data Privacy | Concerns about data handling, deletion requests, unauthorized access |
| Billing / Commercial | Subscription issues, refund requests, pricing concerns |

Complaints classified as "Clinical / Safety" receive priority investigation and are assessed for reportability as serious incidents per [[SOP-009]].

## 5. Reporting

### 5.1 PMS Report

As a Class IIa device, Therapeak shall produce a PMS Report ([[RPT-001]]) annually (exceeding the MDR Article 86 requirement of at least every two years). The PMS Report shall include:

- Summary of PMS data collected during the reporting period
- Results of trend analysis and signal detection
- Summary of complaints and their resolution
- Summary of PMCF findings (from [[PLN-003]])
- Assessment of continued benefit-risk acceptability
- Conclusions on the need for corrective or preventive actions
- Updates to the risk management file ([[RA-001]])
- Input to the clinical evaluation update ([[CE-001]])

### 5.2 Serious Incident Reporting

Serious incidents shall be reported to the competent authority without delay and no later than:
- 15 days for serious incidents
- 2 days for serious incidents that are a serious public health threat
- 10 days for death or unanticipated serious deterioration in health

Reporting follows [[SOP-009]] (Vigilance and Post-Market Surveillance Procedure).

### 5.3 Field Safety Corrective Actions (FSCA)

When a corrective action is necessary to reduce risk of death or serious deterioration in health, a Field Safety Notice shall be issued and the competent authority notified per [[SOP-009]].

## 6. Responsibilities

| Activity | Responsible | Backup |
|---|---|---|
| Daily system monitoring (Telescope) | Sarp Derinsu | -- |
| Complaint receipt and initial assessment | Sarp Derinsu | Nisan Derinsu (for serious incidents if Sarp unavailable) |
| Session quality flag review | Sarp Derinsu | -- |
| AI output quality review | Sarp Derinsu | -- |
| Trend analysis and signal detection | Sarp Derinsu | -- |
| PMS Report authoring | Sarp Derinsu | -- |
| Serious incident reporting | Sarp Derinsu | Nisan Derinsu (emergency backup) |
| Literature and regulatory monitoring | Sarp Derinsu | -- |

**Note:** Nisan Derinsu (director) is designated as the emergency backup for serious incident reporting if Sarp Derinsu is unreachable. She will receive basic training on what constitutes a reportable incident and how to contact the competent authority.

## 7. Timeline

| Activity | Frequency |
|---|---|
| System monitoring (Telescope) | Continuous (daily) |
| Complaint handling | As received (target response: same day) |
| Session quality flag review | Weekly |
| AI output manual review | Daily to weekly |
| Trustpilot review monitoring | Weekly |
| Trend analysis | Monthly metrics, quarterly analysis |
| Literature monitoring | Quarterly alerts, annual comprehensive review |
| Regulatory update review | Quarterly |
| PMS Report ([[RPT-001]]) | Annually |
| PMS Plan review and update | Annually |

## 8. References

- [[RPT-001]] PMS Report
- [[PLN-003]] Post-Market Clinical Follow-up Plan
- [[SOP-009]] Vigilance and Post-Market Surveillance Procedure
- [[RA-001]] Risk Management File
- [[CE-001]] Clinical Evaluation Report
- EU MDR 2017/745 Articles 83-86
- EU MDR 2017/745 Annex III (Technical Documentation on Post-Market Surveillance)
- MDCG 2020-7 Post-market clinical follow-up Plan Template
- MEDDEV 2.12/1 rev.8 Guidelines on a medical devices vigilance system
