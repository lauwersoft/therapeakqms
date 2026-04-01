# Use Requirements Specification

This document defines the use requirements for EpiFlare Version 2.0 as required under EU MDR 2017/745 and IEC 62304.

> Disclaimer (Fictional Example - Beta Release): This document is part of a fictional example technical file provided by Scarlet for educational purposes only. Manufacturers remain responsible for generating evidence appropriate to their medical device.

## Document Control Information

| Field | Value |
|-------|-------|
| Title | Use Requirements Specification |
| Document ID | SAI-EF-UREQ-001 |
| Version | 1.0 |
| Publication date | 2026-12-15 |
| Author(s) | Ingrid Morales |
| Approver(s) | Martin Hauer |

| Change ID | Version | Content changes |
|-----------|---------|-----------------|
| N/A | 1.0 | Initial publication |

## 1. Purpose

Use requirements define how the medical device software is intended to be used, including user needs, intended use environment, user characteristics, and use scenarios. These requirements form the basis for software requirements and usability engineering, as required by:

- IEC 82304-1:2016, Section 4 - Health software - Part 1: General requirements for product safety, which requires comprehensive use requirements analysis for SaMD products
- IEC 62304:2006+AMD1:2015+AMD2:2021, Section 5.1 - Software requirements analysis, which requires use requirements as input to software requirements analysis
- IEC 62366-1:2015+AMD1:2020, Section 5.2 - Application of usability engineering to medical devices, which requires use specification including user needs, intended use environment, and user characteristics
- IEC 81001-5-1:2021, Section 5.2.1 - Health software and health IT systems safety, effectiveness and security - Part 5-1: Security - Activities in the product life cycle, which requires security requirements to be defined at the use requirements level

## 2. Use Requirements Context

This section provides a summary of contextual information that informs the use requirements. Detailed documentation is maintained in the referenced source documents.

### 2.1 User Needs Summary

Twelve user needs have been identified through clinical evaluation, user interviews, and literature review:

| ID | User Need Summary | Priority |
|----|-------------------|----------|
| UN-001 | Remote monitoring of CDD disease activity | High |
| UN-002 | Quantitative, objective disease assessment | High |
| UN-003 | Timely alerts for potential flares | High |
| UN-004 | Patient image capture from home | Medium |
| UN-005 | Image capture guidance | Medium |
| UN-006 | Longitudinal disease trend access | High |
| UN-007 | Data protection and confidentiality | High |
| UN-008 | Secure authentication | High |
| UN-009 | Software update capability | Medium |
| UN-010 | Update notifications | Medium |
| UN-011 | Multi-channel clinical alert delivery | High |
| UN-012 | System status and guidance messages | Medium |

Full details: Usability Engineering File (Document ID: [UEF-001]), Clinical Evaluation Report (Document ID: [CER-001])

### 2.2 Intended Purpose and Environment Summary

| Aspect | Summary |
|--------|---------|
| Intention | Monitoring of disease activity |
| Clinical indications | CDD |
| Primary users | Qualified healthcare professionals (dermatologists, primary care physicians) |
| Secondary users | Adult patients (>=18 years) with confirmed CDD diagnosis |
| User environment | Outpatient dermatology clinics, primary care practices, home environment |
| Technical environment | iOS 14.0+/Android 8.0+ smartphones; web browsers; cloud backend |

Full details: Device Description (Document ID: [DD-001]), Usability Engineering File (Document ID: [UEF-001])

### 2.3 Use Scenarios Summary

Use scenarios are documented in detail in the Usability Engineering File and Risk Management File. The following table summarizes the key scenario categories:

| Scenario Type | Examples | Source Document |
|--------------|----------|-----------------|
| Normal use | Routine monitoring, alert-triggered review, authentication, software updates | UEF-001 (Use specification) |
| Alternative use | Patient-initiated capture, session timeout, critical updates | UEF-001 (Use specification) |
| Edge cases | Poor image quality, device incompatibility, alert fatigue | UEF-001 (Use-related risk analysis) |
| Error scenarios | Network failure, failed authentication, security incidents, update failures, alert delivery failures | RMF-001 (Risk analysis), UEF-001 (Use error analysis) |
| Recovery scenarios | System maintenance, rollback | RMF-001 (Risk controls) |

Full details: Usability Engineering File (Document ID: [UEF-001]), Risk Management File (Document ID: [RMF-001])

## 3. Use Requirements

The following use requirements are organized according to categories outlined in IEC 82304-1:2016, Clause 4.2. Categories not applicable to EpiFlare (e.g., requirements for hardware accessories) are excluded with justification documented in the Requirements Rationale (Document ID: [RR-001]).

### 3.1 Requirement Categories

