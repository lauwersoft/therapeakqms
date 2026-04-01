# Software Architecture and Design

This document defines the software architectural design and detailed design for EpiFlare Version 2.0 as required under EU MDR 2017/745 and IEC 62304.

> Disclaimer (Fictional Example - Beta Release): This document is part of a fictional example technical file provided by Scarlet for educational purposes only. Manufacturers remain responsible for generating evidence appropriate to their medical device.

## Document Control Information

| Field | Value |
|-------|-------|
| Title | SAI-EF-Software Architecture and Design |
| Document ID | SADS-001 |
| Version | 1.0 |
| Publication date | 2026-01-15 |
| Author(s) | Ingrid Morales |
| Approver(s) | Martin Hauer |

| Change ID | Version | Content changes |
|-----------|---------|-----------------|
| N/A | 1.0 | Initial publication |

## 1. Purpose

For the development of software with a "Class B" software safety classification, the software architectural design and detailed design documentation define the structure of a software architecture in terms of software modules, interfaces, units, and SOUP and provide details on the intended design of these software components, as required by IEC 62304:2006+AMD1:2015+AMD2:2021, Section 5.3 (Software architectural design) and Section 5.4 (Software detailed design).

## 2. Regulatory Basis

This document addresses the following regulatory requirements:

- Regulation (EU) 2017/745, Annex II - Technical documentation requirements for medical devices
- IEC 62304:2006+AMD1:2015+AMD2:2021 - Medical device software - Software life cycle processes:
  - Section 5.3 - Software architectural design (for Class B and Class C software)
  - Section 5.4 - Software detailed design (for Class B and Class C software)
  - Section 5.5 - Software unit implementation and verification
  - Section 5.6 - Software integration and integration testing
  - Section 5.7 - Software system testing
- IEC 81001-5-1:2021 - Health software and health IT systems safety, effectiveness and security (for security architecture and design)
- MDR Annex I, GSPR 17.1 - Software lifecycle and validation requirements

## 3. Software Architecture

### 3.1 Architecture Overview

Per IEC 62304:2006+AMD1:2015+AMD2:2021, Section 5.3, the software system is decomposed into a hierarchy of software items (modules), with the lowest level of decomposition termed software units. Interactions between items, units, or external software systems are described by software interfaces.

**High-level architecture description:** EpiFlare consists of three main software items (modules):

- **Mobile Application (Patient-facing):** Native mobile application for iOS and Android platforms that enables patients to capture skin images and upload them securely to the backend
- **Clinical Interface (Clinician-facing):** Web-based application that enables healthcare professionals to view IDM values, trends, and alerts
- **Backend Processing Module:** Cloud-based service that performs image analysis, calculates IDM values, manages patient data, and handles alerting

**Architecture principles (per IEC 62304 guidance on modular architecture):**

- Separation of concerns: Software items are decomposed such that each component addresses a single concern or function
- Encapsulation and interfaces: Encapsulation separates internal logic within software items from interfaces shared with other components
- Security by design: Security controls integrated into architecture per IEC 81001-5-1:2021, Section 5.3
- Scalability: Architecture supports concurrent users and growing data volumes
- Maintainability: Modular design for ease of maintenance and updates
- Segregation of risk: Safety-critical software items are isolated from non-critical items

**Architecture patterns used:**

- Client-server architecture for mobile and web clients
- Microservices architecture for backend services
- RESTful API for communication between components
- Event-driven architecture for alerting

**System boundaries:**

- Mobile application: Runs on patient smartphones (iOS/Android)
- Clinical interface: Runs in web browsers
- Backend services: Cloud-based infrastructure
- External systems: Smartphone camera, network infrastructure

### 3.2 Architecture Diagram

The architecture diagram illustrates the three main software items (modules), their sub-modules, interfaces, and external dependencies.

**Diagram key:**

- Class B sub-modules (shaded): Safety-critical components requiring detailed documentation
- Class A sub-modules: Supporting infrastructure with summary documentation
- Interfaces I-001 to I-005: External interfaces as defined in the Software Interfaces section
- SOUP components: Third-party software dependencies (TensorFlow, PostgreSQL, notification services)

