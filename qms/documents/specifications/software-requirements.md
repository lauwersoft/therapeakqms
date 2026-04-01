---
id: "SPE-001"
title: "Software Requirements Specification"
type: "SPE"
category: "technical"
version: "2.0"
status: "draft"
effective_date: "2026-04-01"
author: "Sarp Derinsu"
iso_refs:
  - "7.3.3"
mdr_refs:
  - "Annex II"
  - "Annex I, 17.1"
---

# Software Requirements Specification

## 1. Purpose

This document defines the software requirements for the Therapeak medical device (software version 1.0, `DEVICE_MODE=medical`). Each requirement is derived from the use requirements ([[SPE-003]]), risk controls ([[RA-001]]), or applicable standards, and includes acceptance criteria and verification method.

**Related documents:** [[SPE-003]] Use Requirements Specification, [[PLN-005]] Software Development Plan, [[RA-001]] Risk Management File

**Applicable standards:**
- IEC 62304:2006+AMD1:2015 Section 5.2 — Software requirements analysis
- IEC 82304-1:2016 Section 4.2 — Use requirements decomposition
- IEC 81001-5-1:2021 Section 5.2.1 — Security requirements
- ISO 14971:2019 Clause 6 — Safety-related requirements
- EU MDR 2017/745 Annex I, GSPR 17.1 — Software lifecycle and validation

## 2. Scope

This specification covers the Therapeak AI therapy platform in its medical device configuration. Requirements are categorized as:

- **FR** — Functional requirements
- **PF** — Performance requirements
- **SF** — Safety requirements (derived from risk controls)
- **SC** — Security / cybersecurity requirements
- **AI** — AI-specific requirements
- **UX** — Usability requirements
- **RG** — Regulatory requirements

## 3. Verification Methods

| Code | Method | Description |
|---|---|---|
| **T** | Test | Verified by executing a defined test procedure and comparing results against acceptance criteria |
| **A** | Analysis | Verified by technical analysis, calculation, or modelling |
| **I** | Inspection | Verified by visual examination of code, configuration, UI, or documentation |
| **R** | Review | Verified by review of design documents, architecture, or third-party documentation |

## 4. Functional Requirements

### 4.1 User Registration and Authentication

| ID | Requirement | Acceptance Criteria | Source | Verification |
|---|---|---|---|---|
| FR-001 | The system shall allow users to register using email/password or social login (Google, Microsoft, Facebook, TikTok). | User can create account via each method; account is active after email verification. | UR-027 | T |
| FR-002 | The system shall verify user email addresses via a 4-digit verification code sent to the provided email. | Verification email is received within 60 seconds; correct code activates the account; incorrect code is rejected. | UR-027 | T |
| FR-003 | The system shall authenticate users via Laravel Sanctum (SPA sessions) and Laravel Passport (service-to-service tokens). | Authenticated users can access protected routes; unauthenticated users are redirected to login. | UR-027 | T |

### 4.2 Trial Survey and Screening

| ID | Requirement | Acceptance Criteria | Source | Verification |
|---|---|---|---|---|
| FR-010 | The system shall present a two-part onboarding questionnaire (17 initial questions + 3 finalization questions) before granting access to therapy. | All 20 questions are presented in sequence; responses are stored; user cannot access therapy without completion. | UR-002 | T |
| FR-011 | The questionnaire shall include a depression screening section (8 items) using the response scale: Not at all / Several days / More than half the days / Nearly every day. | 8 depression items are displayed with the 4-option response scale; responses are recorded with correct values. | UR-002 | T |
| FR-012 | In the medical device configuration, the depression screening shall replace the suicidal ideation item with "The feeling that nothing I do is good enough." | In `DEVICE_MODE=medical`, item 9 text matches the replacement text; in wellness mode, original text is shown. | UR-002, UR-037, C-007e | T |
| FR-013 | The system shall collect: gender, age, relationship status, previous therapy experience, reasons for seeking therapy, functional impairment, anxiety/panic/phobia screening, therapist expectations, therapist preferences, focus areas, and a free-text field. | All fields are present in the questionnaire; responses are stored and accessible for therapist matching. | UR-002 | T |
| FR-014 | The system shall implement an age gate that prevents users who report an age of 18 or below from accessing the free trial or making a payment. Minimum effective age: 19+. | Users selecting age ≤18 cannot proceed to trial or checkout; users selecting age ≥19 can proceed. | UR-020, C-008a | T |
| FR-015 | The age dropdown shall display ages 12-100, but selection of any age ≤18 shall block trial and payment access. | Dropdown displays ages 12-100; selecting 12-18 shows blocking message; selecting 19+ proceeds. | UR-020, C-008a | T |

