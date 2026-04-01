# Software Verification Test Specifications

This document defines the software verification specifications for EpiFlare Version 2.0 as required under EU MDR 2017/745 and IEC 62304.

> Disclaimer (Fictional Example - Beta Release): This document is part of a fictional example technical file provided by Scarlet for educational purposes only. Manufacturers remain responsible for generating evidence appropriate to their medical device.

## Document Control Information

| Field | Value |
|-------|-------|
| Title | Software Verification Test Specifications |
| Document ID | SAI-EF-SVS-001 |
| Version | 1.0 |
| Publication date | 2026-02-01 |
| Author(s) | Rajat Kumar |
| Approver(s) | Ingrid Morales - 2026-02-01 |

| Change ID | Version | Content changes |
|-----------|---------|-----------------|
| N/A | 1.0 | Initial publication |

## 1. Purpose

Software verification specifications define the detailed test cases, test procedures, and acceptance criteria for verifying that software requirements are met. These specifications provide the basis for software verification activities, as required by IEC 62304:2006+AMD1:2015+AMD2:2021, Section 5.5 (Software unit implementation and verification), Section 5.6 (Software integration and integration testing), and Section 5.7 (Software system testing).

## 2. Regulatory Basis

- MDR 2017/745 Annex II - Technical Documentation
- IEC 62304:2006+AMD1:2015+AMD2:2021 - Medical device software - Software life cycle processes
- ISO 13485:2016 - Medical devices - Quality management systems - Requirements for regulatory purposes

## 3. Verification Scope

### 3.1 Software Scope

- Software to be verified: EpiFlare Version 2.0
- Software components: Mobile application (patient-facing), Clinical application / web interface (clinician-facing), Backend processing module - all Version 2.0
- Software configuration: Software-only solution, production configuration

### 3.2 Requirements Scope

- Software requirements to be verified: All software requirements (SR-001 through SR-062)
- Requirement coverage: 100% of software requirements covered by verification activities
- Verification objectives: Verify that all software requirements are met and that software functions correctly, safely, and securely

## 4. Verification Strategy

### 4.1 Verification Approach

Verification is performed at three levels per IEC 62304:

- Unit verification: Individual software units verified per Section 5.5
- Integration verification: Software integration verified per Section 5.6
- System verification: Software system verified per Section 5.7

### 4.2 Test Activities and Environments

**Unit test activities:**

| Test Activity | Test Environment | Test Tools | Requirements Verified |
|--------------|-----------------|------------|----------------------|
| iOS mobile app unit tests | CI pipeline: GitHub Actions, macOS runner with Xcode toolchain | Xcode 16.0, XCTest, xcodebuild | SR-001, SR-002, SR-003, SR-009, SR-010, SR-012, SR-026, SR-032, SR-039, SR-041, SR-043 |
| Android mobile app unit tests | CI pipeline: GitHub Actions, Ubuntu runner with Android SDK | Android Studio 2024.2, JUnit 5, Espresso, Gradle | SR-001, SR-002, SR-003, SR-009, SR-010, SR-012, SR-026, SR-032, SR-039, SR-041, SR-043 |
| Clinical interface unit tests | CI pipeline: GitHub Actions, Ubuntu runner with Node.js | Node.js 20 LTS, Jest 29, React Testing Library | SR-006, SR-007, SR-008, SR-015, SR-018, SR-020, SR-025, SR-040, SR-042, SR-047, SR-049 |
| Backend processing unit tests | CI pipeline: GitHub Actions, Ubuntu runner with Python | Python 3.11, pytest 8.0, pytest-cov | SR-004, SR-005, SR-007, SR-014, SR-016, SR-017, SR-019, SR-021, SR-050 |
| ML model unit tests | CI pipeline: GitHub Actions, GPU runner (self-hosted) | Python 3.11, pytest, TensorFlow 2.15 | SR-004, SR-019 |

**Integration test activities:**

| Test Activity | Test Environment | Test Tools | Requirements Verified |
|--------------|-----------------|------------|----------------------|
| Mobile-Backend API integration | Staging environment: Kubernetes cluster | Postman, Newman, Docker Compose, Appium 2.0 | SR-003, SR-009, SR-010, SR-030, SR-034, SR-051 |
| Clinical-Backend API integration | Staging environment: Kubernetes cluster | Postman, Newman, Playwright 1.40 | SR-006, SR-007, SR-008, SR-011, SR-015, SR-030, SR-034, SR-052 |
| Push notification integration | Staging environment with APNs sandbox, FCM test project | Firebase Admin SDK, APNs Provider API | SR-014, SR-044, SR-055 |
| Alert delivery integration | Staging environment with SendGrid/Twilio sandbox | SendGrid Test API, Twilio Test Credentials | SR-014, SR-017, SR-054 |
| Database integration | Staging environment: PostgreSQL 15 test instance | pgTAP, pytest, SQLAlchemy | SR-005, SR-056, SR-057 |

