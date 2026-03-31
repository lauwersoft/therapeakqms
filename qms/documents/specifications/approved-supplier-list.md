---
id: "LST-001"
title: "Approved Supplier List"
type: "LST"
version: "1.0"
status: "approved"
effective_date: "2026-03-01"
author: "Sarp Derinsu"
iso_refs:
  - "7.4.1"
mdr_refs:
  - "Article 10(9)"
---

# Approved Supplier List

## 1. Purpose

This document maintains the current list of approved suppliers for Therapeak B.V. It documents each supplier's service, criticality classification, quality system evidence, data processing agreement status, and approval status. This list is maintained in accordance with [[SOP-008]] Purchasing and Supplier Control Procedure.

**Related documents:** [[SOP-008]] Purchasing and Supplier Control Procedure, [[FM-005]] Supplier Evaluation Form

## 2. Supplier Classification Criteria

| Classification | Criteria |
|---------------|----------|
| **Critical** | Directly affects device safety, performance, or data integrity. Failure or degradation would impact patient safety or device availability. |
| **Non-critical** | Supports development or business operations but does not directly affect device output or patient safety. Alternative readily available. |

## 3. Critical Suppliers

| Supplier | Service | Criticality | Quality System | DPA Status | Approval Status | Last Evaluation | Next Review |
|----------|---------|-------------|---------------|------------|----------------|----------------|-------------|
| Hetzner | Dedicated server hosting (production) | Critical | TUV-audited data center; ISO 27001 | Signed (2026-03-25) | Approved | 2026-03-01 | 2027-03-01 |
| Anthropic | AI model provider (Claude Sonnet/Opus) | Critical | SOC 2; responsible AI policies | Standard DPA reviewed | Approved | 2026-03-01 | 2027-03-01 |
| OpenRouter | API gateway — routes requests to Anthropic | Critical | SOC 2 (review published policies) | Standard DPA reviewed | Approved | 2026-03-01 | 2027-03-01 |
| OpenAI | AI models for content generation, session summaries, monitoring | Critical | SOC 2; ISO 27001 | Standard DPA reviewed | Approved | 2026-03-01 | 2027-03-01 |
| Stripe | Payment processing (subscriptions) | Critical | PCI DSS Level 1; SOC 2; ISO 27001 | Standard DPA accepted | Approved | 2026-03-01 | 2027-03-01 |
| AWS SES | Transactional email delivery | Critical | SOC 2; ISO 27001; CSA STAR | Standard DPA accepted | Approved | 2026-03-01 | 2027-03-01 |

### 3.1 Critical Supplier Notes

**Hetzner:** All device software and patient data is hosted on Hetzner dedicated server infrastructure in Nuremberg, Germany (EU). The DPA was signed on 2026-03-25 via the Hetzner customer portal and covers personal master data, communication data, and health data (Article 9 GDPR). Hetzner data centers are TUV-audited.

**Anthropic:** The AI model provider. Provides Claude Sonnet 4.5, Claude Sonnet 4.6, and fallback models (Opus 4, Sonnet 4, Sonnet 3.7). API inputs/outputs are not used for training by default. Data retained for up to 30 days for trust and safety monitoring, then deleted. Anthropic is the entity responsible for the AI model's behavior, safety training, and capabilities.

**OpenRouter:** An API gateway that routes requests to Anthropic's models via multiple infrastructure providers (Google Vertex AI, Amazon Bedrock, Anthropic's direct API). OpenRouter does NOT provide AI models — it provides routing and redundancy. If one infrastructure provider is down, OpenRouter routes through another. Data sharing was turned OFF as of 2026-03-25. OpenRouter retains request metadata for billing and may briefly retain prompt/completion text for abuse monitoring.

**OpenAI:** Provides GPT-4o for session summaries, user reports, and session quality monitoring (ChatDebugFlag jobs). Provides GPT-3.5-turbo for content moderation of platform content.

**Stripe:** Processes all user subscriptions with per-country pricing tiers. EU data processing is available. Service disruption would prevent user access to paid features.

**AWS SES:** Delivers transactional emails including session summary emails (which contain therapy summary text), verification emails, and password resets. EU region (eu-north-1, Stockholm) is selected for SMTP.

## 4. Non-Critical Suppliers

| Supplier | Service | Criticality | Quality System | DPA Status | Approval Status | Last Evaluation | Next Review |
|----------|---------|-------------|---------------|------------|----------------|----------------|-------------|
| Fal.ai | AI image generation (therapist avatars) | Non-critical | Published privacy policy | N/A | Approved | 2026-03-01 | 2028-03-01 |
| GitHub | Source code repository | Non-critical | SOC 2; ISO 27001 | N/A | Approved | 2026-03-01 | 2028-03-01 |
| Google Workspace | Business email, documents | Non-critical | SOC 2; ISO 27001 | N/A | Approved | 2026-03-01 | 2028-03-01 |
| Vimexx | Domain registration | Non-critical | Published privacy policy | N/A | Approved | 2026-03-01 | 2028-03-01 |
| JetBrains | IDE (PhpStorm) | Non-critical | Published privacy policy | N/A | Approved | 2026-03-01 | 2028-03-01 |
| Google One | Cloud storage | Non-critical | SOC 2; ISO 27001 | N/A | Approved | 2026-03-01 | 2028-03-01 |

### 4.1 Non-Critical Supplier Notes

**Fal.ai:** Used for AI-generated therapist avatar images via Flux Pro model. Cosmetic feature only — pre-generated avatars are available as fallback. One outage occurred historically, affecting avatar generation only (not therapy sessions).

**GitHub:** Source code repository for development. Code exists locally and can be migrated to an alternative provider.

**Google Workspace:** Business communication and documentation. Not part of device function.

**Vimexx:** Domain name registration only. Transferable to alternative registrar.

**JetBrains:** PhpStorm IDE for software development. Alternative IDEs are available.

**Google One:** Cloud storage for business documents. Not part of device function.

## 5. Review Schedule

| Supplier Type | Review Frequency |
|---------------|-----------------|
| Critical suppliers | Annually |
| Non-critical suppliers | Every 2 years or when issues arise |

Annual supplier reviews are conducted as part of management review input per [[SOP-005]]. Review findings are documented in the [[FM-005]] Supplier Evaluation Form and this list is updated accordingly.

## 6. Change History

| Version | Date | Description |
|---------|------|-------------|
| 1.0 | 2026-03-01 | Initial release — all current suppliers evaluated and approved |

## 7. References

- [[SOP-008]] Purchasing and Supplier Control Procedure
- [[FM-005]] Supplier Evaluation Form
- [[SOP-005]] Management Review Procedure
- [[SOP-006]] Risk Management Procedure
- ISO 13485:2016 Clause 7.4.1
- EU MDR 2017/745 Article 10(9)