### 4.3 AI Therapist Generation

| ID | Requirement | Acceptance Criteria | Source | Verification |
|---|---|---|---|---|
| FR-020 | The system shall generate a personalized AI therapist profile based on the user's survey responses. | After completing the survey, a unique therapist profile is created and assigned to the user. | UR-003 | T |
| FR-021 | Each AI therapist profile shall include 17 randomized personality traits (personality type, communication style, emotional tone, response length, metaphor usage, humor style, empathy level, problem-solving approach, feedback style, session pace, questioning style, emoji usage, punctuation style, etc.). | Generated profiles contain all 17 trait fields with valid values from their respective option sets. | UR-003 | T |
| FR-022 | Each AI therapist shall have a generated name, biography, backstory, and avatar image. | All four fields are populated and non-empty for every generated therapist. | UR-003 | T |
| FR-023 | Avatar images shall be generated via Fal.ai (Flux Pro model), with pre-generated avatars available as fallback if the service is unavailable. | Avatar generation succeeds under normal conditions; when Fal.ai is unavailable, a pre-generated avatar is assigned. | UR-018 | T |
| FR-024 | Users shall be able to switch to a different AI therapist at any time. | User can trigger therapist switch; new therapist is generated and assigned; old therapist remains accessible in history. | UR-003, UR-014 | T |
| FR-025 | When a user switches therapists, conversation history remains with the original therapist. The new therapist starts with trial survey data only. | After switch, new therapist has no access to previous conversation messages; survey data is available in new therapist context. | UR-003, UR-005 | T |

### 4.4 Timed Therapy Sessions

| ID | Requirement | Acceptance Criteria | Source | Verification |
|---|---|---|---|---|
| FR-030 | The system shall provide timed therapy sessions that deduct minutes from the user's balance. | Session timer counts down; minutes are deducted from balance in real time; session ends when balance reaches zero. | UR-004 | T |
| FR-031 | Users shall receive a default allocation of 30 minutes per day, with a maximum accumulation of 45 minutes per day. | Balance resets/accumulates correctly at the daily boundary; balance never exceeds 45 minutes from daily allocation alone. | UR-004 | T |
| FR-032 | New users shall receive 10 free trial minutes. | Upon first registration, user balance shows 10 minutes available before any purchase. | UR-004 | T |
| FR-033 | The AI therapist shall be aware of remaining session time and wrap up the session appropriately near the end. | When session has <3 minutes remaining, AI acknowledges time limit and initiates wrap-up in its response. | UR-004, UR-005 | T |
| FR-034 | User messages shall be dispatched to a queue for processing by the conversation job. | Messages are enqueued within 1 second of submission; conversation job picks up and processes the message. | UR-012 | T |
| FR-035 | The conversation job shall construct a system prompt including: static therapeutic instructions, dynamic personality description, chat room context, user survey answers, previous session summaries, and user profile text. | System prompt sent to AI model contains all specified context sections. | UR-001, UR-005 | I |
| FR-036 | AI responses shall be saved as assistant messages and broadcast to the user via WebSocket in real time. | Response appears in the user's chat interface within 30 seconds of message submission; message is persisted in the database. | UR-012 | T |

### 4.5 Session Summaries and User Reports

| ID | Requirement | Acceptance Criteria | Source | Verification |
|---|---|---|---|---|
| FR-040 | The system shall generate a session summary after each therapy session (maximum 500 tokens). | Summary is generated within 5 minutes of session end; summary length does not exceed 500 tokens. | UR-005 | T |
| FR-041 | Session summaries shall be included as context in subsequent therapy sessions with the same therapist. | System prompt for subsequent sessions includes the most recent session summaries. | UR-005 | I |
| FR-042 | The system shall generate clinical-style user reports after multiple sessions (maximum 4000 tokens) using up to the 10 most recent sessions, trial survey data, and the previous report. | Report is generated using the specified inputs; report length does not exceed 4000 tokens. | UR-006 | T |
| FR-043 | User reports shall contain sections for: presenting problem, background information, assessment findings, diagnosis (with disclaimer), treatment plan, progress notes, recommendations, and summary/prognosis. | Generated report contains all specified sections. | UR-006 | I |
| FR-044 | Reports shall explicitly state "this is not a medical document" and "not a diagnosis." | Both disclaimer phrases are present in every generated report. | UR-025 | I |
| FR-045 | Reports shall never contain advice about medication. | Report generation prompt includes medication restriction; generated reports do not contain medication recommendations. | UR-025, C-004a | I |
| FR-046 | Users shall be able to export session reports as PDF. | PDF export produces a valid PDF file containing the report content. | UR-006, UR-014 | T |

