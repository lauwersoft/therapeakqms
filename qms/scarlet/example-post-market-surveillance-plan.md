# Post-Market Surveillance Plan

> **Disclaimer (Fictional Example -- Beta Release):** This document is part of a fictional example technical file provided by Scarlet for educational purposes only. Manufacturers remain responsible for generating evidence appropriate to their medical device.

## Document Control Information

| Field | Value |
|---|---|
| Title | Post-market Surveillance Plan |
| Document ID | SAI-EF-PMS-001 |
| Version | 1.0 |
| Publication date | 2026-02-28 |
| Author(s) | Elena Wei |
| Approver(s) | Dr. Livia Tan |

### Change History

| Change ID | Version | Content changes |
|---|---|---|
| N/A | 1.0 | Initial publication |

## Table of Contents

1. Purpose
2. Abbreviations
3. Device information
4. Personnel responsible for post-market activities
5. PMS activities
   - 5.1 Complaint and feedback management
   - 5.2 Software and performance monitoring
   - 5.3 Cybersecurity surveillance
   - 5.4 Usability and human factors feedback
   - 5.5 Trend analysis and signal detection
   - 5.6 Benefit-risk assessment
   - 5.7 Vigilance and regulatory communication
6. Expected outputs and reporting
   - 6.1 Periodic safety update report (PSUR)
   - 6.2 PMS report
   - 6.3 CAPA records
   - 6.4 Vigilance records
7. Traceability
8. Plan review and update

## 1. Purpose

This Post-Market Surveillance (PMS) Plan has been prepared in accordance with Article 84 and Annex III of EU MDR 2017/745. It defines the systematic procedures for collecting and analysing post-market data on the safety and performance of EpiFlare throughout its product lifecycle.

## 2. Abbreviations

| Abbreviation | Definition |
|---|---|
| CAPA | Corrective and Preventive Action |
| CDD | Cox-Dewar Dermatitis |
| CER | Clinical Evaluation Report |
| CVSS | Common Vulnerability Scoring System |
| CVE | Common Vulnerabilities and Exposures |
| DDoS | Distributed Denial of Service |
| EUDAMED | European Database on Medical Devices |
| FSCA | Field Safety Corrective Action |
| FSN | Field Safety Notice |
| GDPR | General Data Protection Regulation |
| GSPR | General Safety and Performance Requirement |
| HCP | Healthcare Professional |
| IDM | Inflammatory Disease Metric |
| IFU | Instructions for Use |
| KPI | Key Performance Indicator |
| ML | Machine Learning |
| MDR | Medical Device Regulation (EU) 2017/745 |
| NVD | National Vulnerability Database |
| PHI | Protected Health Information |
| PII | Personally Identifiable Information |
| PMCF | Post-Market Clinical Follow-Up |
| PMS | Post-Market Surveillance |
| PRRC | Person Responsible for Regulatory Compliance |
| PSUR | Periodic Safety Update Report |
| QMS | Quality Management System |
| RMF | Risk Management File |
| SaMD | Software as a Medical Device |
| SOTA | State of the Art |
| SSCP | Summary of Safety and Clinical Performance |
| UDI | Unique Device Identifier |

## 3. Device Information

- **Device name:** EpiFlare
- **Version:** 2.0
- **Basic UDI-DI:** (01)00000000000000
- **Classification:** Class IIa
- **Intended purpose:** EpiFlare is intended to be used by qualified healthcare professionals, including dermatologists and primary care physicians, to support the monitoring of disease activity in adult patients (>=18 years) with a confirmed diagnosis of Cox-Dewar Dermatitis (CDD). The device is intended for disease monitoring and assessment of flare severity; it is not intended to establish an initial diagnosis.

See the Device Description for more detailed information.

## 4. Personnel Responsible for Post-Market Activities

The following roles are responsible for the planning, execution, and oversight of PMS activities. All personnel carrying out PMS activities must have appropriate training and competency documented within the QMS.

