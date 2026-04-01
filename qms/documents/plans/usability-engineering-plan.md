---
id: "PLN-006"
title: "Usability Engineering Plan"
type: "PLN"
category: "technical"
version: "1.0"
status: "approved"
effective_date: "2026-03-01"
author: "Sarp Derinsu"
iso_refs:
  - "7.3.2"
mdr_refs:
  - "Annex I, Section 5"
---

# Usability Engineering Plan

## 1. Purpose

This plan defines the usability engineering process for the Therapeak AI therapy platform, applying IEC 62366-1:2015 principles proportionate to the Class IIa MDSW classification and the single-developer organization. The plan establishes the use specification, identifies hazard-related use scenarios, and defines the formative and summative evaluation strategy to ensure the device can be used safely and effectively by the intended users.

## 2. Scope

This plan applies to the Therapeak medical device software (version 1.0, `DEVICE_MODE=medical`), covering:

- The web-based user interface (responsive SPA accessed via browser)
- All user-facing features: onboarding survey, therapy chat, mood tracking, session reports, user reports, account management
- All touch-points where usability affects safety (crisis resource visibility, disclaimer comprehension, session management)

This plan does not cover the admin interface or backend systems that are not user-facing.

## 3. Use Specification

### 3.1 Intended Users

| Attribute | Specification |
|---|---|
| User type | Patients (lay users) -- no healthcare professional intermediary required |
| Age | Adults aged 19 and older |
| Clinical profile | Individuals with mild to moderate mental health symptoms (anxiety, depression, OCD, trauma/stress-related, impulse control) |
| Digital literacy | Varying levels -- from digitally fluent to basic computer/smartphone skills |
| Language | 20+ supported languages; users interact in their preferred language |
| Physical abilities | Includes users with visual impairments or motor limitations who may use assistive technologies (screen readers, voice control) |
| Mental state during use | Users may be experiencing emotional distress, anxiety, or depressive symptoms during interaction with the device |

### 3.2 Intended Use Environment

| Attribute | Specification |
|---|---|
| Setting | Home use (unsupervised, no clinical oversight during sessions) |
| Device | Any device with a modern web browser (desktop, laptop, tablet, smartphone) |
| Browser | Modern evergreen browsers: Chrome, Firefox, Edge, Safari (including iOS 15+ Safari) |
| Connectivity | Internet connection required (standard broadband or mobile data) |
| Lighting/noise | Variable and uncontrolled (home environment) |
| Privacy | Users may or may not have a private space; text-based interface supports discreet use |
| Supervision | None -- device is used independently by the patient |

### 3.3 User Interface Description

Therapeak is a web-based single-page application (SPA) with the following key interfaces:

1. **Onboarding flow:** Landing page, trial survey (20 questions including depression/anxiety screening items, demographics, and preferences), registration, email verification, therapist matching.
2. **Therapy chat:** Text-based conversational interface with timed sessions. User types messages; AI therapist responds in real-time via WebSocket.
3. **Mood tracking:** UI for recording self-reported mood (Sad/Neutral/Fine/Good/Great), with graphs showing mood trends over time.
4. **Session reports:** Automatically generated post-session summaries viewable in-app.
5. **User reports:** Clinical-style reports generated after multiple sessions, viewable in-app with PDF export.
6. **Account management:** Profile settings, subscription management, therapist switching, account deletion.
7. **Contact/support:** Contact form with FAQ popup for common questions.

## 4. User Profiles

### 4.1 Primary User Profile: Adult with Mild-Moderate Mental Health Symptoms

| Characteristic | Range |
|---|---|
| Age | 19 to 70+ (primary demographic: 25-55) |
| Condition severity | Mild to moderate (self-reported symptoms consistent with mild to moderate depression and anxiety) |
| Previous therapy experience | Mixed -- some with prior therapy, some first-time seekers |
| Digital literacy | Low to high -- must accommodate users who struggle with web navigation |
| Motivation | Seeking accessible, affordable mental health support; may be supplement to or substitute for traditional therapy |
| Emotional state | May be distressed, anxious, or in low mood during use |
| Language | Native or fluent in one of 20+ supported languages |

### 4.2 Secondary User Profile: Healthcare Professional (Optional)