### 4.6 Mood Tracking

| ID | Requirement | Acceptance Criteria | Source | Verification |
|---|---|---|---|---|
| FR-050 | The system shall support user self-reported mood ratings on a scale (Sad/Neutral/Fine/Good/Great, mapped to 1-10), limited to once per 12 hours. | Mood rating is recorded; second rating within 12 hours is rejected; correct mapping to 1-10 scale is stored. | UR-007 | T |
| FR-051 | The system shall generate AI-based session mood ratings (1-10 scale) after each therapy session. | AI mood rating is generated and stored for every completed session; rating is within 1-10 range. | UR-007 | T |
| FR-052 | Users shall be able to toggle mood tracking on or off. | Toggling off stops mood prompts and AI ratings; toggling on resumes them; toggle state persists. | UR-007, UR-014 | T |
| FR-053 | The system shall display graphs of self-reported, AI-reported, and combined mood ratings over time. | Mood graphs render correctly with available data; graphs update when new ratings are added. | UR-007 | T |

### 4.7 Subscription Management

| ID | Requirement | Acceptance Criteria | Source | Verification |
|---|---|---|---|---|
| FR-060 | The system shall manage subscriptions via Stripe (Laravel Cashier) with per-country pricing tiers. | Users from different countries see correct localized pricing; subscription activates upon successful payment. | UR-009, UR-016 | T |
| FR-061 | Supported payment methods shall include: Card, PayPal, Link, and SEPA Debit. | Each payment method is available at checkout and can complete a transaction. | UR-009, UR-016 | T |
| FR-062 | Users shall be able to purchase additional minutes in increments of 15-240 minutes. | Minute packages are available for purchase; purchased minutes are added to the user's balance. | UR-009 | T |
| FR-063 | The system shall block checkout from Netherlands (NL) and Turkey (TR). | Users with billing address in NL or TR cannot complete checkout; appropriate message is displayed. | UR-009 | T |

## 5. Performance Requirements

| ID | Requirement | Acceptance Criteria | Source | Verification |
|---|---|---|---|---|
| PF-001 | The system shall target 99.9% availability, achieved through multi-provider AI infrastructure redundancy (OpenRouter gateway routing through Vertex AI, Amazon Bedrock, and Anthropic API). | Uptime measured over 30-day rolling window meets or exceeds 99.9%. | UR-036 | A |
| PF-002 | AI therapy responses shall be delivered within 30 seconds of user message submission under normal operating conditions. | 95th percentile response time is ≤30 seconds measured over a 7-day period. | UR-012 | T |
| PF-003 | The system shall support 20+ language locales. | Locale switcher displays 20+ languages; UI and AI responses render correctly in each supported locale. | UR-008 | T |
| PF-004 | AI response output shall be limited to a maximum of 400 tokens with 500 reasoning tokens per response. | AI API calls specify max_tokens=400 and reasoning tokens budget=500; responses do not exceed these limits. | UR-001 | I |
| PF-005 | The system shall handle concurrent users without degradation through Redis-based queue processing (Laravel Horizon) and WebSocket connections (Soketi). | Under concurrent load, response times remain within PF-002 thresholds; no queue backup >60 seconds. | UR-036 | T |

## 6. Safety Requirements

### 6.1 Crisis Handling