| Personnel Name | Role | Responsibilities and authorities |
|---|---|---|
| Dr. Livia Tan | Chief Operating Officer | Perform all necessary review activities. |
| Martin Hauer | Quality Management Representative | Complaint management system administration and maintenance of PMS records within the QMS. |
| Dr. Vincent Osei | Clinical Evaluation and Usability Engineer | Usability and human factors feedback collection, analysis, and reporting. Escalation of usability concerns to Quality and Clinical functions. |
| Ingrid Morales | Software Engineer | Overview and contribute to risk control selection and implementation. |
| Rajat Kumar | Software Validation and Verification Engineer | Software performance monitoring, cybersecurity surveillance, technical investigation of software-related complaints, and implementation of corrective software updates. |
| Elena Wei | Post-market surveillance analyst | Coordination of PMS activities, maintenance of this PMS Plan, preparation of PSURs, and management of vigilance reporting and external regulatory communications. |

## 5. PMS Activities

### 5.1 Complaint and Feedback Management

**Objective**

To capture, categorise, investigate, and resolve feedback from all sources relating to the safety, performance, or usability of EpiFlare.

**Sources**

- Customer support tickets submitted via the EpiFlare platform or manufacturer website.
- App store reviews and ratings (iOS App Store, Google Play Store).
- Feedback and complaints submitted directly by healthcare professionals or patients.
- Reports from distribution partners or service providers.

**Methodology**

All incoming complaints and feedback will be logged in the complaints management system within two working days of receipt. Each record will be assigned a unique reference, and the complaint will be categorised by source, type (safety, performance, usability, other), and severity. Severity grading will be applied in accordance with the manufacturer's QMS procedure using a three-tier classification: minor, moderate, and serious. Complaints classified as serious will trigger an immediate review for potential vigilance reporting obligations.

### 5.2 Software Performance Monitoring

**Objective**

To monitor the technical performance of EpiFlare software in real-world deployment and to identify abnormal patterns that may affect safety or performance.

**Sources**

- Anonymised application usage logs, including session data and feature utilisation metrics.
- System error and crash reports generated automatically by the application.
- Performance telemetry including image processing latency, IDM generation times, and alert delivery reliability.
- Review of software updates and patches following release.

**Methodology**

Usage and performance logs will be collected continuously from the deployed application. Data will be aggregated and reviewed on a monthly basis by the software engineering function. Key performance indicators (KPIs) will include application crash rate, image processing failure rate, false alert rate, and IDM generation success rate. Monthly performance reports will be compiled and reviewed against predefined thresholds.

### 5.3 Cybersecurity Surveillance

**Objective**

To identify, assess, and respond to cybersecurity vulnerabilities and threats that could affect the integrity, availability, or confidentiality of EpiFlare or patient data.

**Sources**

- Monitoring of relevant cybersecurity vulnerability databases (e.g., NVD, CVE).
- Threat intelligence feeds specific to mobile health applications and medical device software.
- Security incident reports from users or internal security monitoring.
- Regular review of third-party libraries, software dependencies, and operating system compatibility.

**Methodology**

Automated monitoring tools will be used to screen for relevant cybersecurity vulnerabilities on a continuous basis. A formal review of all third-party dependencies will be conducted quarterly. Identified vulnerabilities will be risk-assessed using a recognised scoring framework (e.g., CVSS). The response timeline will be determined by risk score, with critical vulnerabilities requiring emergency patching within 72 hours.

### 5.4 Usability and Human Factors Feedback

**Objective**

To monitor the real-world effectiveness of usability-dependent risk controls, detect patterns of misuse or user error, and identify any need for revision of instructions for use, in-app guidance, or training materials. This activity is directly informed by the Risk Management File, in which several residual risk controls rely on correct user behaviour and are classified as 'information for safety' measures rather than inherently safe design.

**Sources**

- Structured periodic surveys of healthcare professionals (frequency: semi-annual), including targeted questions on comprehension and application of IDM interpretation guidance and the decision-support framing of the device.
- Patient-facing in-app feedback prompts following image capture sessions, designed to assess engagement with image quality guidance and patient understanding of algorithm limitations.
- Analysis of complaint and support ticket data specifically categorised against the three usability-dependent risk controls identified above.
- Review of image quality rejection logs to assess rates at which the automated image quality check (SAI-EF-RSKCTRL-002) is triggered, as a proxy indicator of patient adherence to image capture guidance.
- Review of support enquiries for patterns suggesting confusion, workarounds, or off-label use.

**Methodology**

Semi-annual healthcare professional surveys will include validated usability scales alongside targeted questions on guidance comprehension and alert interpretation. Patient feedback will be collated and reviewed quarterly. Image quality rejection rates will be tracked monthly as a quantitative proxy for patient adherence to imaging guidance. Identified usability concerns will be categorised by frequency, severity, and linkage to specific risk controls and hazardous sequences in the RMF.

