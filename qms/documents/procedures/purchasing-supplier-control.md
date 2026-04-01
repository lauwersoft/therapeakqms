---
id: "SOP-008"
title: "Purchasing and Supplier Control Procedure"
type: "SOP"
category: "qms"
version: "1.0"
status: "approved"
effective_date: "2026-03-01"
author: "Sarp Derinsu"
iso_refs:
  - "7.4"
mdr_refs:
  - "Article 10(9)"
---

# Purchasing and Supplier Control Procedure

## 1. Purpose

This procedure defines the process for evaluating, selecting, monitoring, and re-evaluating suppliers of products and services that affect the quality and safety of Therapeak. It ensures that purchased products and services conform to specified requirements.

**Related documents:** [[FM-005]] Supplier Evaluation Form, [[LST-001]] Approved Supplier List, [[SOP-002]] Risk Management Procedure

## 2. Scope

This procedure applies to all suppliers whose products or services are incorporated into or directly support the Therapeak medical device, including:
- Infrastructure providers (hosting, databases)
- AI model providers (language models, image generation)
- Software services (email delivery, payment processing, development tools)
- Consulting services (regulatory affairs)

This procedure does not apply to general office supplies or services unrelated to the medical device.

## 3. Responsibilities

| Role | Responsibility |
|------|---------------|
| Sarp Derinsu (Management) | Evaluates and selects suppliers, monitors performance, conducts annual reviews, maintains [[LST-001]] |
| Suzan Slijpen (Consultant) | Advises on supplier qualification requirements for regulatory compliance |

## 4. Procedure

### 4.1 Supplier Classification

Suppliers are classified based on the impact of their product or service on the medical device:

| Classification | Criteria | Evaluation Rigor |
|---------------|----------|-----------------|
| **Critical** | Directly affects device safety, performance, or data integrity. Failure or degradation would impact patient safety or device availability. | Full evaluation, DPA where applicable, annual review |
| **Non-critical** | Supports development or business operations but does not directly affect device output or patient safety. Alternative readily available. | Simplified evaluation, periodic review |

### 4.2 Current Supplier Register

#### 4.2.1 Critical Suppliers

| Supplier | Service | Criticality Rationale |
|----------|---------|----------------------|
| **Hetzner** | Dedicated server hosting (production) | All device software and patient data hosted on Hetzner infrastructure. Downtime = device unavailable. |
| **Anthropic** | AI language model provider (Claude) | Provides the primary therapy AI models. Model quality directly affects device output. |
| **OpenRouter** | AI API gateway / routing | Routes API requests to Anthropic via multiple infrastructure providers (Vertex AI, Bedrock, Anthropic API) for high availability. Not an AI provider itself. |
| **OpenAI** | AI language models (GPT-4o) | Provides models for session summaries, user reports, and session quality monitoring. |
| **Stripe** | Payment processing | Processes all user subscriptions. Service disruption prevents user access. |
| **AWS SES** | Transactional email delivery | Delivers critical notifications (account verification, password reset, session reports). |

#### 4.2.2 Non-Critical Suppliers

| Supplier | Service | Rationale for Non-Critical |
|----------|---------|---------------------------|
| **Fal.ai** | AI image generation (therapist avatars) | Cosmetic feature only. Pre-generated avatars available as fallback. |
| **GitHub** | Source code repository | Development tool. Code exists locally and can migrate to alternative. |
| **Google Workspace** | Email, documents | Business communication. Not part of device function. |
| **Vimexx** | Domain registration | Domain management only. Transferable to alternative registrar. |
| **JetBrains** | IDE (PhpStorm) | Development tool. Alternative IDEs available. |

The complete and current supplier list is maintained in [[LST-001]].

### 4.3 Supplier Evaluation

#### 4.3.1 Initial Evaluation

Before adding a new critical supplier, Sarp shall evaluate the supplier using [[FM-005]] based on:

| Criterion | What to Assess | Evidence |
|-----------|---------------|----------|
| **Service capability** | Does the service meet technical requirements? | Documentation, feature set, API specifications |
| **Reliability** | Uptime guarantees, SLA terms, historical availability | Published SLA, status page history |
| **Security** | Data protection measures, certifications, encryption | Security documentation, certifications (e.g., ISO 27001, SOC 2, TUV audit) |
| **Data protection** | GDPR compliance, data processing terms, data location | DPA availability, privacy policy, data center locations |
| **Terms of service** | Acceptable terms, liability provisions, termination conditions | ToS review |
| **Business stability** | Company viability, market position | Public information, funding, customer base |

For non-critical suppliers, a simplified evaluation is performed: review of service capability and terms of service.

#### 4.3.2 Evaluation Approach for Large Providers

Therapeak operates as a small company using services from large, established providers (Anthropic, OpenAI, Hetzner, Stripe, AWS). In this context:

