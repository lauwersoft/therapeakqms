# Post-Market Surveillance Plan

> Create a PMS plan to maintain a positive benefit-risk ratio over your device's lifecycle.

The post-market surveillance (PMS) plan defines the processes and activities a manufacturer undertakes to monitor the safety and performance of a software medical device once it is placed on the market.

This plan is a **legal requirement** and forms part of the overall quality management system (QMS).

## Minimum Requirements

The plan should specify the following information:

- **Types of activities** (e.g. complaints monitoring, software error logs, user surveys, literature review, etc.).
- **Methodology and scope** (e.g. frequency of review, criteria for trend analysis, sample size for surveys).
- **Indicators and thresholds** for determining when issues trigger corrective actions, vigilance reporting, or FSCA. Decision criteria should be defined to clarify when escalation to CAPA, vigilance reporting, or product modifications is required.
- **Rationale** for each activity in relation to the device's risk profile and novelty.
- **Timelines and responsibilities** within the organisation.

> **Note:** The PMS system must cover both **reactive** sources (such as complaints and incident reports) and **proactive** sources (such as literature reviews, user surveys, registries, and performance monitoring), ensuring a systematic approach in line with Annex III.

## Desired Format

Post market surveillance documentation, such as PMCF plans/reports and PMS plans/reports are typically narrative in nature. Provide them as text files.

Findings must feed into:

- **Risk management**, to ensure risks are continuously assessed and controlled;
- **Clinical evaluation**, to keep the benefit-risk profile up to date;
- **Post-market clinical follow-up (PMCF) activities**, where applicable, and
- the **corrective and preventive action (CAPA) system**, ensuring that identified issues are investigated, root causes analysed, and appropriate actions taken to prevent recurrence.

## Deep Dive

### General Information

There are no surprises here. These same details will feature throughout the technical file.

- **Date and version** - specify when the PMS Plan was created and its version.
- **Revision history** - track updates and contributing authors.
- **Medical device details** - identify the SaMD (name, UDI, version, intended purpose).

### Activities

The PMS Plan should describe specific activities in sufficient detail to demonstrate their purpose, methodology, rationale, timelines, and linkages to regulatory requirements.

Although the list below may not make for pleasant reading, involving the relevant personnel early makes this very manageable and will help to establish important processes for the lifecycle of your device.

#### Complaint and Feedback Management

- Sources: customer support tickets, app store feedback, healthcare professional/user complaints.
- Method: categorisation, severity grading, and timelines for resolution.
- Escalation: predefined thresholds for trending and CAPA.

#### Software Performance Monitoring

- Collection of anonymised usage metrics and performance logs (error rates, crash reports, latency issues).
- Review of updates/patches for effectiveness and unintended effects.
- Thresholds for identifying abnormal performance patterns.

#### Cybersecurity Surveillance

- Monitoring of vulnerabilities, threat intelligence feeds, and security incident reports.
- Regular review of third-party libraries/dependencies.
- Process for rapid patching and user communication.

#### Usability and Human Factors Feedback

- Surveys, interviews, or feedback collection from healthcare professionals and patients.
- Detection of misuse, workarounds, or off-label use.
- Assessment of training/documentation needs.

#### Literature and Market Surveillance

- Systematic review of scientific publications and regulatory databases.
- Monitoring of equivalent/similar devices, recalls, safety notices.
- Benchmarking against alternative treatment or diagnostic options.
- **Monitoring of the evolving state of the art (SOTA)** in relevant clinical, scientific, and technological domains to ensure ongoing conformity with Annex I GSPRs.

#### Trend Analysis and Signal Detection

- Define **quantitative indicators** (e.g., incident rates, complaint frequency, error log thresholds).
- Specify **statistical methods** and criteria for escalation.
- Ensure compliance with **Article 88 (trend reporting)**.

#### Risk and Benefit Reassessment

- Define how PMS data update the Risk Management File and Clinical Evaluation Report.
- Explicitly describe how findings contribute to **reassessing the benefit-risk ratio** (Annex I, GSPR 1).
- Document new hazards, changes in frequency/severity of known risks, or shifts in performance.

#### Vigilance and Communication

- Procedures for **serious incident reporting** (Article 87).
- **Field Safety Corrective Action (FSCA)** process (Article 89).
- **Internal** communication pathways (e.g. reporting findings to management, engineering, clinical, and regulatory functions) and **external** communication pathways (e.g. reporting to competent authorities, notified/approved bodies, users, and customers) must be defined.
- Updates to the **Summary of Safety and Clinical Performance (SSCP, Article 32)**, for implantable devices and for class III devices.

#### Responsibilities and Governance

- Identification of roles responsible for PMS activities (software engineering, clinical safety, regulatory, and the Person Responsible for Regulatory Compliance (PRRC) as required under EU MDR Article 15).
- Escalation pathways for safety issues.

#### Expected Outputs

- **PMS Report (Article 85)** for Class I devices.
- **PSUR (Periodic Safety Update Report, Article 86)** for Class IIa/IIb/III devices.
  - Frequency: every 2 years for IIa, annually for IIb and III.
