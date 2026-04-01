# Risk Management Plan

> **Disclaimer (Fictional Example -- Beta Release):** This document is part of a fictional example technical file provided by Scarlet for educational purposes only. Manufacturers remain responsible for generating evidence appropriate to their medical device.

## Document Control Information

| Field | Value |
|---|---|
| Title | Risk Management Plan |
| Document ID | SAI-EF-RMP-001 |
| Version | 1.1 |
| Publication date | 2025-12-05 |
| Author(s) | Martin Hauer |
| Approver(s) | Dr. Livia Tan |

### Change History

| Change ID | Version | Content changes |
|---|---|---|
| N/A | 1.0 | Initial publication |
| CR-001 | 1.1 | Added section 5.2 |

## Table of Contents

1. Purpose
   - 1.1. Risk management deliverables
2. Responsibilities and authorities
3. Risk management activities
   - 3.1. Risk analysis
   - 3.2. Risk evaluation
   - 3.3. Risk control
   - 3.4. Residual risk evaluation
4. Risk management review
   - 4.1. Review criteria and timing
   - 4.2. Collection and review of production and post-production information
5. Change control and plan updates
   - 5.1. Device change control
   - 5.2. Risk management plan updates
6. Annex A: Risk acceptability policy
   - A.1. Probability and severity estimation criteria
   - A.2. Risk acceptability criteria
   - A.3. Overall risk acceptability criteria

## 1. Purpose

This (safety) Risk Management Plan (RMP) applies to the software medical device EpiFlare, developed by SkintelligentAI, currently classified as a Class IIa device under EU MDR 2017/745. The security risk management plan is out of scope of this document and is tackled separately.

This Risk Management Plan is established in accordance with ISO 14971:2019 and defines the risk management activities to be performed for EpiFlare throughout its lifecycle.

The plan covers the currently developed pre-market version as well as any future updates or versions of EpiFlare, unless otherwise specified in a separate risk management document.

For information on the EpiFlare device, including its intended purpose, see the Device Description.

The plan encompasses all intended users and environments:

- **Patients:** capturing and submitting images from home via the EpiFlare mobile application
- **Healthcare providers:** receiving, interpreting, and acting upon EpiFlare-generated measurement data within the GP or dermatology clinic setting

All reasonably foreseeable misuse scenarios, including incorrect image capture or delayed uploads are considered within the scope of this plan.

This plan applies to all stages of the device lifecycle, including but not limited to:

- Design and development
- Verification and validation
- Production
- Maintenance, updates, and software decommissioning

### 1.1 Risk Management Deliverables

Three risk management documents shall be delivered for EpiFlare:

1. **Risk Management Plan (this document)** -- Defines the risk management activities, responsibilities, and processes to be applied to EpiFlare.
2. **Risk Management File** -- Contains the detailed risk analysis and risk control records, including:
   - Device characteristics
   - Hazardous sequences (i.e hazards leading to hazardous situations, and to harms)
   - Risk estimation
   - Risk evaluation
   - Risk controls
   - Residual risk evaluation
3. **Risk Management Report** -- Summarizes the risk management activities conducted and demonstrates that the overall residual risk is acceptable.

All risk management documents shall be maintained and controlled in accordance with the organization's document control procedures. The Risk Management File shall be updated throughout the device lifecycle as new information becomes available or as the device is modified. All documents are maintained in the designated document management system and are accessible to all personnel with assigned risk management responsibilities.

## 2. Responsibilities and Authorities

| Personnel Name | Role | Responsibilities and authorities |
|---|---|---|
| Dr. Livia Tan | Chief Operating Officer | Perform all necessary review activities. |
| Martin Hauer | Quality Management Representative | Overview of all risk management activities. |
| Dr. Vincent Osei | Clinical Evaluation and Usability Engineer | Conduct risk analysis and risk evaluation, as well as risk control selection. |
| Ingrid Morales | Software Engineer | Overview and contribute to risk control selection and implementation. |
| Rajat Kumar | Software Validation and Verification Engineer | Develop and execute risk control implementation verification activities. |
| Elena Wei | Post-market Surveillance Analyst | Develop risk control effectiveness verification activities as well as collect, review and evaluate production and post-production information. |