- Formal contracts beyond standard Terms of Service are not feasible — these providers offer standardized agreements
- Standard ToS and DPAs published by the provider are reviewed and accepted
- Evaluation focuses on publicly available evidence: certifications, audit reports, published security practices, and SLA commitments
- Provider-published compliance documentation (SOC 2 reports, ISO 27001 certificates, GDPR statements) is accepted as evidence of quality system adequacy

This approach is documented and justified in each [[FM-005]] evaluation.

### 4.4 Data Processing Agreements

For critical suppliers that process personal data or health-related data:

1. Review the provider's standard DPA for GDPR adequacy
2. Sign/accept the DPA where available
3. Document the DPA status in [[LST-001]]
4. Verify data processing location (EU preferred; adequate safeguards if non-EU)

| Supplier | DPA Status | Notes |
|----------|-----------|-------|
| Hetzner | Signed (March 25, 2026) | Data center: EU (Germany). TUV-audited. |
| OpenRouter | Standard DPA reviewed | US-based; standard contractual clauses apply |
| Anthropic | Standard DPA reviewed | US-based; standard contractual clauses apply |
| OpenAI | Standard DPA reviewed | US-based; standard contractual clauses apply |
| Stripe | Standard DPA accepted | EU data processing available |
| AWS SES | Standard DPA accepted | EU region selected |

### 4.5 Supplier Monitoring

Ongoing supplier performance is monitored through:

| Monitoring Method | Frequency | Applicable To |
|------------------|-----------|---------------|
| Service availability tracking | Continuous | Hetzner, OpenRouter, Anthropic, OpenAI |
| Error rate and response quality | Continuous (via Telescope) | OpenRouter, Anthropic, OpenAI |
| Incident review | As they occur | All critical suppliers |
| Security advisory review | As published | All critical suppliers |
| ToS/DPA change notifications | As received | All critical suppliers |

Sarp monitors supplier performance as part of daily operations. Significant issues (outages affecting patients, security incidents, ToS changes affecting compliance) are documented and assessed for impact on device safety and performance.

### 4.6 Annual Supplier Review

Once per year, Sarp shall review all critical suppliers:

1. Review performance over the past year (availability, quality, incidents)
2. Review any changes to ToS, DPA, or security certifications
3. Assess continued suitability against evaluation criteria
4. Update [[FM-005]] with review findings
5. Update [[LST-001]] with current approval status
6. Consider whether classification (critical/non-critical) remains appropriate

Non-critical suppliers are reviewed every 2 years or when issues arise.

Review results are included as input to [[SOP-006]] Management Review.

### 4.7 Supplier Issues and Changes

#### 4.7.1 Supplier Nonconformity

If a critical supplier fails to meet requirements:

1. Document the issue (nature, impact on device, duration)
2. Assess risk to patient safety per [[SOP-002]]
3. Implement immediate mitigation (e.g., switch to fallback AI model, activate backup system)
4. Communicate with supplier if applicable
5. If the issue is recurring or severe, initiate CAPA per [[SOP-003]]
6. Evaluate whether to replace the supplier

#### 4.7.2 Adding or Changing Suppliers

When adding a new supplier or replacing an existing one:

1. Perform initial evaluation per Section 4.3
2. For critical suppliers: assess risk impact of the change per [[SOP-002]]
3. Test the new service in the local development environment
4. Plan migration to minimize service disruption
5. Update [[LST-001]]
6. For AI model changes, follow design controls per [[SOP-007]]

### 4.8 Purchasing Information

For services procured through standard online agreements, purchasing information consists of:
- The selected service plan/tier
- Configuration settings applied
- The ToS/DPA version accepted

No formal purchase orders are issued for standard SaaS subscriptions.

## 5. Records

| Record | Retention | Reference |
|--------|-----------|-----------|
| Supplier Evaluation Form | Lifetime of device + 2 years | [[FM-005]] |
| Approved Supplier List | Lifetime of device + 2 years (current version + history) | [[LST-001]] |
| DPA copies/acceptance records | Lifetime of device + 2 years | — |
| Supplier incident records | Lifetime of device + 2 years | — |
| Annual review records | Lifetime of device + 2 years | [[FM-005]] |

## 6. References

- [[QM-001]] Quality Manual
- [[SOP-001]] Document Control Procedure
- [[SOP-002]] Risk Management Procedure
- [[SOP-003]] CAPA Procedure
- [[SOP-006]] Management Review Procedure
- [[SOP-007]] Design and Development Procedure
- [[FM-005]] Supplier Evaluation Form
- [[LST-001]] Approved Supplier List
- [ISO 13485:2016 Clause 7.4](/references/iso-13485#clause-7-4)
- [EU MDR 2017/745 Article 10(9)](/references/eu-mdr#article-10-general-obligations-of-manufacturers)
