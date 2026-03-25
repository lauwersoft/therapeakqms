---
id: "SPE-001"
title: "Software Requirements Specification"
type: "SPE"
version: "1.0"
status: "approved"
effective_date: "2026-03-01"
author: "Sarp Derinsu"
iso_refs:
  - "7.3.3"
mdr_refs:
  - "Annex II"
---

# Software Requirements Specification

## 1. Purpose

This document defines the software requirements for the Therapeak medical device (software version 1.0, `DEVICE_MODE=medical`). It specifies the functional, performance, safety, security, AI, and usability requirements that the software must satisfy to fulfil its intended purpose under EU MDR 2017/745.

**Related documents:** [[SOP-007]] Software Development Procedure, [[PLN-005]] Software Development Plan

## 2. Scope

This specification covers the Therapeak AI therapy platform in its medical device configuration, including:

- The Therapeak web application
- All third-party integrations that support the device's intended purpose
- The AI conversational therapy engine and its supporting subsystems

This document does not cover the wellness product variant (`DEVICE_MODE=wellness`), which operates under separate non-medical claims.

## 3. Functional Requirements

### 3.1 User Registration and Authentication

| ID | Requirement | Rationale |
|---|---|---|
| FR-001 | The system shall allow users to register using email/password or social login (Google, Microsoft, Facebook, TikTok). | Enable accessible onboarding across platforms. |
| FR-002 | The system shall verify user email addresses via a 4-digit verification code. | Confirm identity and enable account recovery. |
| FR-003 | The system shall authenticate users via Laravel Sanctum (SPA) and Laravel Passport (service-to-service). | Secure session management and inter-service communication. |

### 3.2 Trial Survey and Screening

| ID | Requirement | Rationale |
|---|---|---|
| FR-010 | The system shall present a two-part onboarding questionnaire (17 initial questions + 3 finalization questions) before granting access to therapy. | Collect baseline clinical and preference data for therapist matching and session context. |
| FR-011 | The questionnaire shall include a PHQ-9-style screening instrument (8 items) using the standard response scale: Not at all / Several days / More than half the days / Nearly every day. | Screen for depression severity to inform AI therapy context. |
| FR-012 | The PHQ-9-style screening shall replace the original suicidal ideation item (item 9) with "The feeling that nothing I do is good enough" in the medical device configuration. | Avoid direct suicidal ideation screening in a non-crisis-equipped system. |
| FR-013 | The system shall collect: gender, age, relationship status, previous therapy experience, reasons for seeking therapy, functional impairment, anxiety/panic/phobia screening, therapist expectations, therapist preferences, focus areas, and a free-text "what brings you here" field. | Enable comprehensive therapist matching and personalized session context. |
| FR-014 | The system shall implement an age gate that prevents users who report an age of 18 or below from accessing the free trial or making a payment. The minimum effective age for platform access shall be 19+. | Protect minors from unsupervised AI therapy. Age 18 is blocked as a buffer against minors misreporting their age. |
| FR-015 | The age dropdown shall display ages 12-100, but selection of any age 18 or below shall block trial and payment access. | Allow age collection for data purposes while enforcing the age gate. |

### 3.3 AI Therapist Generation

| ID | Requirement | Rationale |
|---|---|---|
| FR-020 | The system shall generate a personalized AI therapist profile based on the user's survey responses. | Match therapeutic approach to user needs and preferences. |
| FR-021 | Each AI therapist profile shall be composed of 17 randomized personality traits, including: personality type, communication style, emotional tone, response length, metaphor usage, humor style, empathy level, problem-solving approach, feedback style, session pace, questioning style, emoji usage, and punctuation style. | Provide diverse therapeutic experiences and enable user preference matching. |
| FR-022 | Each AI therapist shall have a generated name, biography, backstory, and avatar image. | Create an engaging therapeutic relationship through personalization. |
| FR-023 | Avatar images shall be generated via Fal.ai (Flux Pro). | Provide unique, AI-generated therapist representations. |
| FR-024 | Users shall be able to switch to a different AI therapist at any time. | Allow users to find a therapeutic style that works for them. |
| FR-025 | When a user switches therapists, conversation history shall remain with the original therapist. The new therapist shall start with trial survey data only. | Maintain data integrity; prevent context confusion between therapist profiles. |

### 3.4 Timed Therapy Sessions