Users may share session reports or user reports (PDF export) with their healthcare professional. The healthcare professional is not a direct user of the device but may receive device outputs. Reports include explicit disclaimers that they are not medical documents and not diagnostic.

## 5. Hazard-Related Use Scenarios

The following use scenarios have been identified where usability issues could lead to harm:

### 5.1 User Misinterprets AI Output as Professional Diagnosis

| Aspect | Detail |
|---|---|
| Scenario | User reads session report or user report and interprets the clinical-style language as a formal diagnosis, leading to inappropriate self-treatment decisions. |
| Hazard | User makes health decisions (e.g., stopping medication, avoiding professional care) based on perceived AI diagnosis. |
| Severity | Serious (S3) |
| Current mitigations | Reports state "this is not a medical document" and "not a diagnosis." Reports are instructed never to advise about medication. IFU will clearly state the device does not provide diagnoses. |
| Usability requirement | Disclaimers must be prominently displayed and written in plain language accessible to lay users. |

### 5.2 User Unable to Find Crisis Resources

| Aspect | Detail |
|---|---|
| Scenario | User experiencing a mental health crisis attempts to find emergency resources through the platform but cannot locate them. |
| Hazard | User in crisis delays seeking appropriate emergency help. |
| Severity | Critical (S4) to Catastrophic (S5) |
| Current mitigations | Homepage displays emergency messaging ("In emergencies, this site is not a substitute for immediate help..."). Contraindication statement indicates device is not for emergency/crisis use. Claude's built-in safety handles crisis detection in conversation. |
| Usability requirement | Crisis resources must be accessible within one click/tap from any screen. Emergency messaging must be visible without scrolling on the main pages. |

### 5.3 User Unable to End Session

| Aspect | Detail |
|---|---|
| Scenario | User wants to end a therapy session (e.g., because the conversation is distressing) but cannot find or use the session end control. |
| Hazard | User continues an unwanted interaction, increasing distress. Session minutes continue being consumed. |
| Severity | Minor (S2) to Serious (S3) |
| Current mitigations | Session has a timer and ends automatically. User can navigate away from the chat page. |
| Usability requirement | Session end control must be clearly visible and accessible. User must be able to end a session at any time with a single action. |

### 5.4 User Provides False Age to Bypass Minimum Age Gate

| Aspect | Detail |
|---|---|
| Scenario | A minor (under 19) enters a false age during the onboarding survey to access the platform. |
| Hazard | Minor receives AI therapy not intended or validated for their age group. |
| Severity | Serious (S3) |
| Current mitigations | Age dropdown starts at 12; selecting age 18 or below blocks access to free trial and payment. Age 18 is blocked as a buffer. |
| Usability requirement | Age verification step must be clear and unambiguous. Warning message for blocked ages must explain why access is denied. |

### 5.5 User Confused by Subscription Cancellation Process

| Aspect | Detail |
|---|---|
| Scenario | User wants to cancel their subscription but cannot find or complete the cancellation flow, leading to frustration and continued billing. |
| Hazard | Indirect: user frustration may exacerbate mental health symptoms; trust in the platform is undermined. |
| Severity | Minor (S2) |
| Current mitigations | FAQ popup on the contact page proactively answers cancellation questions. Significantly reduced contact volume. |
| Usability requirement | Cancellation process must be accessible and clearly documented. |

## 6. Known Use Problems and Resolutions

The following use problems have been identified from user feedback during the wellness product operation and have been resolved:

| Problem | Source | Resolution | Status |
|---|---|---|---|
| Voice control accessibility: "Send" button not accessible for screen readers/voice control | German user with visual impairment reported via contact form | Added appropriate accessibility label to the send button | Resolved |
| Subscription cancellation confusion | Multiple users contacting support to ask how to cancel | Implemented FAQ popup on contact page that proactively answers common questions including cancellation | Resolved; contact volume significantly reduced |

These resolutions demonstrate the iterative usability improvement process and will be carried forward into the medical device version.

## 7. Formative Evaluation

### 7.1 Approach

Formative evaluation for Therapeak is conducted through ongoing collection and analysis of real-world user feedback. Given the single-developer organization and the existing user base from the wellness product, formative evaluation is based on:

| Method | Description | Frequency |
|---|---|---|
| User feedback analysis | Systematic review of user contact messages, complaints, and feature requests received via email and contact form | Continuous (as received) |
| Trustpilot review analysis | Analysis of user reviews for usability-related feedback | Weekly monitoring |
| Support interaction analysis | Identification of recurring user difficulties from support conversations | Continuous |
| Session observation | Manual review of therapy sessions for signs of user confusion or difficulty | 1-2 sessions per week |
| Accessibility testing | Verification of screen reader compatibility, voice control accessibility, keyboard navigation | After UI changes affecting interactive elements |

### 7.2 Formative Evaluation Records

Findings from formative evaluation are documented as:
- Contact messages tagged with usability-related categories
- Usability improvement items tracked as development tasks
- Resolution of usability issues documented through git commit history
- Significant usability findings documented in the usability engineering file

## 8. Summative Evaluation

### 8.1 Pre-Market (Medical Device Version)

A summative usability evaluation is planned for the medical device version (version 1.0, `DEVICE_MODE=medical`) before or shortly after market placement. The summative evaluation shall:

- Validate that all hazard-related use scenarios (Section 5) have adequate mitigations
- Confirm that safety-critical information (disclaimers, crisis resources, contraindications) is comprehended by representative users
- Verify that key user tasks (starting a session, ending a session, accessing reports, recording mood) can be completed successfully
- Evaluate the effectiveness of the age gate in preventing minor access

### 8.2 Summative Evaluation Method

| Aspect | Detail |
|---|---|
| Method | Task-based usability evaluation with representative users |
| Participants | 5-8 adults representing the primary user profile (varying age, digital literacy, language) |
| Tasks | Complete onboarding, conduct a therapy session, end a session, find crisis resources, view a report, record mood, attempt cancellation |
| Success criteria | All safety-critical tasks completed successfully by all participants. Non-critical tasks completed successfully by at least 80% of participants. |
| Data collection | Task completion (pass/fail), time on task, errors, user comments |
| Analysis | Identification of residual use problems, assessment of residual use risk acceptability |

### 8.3 Post-Market Summative Activities

Following market placement, summative evaluation shall be updated based on:
- PMCF data indicating usability-related safety concerns (per [[PLN-003]])
- Significant UI changes that affect hazard-related use scenarios
- New use problems identified through post-market surveillance (per [[PLN-004]])

## 9. Usability Engineering File

The usability engineering file shall contain:

- This Usability Engineering Plan (PLN-006)
- Use specification (Section 3 of this document, expanded in [[SPE-002]])
- Hazard-related use scenario analysis (Section 5)
- Known use problems and resolutions (Section 6)
- Formative evaluation records
- Summative evaluation protocol and results
- Residual use risk analysis

## 10. Responsibilities

| Activity | Responsible |
|---|---|
| Use specification definition | Sarp Derinsu |
| Hazard-related use scenario identification | Sarp Derinsu |
| Formative evaluation (ongoing) | Sarp Derinsu |
| Summative evaluation planning and execution | Sarp Derinsu |
| Usability issue resolution | Sarp Derinsu |
| Usability engineering file maintenance | Sarp Derinsu |
| Accessibility verification | Sarp Derinsu |

## 11. Timeline

| Milestone | Target Date |
|---|---|
| Usability engineering plan approved | 2026-03-01 |
| Hazard-related use scenario analysis complete | Before NB Stage 1 (April 2026) |
| Formative evaluation summary for wellness version | Before NB Stage 1 (April 2026) |
| Use specification finalized ([[SPE-002]]) | Before CE marking |
| Summative evaluation completed | Before or shortly after CE marking |
| Ongoing formative evaluation | Continuous (post-market) |

## 12. References

- [[SOP-007]] Usability Engineering Procedure
- [[SPE-002]] Use Specification / Usability Requirements
- [[PLN-001]] Risk Management Plan
- [[PLN-003]] Post-Market Clinical Follow-up Plan
- [[PLN-004]] Post-Market Surveillance Plan
- [[RA-001]] Risk Management File
- IEC 62366-1:2015 Medical devices -- Application of usability engineering to medical devices
- IEC 62366-2:2016 Guidance on the application of usability engineering to medical devices
- EU MDR 2017/745 Annex I, Section 5 (Requirements regarding general safety)
- IEC 62304:2006+A1:2015 (Software lifecycle, usability-related requirements)
