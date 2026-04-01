---
id: "CHK-001"
title: "GSPR Checklist"
type: "CHK"
category: "technical"
version: "1.0"
status: "draft"
effective_date: "2026-04-01"
author: "Sarp Derinsu"
iso_refs:
  - "7.3.3"
mdr_refs:
  - "Annex I"
  - "Annex II"
---

# GSPR Checklist — General Safety and Performance Requirements

## 1. Purpose

This checklist maps each clause of EU MDR 2017/745 Annex I (General Safety and Performance Requirements) to the evidence demonstrating conformity for the Therapeak medical device. It identifies which requirements are applicable, the conformity method, the standards applied, and references to the supporting evidence.

**Related documents:** [[RA-001]] Risk Management File, [[SPE-001]] Software Requirements Specification, [[SPE-003]] Use Requirements Specification, [[LBL-001]] Device Labeling, [[CE-001]] Clinical Evaluation Report

## 2. Device Identification

| Item | Detail |
|---|---|
| Device name | Therapeak |
| Manufacturer | Therapeak B.V. |
| Classification | Class IIa (MDR Annex VIII, Rule 11) |
| Device type | Stand-alone software (SaMD) — no hardware, no physical components |
| Software version | 1.0 |

## 3. GSPR Checklist

### Chapter I — General Requirements

| GSPR | Clause Summary | Applicable | Conformity Method | Standards Applied | Evidence |
|---|---|---|---|---|---|
| 1 | Devices shall achieve intended performance and be safe under normal conditions of use | Yes | Risk management, clinical evaluation, design verification | ISO 14971:2019, IEC 62304, IEC 82304-1 | [[RA-001]], [[CE-001]], [[SPE-001]], [[TST-001]] |
| 2 | Solutions adopted shall conform to safety principles, taking account of the generally acknowledged state of the art | Yes | Risk management, software lifecycle | ISO 14971:2019, IEC 62304, IEC 82304-1, IEC 81001-5-1 | [[RA-001]], [[PLN-005]], [[SOP-011]] |
| 3 | Manufacturers shall establish, implement, document and maintain a risk management system | Yes | Risk management process | ISO 14971:2019 | [[PLN-001]], [[RA-001]], [[RPT-002]], [[SOP-002]] |
| 4 | Risk control measures shall be adopted, in order of priority: inherent safety by design, adequate protection, information for safety | Yes | Risk management file documents all controls in priority order | ISO 14971:2019, EU MDR Annex I Section 4 | [[RA-001]] Section 3.5, [[RPT-002]] |
| 5 | Risks related to use error shall be reduced as far as possible through ergonomic design principles | Yes | Usability engineering | IEC 62366-1:2015 | [[PLN-006]], [[SPE-003]] UR-010 to UR-014 |
| 6 | Characteristics and performance shall not be adversely affected during intended lifetime under transport, storage, and use conditions | Yes | Software is web-based SaaS; no transport/storage degradation. Server infrastructure maintained. | IEC 62304, IEC 82304-1 | [[SOP-016]], [[SOP-017]], [[SPE-001]] PF-001 |
| 7 | Devices shall be designed, manufactured, and packaged to ensure characteristics and performance during transport and storage | N/A | Stand-alone software with no physical packaging or transport | -- | N/A — SaMD has no physical form factor |
| 8 | All known and foreseeable risks and undesirable side-effects shall be minimized and acceptable when weighed against benefits | Yes | Benefit-risk analysis | ISO 14971:2019 | [[RA-001]] Sections 7.1-7.3, [[RPT-002]], [[CE-001]] |
| 9 | Requirements for Annex XVI devices | N/A | Therapeak is not an Annex XVI device | -- | N/A |

### Chapter II — Requirements Regarding Design and Manufacture

