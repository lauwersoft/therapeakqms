# Risk Management Report

This document constitutes the summary output of the risk management process in accordance with ISO 14971:2019 and the requirements of EU MDR 2017/745.

> **Disclaimer (Fictional Example -- Beta Release):** This document is part of a fictional example technical file provided by Scarlet for educational purposes only. Manufacturers remain responsible for generating evidence appropriate to their medical device.

## Document Control Information

| Field | Value |
|---|---|
| Title | Risk Management Report |
| Document ID | SAI-EF-RMR-001 |
| Version | 1.0 |
| Publication date | 2026-02-22 |
| Author(s) | Martin Hauer |
| Approver(s) | Dr. Livia Tan |

### Change History

| Change ID | Version | Content changes |
|---|---|---|
| N/A | 1.0 | Initial publication |

## Table of Contents

1. Purpose
2. Abbreviations
3. Device information
4. Personnel responsible for risk management activities
5. Completion of risk management activities
   - 5.1 Confirmation of completeness
   - 5.2 Risk management file references
6. Benefit-risk analysis
   - 6.1 Overview of clinical benefits
   - 6.2 Risks requiring justification
7. Overall residual risk evaluation
   - 7.1 Completeness, Implementation and effectiveness of risk controls
   - 7.2 Overall residual risk determination
8. Risk management process review
   - 8.1 Reviewer and authority
   - 8.2 Suitability of the Risk acceptability criteria policy
   - 8.3 Implementation of the risk management process
   - 8.4 Post-market surveillance support for risk management
9. Conclusion

## 1. Purpose

This Risk Management Report (RMR) constitutes the summary output of the risk management process conducted for EpiFlare, in accordance with ISO 14971:2019 and the requirements of EU MDR 2017/745. It documents the outcomes of risk management activities undertaken for the initial market release of EpiFlare and serves as evidence that the risk management process has been appropriately implemented.

## 2. Abbreviations

| Abbreviation | Definition |
|---|---|
| CDD | Cox-Dewar Dermatitis |
| CER | Clinical Evaluation Report |
| CE | Conformite Europeenne |
| DDoS | Distributed Denial of Service |
| EUDAMED | European Database on Medical Devices |
| GDPR | General Data Protection Regulation |
| HCP | Healthcare Professional |
| IDM | Inflammation Disease Metric |
| IFU | Instructions for Use |
| ISO | International Organisation for Standardisation |
| MDR | Medical Device Regulation (EU) 2017/745 |
| MFA | Multi-Factor Authentication |
| ML | Machine Learning |
| PII | Personally Identifiable Information |
| PHI | Protected Health Information |
| PMCF | Post-Market Clinical Follow-Up |
| PMS | Post-Market Surveillance |
| PSUR | Periodic Safety Update Report |
| QMR | Quality Management Representative |
| QMS | Quality Management System |
| RMF | Risk Management File |
| RMP | Risk Management Plan |
| RMR | Risk Management Report |
| SaMD | Software as a Medical Device |
| SR | Software Requirement |
| UE | Usability Evaluation |

## 3. Device Information

- **Device name:** EpiFlare
- **Version:** 2.0
- **Basic UDI-DI:** (01)00000000000000
- **Classification:** Class IIa
- **Intended purpose:** EpiFlare is intended to be used by qualified healthcare professionals, including dermatologists and primary care physicians, to support the monitoring of disease activity in adult patients (>=18 years) with a confirmed diagnosis of Cox-Dewar Dermatitis (CDD). The device is intended for disease monitoring and assessment of flare severity; it is not intended to establish an initial diagnosis.

See the Device Description for more detailed information.

## 4. Personnel Responsible for Risk Management Activities

The following personnel were responsible for the generation of this Risk Management Report and the associated risk management activities. All individuals possess the education, professional training, skills, and experience necessary to conduct the activities assigned to them, in accordance with the competency requirements of ISO 14971:2019, Clause 3.

| Personnel Name | Role | Responsibilities and authorities |
|---|---|---|
| Dr. Livia Tan | Chief Operating Officer | Perform all necessary review activities. |
| Martin Hauer | Quality Management Representative | Overview of all risk management activities. |
| Dr. Vincent Osei | Clinical Evaluation and Usability Engineer | Conduct risk analysis and risk evaluation, as well as risk control selection. |
| Ingrid Morales | Software Engineer | Overview and contribute to risk control selection and implementation. |
| Rajat Kumar | Software Validation and Verification Engineer | Develop and execute risk control implementation verification activities. |
| Elena Wei | Post-market Surveillance Analyst | Develop risk control effectiveness verification activities as well as collect, review and evaluate production and post-production information. |

**Evaluator suitability and independence:**

All six contributors meet the requirements specified in ISO 14971:2019, Clause 3, sharing multiple years experience in dermatology and SaMD development. Personnel qualifications, experience, and suitability are detailed in their supporting CVs.