| ID | Requirement | Rationale |
|---|---|---|
| FR-030 | The system shall provide timed therapy sessions that deduct minutes from the user's balance. | Enable metered access to therapy services. |
| FR-031 | Users shall receive a default allocation of 30 minutes per day, with a maximum accumulation of 45 minutes per day. | Provide adequate session time while encouraging regular engagement. |
| FR-032 | New users shall receive 10 free trial minutes. | Allow users to evaluate the service before purchasing. |
| FR-033 | The AI therapist shall be aware of remaining session time and wrap up the session appropriately near the end. | Ensure natural session closure and complete therapeutic exchanges. |
| FR-034 | User messages shall be dispatched to a queue for processing by the conversation job. | Enable reliable, asynchronous message handling. |
| FR-035 | The conversation job shall construct a system prompt including: static therapeutic instructions, dynamic personality description, chat room context, user survey answers, previous session summaries, and user profile text. | Provide the AI model with comprehensive context for therapeutically appropriate responses. |
| FR-036 | AI responses shall be saved as assistant messages and broadcast to the user via WebSocket in real time. | Enable responsive, real-time conversational interaction. |

### 3.5 Session Summaries and User Reports

| ID | Requirement | Rationale |
|---|---|---|
| FR-040 | The system shall generate a session summary after each therapy session (maximum 500 tokens). | Create a concise memory aid for subsequent sessions. |
| FR-041 | Session summaries shall be used as context in subsequent therapy sessions. | Maintain therapeutic continuity across sessions. |
| FR-042 | The system shall generate clinical-style user reports after multiple sessions (maximum 4000 tokens) using up to the 10 most recent sessions, trial survey data, and the previous report. | Provide users with structured insights into their progress. |
| FR-043 | User reports shall contain sections for: presenting problem, background information, assessment findings, diagnosis (with disclaimer), treatment plan, progress notes, recommendations, and summary/prognosis. | Follow a structured clinical report format for consistency and usefulness. |
| FR-044 | Reports shall explicitly state "this is not a medical document" and "not a diagnosis." | Clearly communicate the informational nature of reports. |
| FR-045 | Reports shall never advise about medication. | Prevent the device from operating outside its intended purpose. |
| FR-046 | Users shall be able to export session reports as PDF. | Enable users to share reports with healthcare professionals. |

### 3.6 Mood Tracking

| ID | Requirement | Rationale |
|---|---|---|
| FR-050 | The system shall support user self-reported mood ratings on a scale (Sad/Neutral/Fine/Good/Great, mapped to 1-10), limited to once per 12 hours. | Enable users to track their subjective wellbeing over time. |
| FR-051 | The system shall generate AI-based session mood ratings (1-10 scale) after each therapy session. | Provide an objective complement to self-reported mood data. |
| FR-052 | Users shall be able to toggle mood tracking on or off. | Respect user preferences and autonomy. |
| FR-053 | The system shall display graphs of self-reported, AI-reported, and combined mood ratings. | Visualize mood trends to support self-management. |

### 3.7 Subscription Management

| ID | Requirement | Rationale |
|---|---|---|
| FR-060 | The system shall manage subscriptions via Stripe (Laravel Cashier) with per-country pricing tiers. | Enable localized, fair pricing across markets. |
| FR-061 | Supported payment methods shall include: Card, PayPal, Link, and SEPA Debit. | Provide convenient payment options for EU users. |
| FR-062 | Users shall be able to purchase additional minutes in increments of 15-240 minutes. | Allow flexible usage beyond the base subscription. |
| FR-063 | The system shall block checkout from Netherlands (NL) and Turkey (TR). | Comply with regulatory requirements in those jurisdictions. |

## 4. Performance Requirements

| ID | Requirement | Rationale |
|---|---|---|
| PF-001 | The system shall target 99.9% availability, achieved through multi-provider AI fallback via OpenRouter (routing through Vertex AI, Amazon Bedrock, and Anthropic API). | Ensure reliable access to therapy services. |
| PF-002 | AI therapy responses shall be delivered within 30 seconds of user message submission under normal operating conditions. | Maintain a natural conversational pace. |
| PF-003 | The system shall support 20+ language locales, including: Dutch, German, French, Spanish, Italian, Portuguese, Polish, Czech, Japanese, Korean, Norwegian, Swedish, Danish, Finnish, Turkish, English (US/UK/Canada), and more. | Serve a diverse international user base. |
| PF-004 | AI response output shall be limited to a maximum of 400 tokens with 500 reasoning tokens per response. | Balance response quality with latency and cost. |
| PF-005 | The system shall handle concurrent users without degradation through Redis-based queue processing (Laravel Horizon) and WebSocket connections (Soketi). | Ensure consistent performance under load. |

