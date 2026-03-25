---
id: "PLN-003"
title: "Post-Market Clinical Follow-up Plan"
type: "PLN"
version: "1.0"
status: "approved"
effective_date: "2026-03-01"
author: "Sarp Derinsu"
iso_refs:
  - "8.2.1"
mdr_refs:
  - "Annex XIV Part B"
---

# Post-Market Clinical Follow-up Plan

## 1. Purpose

This Post-Market Clinical Follow-up (PMCF) Plan defines the activities for proactively collecting and evaluating clinical data on the Therapeak AI therapy platform after it is placed on the market as a CE-marked Class IIa medical device. The PMCF aims to confirm the clinical safety and performance throughout the device's expected lifetime and to identify previously unknown side-effects, emerging risks, or systematic misuse.

This plan is based on the MDCG 2020-7 template for PMCF plans and fulfills the requirements of EU MDR 2017/745 Annex XIV Part B.

## 2. Scope

This plan applies to the Therapeak medical device (software version 1.0, `DEVICE_MODE=medical`) after placement on the EU market. It covers all users and all deployment configurations of the medical device version.

### 2.1 Device Description

- **Device name:** Therapeak
- **Intended purpose:** Patient-specific supportive conversational guidance to help users self-manage mild to moderate mental health symptoms at home.
- **Classification:** Class IIa under MDR Rule 11.
- **Target population:** Adults aged 19 and older.
- **Target conditions:** Mild to moderate anxiety, depression, OCD, trauma/stress-related disorders, impulse control disorders.

### 2.2 Residual Risks Requiring PMCF Monitoring

The following residual risks identified in [[RA-001]] require ongoing clinical follow-up:

- Therapeutically suboptimal or inappropriate AI outputs that may cause user distress
- User misinterpretation of AI outputs as clinical diagnoses or professional medical advice
- Users in crisis relying on the device instead of seeking emergency help
- Long-term psychological effects of AI-based therapy as a primary support mechanism
- Differential effectiveness across user subpopulations (language, cultural background, condition type)

## 3. PMCF Objectives

1. **Confirm safety:** Continuously verify that the device does not cause or contribute to adverse clinical outcomes in real-world use.
2. **Confirm performance:** Validate that the clinical benefits demonstrated in the Clinical Evaluation Report [[CE-001]] are sustained in post-market use.
3. **Detect emerging risks:** Identify previously unknown adverse effects, contraindications, or systematic misuse patterns.
4. **Validate clinical evidence:** Strengthen the clinical evidence base with real-world outcome data, addressing limitations of pre-market evidence (self-reported mood ratings, non-standardized scales).
5. **Monitor subpopulation outcomes:** Assess whether clinical performance varies across different user groups (by language, age group, condition type, or usage pattern).

## 4. PMCF Methods

### 4.1 Ongoing User Outcome Data Collection

**Data source:** Built-in mood tracking system (self-reported and AI session-based ratings).

| Data Element | Collection Method | Frequency |
|---|---|---|
| User self-reported mood | UI mood rating (1-10 scale, Sad/Neutral/Fine/Good/Great) | Per user action (max once per 12 hours) |
| AI session-based mood | GPT-4o rating after each session (1-10 scale) | After every therapy session |
| Session engagement | Session duration, message count, session frequency | Continuous (automatic) |
| User retention | Subscription status, active usage periods | Continuous (automatic) |
| Trial survey data | PHQ-9-style screening at onboarding | At registration |

**Analysis approach:** Aggregate mood trend analysis comparing early vs. later sessions for active users. Cohort analysis by condition type and usage duration. Minimum clinically important difference (MCID) benchmarked against published literature (d=0.3-0.5 for depression).

**Planned enhancement for medical device version:** Integration of validated clinical outcome measures (PHQ-9, GAD-7) at regular intervals to enable standardized clinical performance assessment.

### 4.2 Literature Monitoring

**Objective:** Continuously monitor the scientific literature for new evidence relevant to AI-based mental health interventions.

| Aspect | Detail |
|---|---|
| Databases | PubMed, Cochrane Library, PsycINFO |
| Frequency | Quarterly automated alerts + annual comprehensive search |
| Search terms | Same as defined in [[PLN-002]], Section 3.2 |
| Focus areas | New RCTs for AI therapy chatbots, safety reports, regulatory guidance updates, new equivalent device data |

**Trigger for CER update:** Any new publication that materially changes the benefit-risk assessment or identifies new risks for AI-based conversational therapy devices.

### 4.3 Complaint and Feedback Analysis

**Data sources:**

- User complaints via info@therapeak.com and in-app contact form
- Trustpilot reviews (both positive and negative)
- Direct user feedback during support interactions

**Analysis approach:**