| Category | Description | IDs |
|----------|-------------|-----|
| Functional | Intended behaviour: feature logic, use cases, workflows, responses to inputs | UR-001 to UR-008 |
| Performance | Measurable characteristics: timing, throughput, capacity, load performance | UR-009 to UR-013 |
| Risk-control | Safe operation: hazard mitigation, alarms/warnings, fault detection, fail-safe behaviour (traced to RMF-001) | UR-014 to UR-020 |
| User-interface | Interface design: usability per IEC 62366-1, layout, accessibility, use error prevention | UR-021 to UR-028 |
| Interface | Software/system interactions: APIs, protocols, inputs/outputs, error handling | UR-029 to UR-033 |
| Data-definition | Data handling: formats, validation, storage, retention | UR-034 to UR-037 |
| Artificial intelligence | ML/AI elements: training data, model design, performance thresholds | UR-038 to UR-041 |
| Operational/lifecycle | Deployment and maintenance: installation, updates, rollback, recovery | UR-042 to UR-048 |
| Cybersecurity | Secure operation per IEC 81001-5-1: access control, encryption, threat mitigation, audit logging | UR-049 to UR-059 |
| Compliance/regulatory | Legal obligations: healthcare data management, GDPR, audit logging | UR-060 to UR-063 |

### 3.2 Use Requirements Table

| ID | Category | Requirement |
|----|----------|-------------|
| UR-001 | Functional | System shall allow patients to capture skin images using smartphone camera |
| UR-002 | Functional | System shall provide real-time guidance to patients during image capture |
| UR-003 | Functional | System shall analyze captured images and calculate IDM value |
| UR-004 | Functional | System shall store IDM values in patient record |
| UR-005 | Functional | System shall display IDM values and trends to healthcare professionals |
| UR-006 | Functional | System shall generate automated alerts when IDM exceeds predefined thresholds |
| UR-007 | Functional | System shall allow healthcare professionals to set patient-specific alert thresholds |
| UR-008 | Functional | System shall securely transmit images and data between components |
| UR-009 | Performance | System shall calculate IDM value within 30 seconds of image upload under normal operating conditions |
| UR-010 | Performance | System shall display IDM values to healthcare professionals within 5 seconds of request under normal operating conditions |
| UR-011 | Performance | System shall generate alerts within 1 minute of threshold exceedance |
| UR-012 | Performance | System shall maintain 99.5% uptime availability |
| UR-013 | Performance | System shall support concurrent use by at least 100 healthcare professionals and patients simultaneously |
| UR-014 | Risk-control | System shall require healthcare professional interpretation of IDM values (not for standalone diagnostic use) |
| UR-015 | Risk-control | System shall provide warnings when image quality is inadequate for reliable IDM calculation |
| UR-016 | Risk-control | System shall require confirmed CDD diagnosis before patient enrollment |
| UR-017 | Risk-control | System shall restrict use to adult patients (>=18 years) |
| UR-018 | Risk-control | System shall deliver clinical alerts through multiple configurable channels (in-app, email, SMS) |
| UR-019 | Risk-control | System shall require acknowledgment of clinical alerts and track acknowledgment status |
| UR-020 | Risk-control | System shall provide fallback delivery mechanisms when primary alert channels fail |
| UR-021 | User-interface | Mobile application shall be usable by patients with basic smartphone literacy |
| UR-022 | User-interface | Clinical interface shall be usable by healthcare professionals with standard web browser experience |
| UR-023 | User-interface | Image capture guidance shall be clear and easy to follow with visual feedback |
| UR-024 | User-interface | IDM display shall be clear and interpretable by healthcare professionals with appropriate context |
| UR-025 | User-interface | System shall provide adequate feedback to users for all actions |
| UR-026 | User-interface | System shall display system status notifications (maintenance, degraded performance) prominently to users |
| UR-027 | User-interface | System shall provide clear, actionable guidance messages during patient workflows |
| UR-028 | User-interface | System shall provide alert summary and filtering capabilities to support alert management |
| UR-029 | Interface | System shall provide REST API interfaces between mobile application and backend services |
| UR-030 | Interface | System shall provide REST API interfaces between clinical interface and backend services |
| UR-031 | Interface | System shall integrate with push notification services (APNs, FCM) for alert delivery |
| UR-032 | Interface | System shall integrate with email (SMTP/API) and SMS services for alert delivery |
| UR-033 | Interface | System shall validate all inputs and outputs at interface boundaries |
| UR-034 | Data-definition | System shall store patient health data in structured format with defined data types and ranges |
| UR-035 | Data-definition | System shall validate IDM values against expected ranges (0-300) before storage |
| UR-036 | Data-definition | System shall maintain data integrity through transaction management and backup procedures |
| UR-037 | Data-definition | System shall retain patient data according to applicable retention requirements |
| UR-038 | AI | IDM calculation model shall achieve RMSE <=3 IDM units against dermatologist reference standard |
| UR-039 | AI | IDM calculation model shall achieve >=80% agreement within +/-2 IDM units of dermatologist assessment |
| UR-040 | AI | Training data shall be representative of target patient population demographics and disease presentation |
| UR-041 | AI | Model performance shall be validated against geographically diverse patient populations |
| UR-042 | Operational | System shall provide a mechanism for users to receive and install mobile application updates via app stores |
| UR-043 | Operational | System shall validate the integrity and authenticity of software updates before installation (e.g., code signing) |
| UR-044 | Operational | System shall allow backend updates to be deployed with minimal disruption to users |
| UR-045 | Operational | System shall support rollback or recovery mechanisms in case of update installation failure |
| UR-046 | Operational | System shall notify users when software updates are available, including information about changes |
| UR-047 | Operational | System shall inform users when their device or operating system no longer meets minimum requirements |
| UR-048 | Operational | System shall maintain version information accessible to users and support personnel |
| UR-049 | Cybersecurity | System shall require user authentication before granting access to patient data or clinical functions |
| UR-050 | Cybersecurity | System shall support multi-factor authentication (MFA) for healthcare professional accounts |
| UR-051 | Cybersecurity | System shall implement role-based access control (RBAC) to restrict access to authorized functions |
| UR-052 | Cybersecurity | System shall encrypt all data in transit using TLS 1.2 or higher |
| UR-053 | Cybersecurity | System shall encrypt all patient data at rest using AES-256 or equivalent |
| UR-054 | Cybersecurity | System shall automatically terminate user sessions after a configurable period of inactivity |
| UR-055 | Cybersecurity | System shall implement account lockout after a defined number of consecutive failed authentication attempts |
| UR-056 | Cybersecurity | System shall validate and sanitize all user inputs to prevent injection attacks |
| UR-057 | Cybersecurity | System shall detect and respond to suspected security incidents, including alerting administrators |
| UR-058 | Cybersecurity | System shall maintain audit logs of all authentication events, data access, and security-relevant actions |
| UR-059 | Cybersecurity | System shall notify users of critical security updates requiring immediate action |
| UR-060 | Compliance | System shall log all alerts including generation time, delivery attempts, and acknowledgment for audit purposes |
| UR-061 | Compliance | System shall support data subject access requests in compliance with GDPR Article 15 |
| UR-062 | Compliance | System shall support data portability requests in compliance with GDPR Article 20 |
| UR-063 | Compliance | System shall implement data deletion capabilities in compliance with GDPR Article 17 (right to erasure) |

