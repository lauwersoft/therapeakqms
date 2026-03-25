---
id: "SOP-014"
title: "Product Identification and Traceability Procedure"
type: "SOP"
version: "1.0"
status: "approved"
effective_date: "2026-03-01"
author: "Sarp Derinsu"
iso_refs:
  - "7.5.8"
  - "7.5.9"
mdr_refs:
  - "Article 25"
  - "Article 26"
  - "Article 27"
  - "Article 28"
---

# Product Identification and Traceability Procedure

## 1. Purpose

This procedure defines how Therapeak B.V. identifies and traces the Therapeak medical device software throughout its lifecycle, including Unique Device Identification (UDI), software versioning, EUDAMED registration, and requirements traceability. This ensures compliance with EU MDR Articles 25-28 and ISO 13485:2016 Clauses 7.5.8 and 7.5.9.

**Related documents:** [[SOP-001]] Document Control, [[SOP-017]] Change Management, [[SOP-006]] Software Development Lifecycle

## 2. Scope

This procedure applies to:
- The Therapeak AI therapy software (Software as a Medical Device, Class IIa)
- All released versions of the software in medical device mode (`DEVICE_MODE=medical`)
- UDI assignment and maintenance
- EUDAMED registration and data submission
- Requirements-to-validation traceability

This procedure does NOT apply to the wellness version of Therapeak (`DEVICE_MODE=wellness`), which is not a medical device.

## 3. Responsibilities

| Role | Person | Responsibility |
|------|--------|---------------|
| PRRC / Release Manager | Sarp Derinsu | Assigns version numbers, manages UDI, submits EUDAMED data, maintains traceability matrix |

## 4. Procedure

### 4.1 Unique Device Identification (UDI)

#### 4.1.1 UDI System Overview

Therapeak complies with the EU MDR UDI system per Articles 27 and 28. As Software as a Medical Device (SaMD), the UDI is applied to the software itself rather than physical packaging.

| UDI Component | Description | Therapeak Implementation |
|---------------|-------------|-------------------------|
| UDI-DI (Device Identifier) | Identifies the device model and manufacturer | Assigned via an EU-authorized issuing entity (e.g., GS1) and registered in EUDAMED |
| UDI-PI (Production Identifier) | Identifies the specific production unit | For SaMD, the software version number serves as the UDI-PI |

#### 4.1.2 UDI-DI Assignment

1. Obtain a UDI-DI from an authorized issuing entity (GS1 or equivalent) before placing the medical device on the market
2. The UDI-DI remains the same across software versions unless the intended purpose, risk class, or fundamental design changes
3. Register the UDI-DI in EUDAMED

#### 4.1.3 UDI-PI for Software

For SaMD, the software version number IS the production identifier. There is no separate manufacturing lot or serial number. Each released version is uniquely identified by its version string.

#### 4.1.4 UDI Placement

The UDI (combining UDI-DI and UDI-PI) shall be:
- Displayed on the "About" or "Legal" page within the application
- Included in the Instructions for Use (IFU)
- Stored in the device configuration (`settings.device_version`)
- Readable in machine-readable format (AIDC) where technically feasible for software

### 4.2 Software Version Numbering

#### 4.2.1 Version Scheme

Therapeak uses semantic versioning (MAJOR.MINOR.PATCH):

| Component | When Incremented | Examples |
|-----------|-----------------|----------|
| MAJOR | Significant changes: new AI model, changes to intended purpose, major architecture changes, changes requiring NB notification | 1.0 to 2.0 |
| MINOR | Non-significant changes: new features, prompt refinements, UI improvements | 1.0 to 1.1 |
| PATCH | Bug fixes, security patches, dependency updates | 1.0.0 to 1.0.1 |

#### 4.2.2 Version 1.0 — First CE-Marked Release

Version 1.0 is designated as the first CE-marked release of the Therapeak medical device. This version represents the software configuration that has been:
- Verified and validated per [[SOP-006]]
- Risk-assessed per [[RA-001]]
- Reviewed by the Notified Body (Scarlet)
- CE marked as a Class IIa medical device