| ID | Requirement | Acceptance Criteria | Source | Verification |
|---|---|---|---|---|
| SF-001 | Crisis detection and response during therapy conversations shall be delegated to Anthropic Claude's built-in safety mechanisms. | When crisis language is used, Claude responds with empathy, directs to emergency services, and does not continue normal conversation. | UR-019, C-007a | T |
| SF-002 | The homepage shall display: "In emergencies, this site is not a substitute for immediate help. If you are in a crisis, call the national crisis line, dial emergency services, or visit the nearest emergency room." | Emergency disclaimer text is present and visible on the homepage. | UR-019, UR-023, C-007c | I |
| SF-003 | When the depression screening indicates severe symptoms, the system shall display: "If you are having thoughts of self-harm, please contact a crisis helpline immediately." | Warning message is displayed when screening score exceeds the severe threshold. | UR-019, C-007d | T |

### 6.2 Role Enforcement

| ID | Requirement | Acceptance Criteria | Source | Verification |
|---|---|---|---|---|
| SF-010 | The system prompt shall contain explicit, repeated role enforcement instructions establishing the AI as the therapist (10+ reinforcement statements). | System prompt contains ≥10 distinct role enforcement instructions. | UR-021, C-003a | I |
| SF-011 | The system shall include 160-200+ embedded static instructions per conversation job covering role enforcement, safety, and formatting. | Instruction count in the system prompt is ≥160. | UR-021, C-003a | I |
| SF-012 | The AI shall not engage in: role-playing, games, off-platform contact, or referrals to other therapists. | System prompt explicitly prohibits these behaviors; testing with adversarial prompts confirms compliance. | UR-021, UR-024 | T |

### 6.3 Session Quality Monitoring

| ID | Requirement | Acceptance Criteria | Source | Verification |
|---|---|---|---|---|
| SF-020 | The system shall automatically monitor therapy sessions for role confusion (AI responding as patient instead of therapist) via `CheckSessionForSwitchedRolesJob`. | Job runs after each session; FLAG_SWITCHED_ROLES is recorded when role confusion is detected. | UR-022, C-003c | T |
| SF-021 | The system shall automatically monitor therapy sessions for non-response events (>30 second gap where user asks if AI is present) via `CheckSessionForDidNotRespondJob`. | Job runs after each session; FLAG_DID_NOT_RESPOND is recorded when non-response is detected. | UR-022 | T |
| SF-022 | Flagged sessions shall be recorded as `ChatDebugFlag` entries in the database for review. | All detected flags are persisted with session ID, flag type, and timestamp. | UR-022 | T |

### 6.4 Content Moderation and Restrictions

| ID | Requirement | Acceptance Criteria | Source | Verification |
|---|---|---|---|---|
| SF-030 | User-generated platform content (reviews, survey replies, article replies) shall be moderated via an automated moderation system. | Content containing prohibited terms is auto-rejected; clean content is accepted. | UR-030 | T |
| SF-031 | The moderation system shall auto-reject content containing: mentions of AI-generated content, offensive language, violence, drugs, or weapons. | Each prohibited category triggers rejection when present in submitted content. | UR-030 | T |
| SF-032 | Therapy conversations shall NOT be subject to the platform content moderation system. Safety in therapy is handled by AI model safety mechanisms and prompt instructions. | Therapy messages bypass the content moderation filter; therapeutic conversation proceeds normally with sensitive topics. | UR-001, UR-021 | T |
| SF-040 | The AI shall never encourage leaving relationships; it shall support healing first, never demonize people, and never label individuals as toxic or narcissistic. | System prompt contains explicit relationship protection instructions; adversarial testing confirms compliance. | UR-024, C-004a | T |
| SF-041 | AI responses shall use conversational text only: no lists, no bold/italic formatting, and responses shall be kept short. | AI responses contain no markdown formatting; average response length is appropriate for conversational exchange. | UR-001 | I |

## 7. Security Requirements