## 3. Risk Management Activities

### 3.1 Risk Analysis

#### 3.1.1 Intended Use and Reasonable Foreseeable Misuse

The intended use of EpiFlare will be documented. It will take into account:

- **Use environments:** Home, GP practice, dermatology clinic.
- **User profiles:** Doctors, patients.
- **Intended patient population:** Adult patients (>=18 years) with a confirmed diagnosis of CDD.

The Operating principle shall also be defined, and the reasonably foreseeable misuse documented.

#### 3.1.2 Identification of Characteristics Related to Safety

The purpose of this activity is to:

- Identify the qualitative and quantitative characteristics of EpiFlare that could impact patient safety.
- Where applicable, the limits of those characteristics will be defined.

Records of device characteristics related to safety are captured in the Risk Management File.

#### 3.1.3 Identification of Hazards and Hazardous Situations

As part of this activity, the known and foreseeable hazards associated with EpiFlare will be identified and documented.

For each identified hazard, the reasonably foreseeable sequences or combinations of events that can result in a hazardous situation will be considered, and the resulting hazardous situation(s) will be identified.

Hazards and hazardous situations will be identified through the following risk-analysis techniques:

- Preliminary hazard analysis
- Event tree analysis
- Failure mode and effects analysis
- Threat modelling

Records of identified hazards and hazardous sequences are captured in the Risk Management File.

#### 3.1.4 Risk Estimation

Each hazard and hazardous situation pair will be associated with one or more potential harms. In this activity, the associated risk will be estimated for each identified hazardous situation.

This will be done by estimating the probability of the hazardous situation arising from the associated hazard, and, the probability of the harm arising from the hazardous situation.

For hazardous situations for which the probability of the occurrence of harm cannot be estimated, the possible consequences will be listed, and a risk evaluation will still take place.

Additionally, each identified harm will have a severity estimate. Please refer to Annex A for more information on the system used for the categorisation of the probability and severity in the context of EpiFlare's risk management.

Risk estimation records are captured in the Risk Management File.

### 3.2 Risk Evaluation

For each identified hazardous situation, the estimated risk will be evaluated to determine if the risk is acceptable or not. The criteria used is defined in Annex A. If the risk is acceptable, it will be treated as residual risk. If the risk is not acceptable, the risk control activities described in the upcoming section will be applied.

Risk evaluation records are captured in the Risk Management File.

### 3.3 Risk Control

#### 3.3.1 Risk Control Option Analysis

Risk control measures that are appropriate to reduce the risk to an acceptable level will be determined in this activity.

When evaluating which risk control measures to consider for a certain risk, the following priority will be considered:

1. Inherently safe design
2. Protective measures in EpiFlare or in the engineering process
3. Information for safety and/or, where appropriate, training to users

Note that the relevant standards will be applied while analysing the risk control options.

#### 3.3.2 Implementation of Risk Control Measures

All risk control measures will be associated with a requirement. All requirements will be implemented during the design and development phase.

#### 3.3.3 Risk Control Implementation and Effectiveness Verification

Each risk control measure implementation will be verified through either a software verification activity for software-based control measures or a manual review of the implementation of non-software-based risk control measures.

Additionally, the effectiveness of risk control measures will be verified through either usability engineering activities (information for safety and training to users) or a validation test (safe design/protective measures).

The effectiveness of risk control measures will be continuously monitored in the post-market.

#### 3.3.4 Risks Arising from Risk Control Measures

Additionally, once all risk control measures have been implemented, a review will be conducted with regard to whether new hazards or hazardous situations are introduced, or if the estimated risks for previously identified hazardous situations are affected by the introduction of the risk control measures.

#### 3.3.5 Risk Control Completeness

Risk control activities will be reviewed to ensure that the risks from all identified hazardous situations have been considered and all risk control activities have been completed.