#### 4.2.3 Version Tracking Implementation

Software versions are tracked through:

1. **Git commits**: every change to the codebase is recorded with a commit hash, author, date, and message
2. **Git tags**: each released version is tagged (e.g., `v1.0`, `v1.1`, `v1.0.1`)
3. **Device mode configuration**: `settings.device_mode` distinguishes medical device from wellness product
4. **Device version configuration**: `settings.device_version` stores the current version string
5. **Deployment records**: each production deployment is linked to a specific git tag/commit

### 4.3 EUDAMED Registration

Before placing the Therapeak medical device on the EU market, Sarp shall:

1. **Register as manufacturer** in EUDAMED (Single Registration Number — SRN)
2. **Register the device** with:
   - Basic UDI-DI
   - UDI-DI(s) and UDI-PI format
   - Device name and description
   - Risk class (IIa) and classification rule (Rule 11)
   - Intended purpose
   - Notified Body (Scarlet) identification
   - CE certificate reference
3. **Maintain EUDAMED data**: update device registration when:
   - A new major version is released
   - The intended purpose changes
   - The CE certificate is updated
   - Any other UDI-relevant data changes
4. **Submit safety and performance summary** to EUDAMED as required for Class IIa devices

### 4.4 Requirements Traceability

#### 4.4.1 Traceability Matrix

Sarp maintains a traceability matrix that links:

```
User Needs → Design Requirements → Design Specifications → Verification → Validation
     ↕                ↕                      ↕                   ↕             ↕
Risk Controls    Software Requirements    Implementation    Test Results    Clinical Evidence
```

The traceability matrix ensures that:
- Every user need is addressed by at least one design requirement
- Every design requirement is implemented in the software
- Every implementation is verified (tested)
- The overall design is validated against user needs
- Risk controls are traced to the hazards they mitigate

#### 4.4.2 Traceability Records

Traceability is maintained through:

| Element | Where Tracked |
|---------|--------------|
| User needs and design inputs | Requirements documentation in QMS |
| Software requirements | Technical documentation |
| Implementation | Git commits linked to requirements |
| Verification (testing) | Test reports referencing requirements |
| Validation | Validation report referencing user needs |
| Risk controls | Risk management file ([[RA-001]]) |

#### 4.4.3 Maintaining Traceability

When changes are made to the software per [[SOP-017]] Change Management:
1. Update the traceability matrix to reflect new or modified requirements
2. Ensure verification records exist for all changed requirements
3. Assess whether re-validation is needed
4. Update the risk management file if risk controls are affected

### 4.5 Configuration Identification

The complete configuration of a released version includes:

| Component | Identification Method |
|-----------|-----------------------|
| Application source code | Git commit hash + tag |
| AI model versions | Documented in release notes (e.g., Claude Sonnet 4.5 via OpenRouter) |
| System prompt versions | Git-tracked text files (chat_room_instructions.txt, priority_chat_instructions.txt) |
| Dependencies | composer.lock (PHP), package-lock.json (JavaScript) |
| Infrastructure configuration | Docker Compose files, server configuration |
| Database schema | Laravel migration files (git-tracked) |

## 5. Records

| Record | Retention | Reference |
|--------|-----------|-----------|
| UDI assignments and EUDAMED submissions | Lifetime of device + 10 years | -- |
| Version release records (git tags) | Lifetime of device + 10 years | -- |
| Traceability matrix | Lifetime of device + 10 years | -- |
| Configuration baselines per release | Lifetime of device + 10 years | -- |

## 6. References

- [[SOP-001]] Document Control Procedure
- [[SOP-006]] Software Development Lifecycle Procedure
- [[SOP-017]] Change Management Procedure
- [[RA-001]] Risk Management File
- ISO 13485:2016 Clause 7.5.8 — Identification
- ISO 13485:2016 Clause 7.5.9 — Traceability
- EU MDR 2017/745 Articles 25-28 — Identification Within the Supply Chain, UDI System
- MDCG 2019-11 — Guidance on Qualification and Classification of Software