## 5. Safety Requirements

### 5.1 Crisis Handling

| ID | Requirement | Rationale |
|---|---|---|
| SF-001 | Crisis detection and response during therapy conversations shall be delegated to Anthropic Claude's built-in safety mechanisms. | Leverage the AI provider's specialized safety training for crisis scenarios. |
| SF-002 | The homepage shall display a prominent emergency disclaimer: "In emergencies, this site is not a substitute for immediate help. If you are in a crisis, call the national crisis line, dial emergency services, or visit the nearest emergency room." | Direct users in crisis to appropriate emergency resources. |
| SF-003 | When the depression screening indicates severe symptoms, the system shall display: "If you are having thoughts of self-harm, please contact a crisis helpline immediately." | Provide escalation guidance for high-risk screening results. |

### 5.2 Role Enforcement

| ID | Requirement | Rationale |
|---|---|---|
| SF-010 | The system prompt shall contain explicit, repeated role enforcement instructions establishing the AI as the therapist (10+ reinforcement statements per conversation job). | Prevent role confusion where the AI might respond as the patient. |
| SF-011 | The system shall include 160-200+ embedded static instructions per conversation job covering role enforcement, safety, and formatting. | Ensure comprehensive behavioural guardrails for the AI model. |
| SF-012 | The AI shall not engage in: role-playing, games, off-platform contact, or referrals to other therapists. | Keep interactions within the therapeutic scope of the device. |

### 5.3 Session Quality Monitoring

| ID | Requirement | Rationale |
|---|---|---|
| SF-020 | The system shall automatically monitor therapy sessions for role confusion (AI responding as patient instead of therapist) via the `CheckSessionForSwitchedRolesJob`. | Detect and flag sessions where the AI has deviated from its therapeutic role. |
| SF-021 | The system shall automatically monitor therapy sessions for non-response events (>30 second gaps where the user explicitly asks if the AI is present) via the `CheckSessionForDidNotRespondJob`. | Detect service disruptions that affect the user experience. |
| SF-022 | Flagged sessions shall be recorded as `ChatDebugFlag` entries for review. | Enable systematic review and quality improvement. |

### 5.4 Content Moderation

| ID | Requirement | Rationale |
|---|---|---|
| SF-030 | User-generated platform content (assistant reviews, survey replies, article replies) shall be moderated via an automated moderation system. | Protect the platform community from harmful content. |
| SF-031 | The moderation system shall auto-reject content containing: mentions of AI-generated content, offensive language, violence, drugs, or weapons. | Enforce platform content standards. |
| SF-032 | Therapy conversations shall NOT be subject to the platform content moderation system; safety in therapy conversations is handled by the AI model's built-in safety mechanisms and prompt instructions. | Avoid interference with the therapeutic conversation while maintaining safety through model-level controls. |

### 5.5 Content Restrictions

| ID | Requirement | Rationale |
|---|---|---|
| SF-040 | The AI shall never encourage leaving relationships; it shall always attempt to support healing first, never demonize people, and never label individuals as toxic or narcissistic. | Prevent harmful relationship advice that could cause real-world harm. |
| SF-041 | AI responses shall use conversational text only: no lists, no bold/italic formatting, and responses shall be kept short. | Maintain a natural therapeutic conversation style. |

## 6. Security Requirements