### 3.3 Software Items (Modules)

Per IEC 62304:2006+AMD1:2015+AMD2:2021, Section 5.3, the software system is decomposed into software items. For clarity and familiarity with industry terminology, this document refers to software items as "modules" while maintaining compliance with IEC 62304 terminology.

#### Software Item M-001: Mobile Application Core

- **Software item identification:** M-001
- **Description:** Core functionality software item for mobile application including image capture, image quality assessment, secure upload, user authentication, and update management.
- **Responsibilities:** Image capture using smartphone camera; Real-time image quality guidance and validation; Secure image upload to backend with TLS encryption; User authentication and session management; Push notification integration; Software update notifications and version display; User feedback and error handling; Local data storage with encryption
- **Interfaces:** Camera interface (native OS APIs) - Interface I-003; Network interface (HTTPS REST API) - Interface I-001; Push notification interface (APNs/FCM) - Interface I-004; Local storage interface
- **Relationships:** Interfaces with Backend Processing Module (M-003) via REST API (Interface I-001); interfaces with platform push notification services via Interface I-004
- **Software safety classification:** Class B (per IEC 62304:2006+AMD1:2015+AMD2:2021, Annex A)
- **Classification rationale:** This software item can contribute to hazardous situations (e.g., poor image quality leading to incorrect IDM values), but failures are limited to non-serious injuries.

#### Software Item M-002: Clinical Interface Core

- **Software item identification:** M-002
- **Description:** Core functionality software item for clinical web interface including IDM display, trend visualization, alert management, patient enrollment, and system notifications.
- **Responsibilities:** Display IDM values and trends with clear visualization; Alert presentation, acknowledgment tracking, and management; Alert escalation configuration; Patient record access and enrollment; Threshold and notification preference configuration; User authentication with MFA support; Role-based access control; Session management with timeout; System status notifications display; Healthcare professional interpretation warnings; User feedback for all actions
- **Interfaces:** Web browser interface; Network interface (HTTPS REST API) - Interface I-002
- **Relationships:** Interfaces with Backend Processing Module (M-003) via REST API (Interface I-002)
- **Software safety classification:** Class B
- **Classification rationale:** This software item can contribute to hazardous situations (e.g., incorrect IDM display leading to misinterpretation), but failures are limited to non-serious injuries.

#### Software Item M-003: Backend Processing Module

- **Software item identification:** M-003
- **Description:** Core backend software item for image analysis, IDM calculation, data management, alerting, security, and system operations.
- **Responsibilities:** Image analysis and IDM calculation with performance monitoring; Patient data management with GDPR compliance; Alert generation, multi-channel delivery, and escalation; Alert acknowledgment tracking and logging; User authentication with MFA support; Role-based authorization; Session management and account lockout; Data encryption in transit and at rest; Input validation and sanitization; Security incident detection and response; Security audit logging; Update integrity validation and rollback support; Critical security update notifications; Backend update deployment with minimal disruption; System availability monitoring; Concurrent user support; Integration with email/SMS delivery services
- **Interfaces:** REST API for mobile and clinical interfaces (Interfaces I-001, I-002); Database interface; Image processing interface; Alert delivery service interfaces (email, SMS) - Interface I-005
- **Relationships:** Interfaces with Mobile Application (M-001) via Interface I-001, Clinical Interface (M-002) via Interface I-002, and external alert delivery services via Interface I-005
- **Software safety classification:** Class B
- **Classification rationale:** This software item can contribute to hazardous situations (e.g., incorrect IDM calculation leading to inappropriate treatment decisions), but failures are limited to non-serious injuries.

#### Module to Requirements Traceability Matrix

