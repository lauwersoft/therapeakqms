# Software Requirements Specification

This document defines the software requirements for EpiFlare Version 2.0 as required under EU MDR 2017/745 and IEC 62304.

> Disclaimer (Fictional Example - Beta Release): This document is part of a fictional example technical file provided by Scarlet for educational purposes only. Manufacturers remain responsible for generating evidence appropriate to their medical device.

## Document Control Information

| Field | Value |
|-------|-------|
| Title | Software Requirements Specification |
| Document ID | SAI-EF-SWREQ-001 |
| Version | 1.0 |
| Publication date | 2026-12-20 |
| Author(s) | Ingrid Morales |
| Approver(s) | Martin Hauer |

| Change ID | Version | Content changes |
|-----------|---------|-----------------|
| N/A | 1.0 | Initial publication |

## 1. Purpose

The software requirements documentation contains a set of uniquely identifiable software requirements related to the medical device, which comprehensively meet the requirements analysis expectations described in IEC 62304 Section 5.2. This document addresses the requirements specified in IEC 62304:2006+AMD1:2015+AMD2:2021, Section 5.2 (Software requirements analysis).

## 2. Regulatory Basis

This document addresses the following regulatory requirements:

- Regulation (EU) 2017/745, Annex II - Technical documentation requirements for medical devices, which requires documentation of software requirements per MDR Annex II, Section 1.1(d)
- IEC 62304:2006+AMD1:2015+AMD2:2021, Section 5.2 - Software requirements analysis, which requires:
  - Identification of software requirements per Section 5.2.1
  - Documentation of software requirements per Section 5.2.2
  - Review and approval of software requirements per Section 5.2.3
  - Traceability of software requirements per Section 5.2.4
- MDR Annex I, GSPR 17.1 - Software lifecycle and validation requirements
- IEC 81001-5-1:2021, Section 5.2.1 - for security requirements
- ISO 14971:2019, Clause 6 - for safety-related requirements
- IEC 82304-1:2016, Section 4.5 - for system requirements

## 3. Software Requirements

### 3.1 Requirement Categories

| Category | Description | IDs |
|----------|-------------|-----|
| Functional | Intended behaviour: feature logic, use cases, workflows, responses to inputs | SR-001 to SR-008 |
| Performance | Measurable characteristics: timing, throughput, capacity, load performance | SR-009 to SR-014 |
| Risk-control | Safe operation: hazard mitigation, alarms/warnings, fault detection, fail-safe behaviour | SR-015 to SR-023 |
| User-interface | Interface design: usability per IEC 62366-1, layout, accessibility, use error prevention | SR-024 to SR-031 |
| Interface | Software/system interactions: APIs, protocols, inputs/outputs, error handling | SR-032 to SR-036 |
| Data-definition | Data handling: formats, validation, storage, retention | SR-037 to SR-040 |
| Artificial intelligence | ML/AI elements: training data, model design, performance thresholds | SR-041 to SR-044 |
| Operational/lifecycle | Deployment and maintenance: installation, updates, rollback, recovery | SR-045 to SR-051 |
| Cybersecurity | Secure operation per IEC 81001-5-1: access control, encryption, threat mitigation, audit logging | SR-052 to SR-062 |
| Compliance/regulatory | Legal obligations: healthcare data management, GDPR, audit logging | SR-063 to SR-066 |

### 3.2 Requirement Structure

Each software requirement contains:

- Unique identifier: SR-XXX format (e.g., SR-001)
- Description: Clear, unambiguous requirement description
- Acceptance criteria: Measurable criteria for requirement verification
- Source: Traceability to use requirements, risk controls, and/or standards

### 3.3 Software Requirements Table

