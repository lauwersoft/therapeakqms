---
id: "CTX-001"
title: "Submission Context Document"
type: "CTX"
category: "technical"
version: "1.0"
status: "draft"
effective_date: "2026-04-01"
author: "Sarp Derinsu"
iso_refs: []
mdr_refs:
  - "Annex II"
  - "Annex III"
---

# Submission Context Document

## 1. Purpose

This document provides context for the Therapeak technical documentation submission to facilitate regulatory review by the Notified Body (Scarlet). It describes the device, its classification, intended purpose, and maps Scarlet's expected documentation categories to the specific documents in our technical file.

## 2. Device Classification and Description

### 2.1 Device Identification

| Item | Detail |
|---|---|
| Device name | Therapeak |
| Manufacturer | Therapeak B.V. (KvK 96490713) |
| Registered address | Utrecht, The Netherlands |
| Person responsible for regulatory compliance | Sarp Derinsu, CEO / Quality Manager |
| Device type | Stand-alone medical device software (SaMD) |
| Software version | 1.0 |
| EMDN code | V92 |
| MDA code | MDA 0315 |

### 2.2 Intended Purpose

Therapeak provides patient-specific supportive conversational guidance to help users self-manage mild to moderate mental health symptoms at home.

The device uses AI-based conversational therapy (Anthropic Claude) to provide text-based therapeutic sessions, session summaries, progress reports, and mood tracking. It is intended for unsupervised home use by adults aged 19 and older.

**The device does NOT:**
- Diagnose mental health conditions
- Triage patients
- Select or recommend treatments or medication
- Serve as a crisis or emergency intervention tool
- Replace professional mental healthcare

### 2.3 Classification

| Item | Detail |
|---|---|
| Risk class | Class IIa |
| Classification rule | Rule 11, MDR Annex VIII, Chapter III, Section 6.3 |
| Justification | Software intended to provide information used to take decisions with diagnosis or therapeutic purposes. Classified as Class IIa because it is intended to inform clinical management (IMDRF category). |
| Conformity assessment | Annex IX — Quality management system and assessment of technical documentation |

### 2.4 Key Functional Elements

| Element | Description |
|---|---|
| AI therapy engine | Anthropic Claude (Sonnet 4.5/4.6) accessed via OpenRouter API gateway, with multi-model fallback |
| Onboarding questionnaire | 20-question custom questionnaire collecting demographics, symptoms, and preferences |
| Timed therapy sessions | Text-based conversational therapy with daily session limits (30 min/day, max 45 min) |
| Session summaries | AI-generated summaries (GPT-4o) providing continuity between sessions |
| User reports | Clinical-style progress reports with explicit disclaimers |
| Mood tracking | Self-reported and AI-assessed mood ratings with visualization |
| Session quality monitoring | Automated detection of role confusion and non-response via ChatDebugFlag system |

### 2.5 Novel Features

Therapeak is an AI-powered SaMD that uses large language models (LLMs) for therapeutic conversation. This represents a novel application of AI in mental health that differs from traditional rule-based chatbots in that:
- The AI model generates contextual, personalized responses (not scripted)
- Crisis handling is delegated to the AI model's built-in safety mechanisms (Anthropic's safety training)
- Therapeutic quality depends on prompt engineering and model behavior, not hard-coded decision trees

### 2.6 Equivalence Claims

No equivalence claims are made. The clinical evaluation ([[CE-001]]) follows the MDCG 2020-1 pathway for medical device software using published clinical evidence from similar AI-based mental health interventions, supplemented by pre-market experience data from the wellness version.

## 3. Technical File Map

This section maps Scarlet's expected documentation categories to our specific documents.

### 3.1 Core Documentation (Required)

| Scarlet Category | Our Document(s) | Document ID(s) |
|---|---|---|
| **Declaration of Conformity** | EU Declaration of Conformity | [[DOC-001]] (draft) |
| **GSPR Checklist** | GSPR Checklist | [[CHK-001]] |
| **PMS and Clinical Follow-up Plans** | PMS Plan, PMCF Plan | [[PLN-004]], [[PLN-003]] |

### 3.2 Software Documentation