| ID | Requirement | Rationale |
|---|---|---|
| SC-001 | All data in transit shall be encrypted via SSL/TLS (Let's Encrypt, auto-renewed). | Protect data confidentiality during transmission. |
| SC-002 | SSH access to production servers shall be restricted to Sarp Derinsu only. | Minimize attack surface and enforce access control. |
| SC-003 | A Data Processing Agreement (DPA) shall be maintained with the hosting provider (Hetzner). | Comply with GDPR data processor requirements. |
| SC-004 | Upon user-initiated account deletion, the system shall perform a soft delete immediately and a permanent data wipe after 180 days via an automated scheduled command. | Balance data retention needs with user privacy rights. |
| SC-005 | Upon an explicit GDPR erasure request, the system shall perform a permanent data wipe within 30 days. | Comply with GDPR Article 17 right to erasure. |
| SC-006 | 2FA shall be enabled on all critical infrastructure accounts: GitHub, Hetzner, Stripe, and AWS. | Protect against unauthorized access to critical systems. |
| SC-007 | API keys and secrets shall be stored in environment variable files (`.env`) and shall not be committed to version control. | Prevent credential exposure. |
| SC-008 | The system shall implement anti-abuse measures including: temporary email domain blocklist, IP/country/VPN blocking via middleware, and user banning capabilities. | Protect the platform from abuse and fraudulent usage. |

## 7. AI Requirements

| ID | Requirement | Rationale |
|---|---|---|
| AI-001 | The primary therapy AI model shall be Claude Sonnet 4.5 via OpenRouter, with multi-layer fallback: Claude Opus 4.5, then OpenRouter's model fallback array (Claude Sonnet 4, Claude Sonnet 3.7, Claude Opus 4). | Ensure continuous AI availability through redundancy. |
| AI-002 | OpenRouter shall route requests through multiple providers (Vertex AI, Amazon Bedrock, Anthropic API) to achieve provider-level redundancy. | Eliminate single-provider dependency. |
| AI-003 | The conversation job shall retry failed AI requests up to 3 times with 1-second intervals before escalating to the fallback model. | Handle transient API errors gracefully. |
| AI-004 | All therapy conversation prompts shall include comprehensive safety instructions covering: crisis delegation, role enforcement, relationship protection, content restrictions, and formatting rules. | Ensure consistent, safe AI behaviour across all conversations. |
| AI-005 | AI-generated session summaries and user reports shall be generated by GPT-4o with defined maximum token limits (500 for summaries, 4000 for reports). | Ensure consistent quality and length for generated clinical documents. |
| AI-006 | The system shall monitor AI output quality through automated session quality checks (role confusion detection, non-response detection). | Detect and respond to AI performance degradation. |
| AI-007 | OpenRouter data sharing shall be disabled to prevent therapy conversation data from being used for third-party training. | Protect user health data privacy. |

## 8. Usability Requirements

| ID | Requirement | Rationale |
|---|---|---|
| UX-001 | The system shall be a web-based responsive application accessible from modern evergreen browsers (Chrome, Firefox, Edge, Safari), with specific handling for iOS 15+ Safari. | Ensure broad accessibility without requiring native app installation. |
| UX-002 | Interactive elements shall include appropriate ARIA labels for screen reader and voice control accessibility. | Enable use by persons with visual impairments, as validated by real user feedback. |
| UX-003 | The contact page shall include an FAQ popup that automatically answers common questions (e.g., how to cancel subscription) before users submit a message. | Reduce support burden and provide immediate answers to common queries. |
| UX-004 | The system shall provide clear disclaimers on: the homepage (emergency disclaimer), AI therapist profiles ("fictional profiles with AI-generated avatars"), and reports ("not a medical document"). | Ensure users understand the nature and limitations of the device. |
| UX-005 | The system shall support user self-service for: subscription management, therapist switching, mood tracking preferences, and account deletion. | Empower users to manage their own experience. |
| UX-006 | Support shall be available via info@therapeak.com and the in-app contact form. | Provide accessible support channels. |

## 9. Regulatory Requirements

| ID | Requirement | Rationale |
|---|---|---|
| RG-001 | The system shall operate in two modes (`DEVICE_MODE=wellness` and `DEVICE_MODE=medical`) controlled by a configuration value. The medical device mode shall use medical terminology from dedicated translation files. | Separate the CE-marked medical device from the wellness product within a single codebase. |
| RG-002 | The software version for the CE-marked release shall be designated as version 1.0. | Establish a formal version baseline for regulatory submission. |
| RG-003 | All AI therapist profiles shall include a disclaimer: "All AI therapists on this platform are fictional profiles with AI-generated avatars. No real people are depicted." | Comply with transparency requirements for AI-generated content. |

## 10. Traceability

Requirements defined in this document are traceable to:

- Risk controls in [[RA-001]] Risk Assessment
- Verification activities in the Software Verification Report
- Validation activities in the Software Validation Report
- Design outputs in [[SPE-002]] Product Specification

## 11. Revision History

| Version | Date | Author | Description |
|---|---|---|---|
| 1.0 | 2026-03-01 | Sarp Derinsu | Initial release |