| ID | Category | Requirement | Acceptance Criteria | Source |
|----|----------|-------------|---------------------|--------|
| SR-001 | Functional | Mobile application shall allow users to capture skin images using smartphone camera | User can capture image; image stored locally before upload | UR-001 |
| SR-002 | Functional | Mobile application shall provide real-time guidance during image capture | Guidance displayed; feedback on distance, lighting, focus | UR-002, UR-025 |
| SR-003 | Functional | Mobile application shall securely upload captured images to backend | Images encrypted in transit; progress displayed; failures handled | UR-001, UR-008 |
| SR-004 | Functional | Backend shall analyze images and calculate IDM values | IDM calculated within 30s; values in range 0-300; RMSE <=3 | UR-003, UR-040 |
| SR-005 | Functional | System shall store IDM values with metadata (timestamp, image reference) | Values stored with correct patient association; data retrievable | UR-004, UR-036 |
| SR-006 | Functional | Clinical interface shall display IDM values and trends | Values displayed clearly; trends visualized; accessible within 5s | UR-005, UR-026 |
| SR-007 | Functional | System shall generate alerts when IDM exceeds thresholds (>99 or >2 SD increase) | Alerts generated within 1 min; delivered to HCP; content actionable | UR-006, UR-019 |
| SR-008 | Functional | Clinical interface shall allow HCPs to set patient-specific thresholds | Thresholds configurable per patient; saved and applied correctly | UR-007 |
| SR-009 | Performance | System shall calculate IDM within 30s under normal operating conditions | 95% within 30s; max 60s; degradation communicated | UR-009 |
| SR-010 | Performance | Clinical interface shall display IDM within 5s under normal conditions | 95% within 5s; max 10s; degradation communicated | UR-010 |
| SR-011 | Performance | System shall generate alerts within 1 min of threshold exceedance | 100% within 1 min; reliable delivery; delays logged | UR-011 |
| SR-012 | Performance | System shall maintain 99.5% uptime excluding scheduled maintenance | >=99.5% monthly; downtime logged; outages trigger notification | UR-012 |
| SR-013 | Performance | System shall support >=100 concurrent users | No degradation at 100 users; graceful degradation above | UR-013 |
| SR-014 | Performance | Backend updates deployable with minimal disruption | Rolling/blue-green deployment; <5 min interruption; 24h notice | UR-046 |
| SR-015 | Risk-control | System shall require HCP interpretation of IDM values | Warning displayed; IFU states requirement | UR-014, RC-001 |
| SR-016 | Risk-control | System shall validate image quality and warn when inadequate | Poor quality detected; warning displayed; re-capture requested | UR-015, RC-002 |
| SR-017 | Risk-control | System shall require confirmed CDD diagnosis before use | HCP confirms diagnosis before enrollment; documented | UR-016, RC-003 |
| SR-018 | Risk-control | System shall restrict use to adult patients (>=18 years) | Age validated; under-18 prevented; enforced | UR-017, RC-004 |
| SR-019 | Risk-control | System shall deliver alerts through multiple channels (in-app, email, SMS) | All configured channels attempted; delivery tracked | UR-019 |
| SR-020 | Risk-control | System shall require alert acknowledgment and track status | Explicit acknowledgment; timestamp/user recorded; unacknowledged tracked | UR-020 |
| SR-021 | Risk-control | System shall escalate unacknowledged alerts after defined period | Escalation configurable; automatic trigger; recipients configurable | UR-021 |
| SR-022 | Risk-control | System shall provide fallback when primary alert channels fail | Failures detected; alternatives attempted; status tracked | UR-022 |
| SR-023 | Risk-control | System shall allow HCPs to configure alert thresholds and preferences | Preferences configurable; applied to subsequent alerts | UR-007, UR-030 |
| SR-024 | User-interface | Mobile application usable by patients with basic smartphone literacy | >=90% task completion; >=4.0/5.0 satisfaction | UR-023, IEC 62366-1 |
| SR-025 | User-interface | Clinical interface usable by HCPs with standard browser experience | >=95% task completion; >=4.0/5.0 satisfaction | UR-024, IEC 62366-1 |
| SR-026 | User-interface | Image capture guidance shall be clear and easy to follow | Users can follow without training; understandable | UR-025, IEC 62366-1 |
| SR-027 | User-interface | IDM display shall be clear and interpretable by HCPs | HCPs interpret correctly; intuitive format | UR-026, IEC 62366-1 |
| SR-028 | User-interface | System shall provide adequate feedback for all user actions | All actions receive feedback; timely and clear | UR-027, IEC 62366-1 |
| SR-029 | User-interface | System shall display system status notifications prominently | Notifications prominent; include resolution time; removed when resolved | UR-028 |
| SR-030 | User-interface | System shall provide guidance messages during patient workflows | Step-by-step guidance; clear and actionable | UR-029, IEC 62366-1 |
| SR-031 | User-interface | System shall provide alert summary and filtering capabilities | Summary view; filter by status/date/patient; statistics available | UR-030 |
| SR-032 | Interface | Mobile app shall communicate via secure REST API | API documented; TLS secured; errors handled gracefully | UR-031 |
| SR-033 | Interface | Clinical interface shall communicate via secure REST API | API documented; TLS secured; supports required functionality | UR-032 |
| SR-034 | Interface | Mobile app shall interface with smartphone camera | Camera accessible; controls available; permissions handled | UR-031 |
| SR-035 | Interface | System shall integrate with email/SMS services for alerts | SendGrid/AWS SES and Twilio integrated; status tracking | UR-033, UR-034 |
| SR-036 | Interface | Mobile app shall integrate with push notifications (APNs, FCM) | iOS/Android push supported; reliable delivery | UR-033 |
| SR-037 | Data-definition | System shall store patient data in structured format | Defined data types and ranges; validation on storage | UR-036 |
| SR-038 | Data-definition | System shall validate IDM values against expected ranges (0-300) | Out-of-range values rejected; validation logged | UR-037 |
| SR-039 | Data-definition | System shall maintain data integrity through transactions/backups | Integrity checks; corruption prevented; backup/recovery available | UR-038 |
| SR-040 | Data-definition | System shall retain patient data per regulatory requirements | Retention policies implemented; deletion supported | UR-039 |
| SR-041 | AI | IDM model shall achieve RMSE <=3 against dermatologist reference | Validation against reference dataset; RMSE measured | UR-040 |
| SR-042 | AI | IDM model shall achieve >=80% agreement within +/-2 IDM units | Agreement measured against dermatologist assessment | UR-041 |
| SR-043 | AI | Training data shall be representative of target population | Demographics and disease presentation representative | UR-042 |
| SR-044 | AI | Model performance validated against diverse populations | Geographic diversity in validation dataset | UR-043 |
| SR-045 | Operational | System shall provide update mechanism via app stores | iOS App Store/Google Play distribution; in-app notification | UR-044 |
| SR-046 | Operational | System shall validate update integrity via code signing | Binaries code-signed; platform validates; tampered rejected | UR-045 |
| SR-047 | Operational | System shall support rollback on update failure | Failed updates recoverable; previous version restorable | UR-047 |
| SR-048 | Operational | System shall notify users of available updates | Notification with version/changes; security changes highlighted | UR-048 |
| SR-049 | Operational | System shall notify when device no longer meets requirements | Incompatibility detected; requirements explained; guidance provided | UR-049 |
| SR-050 | Operational | System shall display version information to users/support | Version in settings/about; accessible for troubleshooting | UR-050 |
| SR-051 | Operational | Backend updates deployable with minimal user disruption | Rolling/blue-green deployment; <5 min interruption | UR-046 |
| SR-052 | Cybersecurity | System shall require authentication before data access | All users authenticate; unauthenticated rejected; state maintained | UR-051 |
| SR-053 | Cybersecurity | System shall support MFA for HCP accounts | MFA configurable; SMS/authenticator/email supported | UR-052 |
| SR-054 | Cybersecurity | System shall implement RBAC | Roles defined; access restricted; unauthorized blocked/logged | UR-053 |
| SR-055 | Cybersecurity | System shall encrypt data in transit (TLS 1.2+) | All communications TLS 1.2+; verified through testing | UR-054 |
| SR-056 | Cybersecurity | System shall encrypt data at rest (AES-256) | All patient data encrypted; keys managed securely | UR-055 |
| SR-057 | Cybersecurity | System shall terminate sessions after inactivity | Timeout configurable; defaults 15 min (clinical)/30 min (mobile) | UR-056 |
| SR-058 | Cybersecurity | System shall lock accounts after failed login attempts | Locked after 5 attempts; duration configurable; user notified | UR-057 |
| SR-059 | Cybersecurity | System shall validate/sanitize inputs to prevent injection | Server-side validation; SQL/XSS prevented; invalid rejected | UR-058 |
| SR-060 | Cybersecurity | System shall detect and respond to security incidents | Suspicious patterns detected; administrators alerted; logged | UR-059 |
| SR-061 | Cybersecurity | System shall maintain security audit logs | Auth events/data access logged; tamper-resistant; retained | UR-060 |
| SR-062 | Cybersecurity | System shall notify users of critical security updates | Critical updates flagged; multi-channel notification; may restrict functionality | UR-061 |
| SR-063 | Compliance | System shall log alerts (generation, delivery, acknowledgment) | All events timestamped; delivery tracked; available for audit | UR-062 |
| SR-064 | Compliance | System shall support GDPR data subject access requests | Article 15 compliance; data export capability | UR-063 |
| SR-065 | Compliance | System shall support GDPR data portability requests | Article 20 compliance; standard format export | UR-064 |
| SR-066 | Compliance | System shall support GDPR data deletion (right to erasure) | Article 17 compliance; deletion capability | UR-065 |

