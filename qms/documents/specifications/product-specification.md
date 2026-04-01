---
id: "SPE-002"
title: "Product Specification"
type: "SPE"
category: "technical"
version: "1.0"
status: "approved"
effective_date: "2026-03-01"
author: "Sarp Derinsu"
iso_refs:
  - "7.3.3"
mdr_refs:
  - "Annex II"
---

# Product Specification

## 1. Purpose

This document defines the product specification for the Therapeak medical device, an AI-based conversational therapy platform classified as Software as a Medical Device (SaMD) under EU MDR 2017/745. It describes the device's intended purpose, target population, clinical claims, technical architecture, and classification rationale.

**Related documents:** [[LBL-001]] Instructions for Use, [[RA-001]] Risk Assessment

## 2. Device Description

Therapeak is an AI-powered conversational therapy platform that provides mental health support through text-based interactions with AI-generated therapists. Users chat with personalized AI therapists in timed therapy sessions. The platform generates session summaries, clinical-style user reports, and mood tracking data to support self-management of mild to moderate mental health conditions.

The device is Software as a Medical Device (SaMD) — it operates entirely as software with no hardware component. Users access it through a web browser on their own devices (computers, tablets, smartphones).

## 3. Intended Purpose

"Therapeak provides patient-specific supportive conversational guidance intended to help users self-manage mild to moderate mental health symptoms at home."

The device output consists of:

- Patient-specific conversational guidance and recommendations
- Alerts and insights
- Session reports and summaries
- Informational coaching content, reflective prompts, and structured coping suggestions
- Summaries of user-reported concerns that can optionally be shared with a healthcare professional

## 4. Intended Use Environment

- **Setting:** Home use
- **Access method:** Web browser (no native mobile application)
- **Supervision:** Unsupervised — no healthcare professional intermediary is required
- **Complementary use:** May be used standalone or as a supplement to traditional therapy. Output may be shared with healthcare professionals to support remote monitoring, consultation preparation, and follow-up.

## 5. Target Conditions

The device addresses mild to moderate mental health conditions, specifically:

| Condition | Scope |
|---|---|
| Anxiety disorders | Mild to moderate |
| Depression | Mild to moderate |
| Obsessive-compulsive disorders | Mild to moderate |
| Trauma or stress-related disorders | Mild to moderate |
| Disorders related to impulse control | Mild to moderate |

## 6. Target Patient Population

| Parameter | Specification |
|---|---|
| **Age** | Adults aged 19 and older |
| **Age enforcement** | The survey age dropdown displays ages 12-100. Users reporting age 18 or below are blocked from accessing the free trial or making a payment. Age 18 is blocked as a buffer against minors misreporting their age. |
| **Clinical profile** | Individuals with mild to moderate mental health symptoms as described in Section 5 |
| **Setting** | Home use, self-directed |
| **Intended users** | Patients directly (lay users, no clinical training required) |

## 7. Contraindications

The device is **not intended** for use by individuals with:

- **Complex psychotic or dissociative disorders** — the conversational AI is not designed to manage symptoms associated with psychosis, severe dissociation, or related conditions
- **Neurobiological and neurocognitive disorders** — the device may be less useful for conditions with primarily organic/neurological aetiology
- **Emergency or crisis situations** — the device is not a substitute for emergency mental health services, crisis helplines, or in-person emergency care

## 8. Clinical Claims

### 8.1 IMDRF Classification

The device **informs clinical management** per the IMDRF SaMD categorization framework. Specifically:

- The output provides information that may be used by patients to self-manage their mental health
- The output may assist clinician-patient discussions when shared with a healthcare professional

### 8.2 What the Device Does NOT Do

The device does **not**:

- Diagnose any mental health condition
- Establish severity or staging of any condition
- Triage urgency of care
- Recommend or select specific clinical interventions
- Determine medication or treatment changes
- Replace clinical decision-making by a healthcare professional

Any clinical decisions remain the sole responsibility of the healthcare professional and/or user.

## 9. Risk Classification

| Parameter | Value |
|---|---|
| **Classification** | Class IIa |
| **Rule** | Rule 11 (Annex VIII, EU MDR 2017/745) |
| **Conformity assessment route** | Annex IX (Quality Management System + Technical Documentation assessment) |
| **Notified Body** | Scarlet (scarlet.cc) |
| **MDA code** | MDA 0315 |
| **EMDN code** | V92 (medical device software not included in other classes) |

### 9.1 Classification Justification

Class IIa under Rule 11 because:

- The software provides information used for therapeutic decisions (self-management)
- It is intended for mild-to-moderate symptoms in a home use setting
- Reasonably foreseeable harm from erroneous outputs is primarily minor and reversible (e.g., transient distress, unhelpful coping suggestion)
- The device is NOT intended for diagnosis, triage, emergency/crisis use, or directing specific treatment changes
- Risk is mitigated through limitations-of-use, safety messaging, escalation pathways, monitoring, and controlled updates