**System test activities:**

| Test Activity | Test Environment | Requirements Verified |
|--------------|-----------------|----------------------|
| End-to-end functional testing | Pre-production environment with physical devices | SR-001 to SR-018 (functional) |
| Performance testing | Dedicated performance environment | SR-019 to SR-024 |
| Security testing | Isolated security test environment | SR-009 to SR-011, SR-030 to SR-036 |
| Penetration testing | Production-like environment with sanitized data | SR-030 to SR-036 (per IEC 81001-5-1:2021) |
| Usability testing | Usability lab with screen recording | SR-039 to SR-050 |
| Compatibility testing (iOS/Android/browsers) | BrowserStack device farm | SR-053, SR-060, SR-061 |
| Network connectivity testing | Controlled network environment with traffic shaping | SR-062 |

### 4.3 Test Coverage Strategy

| Coverage Type | Target | Measurement Tool | Verification |
|--------------|--------|-----------------|-------------|
| Requirements coverage | 100% (all 62 SRs) | TestRail, traceability matrix | Each SR mapped to >=1 test case |
| Code coverage (Class B units) | >=80% line coverage | xcov, JaCoCo, Istanbul, pytest-cov | CI pipeline gates |
| Code coverage (Class A units) | >=60% line coverage | xcov, JaCoCo, Istanbul, pytest-cov | CI pipeline gates |
| Risk coverage | 100% (all 10 risks) | TestRail, risk traceability | Each risk mapped to verification activity |
| Security coverage | Per IEC 81001-5-1:2021 | Penetration test report, vulnerability scans | Third-party attestation |

## 5. Test Specifications

### 5.1 Unit Test Specifications

Unit tests verify individual software units per IEC 62304, Section 5.5. Class B units require >=80% code coverage; Class A units require >=60%.

| Test ID | Unit | Test Description | Acceptance Criteria | SRs Verified |
|---------|------|-----------------|---------------------|-------------|
| UT-001-001 | Image Capture | Verify camera initialization, image capture, preview display, error handling | All assertions pass; >=80% line coverage | SR-001, SR-053 |
| UT-001-002 | Image Quality Assessment | Verify quality scoring algorithm for lighting, focus, distance | Quality classification accuracy >=95%; >=80% coverage | SR-002, SR-026, SR-041 |
| UT-001-003 | Image Upload | Verify encryption, chunked upload, retry logic, progress reporting | All assertions pass; retry succeeds after transient failure | SR-003, SR-030 |
| UT-003-001 | Image Analysis | Verify feature extraction from skin images | Feature extraction accuracy >=98%; >=80% coverage | SR-004 |
| UT-003-002 | IDM Calculation | Verify ML model inference and IDM value computation | RMSE <=3 IDM units; 95% within +/-2 units | SR-004, SR-019 |
| UT-003-003 | Alert Generation | Verify threshold evaluation and alert record creation | Correct alerts generated for all threshold conditions | SR-007, SR-021 |
| UT-003-007 | Authentication Service | Verify credential validation, token generation, MFA flow, lockout | Auth succeeds/fails correctly; lockout after 5 failures | SR-009, SR-010, SR-033 |

### 5.2 Integration Test Specifications

Integration tests verify interfaces between modules per IEC 62304, Section 5.6.

| Test ID | Interface/Integration | Acceptance Criteria | SRs Verified |
|---------|----------------------|---------------------|-------------|
| IT-001 | Mobile-Backend API (I-001) | All endpoints return correct responses; TLS 1.2+ verified | SR-003, SR-009, SR-030, SR-051 |
| IT-002 | Clinical-Backend API (I-002) | All endpoints functional; RBAC enforced | SR-006, SR-007, SR-011, SR-052 |
| IT-003 | Push Notification (I-004) | Notifications delivered within 5 seconds | SR-014, SR-044, SR-055 |
| IT-004 | Alert Delivery (I-005) | Delivery confirmed; status webhooks received; retry succeeds | SR-014, SR-017, SR-054 |
| IT-005 | Database Integration | Data integrity maintained; queries <100ms | SR-005, SR-056, SR-057 |

### 5.3 System Test Specifications

**Functional system tests:**