This Risk Management Report has been reviewed and approved by the Risk Management Lead and the Quality Management Representative. The approval of this document confirms that all risk management activities have been carried out by personnel with appropriate competence and that the outputs are considered complete and accurate for the purposes of regulatory submission.

## 5. Completion of Risk Management Activities

### 5.1 Confirmation of Completeness

This section confirms that all risk management activities specified in the EpiFlare Risk Management Plan (SAI-EF-RMP-001) have been completed in accordance with ISO 14971:2019. The following activities have been performed:

- Identification of intended use, intended users, and foreseeable misuse (SAI-EF-CHAR-001 to SAI-EF-CHAR-023).
- Identification of hazards and hazardous situations across all relevant device characteristics and use scenarios.
- Estimation of the probability of occurrence of harm and severity of harm for each hazardous situation, both prior to and following implementation of risk controls.
- Evaluation of risk acceptability against the criteria defined in the Risk Management Plan.
- Identification, implementation, and verification of risk control measures for all risk sequences where the initial risk was determined to be unacceptable.
- Re-estimation of residual risk following implementation of all risk controls.
- Evaluation of the overall residual risk across all 49 identified risk sequences (see the Risk Management File).

A total of 49 hazardous sequences were identified, spanning eight hazard categories. Twenty-three risk control measures were defined, implemented, and verified. One risk remains unacceptable after the implementation of control measures. All risk management activities have been completed in full.

### 5.2 Risk Management File References

The following documents constitute the Risk Management File for EpiFlare:

- Risk management plan - Document ID: [SAI-EF-RMP-001]
- Risk management file - Document ID: [SAI-EF-RMF-001]
- Instructions for use - Document ID: [SAI-EF-IFU-001]
- Usability engineering reports - Document ID: [SAI-EF-UER-001]

## 6. Benefit-Risk Analysis

### 6.1 Overview of Clinical Benefits

EpiFlare is intended to provide clinical benefit by supporting the longitudinal monitoring of disease activity in adult patients with Cox-Dewar Dermatitis (CDD), enabling earlier identification of disease flares and supporting timely clinical review. Specifically:

- Objective monitoring of disease activity in adult patients with Cox-Dewar Dermatitis (CDD) through quantitative IDM values derived from serial skin images.
- Identification of patients at increased risk of disease flare, using predefined IDM thresholds and trend-based changes to prompt timely clinical review.
- Longitudinal assessment of disease trends over time, enabling clinicians to detect changes in disease activity that may not be captured through episodic clinic-based assessment alone.

### 6.2 Risks Requiring Justification

The following risk sequences were evaluated as unacceptable prior to the implementation of risk controls and required residual risk reduction. In each case, the residual risk after implementation of controls was reduced to an acceptable level. No risk sequences remain unacceptable following the full application of risk controls.

However, consistent with the requirements of ISO 14971:2019 Clause 8, a benefit-risk analysis was conducted for risks involving high-severity harms (Critical or Serious), even where residual risk was ultimately deemed acceptable, to confirm that the benefits of the device outweigh these residual risks.

#### Risks Involving Critical Harms (Organ Damage)

Nine risk sequences (SAI-EF-HS-003, 009, 015, 021, 033, 039) were identified involving potential organ damage arising from false-positive IDM outputs leading to unnecessary immunotherapy treatment.

Following the implementation of controls (image quality guidance and validation, HCP interpretation guidance, HCP confirmation workflow, and data integrity and encryption measures), the probability of occurrence of harm was reduced from Occasional to Improbable. The residual risk was assessed as acceptable in all but one case: SAI-EF-HS-003. Even with risk controls implemented, the probability of this critical harm occurring is Occasional.

**Benefit-risk justification:** The probability that EpiFlare contributes causally to organ damage is substantially mitigated by the multi-layered controls in place, in particular the mandatory HCP review and confirmation step, which acts as the primary safeguard against inappropriate treatment decisions.

The clinical benefit of enabling early, systematic monitoring of CDD -- and thereby reducing the risk of uncontrolled disease progression, scarring, and psychological harm -- is considered to substantially outweigh the low residual risk of critical harm in the context of appropriate clinical use.

#### Risks Involving Serious Harms (Permanent Scarring and Psychological Distress)

Multiple risk sequences involving permanent scarring (from false-negative outputs leading to missed flare detection) and psychological distress (from loss of data confidentiality) were initially assessed as unacceptable. Following implementation of controls, these risks were reduced to acceptable levels (Improbable or Remote probability of occurrence).

**Benefit-risk justification:** The risk of permanent scarring through delayed flare identification is considered substantially lower when EpiFlare is in use than in its absence, given the device's ability to provide continuous longitudinal monitoring and automated threshold-based alerts.