## 4. Requirement Evaluation

### 4.1 Requirements Review

**Review activities:**

- Requirements review meeting with stakeholders (clinical, development, QA, regulatory)
- Requirements validation against use requirements
- Requirements verification against intended use
- Requirements approval by authorized personnel

**Review criteria:**

- Completeness: All use requirements addressed
- Accuracy: Requirements accurately reflect intended use
- Feasibility: Requirements are technically feasible
- Verifiability: Requirements can be verified through testing or analysis
- Traceability: Requirements traceable to use requirements and intended use

**Review participants:** Clinical stakeholders, Software development team, Quality assurance, Regulatory affairs, Risk management

**Review approval:** Requirements reviewed and approved by: Clinical Lead, Software Development Lead, Quality Assurance, Regulatory Affairs.

### 4.2 Requirements Confirmation

Statement of confirmation: The software requirements are confirmed to be:

- Comprehensive: All use requirements (UR-001 through UR-050) are addressed by software requirements
- Feasible: All requirements are technically feasible and can be implemented with available resources
- Verifiable: All requirements have defined acceptance criteria and can be verified through testing or analysis

## 5. Traceability

Full bidirectional traceability is maintained in the Software Traceability Matrix (Document ID: [STM-001]). The following matrix summarizes traceability between software requirement categories and source artifacts.

