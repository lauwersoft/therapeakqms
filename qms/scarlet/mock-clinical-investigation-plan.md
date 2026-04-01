# Clinical Investigation Plan (CIP)

This document outlines the structure and content requirements for the Clinical Investigation Plan as required under EU MDR 2017/745.

> Disclaimer (fictional example - beta release): This document is part of a fictional example technical file provided by Scarlet for educational purposes only. Manufacturers remain responsible for generating evidence appropriate to their medical device.

## Document Control Information

| Field | Value |
|-------|-------|
| Title | Clinical Investigation Plan: Evaluating the performance of EpiFlare, a digital technology, in the monitoring of disease activity in Cox-Dewar Dermatitis (CDD) |
| Document ID | SAI-EF-CIP-001 |
| Version | 1.0 |
| Publication date | 2026-01-30 |
| Author(s) | James Anderson; Neha Fleming |
| Approver(s) | Sarah Mitchell |

| Change ID | Version | Content changes |
|-----------|---------|-----------------|
| N/A | 1.0 | Initial publication |

## 1. Abbreviations

| Abbreviation / Term | Definition |
|---------------------|------------|
| AE | Adverse Event |
| CDD | Cox-Dewar Dermatitis |
| CE | Clinical Evaluation |
| CIP | Clinical Investigation Plan |
| ClinSig | Clinically Significant |
| CRF | Case Report Form |
| EC | Ethics Committee |
| EUDAMED | European Database on Medical Devices |
| EU MDR | EU Medical Device Regulation 2017/745 |
| GCP | Good Clinical Practice |
| HCP | Healthcare Professional |
| IDM | Index of Disease Monitoring (EpiFlare primary output) |
| IEC | Independent Ethics Committee |
| ISO | International Organisation for Standardisation |
| ITT | Intent-to-Treat |
| MedDRA | Medical Dictionary for Regulatory Activities |
| NPV | Negative Predictive Value |
| PAS | Post-Approval Study |
| PDCS | Physician's Dermatology Classification Score (reference standard) |
| PI | Principal Investigator |
| PPV | Positive Predictive Value |
| PSUR | Periodic Safety Update Report |
| SAE | Serious Adverse Event |
| SaMD | Software as a Medical Device |
| SAP | Statistical Analysis Plan |
| SoA | Schedule of Activities |
| SOP | Standard Operating Procedure |
| SOTA | State of the Art |

## 2. Clinical Background

### 2.1 Disease Context

Cox-Dewar Dermatitis (CDD) is a chronic, relapsing-remitting inflammatory skin condition characterised by periods of disease quiescence punctuated by episodes of acute exacerbation ('flares'). Accurate and timely monitoring of disease activity is fundamental to effective patient management, enabling clinicians to adjust therapeutic regimens, predict impending flares, and assess treatment response.

Current approaches to CDD disease monitoring rely primarily on clinician-administered scoring tools, patient self-report, and periodic clinical assessment. These methods are subject to inter- and intra-observer variability, are resource-intensive, and frequently fail to capture the dynamic nature of CDD activity between scheduled appointments. There is an unmet clinical need for a reliable, objective, and scalable tool to support disease monitoring in this population.

### 2.2 Device Description

- Device name: EpiFlare
- Version: 2.0
- Basic UDI-DI: (01)00000000000000
- Classification: Class IIa
- Intended purpose: EpiFlare is intended to be used by qualified healthcare professionals, including dermatologists and primary care physicians, to support the monitoring of disease activity in adult patients (>=18 years) with a confirmed diagnosis of Cox-Dewar Dermatitis (CDD). The device is intended for disease monitoring and assessment of flare severity; it is not intended to establish an initial diagnosis.

### 2.3 Rationale for Clinical Investigation

Pursuant to EU MDR 2017/745 Annex XIV and Annex XV, a clinical investigation is required to generate clinical evidence that demonstrates the clinical performance and safety of EpiFlare. This builds on a retrospective image database study that demonstrated generation of an accurate and reliable output (IDM values) from the input data (skin images).