| ID | Requirement | Acceptance Criteria | Source | Verification |
|---|---|---|---|---|
| SC-001 | All data in transit shall be encrypted via TLS (Let's Encrypt, auto-renewed). | SSL certificate is valid and auto-renewed; all HTTP traffic redirects to HTTPS; SSL Labs score ≥A. | UR-026 | T |
| SC-002 | SSH access to production servers shall be restricted to Sarp Derinsu only via SSH key authentication. | Password authentication is disabled; only authorized SSH key can connect. | UR-031 | I |
| SC-003 | A Data Processing Agreement (DPA) shall be maintained with all processors handling health data (Hetzner, OpenRouter/Anthropic, AWS, Stripe). | Signed DPA or accepted standard DPA is on file for each processor. | UR-028 | R |
| SC-004 | Upon user-initiated account deletion, the system shall perform a soft delete immediately and a permanent data wipe after 180 days via `app:purge-deleted-users`. | Soft delete marks account immediately; scheduled command permanently removes data after 180 days; no user data remains after wipe. | UR-029, UR-035 | T |
| SC-005 | Upon an explicit GDPR erasure request, the system shall perform a permanent data wipe within 30 days. | Manual trigger of wipe process removes all user data within 30 days of request. | UR-029 | T |
| SC-006 | 2FA shall be enabled on all critical infrastructure accounts: GitHub, Hetzner, Stripe, and AWS. | 2FA is verified as active on each account. | UR-031 | I |
| SC-007 | API keys and secrets shall be stored in `.env` files on the production server and shall not be committed to version control. | `.env` is listed in `.gitignore`; no API keys or secrets are present in the git repository history. | UR-031 | I |
| SC-008 | The system shall implement anti-abuse measures: temporary email domain blocklist, IP/country/VPN blocking via middleware, user banning, rate limiting on API endpoints. | Each measure is implemented and functional: temp emails are rejected, blocked countries cannot access, banned users cannot log in, rate limits return 429 responses. | UR-030 | T |

## 8. AI Requirements

| ID | Requirement | Acceptance Criteria | Source | Verification |
|---|---|---|---|---|
| AI-001 | The primary therapy AI model shall be Anthropic Claude Sonnet 4.5, with multi-layer fallback: Claude Opus 4.5, then Claude Sonnet 4, Claude Sonnet 3.7, Claude Opus 4. | Primary model is configured as Claude Sonnet 4.5; fallback chain is configured in the correct order; failover occurs automatically on primary model failure. | UR-015, UR-036 | T |
| AI-002 | API requests to Anthropic shall be routed via the OpenRouter gateway, which provides infrastructure-level redundancy through Vertex AI, Amazon Bedrock, and the Anthropic API. | API calls are directed to OpenRouter endpoint; OpenRouter routes to available infrastructure provider. | UR-015, UR-036 | I |
| AI-003 | The conversation job shall retry failed AI requests up to 3 times with 1-second intervals before escalating to the fallback model. | Failed requests trigger up to 3 retries; after 3 failures, the next fallback model is attempted. | UR-015, UR-036 | T |
| AI-004 | All therapy conversation prompts shall include comprehensive safety instructions covering: crisis delegation, role enforcement, relationship protection, content restrictions, and formatting rules. | System prompt contains sections for each safety category. | UR-001, UR-019, UR-021, UR-024 | I |
| AI-005 | Session summaries and user reports shall be generated by GPT-4o with maximum token limits (500 for summaries, 4000 for reports). | GPT-4o is configured as the model for these jobs; token limits are enforced in the API calls. | UR-005, UR-006 | I |
| AI-006 | The system shall monitor AI output quality through automated session quality checks (role confusion detection, non-response detection). | Quality check jobs execute after each session; flags are recorded for detected issues. | UR-022 | T |
| AI-007 | Data sharing at the OpenRouter gateway shall be disabled to prevent therapy conversation data from being used for third-party training. | OpenRouter dashboard confirms data sharing is set to OFF; API requests include appropriate headers. | UR-032 | I |

## 9. Usability Requirements

| ID | Requirement | Acceptance Criteria | Source | Verification |
|---|---|---|---|---|
| UX-001 | The system shall be a web-based responsive application accessible from modern evergreen browsers (Chrome, Firefox, Edge, Safari), with specific handling for iOS 15+ Safari. | Application loads and functions correctly in each listed browser on desktop and mobile. | UR-010, UR-033 | T |
| UX-002 | Interactive elements shall include appropriate ARIA labels for screen reader and voice control accessibility. | Key interactive elements (buttons, inputs, navigation) have ARIA labels; screen reader can navigate core workflows. | UR-011 | I |
| UX-003 | The contact page shall include an FAQ popup that answers common questions before users submit a message. | FAQ popup appears before the contact form; common questions (cancellation, billing) are covered. | UR-014 | T |
| UX-004 | The system shall provide clear disclaimers on: the homepage (emergency disclaimer), AI therapist profiles ("fictional profiles with AI-generated avatars"), and reports ("not a medical document"). | Each disclaimer is present at the specified location with the specified text. | UR-013, UR-023 | I |
| UX-005 | Users shall be able to self-service: subscription management, therapist switching, mood tracking preferences, and account deletion. | Each self-service action is accessible from the user's settings/profile page and functions correctly. | UR-014 | T |
| UX-006 | Support shall be available via info@therapeak.com and the in-app contact form. | Email link and contact form are accessible; submitted messages are received by support. | UR-014 | T |

## 10. Regulatory Requirements

| ID | Requirement | Acceptance Criteria | Source | Verification |
|---|---|---|---|---|
| RG-001 | The system shall operate in two modes (`DEVICE_MODE=wellness` and `DEVICE_MODE=medical`) controlled by a configuration value. The medical device mode shall use medical terminology from dedicated translation files. | Setting `DEVICE_MODE=medical` activates medical-specific translations and labeling; setting `DEVICE_MODE=wellness` uses wellness translations. | UR-037, UR-038 | T |
| RG-002 | The software version for the CE-marked release shall be designated as version 1.0 per [[SOP-014]]. | Application version is displayed as 1.0; git tag matches v1.0. | UR-041 | I |
| RG-003 | All AI therapist profiles shall include: "All AI therapists on this platform are fictional profiles with AI-generated avatars. No real people are depicted." | Disclaimer text is present on every therapist profile page. | UR-013 | I |

## 11. Traceability Summary

Full bidirectional traceability is maintained in the Software Traceability Matrix:

- **Upstream:** Each software requirement traces to one or more use requirements ([[SPE-003]]) and/or risk controls ([[RA-001]])
- **Downstream:** Each software requirement traces to verification test specifications and verification reports

| Requirement Category | IDs | Count | Primary Use Requirements |
|---|---|---|---|
| Functional | FR-001 to FR-063 | 34 | UR-001 to UR-009, UR-012 to UR-020 |
| Performance | PF-001 to PF-005 | 5 | UR-008, UR-012, UR-036 |
| Safety | SF-001 to SF-041 | 14 | UR-019 to UR-025 |
| Security | SC-001 to SC-008 | 8 | UR-026 to UR-032 |
| AI | AI-001 to AI-007 | 7 | UR-001, UR-005, UR-015, UR-022, UR-032, UR-036 |
| Usability | UX-001 to UX-006 | 6 | UR-010 to UR-014, UR-033 |
| Regulatory | RG-001 to RG-003 | 3 | UR-013, UR-037, UR-038, UR-041 |
| **Total** | | **77** | |

## 12. Requirements Review

| Criterion | Status |
|---|---|
| Completeness: all use requirements are addressed by at least one software requirement | Confirmed |
| Accuracy: requirements reflect the intended product behavior | Confirmed |
| Feasibility: requirements are technically achievable within the current architecture | Confirmed |
| Verifiability: each requirement has defined acceptance criteria and verification method | Confirmed |
| Traceability: each requirement traces to use requirements and/or risk controls | Confirmed |
| Risk coverage: all risk controls from [[RA-001]] that require software implementation are addressed | Confirmed |

| Role | Name | Date |
|---|---|---|
| Author / Reviewer | Sarp Derinsu | 2026-04-01 |

## 13. Change History

| Version | Date | Author | Description |
|---|---|---|---|
| 1.0 | 2026-03-01 | Sarp Derinsu | Initial release |
| 2.0 | 2026-04-01 | Sarp Derinsu | Added acceptance criteria, source traceability (UR-NNN, risk controls), and verification method columns per IEC 62304 and Scarlet requirements. Separated use requirements into [[SPE-003]]. Fixed questionnaire description (removed PHQ-9 reference). |

## 14. References

- [[SPE-003]] Use Requirements Specification
- [[SPE-002]] Product Specification
- [[PLN-005]] Software Development Plan
- [[RA-001]] Risk Management File
- [[SOP-007]] Design and Development Procedure
- [[SOP-011]] Software Lifecycle Management Procedure
- [[SOP-014]] Product Identification and Traceability
- IEC 62304:2006+AMD1:2015 — Medical device software — Software lifecycle processes
- IEC 82304-1:2016 — Health software — Product safety
- IEC 81001-5-1:2021 — Health software — Security
- ISO 14971:2019 — Medical devices — Application of risk management
- EU MDR 2017/745 Annex I — General Safety and Performance Requirements
- EU MDR 2017/745 Annex II — Technical Documentation