| SR Category | IDs | Use Requirements | Standards/Risk Controls |
|-------------|-----|------------------|------------------------|
| Functional | SR-001 to SR-008 | UR-001 to UR-008 | -- |
| Performance | SR-009 to SR-014 | UR-009 to UR-013, UR-046 | -- |
| Risk-control | SR-015 to SR-023 | UR-014 to UR-022, UR-007, UR-030 | -- |
| User-interface | SR-024 to SR-031 | UR-023 to UR-030 | IEC 62366-1 |
| Interface | SR-032 to SR-036 | UR-031 to UR-035 | -- |
| Data-definition | SR-037 to SR-040 | UR-036 to UR-039 | -- |
| AI | SR-041 to SR-044 | UR-040 to UR-043 | CEP-001, IMDRF N67 |
| Operational | SR-045 to SR-051 | UR-044 to UR-050 | IEC 62304 S6.2 |
| Cybersecurity | SR-052 to SR-062 | UR-051 to UR-061 | IEC 81001-5-1, MDCG 2019-16 |
| Compliance | SR-063 to SR-066 | UR-062 to UR-065 | GDPR |

## 6. Review and Approval Checks

| Checklist | Status |
|-----------|--------|
| Software requirements are complete | Done |
| Requirements are reviewed | Done |
| Requirements are approved | Done |
| Traceability is established | Done |
| Requirements are testable | Done |
| All use requirements are addressed | Done |
| All risk controls are addressed | Done |
| Requirements meet quality characteristics | Done |

> Fictional example only | Not regulatory advice | Beta content - incomplete and subject to change