## 10. Technical Specifications

### 10.1 System Architecture

The device comprises two applications within a single codebase:

Therapeak is a monolithic Laravel web application that handles all functionality: therapy sessions, user management, payments, reports, AI content generation, therapist profile creation, and content moderation.

### 10.2 Technology Stack

| Layer | Technology |
|---|---|
| **Backend** | PHP 8.2, Laravel 10 |
| **Frontend** | Vue 3, Tailwind CSS + DaisyUI |
| **Database** | MariaDB 10 |
| **Cache / Queue** | Redis, Laravel Horizon |
| **Real-time** | Soketi (self-hosted Pusher-compatible WebSocket server) |
| **Build** | Vite 4 |
| **Local web server** | Nginx for development |

### 10.3 AI Models

| Function | Model | Provider | Accessed via |
|---|---|---|---|
| Primary therapy chat | Claude Sonnet 4.5 | Anthropic | OpenRouter gateway |
| AB test variant | Claude Sonnet 4.6 | Anthropic | OpenRouter gateway |
| Therapy chat fallback (level 1) | Claude Opus 4.5 | Anthropic | OpenRouter gateway |
| Therapy chat fallback (level 2) | Claude Sonnet 4, Sonnet 3.7, Opus 4 | Anthropic | OpenRouter gateway (multi-provider routing) |
| Session summaries | GPT-4o | OpenAI |
| User reports | GPT-4o | OpenAI |
| Session quality monitoring | GPT-4o | OpenAI |
| Therapist avatar images | Flux Pro | Fal.ai |

### 10.4 Hosting and Infrastructure

| Parameter | Specification |
|---|---|
| **Hosting provider** | Hetzner |
| **Server location** | Nuremberg, Germany (EU) |
| **Server type** | VPS (self-managed) |
| **SSL/TLS** | Let's Encrypt (auto-renewed) |
| **Email** | AWS SES (eu-north-1, Stockholm) |
| **Object storage** | AWS S3 (avatar images via Spatie Media Library) |

## 11. External Interfaces

### 11.1 Anthropic Claude (via OpenRouter)

- **Purpose:** AI language models powering therapy conversations
- **Model provider:** Anthropic (Claude Sonnet 4.5, 4.6, Opus, and fallback models)
- **API gateway:** OpenRouter — routes API requests to Anthropic via multiple infrastructure providers (Vertex AI, Amazon Bedrock, Anthropic API) for high availability
- **Data exchanged:** Conversation prompts (containing user messages, system instructions, session context) and AI responses
- **Health data:** Yes — therapy conversation content is transmitted
- **Data sharing:** Disabled at OpenRouter level (no third-party training use). Anthropic does not use API data for training by default.

### 11.2 Stripe API

- **Purpose:** Payment processing and subscription management
- **Data exchanged:** Subscription status, payment method tokens, pricing, invoices
- **Health data:** No — financial data only
- **Integration:** Laravel Cashier

### 11.3 AWS SES

- **Purpose:** Transactional email delivery
- **Data exchanged:** Email content including session summary text, user report notifications, verification codes, and welcome emails
- **Health data:** Yes — session summary emails contain full therapy summary text in the email body
- **Region:** eu-north-1 (Stockholm)

### 11.4 Fal.ai

- **Purpose:** AI therapist avatar image generation
- **Data exchanged:** Image generation prompts and resulting images
- **Health data:** No

### 11.5 OpenAI API

- **Purpose:** Content generation, moderation, session summaries, reports, and session quality monitoring
- **Data exchanged:** Therapy transcripts (for summaries/reports/monitoring), platform content (for moderation)
- **Health data:** Yes — therapy session transcripts are transmitted for summary and report generation

## 12. Device Inputs and Outputs

### 12.1 Inputs

- Patient-reported information: text messages during therapy sessions
- Questionnaire responses: trial survey (custom onboarding questionnaire with depression/anxiety screening items, demographics, preferences)
- Mood self-reports
- User profile information

### 12.2 Outputs

- Patient-specific conversational guidance and recommendations
- Session summaries (post-session, max 500 tokens)
- Clinical-style user reports (multi-session, max 4000 tokens)
- Mood tracking visualizations (self-reported and AI-assessed)
- Alerts and insights
- PDF export of session reports

## 13. Software Version

The CE-marked medical device release is designated as **version 1.0**. The medical device is activated by the configuration value `DEVICE_MODE=medical`, which enables medical terminology and medical-device-specific functionality.

## 14. Revision History

| Version | Date | Author | Description |
|---|---|---|---|
| 1.0 | 2026-03-01 | Sarp Derinsu | Initial release |