This Clinical Investigation Plan (CIP) is designed in accordance with ISO 14155:2020, the principles of the Declaration of Helsinki, and applicable EU MDR requirements.

## 3. Administrative Details

### 3.1 Sponsor Details

| Field | Details |
|-------|---------|
| Sponsor Name | SkintelligentAI |
| Registered Address | 1 Lesion Lane, London, EC1V 2NX, England |
| Primary Contact | Dr. Sarah Mitchell, MD, Consultant Dermatologist, Chief Medical Officer |
| Email | sarah.mitchell@skintelligent.com |

### 3.2 Coordinating Investigator

| Field | Details |
|-------|---------|
| Name | Dr. Sarah Mitchell, MD, Consultant Dermatologist, Chief Medical Officer |
| Institution | University College Hospital, 3rd Floor Central, 250 Euston Road, London, NW1 2PG |
| Department | Dermatology |
| Email | sarah.mitchell@skintelligent.com |
| GCP Training | Confirmed |

### 3.3 Investigation Sites

This is a multi-centre investigation. A minimum of three (3) clinical sites are planned, subject to ethics approval and site feasibility assessments. Sites will be selected to ensure the participant population is representative of the intended use population across diverse clinical settings.

### 3.4 Financing and Insurance

This clinical investigation is funded entirely by SkintelligentAI. No external public funding or investigator-initiated grants are involved. Financial arrangements with investigators and sites comply with applicable regulations and are documented in individual site agreements.

Subject insurance is in place in accordance with EU MDR Annex XV, Chapter II, Section 4.3, and applicable national requirements at each participating country.

All investigators and site staff will disclose any conflicts of interest prior to study initiation in accordance with ISO 14155:2020 requirements.

## 4. Ethics Review and Informed Consent

### 4.1 Ethics Committee Oversight

This clinical investigation will not commence at any site until written approval has been obtained from the relevant Independent Ethics Committee (IEC) or Institutional Review Board (IRB) for that site, in compliance with ISO 14155:2020, EU MDR Annex XV, and national regulations.

### 4.2 Informed Consent Process

All participants must provide written, freely given, informed consent prior to any study-specific procedures. The informed consent process will be conducted in accordance with ISO 14155:2020 Clause 4.9, the principles of the Declaration of Helsinki, and applicable national legislation.

#### 4.2.1 Standard Consent Procedure

- Potential participants will be identified by the site investigator during routine clinical visits.
- The investigator or a designated, GCP-trained study team member will discuss the study with the potential participant, provide the approved Participant Information Sheet (PIS), and allow adequate time for consideration (minimum 24 hours where clinically appropriate).
- Questions will be addressed, and written consent will be obtained on the approved Informed Consent Form (ICF) prior to any study procedures.
- A signed copy of the ICF will be retained in the study files, and a copy provided to the participant.

#### 4.2.2 Withdrawal of Consent

Participants may withdraw consent at any time without prejudice to their ongoing clinical care. Data collected prior to withdrawal may be retained and used in analyses as permitted by the consent form and applicable data protection regulations, unless the participant explicitly requests deletion.

## 5. Objectives, Endpoints and Hypotheses

### 5.1 Study Objective

To evaluate the clinical performance of EpiFlare in generating an Index of Disease Monitoring (IDM) output that accurately characterises disease activity status (active flare vs. no active flare) in adult patients with confirmed CDD, as compared against the Physician's Dermatology Classification Score (PDCS) as the clinical reference standard, under conditions representative of the intended use.

### 5.2 Primary Endpoint

Sensitivity and specificity of the EpiFlare IDM binary output (active flare / no active flare) compared to the PDCS reference standard classification, estimated with 95% confidence intervals.

Acceptability criterion (derived from state of the art): The device will be considered clinically acceptable if the lower bound of the 95% CI for sensitivity is >=0.80 and the lower bound of the 95% CI for specificity is >=0.80.

### 5.3 Secondary Endpoints