| Module | Use Requirements | Software Requirements |
|--------|-----------------|----------------------|
| M-001: Mobile Application Core | UR-001, UR-002, UR-008, UR-009, UR-010, UR-012, UR-037, UR-039, UR-041, UR-042, UR-043, UR-044, UR-046 | SR-001, SR-002, SR-003, SR-009, SR-010, SR-012, SR-013, SR-026, SR-029, SR-031, SR-038, SR-040, SR-042, SR-043, SR-044, SR-045, SR-047, SR-050, SR-052, SR-054, SR-059 |
| M-002: Clinical Interface Core | UR-005, UR-006, UR-007, UR-009, UR-010, UR-011, UR-014, UR-015, UR-016, UR-017, UR-019, UR-023, UR-025, UR-026, UR-027, UR-038, UR-040, UR-041, UR-045, UR-047 | SR-006, SR-007, SR-008, SR-009, SR-010, SR-011, SR-014, SR-015, SR-016, SR-017, SR-018, SR-020, SR-025, SR-027, SR-028, SR-029, SR-030, SR-031, SR-039, SR-041, SR-042, SR-046, SR-048, SR-051, SR-060 |
| M-003: Backend Processing Module | UR-003, UR-004, UR-006, UR-008, UR-009, UR-010, UR-011, UR-013, UR-014, UR-015, UR-016, UR-017, UR-018, UR-020, UR-021, UR-022, UR-028, UR-029, UR-031, UR-032, UR-033, UR-048 | SR-004, SR-005, SR-007, SR-009, SR-010, SR-011, SR-013, SR-014, SR-015, SR-016, SR-017, SR-019, SR-021, SR-022, SR-023, SR-024, SR-029, SR-030, SR-032, SR-033, SR-034, SR-035, SR-036, SR-037, SR-049, SR-053, SR-055, SR-056, SR-057, SR-058, SR-061 |

### 3.4 Software Interfaces

| Interface ID | Name | Type | Description |
|-------------|------|------|-------------|
| I-001 | Mobile-Backend REST API | External | RESTful API for communication between mobile application and backend services |
| I-002 | Clinical-Backend REST API | External | RESTful API for communication between clinical interface and backend services |
| I-003 | Camera Interface | External (OS-level) | Native OS interface for smartphone camera access |
| I-004 | Push Notification Interface | External (platform services) | Platform push notification services for mobile alert and update notifications |
| I-005 | Alert Delivery Service Interface | External (third-party services) | External email and SMS delivery services for multi-channel alert notifications |

**Interface specifications:**

| Interface ID | Protocol | Authentication | Data Format | Key Endpoints/Capabilities |
|-------------|----------|----------------|-------------|---------------------------|
| I-001 | HTTPS (TLS 1.2+) | OAuth 2.0 with MFA | JSON | Image upload, authentication, patient data retrieval, version check |
| I-002 | HTTPS (TLS 1.2+) | OAuth 2.0 with MFA | JSON | IDM retrieval, trend data, alert management, threshold configuration, patient enrollment, system status |
| I-003 | Native OS API | OS-managed permissions | Binary (image) | iOS: AVFoundation; Android: Camera2 API |
| I-004 | Platform-specific | Platform credentials | JSON | iOS: APNs; Android: FCM; Types: alerts, updates, system status |
| I-005 | HTTPS | API key/OAuth | JSON | Email: SendGrid/AWS SES; SMS: Twilio; Delivery status webhooks; Auto-retry |

**Interface security considerations:**

| Interface ID | Security Controls |
|-------------|-------------------|
| I-001 | TLS 1.2+ encryption; Authentication required for all requests; Authorization checks for data access; Input validation and sanitization; Session management with timeout |
| I-002 | TLS 1.2+ encryption; Authentication required for all requests; Role-based authorization for healthcare professional access; Input validation and sanitization; Session management with timeout |
| I-003 | Camera access permissions managed by OS; Images stored locally before upload; No persistent local storage of sensitive data |
| I-004 | Push notification credentials secured; Notification payloads do not contain sensitive patient data; Device tokens managed securely |
| I-005 | API credentials secured using secrets management; TLS encryption for all API communications; Delivery logs maintained for audit; Rate limiting to prevent abuse |

### 3.5 Software Item Segregation

For Class B software, segregation requirements are not as stringent as Class C. However, the following segregation is implemented:

- **Segregation requirements:** Critical functionality (IDM calculation, alerting) is segregated from non-critical functionality (UI, data display)
- **Segregation rationale:** Segregation ensures that failures in non-critical components do not impact critical safety-related functionality
- **How segregation achieves safety:** Critical components are isolated and can continue to function even if non-critical components fail