- Each complaint is categorized by type (clinical, technical, usability, safety)
- Complaints with potential clinical significance (e.g., user reports of harm, distress caused by AI output, crisis situations) are flagged for immediate investigation
- Quarterly trend analysis of complaint categories and rates
- Complaint rate tracked per 1,000 active users per quarter

### 4.4 Session Quality Data

**Data source:** ChatDebugFlag monitoring system.

| Flag | What It Detects | Clinical Relevance |
|---|---|---|
| FLAG_SWITCHED_ROLES | AI responding as patient instead of therapist | User confusion, breakdown of therapeutic interaction |
| FLAG_DID_NOT_RESPOND | Prolonged AI non-response with user noticing | User frustration, potential abandonment during critical moment |

**Analysis:** Monthly rate tracking per 1,000 sessions. Trend analysis to detect degradation in AI model quality over time.

### 4.5 Incident Monitoring

All serious incidents and field safety corrective actions are tracked per [[SOP-009]]. PMCF analysis includes:

- Incident rate per 1,000 active users
- Root cause analysis outcomes
- Effectiveness of corrective actions

## 5. PMCF Endpoints

### 5.1 Primary Endpoints

| Endpoint | Target | Measurement |
|---|---|---|
| User-reported mood improvement | At least 40% of users with 14+ sessions show improvement | Comparison of earliest vs. most recent mood ratings |
| Complaint rate (clinical) | Less than 5 clinical complaints per 1,000 active users per quarter | Complaint tracking system |
| Serious incident rate | Zero serious incidents | Vigilance reporting system |

### 5.2 Secondary Endpoints

| Endpoint | Target | Measurement |
|---|---|---|
| Session quality flag rate | Less than 2% of sessions flagged | ChatDebugFlag monitoring |
| User retention at 4 weeks | At least 50% of subscribers active after first 4-week period | Subscription and usage data |
| User satisfaction | Trustpilot rating maintained above 4.0/5.0 | Trustpilot reviews |

### 5.3 Endpoint Thresholds for Action

- If mood improvement drops below 30%, a clinical investigation is triggered.
- If complaint rate exceeds 10 per 1,000 users per quarter, a root cause analysis is initiated.
- If any serious incident occurs, immediate investigation per [[SOP-009]] and potential field safety corrective action.
- If session quality flag rate exceeds 5%, AI model evaluation and prompt review are initiated.

## 6. Responsibilities

| Activity | Responsible |
|---|---|
| PMCF data collection and monitoring | Sarp Derinsu |
| Outcome data analysis | Sarp Derinsu |
| Literature monitoring | Sarp Derinsu |
| Complaint analysis and trending | Sarp Derinsu |
| PMCF Evaluation Report authoring | Sarp Derinsu |
| CER update based on PMCF findings | Sarp Derinsu |
| Escalation of safety signals | Sarp Derinsu |

## 7. Timeline

| Activity | Frequency |
|---|---|
| Mood data collection | Continuous (automated) |
| Session quality flag monitoring | Continuous (automated), reviewed monthly |
| Complaint trending | Quarterly |
| Literature search alerts | Quarterly (automated) |
| Comprehensive literature review | Annually |
| PMCF Evaluation Report | Annually (included in CER update) |
| CER update | Annually, or when triggered by new evidence |
| PMCF Plan review and update | Annually, or when triggered by significant findings |

### 7.1 First Year Milestones

| Milestone | Target |
|---|---|
| PMCF data collection systems operational | At market placement |
| First quarterly complaint trend analysis | 3 months after market placement |
| First literature monitoring review | 3 months after market placement |
| First PMCF Evaluation Report | 12 months after market placement |

## 8. PMCF Evaluation Report

The PMCF Evaluation Report shall be produced annually and shall include:

- Summary of all PMCF data collected during the reporting period
- Analysis of primary and secondary endpoints against targets
- Assessment of whether clinical evidence continues to support the benefit-risk determination
- Identification of any new risks or changes to known risks
- Conclusions on the continued safety and performance of the device
- Recommendations for any updates to the clinical evaluation, risk management, or device design

The PMCF Evaluation Report feeds directly into the annual CER update ([[CE-001]]) and the PMS Report ([[RPT-001]]).

## 9. References

- [[CE-001]] Clinical Evaluation Report
- [[PLN-002]] Clinical Evaluation Plan
- [[PLN-004]] Post-Market Surveillance Plan
- [[SOP-009]] Vigilance and Post-Market Surveillance Procedure
- [[SOP-012]] Clinical Evaluation Procedure
- [[RA-001]] Risk Management File
- [[RPT-001]] PMS Report
- EU MDR 2017/745 Annex XIV Part B
- MDCG 2020-7 Post-market clinical follow-up Plan Template
- MDCG 2020-8 Post-market clinical follow-up Evaluation Report Template