**Responsibility**

Clinical Affairs, with support from Regulatory Affairs.

### 5.5 Trend Analysis and Signal Detection

**Objective**

To systematically identify emerging safety or performance signals from aggregated post-market data and to ensure compliance with the trend reporting requirements of Article 88 of EU MDR 2017/745.

**Indicators**

The following quantitative indicators will be tracked on an ongoing basis:

- Complaint rate (number of complaints per 1,000 active users per quarter).
- Serious incident rate (number of incidents meeting Article 2(65) definition per quarter).
- Software crash and error rate (percentage of sessions, monthly).
- IDM false alert rate (deviation from pre-market baseline, monthly).
- IDM sensitivity and specificity estimates from real-world performance data (derived from PMCF, annually).
- Rate of alert delivery failures (monthly).

**Methodology**

Quantitative indicators will be tracked against predefined baseline values established from the pre-market dataset and from the first six months of post-market data. Statistical process control methods will be applied to identify trends that deviate from expected variation. A formal trend analysis report will be compiled quarterly. Findings will be reviewed by a cross-functional team including Quality, Clinical, and Regulatory representation.

**Responsibility**

Quality Assurance, with cross-functional review involving Clinical Affairs and Regulatory Affairs.

### 5.6 Benefit-Risk Assessment

**Objective**

To ensure that post-market data are systematically used to update the Risk Management File (RMF) and Clinical Evaluation Report (CER), and to confirm that the benefit-risk profile of EpiFlare remains favourable throughout the product lifecycle.

**Risk informed context**

The RMF notes that for several hazardous sequences, inherently safe design is not a feasible risk control method because the device's core function relies on an ML-based prediction algorithm whose outputs are inherently probabilistic rather than fully deterministic. As a result, the complete elimination of inaccurate predictions through design alone is not technically achievable. Residual risk for these sequences therefore relies on a combination of protective measures and information for safety controls, the continued effectiveness of which must be confirmed through post-market data.

**Methodology**

All PMS activities described in this plan feed into a structured annual review of the RMF and CER. New hazards identified from complaint data, literature monitoring, usability feedback, or PMCF activities will be assessed for inclusion in the risk assessment. Changes in the estimated frequency or severity of known risks will be documented and the residual risk re-evaluated. In particular, post-market incidence data will be used to validate or challenge the probability estimates assigned at pre-market stage, which were based on expert judgement in the absence of real-world use data.

**Responsibility**

Clinical Affairs and Regulatory Affairs, coordinated by the PRRC.

### 5.7 Vigilance and Regulatory Communication

**Objective**

To ensure timely identification, reporting, and follow-up of serious incidents and field safety corrective actions in accordance with the vigilance requirements of EU MDR 2017/745.

#### Serious Incident Reporting (Article 87)

A serious incident is defined in accordance with Article 2(65) of EU MDR 2017/745 as any malfunction or deterioration in the characteristics or performance of a device, or any inadequacy in the labelling or instructions for use, which directly or indirectly has or could have led to death or serious deterioration in the state of health of a patient, user, or other person.

For EpiFlare, serious incidents may include, but are not limited to: systematic failure to generate alerts for clinically significant disease flares; generation of false alerts at a rate causing patient harm or clinical resource depletion; software malfunction resulting in data loss that affects clinical decision-making; and cybersecurity incidents resulting in patient data breach or device compromise.

Suspected serious incidents will be assessed by the Regulatory Affairs function within 24 hours of identification. Reportable incidents will be submitted to the relevant competent authority via EUDAMED within the timelines specified under Article 87(5): without delay and no later than 15 days (life-threatening incidents), 30 days (other serious incidents), or a trend report as applicable.

#### Field Safety Corrective Actions (Article 89)

Where a FSCA is required, the manufacturer will prepare and submit a Field Safety Notice (FSN) to the relevant competent authority in advance of its communication to users, unless the urgency of the situation requires immediate action. FSCAs will be executed in accordance with the documented FSCA procedure within the QMS.

#### Internal Communication

Internal communication pathways will ensure that safety signals are escalated promptly to quality management, engineering, clinical, and regulatory functions. A cross-functional safety review meeting will be convened within 48 hours of identification of any suspected serious incident.

#### External Communication