| Test ID | Test Description | Acceptance Criteria | SRs Verified |
|---------|-----------------|---------------------|-------------|
| ST-001 | Image capture and upload end-to-end | Image captured, uploaded, IDM calculated and displayed | SR-001, SR-002, SR-003, SR-004 |
| ST-007 | Alert generation and delivery end-to-end | Alert generated <1 min; delivered via in-app, email, SMS | SR-007, SR-014, SR-021 |
| ST-009 | Authentication flow end-to-end | Login succeeds with valid creds + MFA; fails otherwise | SR-009, SR-010 |
| ST-027 | Patient enrollment with restrictions | Enrollment rejected for age <18 and no CDD confirmation | SR-027, SR-028 |

**Performance system tests:**

| Test ID | Test Description | Acceptance Criteria | SRs Verified |
|---------|-----------------|---------------------|-------------|
| ST-019 | IDM calculation performance | 95% complete within 30 seconds; max 60 seconds | SR-019 |
| ST-020 | Clinical display response time | 95% display within 5 seconds; max 10 seconds | SR-020 |
| ST-022 | System availability under load | Uptime >=99.5%; no memory leaks; graceful degradation | SR-022 |
| ST-023 | Concurrent user capacity | System stable at 100 users; graceful degradation at 150 | SR-023 |

**Security system tests:**

| Test ID | Test Description | Acceptance Criteria | SRs Verified |
|---------|-----------------|---------------------|-------------|
| ST-030 | TLS encryption verification | All traffic TLS 1.2+; no plaintext; TLS 1.1 rejected | SR-030 |
| ST-033 | Account lockout | Account locked after 5 failures; unlocks after timeout | SR-033, SR-036 |
| ST-034 | Input validation / injection prevention | All injection attempts blocked; appropriate error responses | SR-034 |
| ST-PEN | Penetration test | No critical/high vulnerabilities; medium addressed within 30 days | SR-030-036 |

**Usability system tests:**

| Test ID | Test Description | Acceptance Criteria | SRs Verified |
|---------|-----------------|---------------------|-------------|
| ST-039 | Mobile app usability | Task completion >=90%; satisfaction >=4.0/5.0; no critical errors | SR-039, SR-041, SR-043 |
| ST-040 | Clinical interface usability | Task completion >=95%; satisfaction >=4.0/5.0 | SR-040, SR-042, SR-049 |

**Compatibility system tests:**

| Test ID | Test Description | Acceptance Criteria | SRs Verified |
|---------|-----------------|---------------------|-------------|
| ST-060-iOS | iOS compatibility | All tests pass on all device/OS combinations | SR-053, SR-060 |
| ST-060-Android | Android compatibility | All tests pass on all device/OS combinations | SR-053, SR-060 |
| ST-061 | Browser compatibility | All tests pass on all browsers | SR-061 |
| ST-062 | Network connectivity | Functions at 1 Mbps; graceful degradation at 512 kbps; offline handling | SR-062 |

### 5.4 Test Case to Requirements Traceability

Full bidirectional traceability between software requirements, test cases, and verification evidence is documented in the Software Traceability Matrix (Document ID: [STM-001]).

| Requirement Category | Requirements | Test IDs |
|---------------------|-------------|----------|
| Functional (SR-001 to SR-018) | Image capture, analysis, alerts, auth, updates | UT-001-*, UT-003-*, IT-001 to IT-005, ST-001, ST-007, ST-009, ST-027 |
| Performance (SR-019 to SR-024) | Response times, availability, concurrency | UT-003-002, ST-019, ST-020, ST-022, ST-023 |
| Safety (SR-025 to SR-038) | Clinical safety, encryption, security controls | UT-003-007, ST-030, ST-033, ST-034, ST-PEN |
| Usability (SR-039 to SR-050) | UI, notifications, guidance, alerts | UT-001-002, ST-039, ST-040 |
| Interface (SR-051 to SR-055) | API, camera, notifications | IT-001, IT-002, IT-003, IT-004 |
| Data (SR-056 to SR-059) | Data management, integrity, retention | IT-005 |
| Environmental (SR-060 to SR-062) | OS, browser, network compatibility | ST-060-iOS, ST-060-Android, ST-061, ST-062 |

## 6. Review and Approval

| Checklist | Status |
|-----------|--------|
| Verification strategy and test activities defined | Done |
| Test specifications complete (unit, integration, system) | Done |
| Acceptance criteria established per test specification | Done |
| Traceability to requirements established | Done |

> Fictional example only | Not regulatory advice | Beta content - incomplete and subject to change