## 4. Traceability

Full bidirectional traceability is maintained in the Software Traceability Matrix (Document ID: [STM-001]). The following matrix summarizes traceability between use requirement categories and related artifacts.

### 4.1 Traceability Matrix

| UR Category | IDs | User Needs | Upstream Sources | Downstream Outputs |
|-------------|-----|------------|------------------|--------------------|
| Functional | UR-001 to UR-008 | UN-001 to UN-006 | DD-001, CEP-001 | SR-001, UEP-001 |
| Performance | UR-009 to UR-013 | UN-001 to UN-003 | DD-001, CEP-001 | SR-001, SVS-001 |
| Risk-control | UR-014 to UR-020 | UN-003, UN-011 | RMF-001, ISO 14971 | SR-001, RMF-001 |
| User-interface | UR-021 to UR-028 | UN-005, UN-012 | UEF-001, IEC 62366-1 | SR-001, UEP-001, URRA-001 |
| Interface | UR-029 to UR-033 | UN-004, UN-011 | DD-001 | SR-001, SADD-001 |
| Data-definition | UR-034 to UR-037 | UN-002, UN-006 | DD-001, GDPR | SR-001 |
| AI | UR-038 to UR-041 | UN-002 | CEP-001, IMDRF N67 | SR-001, CEP-001 |
| Operational | UR-042 to UR-048 | UN-009, UN-010 | SMP-001, IEC 62304 S6.2 | SR-001, SMP-001 |
| Cybersecurity | UR-049 to UR-059 | UN-007, UN-008 | CSRA-001, IEC 81001-5-1, MDCG 2019-16 | SR-001, CSRA-001 |
| Compliance | UR-060 to UR-063 | UN-007 | GDPR, MDR | SR-001 |

### 4.2 Document References

| ID | Document |
|----|----------|
| DD-001 | Device Description |
| CEP-001 | Clinical Evaluation Plan |
| RMF-001 | Risk Management File |
| UEF-001 | Usability Engineering File |
| UEP-001 | Usability Engineering Plan |
| URRA-001 | Use-related Risk Analysis |
| SMP-001 | Software Maintenance Plan |
| CSRA-001 | Cybersecurity Risk Assessment |
| SR-001 | Software Requirements |
| SVS-001 | Software Verification Specs |
| SADD-001 | Software Architecture and Detailed Design |
| STM-001 | Software Traceability Matrix |

## 5. Review and Approval

Use requirements have been reviewed for completeness, accuracy, feasibility, verifiability, and traceability per the requirements review procedure.

| Review Activity | Participants | Result |
|----------------|-------------|--------|
| Requirements review | Clinical, Development, Regulatory, QA, Usability | Approved - minor clarifications made |
| Validation against intended use | Clinical, Regulatory | Approved |
| Verification against user needs | Development, Usability | Approved |

| Checklist | Status |
|-----------|--------|
| All user needs addressed | Done |
| Requirements accurately reflect intended use | Done |
| Requirements are technically feasible | Done |
| Requirements are verifiable | Done |
| Traceability established | Done |

> Fictional example only | Not regulatory advice | Beta content - incomplete and subject to change