The risk of data confidentiality breach, whilst carrying potential for psychological harm, is mitigated through comprehensive security controls including encryption, authentication, role-based access control, and continuous monitoring. The clinical benefit of providing accessible, objective, real-time disease monitoring outweighs the residual risk of these outcomes.

## 7. Overall Residual Risk Evaluation

### 7.1 Completeness, Implementation and Effectiveness of Risk Controls

All 23 risk control measures defined in the Risk Management File (SAI-EF-RSKCTRL-001 to SAI-EF-RSKCTRL-023) have been implemented and verified as effective. Implementation was confirmed through reference to applicable software requirements specifications and usability evaluation summaries. Effectiveness was verified through software verification testing and usability evaluation activities, as referenced in the Risk Controls register.

Risk control measures were applied in accordance with the ISO 14971:2019 priority hierarchy: (1) inherently safe design, (2) protective measures, (3) information for safety. Where inherently safe design was not technically feasible -- due to the probabilistic nature of the machine learning algorithm underlying the IDM calculation -- protective measures and information for safety were implemented. Rationale for this approach is documented in the Risk management file.

### 7.2 Overall Residual Risk Determination

The overall residual risk of EpiFlare is considered acceptable. This determination is based on the following findings:

- 48 of 49 identified risk sequences have been reduced to acceptable residual risk levels following implementation of the 23 defined risk control measures.
- One risk sequence remained at an unacceptable level of residual risk.
- For risk sequences involving Critical or Serious severity harms, benefit-risk analyses were conducted (see Section 5) confirming that the clinical benefits of EpiFlare outweigh the residual risks.
- The overall residual risk, when considered in aggregate, is consistent with the benefit profile of the device and with the expected residual risk of comparable SaMDs or approaches to management of inflammatory skin diseases. This is evidenced by results of a literature search that sought to gather clinical evidence for similar devices or approaches, found here.

## 8. Risk Management Process Review

### 8.1 Reviewer and Authority

The risk management process review was conducted by Dr. Livia Tan (Risk Management Lead) and independently reviewed by the Quality Management Representative (QMR). The QMR holds overall accountability for the quality management system, including risk management processes, and has the authority to confirm the suitability and adequacy of those processes. This authority is established within the organisation's Quality Management System in accordance with ISO 13485:2016.

### 8.2 Suitability of the Risk Acceptability Criteria Policy

The risk acceptability criteria defined in the Risk Management Plan (SAI-EF-RMP-001) were reviewed and confirmed to remain suitable for EpiFlare. The criteria reflect the severity and probability classifications defined in ISO 14971:2019 Annex C and are consistent with the clinical context of a Class IIa SaMD intended for disease monitoring in an adult outpatient population. The criteria were applied consistently throughout the risk estimation and evaluation activities documented in this report.

### 8.3 Implementation of the Risk Management Process

The risk management process was implemented in accordance with the Risk Management Plan (SAI-EF-RMP-001). All activities specified in the plan -- including hazard identification, risk estimation, risk evaluation, risk control definition and verification, and overall residual risk evaluation -- were completed prior to the generation of this report. The risk management file is confirmed to be complete.

### 8.4 Post-Market Surveillance Support for Risk Management

A Post-Market Surveillance (PMS) system has been established for EpiFlare in accordance with EU MDR 2017/745, Articles 83 and 84. The PMS plan (SAI-EF-PMS-001) specifies mechanisms for the systematic collection, recording, and analysis of safety-related data from the post-market phase. These include:

- Vigilance reporting via EUDAMED in accordance with MDR 2017/745, Articles 87-90.
- Analysis of complaint data, adverse event reports, and near-miss events through the organisation's quality management system.
- Post-Market Clinical Follow-Up (PMCF) activities, including ongoing review of real-world clinical performance data and periodic updates to the Clinical Evaluation Report (SAI-EF-CER-001).
- Periodic Safety Update Reports (PSUR) at intervals defined in the PMS Plan, to provide a consolidated review of safety data and risk management inputs.
- Feedback mechanisms including healthcare professional and patient reporting channels accessible through the EpiFlare clinical interface and mobile application.

Post-market data will be reviewed against the risk management file on a defined periodic basis, and the risk management file will be updated where new information has safety implications, in accordance with ISO 14971:2019 Clause 10.

## 9. Conclusion

Following the implementation of all 23 risk control measures, the residual risk of all 48 out of 49 identified hazardous situations has been reduced to an acceptable level. One risk sequence remains unacceptable. A literature review has been conducted to help determine if EpiFlare's clinical benefits outweigh the residual risk. The overall residual risk, when considered in aggregate, is consistent with the expected residual risk of comparable SaMDs and is therefore considered acceptable.

> **Fictional example only | Not regulatory advice | Beta content -- incomplete and subject to change**