| GSPR | Clause Summary | Applicable | Conformity Method | Standards Applied | Evidence |
|---|---|---|---|---|---|
| 10 | Chemical, physical and biological properties | N/A | Therapeak is stand-alone software with no physical materials, substances, or biological components | -- | N/A — SaMD has no physical/chemical/biological properties |
| 10.1-10.6 | Material safety, contaminants, substances, particles | N/A | No physical materials | -- | N/A |
| 11 | Infection and microbial contamination | N/A | No physical device; no contact with patients or biological materials | -- | N/A |
| 11.1-11.8 | Sterilization, packaging, microbial state | N/A | No physical device | -- | N/A |
| 12 | Devices incorporating medicinal substances | N/A | No medicinal substances | -- | N/A |
| 13 | Devices incorporating materials of biological origin | N/A | No biological materials | -- | N/A |
| 14.1 | Devices used in combination with other devices/equipment | Partial | SaMD runs on user's browser and device. Minimum requirements defined in IFU. | IEC 82304-1 | [[LBL-001]] IFU (browser requirements), [[SPE-003]] UR-010, UR-033 |
| 14.2 | Design to remove/reduce risks from physical and environmental factors | N/A | No physical risks — SaMD | -- | N/A |
| 14.3 | Fire and explosion risk | N/A | No physical risks — SaMD | -- | N/A |
| 14.4 | Safe adjustment, calibration, maintenance | Partial | Software updates managed server-side per change management procedure | IEC 62304, IEC 82304-1 | [[SOP-017]], [[SPE-003]] UR-034 |
| 14.5 | Interoperability and compatibility | Yes | SaMD interfaces with AI providers, payment, email services. Interfaces documented. | IEC 82304-1 | [[SPE-003]] UR-015 to UR-018, [[SOP-016]] |
| 14.6 | Measurement/monitoring display scales | N/A | Device does not perform measurements or diagnostics. Mood tracking is self-reported, not clinical measurement. | -- | N/A |
| 14.7 | Safe disposal procedures | Partial | User account deletion and data erasure per GDPR. No physical disposal. | GDPR | [[SPE-001]] SC-004, SC-005, [[SOP-016]] Section 4.2.5 |
| 15 | Devices with diagnostic or measuring function | N/A | Therapeak does not diagnose, measure, or monitor physiological parameters. Classification is "informs clinical management" per IMDRF. | -- | N/A |
| 16 | Protection against radiation | N/A | No radiation — SaMD | -- | N/A |
| **17.1** | **Electronic programmable systems — repeatability, reliability, performance** | **Yes** | **Software lifecycle management per state of the art** | **IEC 62304, IEC 82304-1** | **[[PLN-005]], [[SOP-011]], [[SPE-001]], [[TST-001]]** |
| **17.2** | **Software designed and manufactured in accordance with the state of the art, taking into account principles of development lifecycle, risk management, information security, verification and validation** | **Yes** | **Full software lifecycle with risk management, cybersecurity, verification** | **IEC 62304, IEC 82304-1, IEC 81001-5-1, ISO 14971** | **[[PLN-005]], [[SOP-011]], [[SOP-016]], [[RA-001]], [[TST-001]], [[SPE-001]], [[SPE-003]]** |
| 17.3 | Software intended for mobile computing platforms | Partial | Therapeak is web-based, accessed via mobile browsers. Responsive design ensures mobile usability. Not a native mobile app. | IEC 62304, IEC 82304-1 | [[SPE-001]] UX-001, [[SPE-003]] UR-010 |
| **17.4** | **Minimum hardware, IT network, and IT security requirements** | **Yes** | **Cybersecurity management procedure; minimum browser requirements in IFU** | **IEC 81001-5-1, MDCG 2019-16** | **[[SOP-016]], [[LBL-001]], [[SPE-001]] SC-001 to SC-008** |
| 18.1-18.7 | Active devices — single fault, power, alarms, EMC, electric shock | N/A | No active hardware device — SaMD | -- | N/A |
| **18.8** | **Protection against unauthorised access that could hamper the device from functioning as intended** | **Yes** | **Access control, authentication, cybersecurity** | **IEC 81001-5-1** | **[[SOP-016]], [[SPE-001]] SC-002, SC-006, SC-007, SC-008** |
| 19 | Active implantable devices | N/A | Not implantable — SaMD | -- | N/A |
| 20 | Mechanical and thermal risks | N/A | No physical risks — SaMD | -- | N/A |
| 21 | Energy or substance delivery | N/A | No energy or substance delivery — SaMD | -- | N/A |
| **22.1** | **Devices for lay persons shall perform appropriately for their intended purpose, given skills and means available to lay persons** | **Yes** | **Usability engineering; product designed for unsupervised home use by adults** | **IEC 62366-1** | **[[PLN-006]], [[SPE-003]] UR-010 to UR-014, [[SPE-001]] UX-001 to UX-006** |
| **22.2** | **Design for safe and accurate use by lay persons** | **Yes** | **Onboarding flow, disclaimers, age gate, clear UI** | **IEC 62366-1** | **[[PLN-006]], [[SPE-001]] FR-014, UX-004, SF-002** |
| 22.3 | Device verification and failure warning for lay persons | Partial | User is informed when AI service is unavailable. Error states are handled gracefully. | IEC 62366-1 | [[SPE-001]] AI-003 (retry/fallback), UX-004 (disclaimers) |

### Chapter III — Requirements Regarding Information Supplied with the Device