Risk control measures and traceability to their implementation and effectiveness verification are captured in the Risk Management File. Confirmation of risk control completeness is captured in the Risk Management Report.

### 3.4 Residual Risk Evaluation

Once all risk control measures are implemented, the residual risk of each risk record will be evaluated using the same criteria defined in Annex A.

Residual risk evaluation records are captured in the Risk Management File.

#### 3.4.1 Benefit-Risk Analysis

If a residual risk is not judged acceptable and further risk control is not practicable, the appropriate data and literature will be gathered and reviewed to determine if the benefits of the intended use outweigh this residual risk.

If the conclusion does not prove that the benefits outweigh the residual risk, the medical device or its intended use will be modified.

Benefit-risk analysis statements are captured in the Risk Management Report.

#### 3.4.2 Overall Residual Risk Evaluation

The overall residual risk posed by EpiFlare will be evaluated taking into account the contributions of all residual risks in relation to the benefits of the intended use, using the method and criteria for acceptability of the overall residual risk defined in Annex A.

When the overall residual risk is judged acceptable, the users will be informed of any significant residual risks through the accompanying documentation by including all the necessary information. The criteria for determining what constitutes a significant residual risk is outlined in Annex A.

When the overall residual risk is not acceptable in relation to the benefits of the intended use, we will consider implementing additional risk control measures or modifying the medical device or its intended use.

Confirmation of overall residual risk evaluation is captured in the Risk Management Report.

## 4. Risk Management Review

### 4.1 Review Criteria and Timing

Risk management reviews shall be conducted to ensure that:

- The risk management plan has been appropriately implemented;
- The overall residual risk is acceptable; and
- Appropriate methods are in place to collect and review information in the production and post-production phases.

**Review timing:**

- Prior to design transfer
- Prior to initial production release
- Annually during post-production
- When triggered by:
  - Significant post-production information
  - Device changes that could affect safety
  - Identification of new hazards or hazardous situations
  - Changes to risk acceptability criteria
  - Regulatory requirements

**Review criteria:** A risk management review is considered complete when:

- All risk management activities defined in this plan have been completed or are in progress with defined completion dates
- All identified hazardous situations have been evaluated
- All unacceptable risks have been controlled or subject to benefit-risk analysis
- Overall residual risk has been evaluated and deemed acceptable
- Risk management file is complete and up to date
- Post-production information collection and review processes are established and functioning

**Review records:** All risk management reviews shall be documented, including:

- Date of review
- Review participants
- Review findings
- Actions required (if any)
- Approval by top management representative

These records will be referenced in the Risk Management Report.

### 4.2 Collection and Review of Production and Post-Production Information

Information relevant to the medical device in the production and post-production phases will be actively collected and reviewed. The following information will be collected:

- Information generated during production and monitoring of the production process;
- Information generated by the user;
- Information generated by those accountable for the installation, use and maintenance of the EpiFlare;
- Publicly available information; and
- Information related to the generally acknowledged state of the art.

A review of the above information will be conducted to establish its relevance to safety, especially whether:

- Previously unrecognised hazards or hazardous situations are present;
- An estimated risk arising from a hazardous situation is no longer acceptable;
- The overall residual risk is no longer acceptable in relation to the benefits of the intended use;
- The generally acknowledged state of the art has changed;
- The risk control measures implemented remain effective.

As an outcome:

- If the collected information is determined to be relevant to safety, the risk management file will be reviewed to determine whether reassessment of risks and/or assessment of new risks is necessary.
- If the residual risk is no longer acceptable, the impact on previously implemented risk control measures will be evaluated and considered as an input for modification of the medical device.
- If a risk control measure is not proving to be effective, the corresponding risk record will be re-evaluated and additional risk control measures may be introduced.

In regards to the risk management process, the information gathered will be used to evaluate the impact on previously implemented risk management activities. The evaluation result will be considered as an input for the review of the suitability of the risk management process by top management.

## 5. Change Control and Plan Updates