| Secondary Endpoint | Acceptability Criterion | Measure |
|-------------------|------------------------|---------|
| Flare severity classification agreement | kappa >= 0.70 (substantial agreement) | Weighted kappa for EpiFlare severity category vs. PDCS severity category |
| Positive Predictive Value (PPV) | Lower 95% CI >= 0.75 | PPV of EpiFlare IDM binary output vs. reference standard |
| Negative Predictive Value (NPV) | Lower 95% CI >= 0.80 | NPV of EpiFlare IDM binary output vs. reference standard |
| Safety: AE incidence | No device-related SAEs attributable to EpiFlare; AE rate consistent with background rates | Number and proportion of participants experiencing device-related AEs or SAEs |

### 5.4 Hypothesis

- H0 (Null Hypothesis): The sensitivity (or specificity) of EpiFlare IDM binary output is <=0.80 when compared against the PDCS reference standard.
- H1 (Alternative Hypothesis): The sensitivity and specificity of EpiFlare IDM binary output are both >0.80 when compared against the PDCS reference standard.

The study is powered to reject H0 in favour of H1, demonstrating non-inferiority relative to the predefined acceptability threshold derived from the state of the art.

## 6. Study Design and Methodology

### 6.1 Overall Design

EpiFlare-PERFORM-01 is a prospective, multi-centre, non-randomised, non-interventional clinical performance study. Participants will attend a scheduled clinical assessment visit at which both the EpiFlare IDM output and the PDCS reference standard assessment will be obtained by qualified HCPs, in a manner representative of the intended use of the device in routine clinical practice.

The study is designed in accordance with ISO 14155:2020, ISO 13485:2016, and applicable MDCG guidance for SaMD clinical evaluation. It follows the framework for diagnostic accuracy studies as described in MDCG 2020-1.

### 6.2 Reference Standard

The Physician's Dermatology Classification Score (PDCS) will serve as the clinical reference standard. The PDCS is a validated, clinician-administered scoring instrument used in the assessment of CDD disease activity. PDCS assessments will be performed by experienced, trained dermatologists at each site, blinded to the EpiFlare IDM output.

The reference standard assessment will be conducted within the same clinical encounter as the EpiFlare assessment, with a maximum permissible interval of 4 hours between the two assessments to minimise temporal variability.

### 6.3 Blinding

The dermatologist performing the PDCS reference standard assessment will be blinded to the EpiFlare IDM output. Conversely, the HCP operating EpiFlare will not be provided with the PDCS reference standard score prior to completing the EpiFlare assessment. This dual-blind approach minimises assessment bias. Blinding procedures and verification will be documented per site SOP.

### 6.4 Schedule of Activities

| Activity | Screening | Enrolment Visit (Day 0) | Follow-up (Day 90 +/- 14) |
|----------|-----------|------------------------|---------------------------|
| Informed consent | X | | |
| Eligibility review (inclusion/exclusion) | X | X | |
| Medical history / demographics | | X | |
| CDD diagnosis confirmation | | X | |
| EpiFlare IDM assessment | | X | X |
| PDCS reference standard assessment | | X | X |
| Photography / image capture | | X | X |
| Adverse event monitoring | | X | X |
| Concomitant medication review | | X | X |
| HCP usability questionnaire | | X | |
| Study completion / withdrawal | | | X |

## 7. Patient Population

### 7.1 Inclusion Criteria

Participants must satisfy all of the following inclusion criteria to be eligible:

1. Adults aged 18 years or older at the time of enrolment.
2. Confirmed clinical diagnosis of Cox-Dewar Dermatitis (CDD) according to established diagnostic criteria, documented in the medical record by a qualified dermatologist.
3. Currently under the care of a dermatologist or primary care physician for ongoing CDD management.
4. Able and willing to provide written informed consent prior to any study procedures.
5. Sufficient language proficiency to understand study information and complete patient-reported components of the EpiFlare assessment.
6. Access to, and ability to use, a compatible mobile or web-based device (per EpiFlare minimum specifications).

### 7.2 Exclusion Criteria