| GSPR | Clause Summary | Applicable | Conformity Method | Standards Applied | Evidence |
|---|---|---|---|---|---|
| **23.1** | **General requirements: manufacturer shall provide information needed for safe use, clearly identifying the device and manufacturer** | **Yes** | **Electronic labeling and IFU per Regulation (EU) 207/2012 for eIFU** | **EU MDR Annex I 23, IEC 82304-1 7.2** | **[[LBL-001]], [[SPE-003]] UR-040, UR-041** |
| **23.2** | **Information on the label** | **Yes** | **Electronic label displayed in-app** | **EU MDR Annex I 23.2** | **[[LBL-001]]** |
| 23.2(a) | Device name or trade name | Yes | In-app labeling | -- | [[LBL-001]] — "Therapeak" |
| 23.2(b) | Details necessary for identification of the device | Yes | Software version, UDI-DI | -- | [[LBL-001]], [[SOP-014]] |
| 23.2(c) | Name and address of manufacturer | Yes | In-app labeling | -- | [[LBL-001]] — Therapeak B.V. |
| 23.2(d) | Authorized representative (if applicable) | N/A | Manufacturer is in EU (Netherlands) | -- | N/A |
| 23.2(e) | Indication that the device is a medical device | Yes | In-app labeling | -- | [[LBL-001]] |
| 23.2(k) | Warning or precaution to be taken | Yes | Contraindications and warnings in labeling | -- | [[LBL-001]] |
| 23.2(n) | Special storage/handling conditions | N/A | SaMD — no physical storage | -- | N/A |
| 23.2(p) | UDI carrier | Yes | UDI displayed in-app | -- | [[LBL-001]], [[SOP-014]] |
| 23.3 | Information on sterile packaging | N/A | No sterile packaging — SaMD | -- | N/A |
| **23.4** | **Information in the instructions for use** | **Yes** | **Electronic IFU** | **EU MDR Annex I 23.4, IEC 82304-1 7.2.2** | **[[LBL-001]]** |
| 23.4(a) | Intended purpose, intended user, intended patient population | Yes | IFU | -- | [[LBL-001]] |
| 23.4(b) | Intended clinical benefits | Yes | IFU, CER | -- | [[LBL-001]], [[CE-001]] |
| 23.4(c) | Performance characteristics | Yes | IFU | -- | [[LBL-001]], [[SPE-001]] |
| 23.4(d) | Contraindications, warnings, precautions | Yes | IFU | -- | [[LBL-001]] — crisis, minors, severe illness |
| 23.4(e) | Residual risks and undesirable side-effects | Yes | IFU | -- | [[LBL-001]], [[RA-001]] |
| 23.4(f) | Installation and use instructions | Yes | IFU — browser requirements, registration process | -- | [[LBL-001]] |
| 23.4(g) | Interfering treatments or activities | N/A | No interfering treatments for SaMD | -- | N/A |
| 23.4(h) | Accessories and consumables | N/A | No accessories — SaMD | -- | N/A |
| 23.4(k) | Qualifications and training of users | Partial | IFU states minimum age 19+, basic digital literacy | -- | [[LBL-001]] |
| 23.4(n) | Software version identification | Yes | Version displayed in-app | -- | [[LBL-001]], [[SOP-014]] |
| 23.4(o) | Hardware and IT network requirements | Yes | IFU — browser requirements, internet connection | -- | [[LBL-001]], [[SOP-016]] |
| 23.4(q) | Disposal or decommissioning information | Yes | IFU — account deletion process | -- | [[LBL-001]], [[SPE-001]] SC-004 |
| 23.4(u) | Information for patients with implantable devices | N/A | No implantable device | -- | N/A |
| 23.4(aa) | Serious incidents and field safety corrective actions | Yes | IFU — contact information for reporting | -- | [[LBL-001]], [[SOP-013]] |

## 4. Summary

| Category | Applicable | Not Applicable | Partial |
|---|---|---|---|
| Chapter I (General Requirements, 1-9) | 7 | 2 | 0 |
| Chapter II (Design and Manufacture, 10-22) | 6 | 18 | 5 |
| Chapter III (Information, 23) | 14 | 7 | 2 |
| **Total** | **27** | **27** | **7** |

All applicable requirements have identified conformity methods, standards, and evidence references.

Non-applicable requirements are justified by the nature of the device: Therapeak is stand-alone software (SaMD) with no physical components, no biological materials, no radiation, no energy delivery, and no implantable features.

## 5. Approval

| Role | Name | Date |
|---|---|---|
| Author / Quality Manager | Sarp Derinsu | 2026-04-01 |

## 6. Change History

| Version | Date | Description |
|---|---|---|
| 1.0 | 2026-04-01 | Initial release |

## 7. References

- [[RA-001]] Risk Management File
- [[RPT-002]] Risk Management Report
- [[PLN-001]] Risk Management Plan
- [[CE-001]] Clinical Evaluation Report
- [[SPE-001]] Software Requirements Specification
- [[SPE-003]] Use Requirements Specification
- [[PLN-005]] Software Development Plan
- [[PLN-006]] Usability Engineering Plan
- [[SOP-011]] Software Lifecycle Management Procedure
- [[SOP-016]] Cybersecurity Management Procedure
- [[SOP-017]] Change Management Procedure
- [[LBL-001]] Device Labeling
- [[SOP-014]] Product Identification and Traceability
- [[TST-001]] Software Verification Test Specifications
- EU MDR 2017/745 Annex I — General Safety and Performance Requirements
- ISO 14971:2019 — Risk management
- IEC 62304:2006+AMD1:2015 — Software lifecycle
- IEC 82304-1:2016 — Health software product safety
- IEC 81001-5-1:2021 — Health software security
- IEC 62366-1:2015 — Usability engineering
- MDCG 2019-16 — Guidance on cybersecurity for medical devices