External communications will be coordinated by the Regulatory Affairs function and the PRRC. Communications to healthcare providers and patients regarding safety matters will be documented and tracked. Regular updates will be submitted to the Notified Body as required, including through annual PSUR submissions.

**Responsibility**

Regulatory Affairs and PRRC, with cross-functional support.

## 6. Expected Outputs and Reporting

### 6.1 Periodic Safety Update Report (PSUR)

EpiFlare is classified as Class IIa under EU MDR 2017/745. In accordance with Article 86, a PSUR will be prepared and updated at least every two years. The PSUR will summarise the results and conclusions of PMS activities, an assessment of whether benefits continue to outweigh risks, the main findings of the PMCF, sales volumes and the estimated number of users, and an evaluation of the overall risk-benefit profile of the device.

The PSUR will be submitted to the Notified Body as part of the post-market surveillance reporting obligations and will be made available to competent authorities upon request. The PSUR will be submitted via EUDAMED as required.

### 6.2 PMS Report

Notwithstanding the PSUR requirement, a brief internal PMS Report summarising key PMS findings will be compiled on an annual basis. This report will be reviewed at the annual management review and will inform updates to the RMF and CER.

### 6.3 CAPA Records

All corrective and preventive actions initiated as a result of PMS findings will be documented in the CAPA system, tracked to completion, and reviewed for effectiveness. CAPA records will form part of the PMS documentation reviewed in each PSUR cycle.

### 6.4 Vigilance Records

All serious incident assessments, reports submitted to competent authorities, and FSCA records will be maintained within the QMS and referenced in the PSUR. Reporting records will include the date of initial identification, assessment timeline, reporting date, and outcome.

## 7. Traceability to Technical File Documents

This PMS Plan operates as an integral component of the manufacturer's QMS and the Technical File for EpiFlare. The following table identifies the key linkages between this plan and other documents in the technical file.

| Document | Document ID | Relationship to the PMS Plan |
|---|---|---|
| Risk Management File | SAI-EF-RMF-001 | PMS findings feed directly into ongoing risk identification, estimation, and control evaluation. Any new hazard or change in risk profile identified through PMS activities is assessed under the risk management process defined in the RMF, in accordance with ISO 14971. |
| Risk Management Plan | SAI-EF-RMP-001 | Defines the risk management process, risk acceptability criteria, and residual risk evaluation methodology referenced by PMS activities when assessing the significance of post-market safety signals. |
| Clinical Evaluation Report (CER) | SAI-EF-CER-001 | Post-market clinical performance data, literature surveillance findings, and PMCF outputs collected under this PMS Plan update the clinical evidence base on an ongoing basis and support periodic CER renewal. |
| Clinical Evaluation Plan (CEP) | SAI-EF-CEP-001 | Defines the clinical evaluation methodology and acceptability criteria against which post-market clinical data collected under this PMS Plan are assessed during CER updates. |
| Post-Market Clinical Follow-Up Plan (PMCF Plan) | SAI-EF-PMCF-001 | PMS activities complement and inform PMCF activities. Residual uncertainties identified through PMS may result in new PMCF objectives or updated methodologies. Conversely, PMCF outputs are a key input to the PMS data analysis and trend reporting described in this plan. |
| Design History File | SAI-EF-DHF-001 | Significant findings from PMS that result in device modifications are documented in the Design History File and assessed through the change control process. |
| Instructions for Use (IFU) | SAI-EF-IFU-001 | PMS data -- particularly usability and human factors feedback (Section 5.4) -- may identify the need for revisions to the IFU, in-app guidance, or training materials to maintain the effectiveness of information-for-safety risk controls. |
| Device Description | SAI-EF-DEVD-001 | Provides the device characteristics, intended purpose, and intended patient population that define the scope of PMS activities described in this plan (Section 3). |

## 8. Plan Review and Update

This PMS Plan will be reviewed and updated at least annually, or more frequently in response to significant findings from PMS activities, regulatory guidance updates, changes to the device or its intended use, or as recommended by the PRRC. Updates to this document will be subject to document control procedures and will be reflected in the revision history.

The adequacy and effectiveness of PMS activities will be evaluated as part of the annual management review. Where activities are found to be insufficient to detect emerging safety or performance signals, the plan will be revised and additional activities introduced.

> **Fictional example only | Not regulatory advice | Beta content -- incomplete and subject to change**