### 5.1 Device Change Control

Any change to EpiFlare, including but not limited to:

- Design changes
- Software updates or modifications
- Changes to intended use or indications for use
- Changes to manufacturing processes
- Changes to labeling or instructions for use

shall trigger an evaluation to determine if risk management activities need to be performed or updated. The evaluation shall consider:

- Whether the change could introduce new hazards or hazardous situations
- Whether the change could affect existing risk estimates
- Whether existing risk control measures remain effective
- Whether new risk control measures are required

If the evaluation determines that risk management activities are required, the following shall be performed:

1. Update risk analysis as necessary (identification of new hazards, hazardous situations, or changes to existing ones)
2. Re-evaluate affected risks
3. Implement additional risk control measures if required
4. Re-evaluate residual risks
5. Update overall residual risk evaluation if necessary
6. Update risk management file

### 5.2 Risk Management Plan Updates

This Risk Management Plan shall be reviewed and updated as necessary:

- When changes to the risk management process are required
- When regulatory requirements change
- When ISO 14971 standard is updated
- When deficiencies in the risk management process are identified
- As part of the annual risk management review

Plan updates shall be:

- Documented in the change history
- Reviewed and approved by the top management representative
- Communicated to all personnel with risk management responsibilities
- Maintained under document control procedures

## Annex A: Risk Acceptability Policy

### A.1 Probability and Severity Estimation Criteria

We will use a semi-quantitative categorization of probability and severity.

#### A.1.1 Probability

| Definition | Probability | Value |
|---|---|---|
| Frequent | >= 1/1,000 | 5 |
| Probable | < 1/1,000 | 4 |
| Occasional | < 1/10,000 | 3 |
| Remote | < 1/100,000 | 2 |
| Improbable | < 1/1,000,000 | 1 |

Including criteria for accepting risks when the probability of occurrence of harm cannot be estimated.

#### A.1.2 Severity

| Rating | Definition | Severity |
|---|---|---|
| Catastrophic | Death | 5 |
| Critical | Permanent impairment or life-threatening | 4 |
| Serious | Injury or impairment | 3 |
| Minor | Temporary injury or impairment | 2 |
| Negligible | Inconvenience or temporary discomfort | 1 |

### A.2 Risk Acceptability Criteria

| Severity / Probability | 1 (Negligible) | 2 (Minor) | 3 (Serious) | 4 (Critical) | 5 (Catastrophic) |
|---|---|---|---|---|---|
| 1 (Improbable) | Acc | Acc | Acc | Acc | Acc |
| 2 (Remote) | Acc | Acc | Acc | Acc | N Acc |
| 3 (Occasional) | Acc | Acc | Acc | N Acc | N Acc |
| 4 (Probable) | Acc | Acc | N Acc | N Acc | N Acc |
| 5 (Frequent) | Acc | N Acc | N Acc | N Acc | N Acc |

### A.3 Overall Risk Acceptability Criteria

The overall residual risk of the device shall be deemed acceptable when all individual residual risks are either:

- Acceptable per the predefined risk matrix, or
- Subject to a documented benefit-risk analysis that demonstrates clinical benefit outweighs the residual risk,

and no additional risk control measures are practicable.

Furthermore, significant residual risks will be disclosed in the IFU. A significant residual risk is calculated according to the below table:

| Severity / Probability | 1 (Negligible) | 2 (Minor) | 3 (Serious) | 4 (Critical) | 5 (Catastrophic) |
|---|---|---|---|---|---|
| 1 (Improbable) | Acc | Acc | Acc | Acc | Sig |
| 2 (Remote) | Acc | Acc | Acc | Sig | N Acc |
| 3 (Occasional) | Acc | Acc | Sig | N Acc | N Acc |
| 4 (Probable) | Acc | Sig | N Acc | N Acc | N Acc |
| 5 (Frequent) | Sig | N Acc | N Acc | N Acc | N Acc |

> **Fictional example only | Not regulatory advice | Beta content -- incomplete and subject to change**