1. Age under 18 years.
2. Absence of a formally confirmed CDD diagnosis.
3. Any concurrent skin condition that would substantially confound assessment of CDD disease activity or the PDCS reference standard evaluation.
4. Inability to tolerate or complete the EpiFlare assessment procedure.
5. Active systemic infection or acute hospitalisation at the time of enrolment.
6. Any other condition that would make participation inadvisable or compromise data integrity.

### 7.3 Estimated Number of Subjects

A minimum of 115 evaluable participants are required across all sites, based on the sample size calculations described in Section 8. Accounting for an estimated 15% dropout or evaluability failure rate, approximately 135 participants will be enrolled.

## 8. Statistical Design and Analysis

### 8.1 Sample Size Calculation

| Parameter | Value | Justification |
|-----------|-------|---------------|
| Expected sensitivity of EpiFlare | 88% | Based on internal validation data and SOTA for comparable SaMD-based dermatology monitoring tools |
| Minimum acceptable sensitivity (H0) | 80% | Predefined acceptability criterion derived from SOTA review |
| Expected specificity of EpiFlare | 85% | Based on internal validation data |
| Minimum acceptable specificity (H0) | 80% | Predefined acceptability criterion |
| Significance level (alpha) | 0.025 (one-sided per endpoint) | To control Type I error rate for each co-primary measure |
| Statistical power (1-beta) | 90% | High power required for a performance claim in a clinical monitoring context |
| Estimated CDD flare prevalence in study population | 45% | Derived from epidemiological data and published CDD natural history studies |

Using exact binomial methods for a one-sided test at alpha=0.025 with 90% power:

- Required number of CDD flare cases (positive reference standard): ~65.
- Required number of non-flare participants (negative reference standard): ~80.
- Total required evaluable participants: ~115 (assuming 45% flare prevalence).
- To account for an anticipated 15% dropout rate, approximately 135 participants will be enrolled.

### 8.2 Analysis Populations

| Population | Definition | Primary Use |
|-----------|------------|-------------|
| Intent-to-Enrol (ITE) | All participants who provide informed consent | Safety analyses |
| Per-Protocol (PP) | All participants who complete at least one valid paired EpiFlare IDM and PDCS assessment without major protocol deviations | Primary performance analyses (main analysis) |
| Full Analysis Set (FAS) | All enrolled participants with at least one IDM output, regardless of protocol deviations | Sensitivity analyses for primary endpoint |

### 8.3 Statistical Methods

#### 8.3.1 Primary Analysis: Diagnostic Accuracy

Sensitivity, specificity, PPV, and NPV of the EpiFlare IDM binary output (active flare vs. no active flare) will be calculated against the PDCS reference standard classification.

Since both the EpiFlare IDM and the PDCS reference standard are applied to the same participants (paired design), McNemar's test will be used to compare the proportion of concordant and discordant classifications. A receiver operating characteristic (ROC) curve analysis will be performed as a supplementary exploration.

#### 8.3.2 Secondary Analyses

- Flare severity classification agreement: Weighted Cohen's kappa with quadratic weights, with 95% CI.
- Safety: Adverse events will be summarised descriptively by MedDRA preferred term, system organ class, severity, and causal relationship to EpiFlare. No formal hypothesis test is planned for the safety endpoint.
- Usability: Mean System Usability Scale (SUS) score and standard deviation will be reported.

#### 8.3.3 Handling of Missing Data

Participants with missing primary endpoint data will be excluded from the PP analysis. Sensitivity analyses using multiple imputation (fully conditional specification) will be performed on the FAS population to assess the impact of missing data on primary endpoint conclusions.

The proportion of missing data will be reported, and reasons documented. Where missing data exceed 10% of the target sample, a pre-specified sensitivity analysis will be conducted.

## 9. Safety Monitoring and Adverse Event Management

### 9.1 Definitions

| Term | Definition |
|------|------------|
| Adverse Event (AE) | Any untoward medical occurrence, unintended disease or injury, or untoward clinical signs in participants using EpiFlare, whether or not considered related to the device. |
| Serious Adverse Event (SAE) | An AE that results in death; a life-threatening condition; requires inpatient hospitalisation or prolongation of existing hospitalisation; results in persistent or significant disability or incapacity; or is a congenital anomaly or birth defect. |
| Device Deficiency | A malfunction, failure, or inadequacy of the investigational device, including failure to perform as expected based on the intended purpose or specifications. |