## 4. Software Detailed Design

### 4.1 Design Decomposition

**Decomposition levels:**

- Level 1: Top-level modules (M-001, M-002, M-003)
- Level 2: Sub-modules within each top-level module
- Level 3: Software units within each sub-module

In accordance with ISO 62304:2006/AMD1:2015, the software system has been decomposed into software items to support appropriate safety classification. Where software items have been assigned a lower safety class than the overall software system, justification is provided through architectural separation and risk control measures.

**Safety classification approach:** Sub-modules and units are classified per IEC 62304. Class A components do not require the same level of granular documentation as Class B components.

**Module M-001: Mobile Application Core**

| Sub-module | Safety Class | Description | Software Units |
|-----------|-------------|-------------|----------------|
| M-001-A: Image Acquisition | Class B | Image capture and quality assessment | U-001-001: Image Capture (SR-001, SR-052); U-001-002: Image Quality Assessment (SR-002, SR-026, SR-040) |
| M-001-B: Data Transmission | Class B | Secure upload of images to backend | U-001-003: Image Upload (SR-003, SR-029) |
| M-001-C: User Services | Class A | Authentication, notifications, updates, feedback | U-001-004: Mobile Authentication; U-001-005: Update Manager; U-001-006: Notification; U-001-007: User Feedback |

**Module M-002: Clinical Interface Core**

| Sub-module | Safety Class | Description | Software Units |
|-----------|-------------|-------------|----------------|
| M-002-A: Clinical Display | Class B | IDM display and trend visualization | U-002-001: IDM Display; U-002-002: Trend Visualization |
| M-002-B: Alert Management | Class B | Alert presentation and acknowledgment | U-002-003: Alert Management |
| M-002-C: Patient Management | Class B | Patient enrollment with safety restrictions | U-002-005: Patient Enrollment |
| M-002-D: Clinical Services | Class A | Authentication, configuration, status display | U-002-004: Clinical Authentication; U-002-006: Threshold Configuration; U-002-007: System Status Display |

**Module M-003: Backend Processing Module**

| Sub-module | Safety Class | Description | Software Units |
|-----------|-------------|-------------|----------------|
| M-003-A: IDM Processing | Class B | Image analysis and IDM calculation | U-003-001: Image Analysis; U-003-002: IDM Calculation |
| M-003-B: Alerting | Class B | Alert generation and delivery | U-003-003: Alert Generation; U-003-004: Alert Delivery; U-003-005: Alert Logging |
| M-003-C: Data Management | Class B | Patient data storage with integrity and compliance | U-003-006: Data Management |
| M-003-D: Security Services | Class A | Authentication, authorization, monitoring, encryption | U-003-007 to U-003-011 |
| M-003-E: System Operations | Class A | Availability, updates, operations | U-003-012: System Operations |

**Safety classification summary:**

| Classification | Sub-modules | Rationale |
|---------------|-------------|-----------|
| Class B | M-001-A, M-001-B, M-002-A, M-002-B, M-002-C, M-003-A, M-003-B, M-003-C | These sub-modules can contribute to hazardous situations but failures are limited to non-serious injuries. Detailed unit-level documentation is required. |
| Class A | M-001-C, M-002-D, M-003-D, M-003-E | These sub-modules provide supporting infrastructure that does not directly contribute to clinical output. Granular unit-level documentation is not required per IEC 62304. |

### 4.2 Software Units

Detailed specifications for Class B software units are documented separately in the full design document. Class A software units require only summary documentation.

### 4.3 Design Patterns

| Pattern | Usage | Rationale |
|---------|-------|-----------|
| Model-View-Controller (MVC) | Mobile and web applications | Separation of data, presentation, and control logic |
| Repository | Data access layer | Abstraction of data storage, testability |
| Factory | Object creation | Decoupling of object instantiation, flexibility |
| Observer | Event handling, alerts | Loose coupling between event producers and consumers |

## 5. SOUP (Software of Unknown Provenance)

### 5.1 SOUP Identification and Requirements

SOUP versions are documented in OTS Report / SBOM (Document ID: [OTS-001]). Key SOUP components include:

| SOUP Component | Version | Functional Requirements | SRs Addressed |
|---------------|---------|------------------------|----------------|
| iOS | 14.0+ | Provide camera access, secure storage, push notification APIs | SR-052, SR-054, SR-059 |
| Android | 8.0+ | Provide Camera2 API, secure storage, FCM APIs | SR-052, SR-054, SR-059 |
| TensorFlow | [Version in SBOM] | Provide ML inference for image analysis | SR-004, SR-019 |
| PostgreSQL | [Version in SBOM] | Provide relational data storage, ACID transactions | SR-005, SR-056, SR-057 |
| React Native | [Version in SBOM] | Provide cross-platform mobile UI framework | SR-038, SR-040, SR-042 |
| React | [Version in SBOM] | Provide web UI framework with component lifecycle | SR-039, SR-041 |
| Flask/Django | [Version in SBOM] | Provide REST API framework, request routing, middleware | SR-050, SR-051 |
| OpenSSL | [Version in SBOM] | Provide TLS 1.2+ encryption, certificate validation | SR-029 |

### 5.2 SOUP Risk Assessment

| SOUP Component | Contribution to Hazards | Anomaly Monitoring | Security Assessment |
|---------------|------------------------|--------------------|--------------------|
| TensorFlow, scikit-learn | High - directly affects IDM calculation accuracy | CVE monitoring, version updates tracked | Vulnerability scanning, secure model loading |
| React Native, React | Medium - UI errors could lead to misinterpretation | Bug tracking, version updates | XSS prevention, secure rendering |
| OAuth/JWT libraries | Medium - authentication failures could allow unauthorized access | Security advisories monitored | Penetration testing, token security review |
| PostgreSQL, Redis | Medium - data corruption could affect patient records | Backup verification, replication monitoring | Access control review |
| APNs/FCM, SendGrid, Twilio | Medium - delivery failures could delay clinical alerts | Service status monitoring, delivery tracking | API security review |

## 6. Secure Software Architecture and Design

### 6.1 Security Principles

- Defence-in-depth architecture: Multiple layers of security controls
- Trust boundaries: Clear trust boundaries between components with security controls at boundaries
- Principle of least privilege: Users and components have minimum necessary permissions
- Attack surface reduction: Minimize exposed interfaces and functionality
- Secure design patterns: Input validation, output encoding, secure communication
- Run-time validation: Input validation and output validation at runtime
- Effective segregation: Security-critical components segregated from non-critical components

### 6.2 Security Controls

**Security controls in architecture:** Network encryption using TLS 1.2+; User authentication with MFA; RBAC for authorization; Data encryption at rest using AES-256; Security audit logging; Security incident detection and alerting; Account lockout after failed authentication attempts.

**Security controls in design:** Input validation and sanitization; Output encoding; Secure error handling; Session management with configurable timeout; Code signing for update integrity; Secure credential storage.

## 7. Software Safety Classification

### 7.1 Classification Rationale

The overall software safety classification is determined per IEC 62304:2006+AMD1:2015+AMD2:2021, Annex A, based on the contribution of the software to a hazardous situation. Classification is justified based on risk analysis results, device classification (Class IIa), and intended use.

### 7.2 Classification by Component

Each software module is classified per IEC 62304, Annex A. Module and unit classifications are documented in the Software Architecture and Detailed Design sections. Classification consistency is ensured through systematic application of IEC 62304 criteria and traceability to risk analysis.

## 8. Review and Approval

| Review Activity | Participants | Result | Findings |
|----------------|-------------|--------|----------|
| Architecture review | Development team, Security team, Quality assurance | Approved | Minor findings addressed, no critical issues |
| Design review | Development team, Quality assurance | Approved | Minor findings addressed, no critical issues |

| Checklist | Status |
|-----------|--------|
| Software architecture is documented | Done |
| Software detailed design is documented | Done |
| SOUP is identified and assessed | Done |
| Security considerations are addressed | Done |
| Risk considerations are addressed | Done |
| Traceability is established | Done |

> Fictional example only | Not regulatory advice | Beta content - incomplete and subject to change