| Scarlet Category | Our Document(s) | Document ID(s) |
|---|---|---|
| SDLC plans | Software Development Plan, Software Lifecycle SOP | [[PLN-005]], [[SOP-011]] |
| Validation plans | Software Validation Report (includes protocol) | [[RPT-003]] |
| Use requirements | Use Requirements Specification | [[SPE-003]] |
| Software requirements | Software Requirements Specification | [[SPE-001]] |
| Software architecture and design | Software Development Plan (architecture sections) | [[PLN-005]] |
| AI model design | Software Development Plan (AI sections), Product Specification | [[PLN-005]], [[SPE-002]] |
| Software verification test specs and execution reports | Software Verification Test Specifications | [[TST-001]] |
| Software release | Software Release Record | [[RPT-005]] |
| Validation reports | Software Validation Report | [[RPT-003]] |

### 3.3 Risk Management

| Scarlet Category | Our Document(s) | Document ID(s) |
|---|---|---|
| Risk management plan | Risk Management Plan | [[PLN-001]] |
| Risk management file | Risk Management File | [[RA-001]] |
| Risk management report | Risk Management Report | [[RPT-002]] |

### 3.4 Usability Engineering

| Scarlet Category | Our Document(s) | Document ID(s) |
|---|---|---|
| Usability engineering summative evaluation plans | Usability Engineering Plan (Sections 8.1-8.2) | [[PLN-006]] |
| Usability engineering summative evaluation reports | Usability Engineering Summative Evaluation Report | [[RPT-004]] |

### 3.5 Clinical Evidence

| Scarlet Category | Our Document(s) | Document ID(s) |
|---|---|---|
| Clinical evaluation plan | Clinical Evaluation Plan | [[PLN-002]] |
| Clinical literature review | Clinical Evaluation Report (Section 5) | [[CE-001]] |
| Clinical investigation plan(s) | N/A — no clinical investigation conducted | -- |
| Clinical investigation report(s) | N/A — no clinical investigation conducted | -- |
| Clinical evaluation report | Clinical Evaluation Report | [[CE-001]] |

### 3.6 Labeling and Accompanying Documentation

| Scarlet Category | Our Document(s) | Document ID(s) |
|---|---|---|
| Label | Device Labeling (electronic) | [[LBL-001]] |
| Instructions for Use | Device Labeling (IFU section) | [[LBL-001]] |

### 3.7 Supporting Documentation (Non-blocking)

| Scarlet Category | Our Document(s) | Document ID(s) |
|---|---|---|
| Cybersecurity threat modelling | Cybersecurity Management Procedure | [[SOP-016]] |
| Cybersecurity testing | *Included in TST-001 (TS-016, TS-017, TS-018)* | [[TST-001]] |
| Software traceability matrix | Software Traceability Matrix | [[TRC-001]] |
| Formative evaluation | Usability Evaluation Report (Section 3) | [[RPT-004]] |

## 4. Quality Management System Documentation

The following QMS documentation is maintained per ISO 13485:2016 and supports the Annex IX conformity assessment:

| Category | Documents |
|---|---|
| Quality Manual | [[QM-001]] |
| Quality Policy | [[POL-001]] |
| Document Control | [[SOP-001]] |
| Risk Management | [[SOP-002]] |
| CAPA | [[SOP-003]] |
| Complaint Handling | [[SOP-004]] |
| Internal Audit | [[SOP-005]] |
| Management Review | [[SOP-006]] |
| Design and Development | [[SOP-007]] |
| Purchasing and Supplier Control | [[SOP-008]] |
| Post-Market Surveillance | [[SOP-009]] |
| Training and Competency | [[SOP-010]] |
| Software Lifecycle | [[SOP-011]] |
| Clinical Evaluation | [[SOP-012]] |
| Vigilance | [[SOP-013]] |
| Product Identification and Traceability | [[SOP-014]] |
| Control of Nonconforming Product | [[SOP-015]] |
| Cybersecurity | [[SOP-016]] |
| Change Management | [[SOP-017]] |

## 5. Contact

For questions regarding this submission:

| Item | Detail |
|---|---|
| Contact person | Sarp Derinsu |
| Email | sarp@therapeak.com |
| Regulatory consultant | Suzan Slijpen (Pander Consultancy) |

## 6. Change History

| Version | Date | Description |
|---|---|---|
| 1.0 | 2026-04-01 | Initial release |