### 9.2 Adverse Event Reporting

All AEs will be recorded in the CRF from the date of enrolment through to the final follow-up visit. AEs will be described in terms of onset date, duration, severity (mild, moderate, severe), relationship to EpiFlare, and outcome.

SAEs and device deficiencies that could have led to an SAE will be reported to the Sponsor within 24 hours of the investigator becoming aware. The Sponsor will report SAEs to the relevant competent authority in accordance with EU MDR Article 80 and ISO 14155:2020 Clause 9.4, within the applicable regulatory timelines.

## 10. Data Management

### 10.1 Data Collection

Data will be collected using validated electronic Case Report Forms (eCRFs) hosted on a validated clinical data management system (CDMS). EpiFlare IDM outputs will be automatically captured and transferred from the device to the CDMS via a secure, encrypted API, minimising manual transcription errors.

PDCS reference standard scores and all other clinical data will be entered into the eCRF by site investigators or designated, trained site staff.

### 10.2 Data Governance and Protection

All participant data will be pseudonymised at source. Personal identifiable information will be stored separately from study data in accordance with GDPR (EU 2016/679) and applicable national data protection legislation. Participants will be assigned a unique study identification number (SIN) for all data records.

Data will be stored on secure, access-controlled servers within the European Economic Area (EEA). Access to identifiable data will be restricted to authorised personnel with a defined role in the study. A data management plan (DMP) and data transfer agreements will be established prior to study initiation.

### 10.3 Data Retention

All study data and essential documents will be retained for a minimum of 15 years from the study completion date, in accordance with EU MDR Article 87 and ISO 14155:2020 Clause 7.3.4.

## 11. Protocol Deviations and Amendments

### 11.1 Protocol Deviations

A protocol deviation is any departure from the approved CIP. Deviations will be categorised as major or minor according to their potential impact on participant safety, data integrity, or the validity of study conclusions. All deviations will be documented in the CRF, reported to the Sponsor, and reviewed for their potential impact on the analysis population classification.

Major deviations will be reported to the Ethics Committee and, where required, to the relevant competent authority within the applicable regulatory timelines.

### 11.2 Protocol Amendments

Substantial amendments to this CIP will be submitted to the relevant IEC/IRB and competent authority for approval prior to implementation. Non-substantial amendments will be documented and retained in the Trial Master File. A version history of all CIP amendments will be maintained.

## 12. Publication Policy

The Sponsor is committed to the transparent reporting of the results of this clinical investigation, regardless of outcome, in accordance with the principles of the Declaration of Helsinki and applicable EU MDR requirements. Study results will be submitted for peer-reviewed publication within 12 months of the final data lock. Investigators will be acknowledged as co-authors in accordance with ICMJE authorship criteria.

Results will be registered on an approved clinical trials registry (e.g. ClinicalTrials.gov or EU Clinical Trials Register) prior to study initiation, and summary results will be posted upon study completion.

## 13. References and Regulatory Framework

1. EU Medical Device Regulation 2017/745 (EU MDR), Annex XIV and Annex XV.
2. ISO 14155:2020 - Clinical investigation of medical devices for human subjects - Good clinical practice.
3. ISO 14971:2019 - Medical devices - Application of risk management to medical devices.
4. IEC 62366-1:2015+AMD1:2020 - Medical devices - Part 1: Application of usability engineering to medical devices.
5. MDCG 2020-1 - Guidance on clinical evaluation (MDR) / performance evaluation (IVDR) of medical device software.
6. MDCG 2020-5 - Guidance on Clinical Evaluation - Equivalence.
7. MDCG 2021-24 - Guidance on the classification of medical devices.
8. Declaration of Helsinki - World Medical Association (2013 revision).
9. General Data Protection Regulation (GDPR) - EU 2016/679.

> Fictional example only | Not regulatory advice | Beta content - incomplete and subject to change
