# Clinical Evaluation Report (CER)

This document outlines the structure and content requirements for the Clinical Evaluation Report as required under EU MDR 2017/745.

> Disclaimer (fictional example - beta release): This document is part of a fictional example technical file provided by Scarlet for educational purposes only. Manufacturers remain responsible for generating evidence appropriate to their medical device.

## Document Control Information

| Field | Value |
|-------|-------|
| Title | Clinical Evaluation Report (CER) |
| Document ID | SAI-EF-CER-001 |
| Version | 1.0 |
| Publication date | 2026-02-15 |
| Author(s) | Dr. James Anderson; Neha Fleming |
| Approver(s) | Dr. Sarah Mitchell |

| Change ID | Version | Content changes |
|-----------|---------|-----------------|
| N/A | 1.0 | Initial publication |

## 1. Purpose

The purpose of this report is to present a systematic and comprehensive assessment of the clinical evidence -- including data identification, appraisal, and analysis -- to demonstrate that EpiFlare:

- achieves its intended clinical performance and provides a clinically meaningful benefit when used as intended;
- has an acceptable safety profile, with residual risks that are low and appropriately managed;
- demonstrates a favourable benefit-risk ratio, taking into account the current clinical state of the art, available alternative management options, and the totality of clinical evidence; and
- conforms with the applicable General Safety and Performance Requirements (GSPRs) set out in Annex I of the EU MDR, in particular GSPRs 1, 3, 23, and 24.

## 2. Abbreviations

| Abbreviation | Definition |
|-------------|------------|
| CDD | Cox-Dewar Dermatitis |
| CEP | Clinical Evaluation Plan |
| CER | Clinical Evaluation Report |
| CIR | Clinical Investigation Report |
| CSR | Clinical Study Report |
| DIAU | Dermal Inflammation Assessment Unit |
| EU MDR | European Union Medical Device Regulation (Regulation (EU) 2017/745) |
| GSPR | General Safety and Performance Requirement |
| IDM | Inflammatory Decompensation Marker |
| IFU | Instructions for Use |
| MEDDEV | Medical Devices Guidance Document |
| PMCF | Post-Market Clinical Follow-up |
| PMS | Post-Market Surveillance |
| PSUR | Periodic Safety Update Report |
| RMSE | Root Mean Square Error |
| UDI-DI | Unique Device Identifier - Device Identifier |

## 3. Device Description

- Device name: EpiFlare
- Version: 2.0
- Basic UDI-DI: (01)00000000000000
- Classification: Class IIa
- Intended purpose: EpiFlare is intended to be used by qualified healthcare professionals, including dermatologists and primary care physicians, to support the monitoring of disease activity in adult patients (>=18 years) with a confirmed diagnosis of Cox-Dewar Dermatitis (CDD). The device is intended for disease monitoring and assessment of flare severity; it is not intended to establish an initial diagnosis.

See the Device Description for more detailed information.

## 4. Clinical Evaluators

**Evaluators:**

- Dr. Sarah Mitchell, MD - Consultant Dermatologist, Chief Medical Officer (Lead Clinical Evaluator)
- Dr. James Anderson, PhD - Clinical Research Specialist (Clinical Evaluator)
- Neha Fleming - Clinical Portfolio Manager

**Roles and contributions:**

- Dr. Mitchell led clinical interpretation and benefit-risk assessment.
- Dr. Anderson led methodological oversight and statistical analysis.
- Both evaluators contributed to data identification, appraisal, analysis, and preparation of this report.
- Neha Fleming is responsible for the preparation of the CEP and supports the clinical evaluation activities.

**Evaluator suitability and independence:**

Dr. Sarah Mitchell and Dr. James Anderson meet the requirements specified in MEDDEV 2.7/1 Rev. 4, Section 6.4. Both evaluators possess relevant higher education degrees and professional experience exceeding the minimum requirements.

Neha Fleming does not individually meet the requirements of MEDDEV 2.7/1 Rev. 4, Section 6.4; however, her contributions were limited to preparation of the Clinical Evaluation Plan and support of clinical evaluation activities, performed under the supervision of Dr. Anderson. The clinical evaluation was conducted by a multidisciplinary team which, taken as a whole, meets the applicable competence requirements.

Signed declarations of interest have been provided, confirming evaluator independence for the purposes of this clinical evaluation.

## 5. Clinical Background

### 5.1 Disease Characteristics

Cox-Dewar Dermatitis (CDD) is a chronic inflammatory skin condition characterised by cyclic episodes of dermal inflammation with distinctive flagellate (whip-like) erythematous patterns (Martinez et al., 2020; European Dermatology Guidelines Working Group, 2020). The condition is marked by fluctuating disease activity, with periods of relative stability interspersed with acute flares.

Disease activity and progression are commonly assessed using the Inflammatory Decompensation Marker (IDM), a quantitative measure intended to reflect the degree of underlying inflammatory activity (Smith et al., 2018).

CDD is a relatively uncommon condition, with an estimated prevalence of approximately 40-45 per 100,000 population (European Dermatology Registry, 2023). Epidemiological studies report a female-to-male ratio of approximately 3:1 (Harrison et al., 2017), with peak onset between 25 and 45 years of age. An increased incidence has been reported among individuals with other autoimmune conditions, with approximately 25% of patients having at least one concurrent autoimmune diagnosis (Brown et al., 2019).

### 5.2 IDM Value Interpretation and Clinical Significance

Published guidance describes pragmatic IDM value ranges that support clinical interpretation:

- IDM <50: Generally not indicative of active dermal inflammation
- IDM 50-150: Intermediate range; repeat measurement and clinical correlation recommended
- IDM >100: Associated with a substantially increased risk of CDD flare
- IDM >300 (sustained): Associated with an increased risk of permanent dermal scarring
- IDM >1000: Consider escalation of therapy depending on clinical context

### 5.3 Relevance to This Clinical Evaluation

In this clinical evaluation, IDM values are interpreted in the context of the described state of the art and the intended role of EpiFlare as a monitoring and clinical decision-support tool requiring professional interpretation.

## 6. Clinical Evaluation Methodology

The clinical evaluation was conducted in accordance with the methodology defined in the Clinical Evaluation Plan (CEP) and in alignment with MEDDEV 2.7/1 Rev. 4. All stages of the clinical evaluation process were executed as planned. No deviations from the CEP-defined methodology were identified.

- **Data identification and selection:** Clinical investigations and scientific literature identified in accordance with the CEP were included. All identified relevant data sources met predefined relevance and quality criteria.
- **Data appraisal:** Clinical data were appraised for scientific validity and relevance to the intended purpose.
- **Evidence weighting:** Greater weight was assigned to the prospective clinical performance study due to its closer alignment with intended use conditions.
- **Bias and uncertainty management:** Known limitations related to retrospective design, simulated use conditions, and potential spectrum bias were identified and considered during analysis.

## 7. Summary of Literature Review and Implications for Clinical Evaluation

### 7.1 Overview of the Literature Review

A systematic literature review was conducted in accordance with the CEP and the predefined Literature Search Protocol (Document ID: [LSP-001]).

### 7.2 Key Findings Relevant to the Clinical State of the Art

The reviewed literature consistently indicates that:

- Current standard of care relies primarily on periodic clinical assessment, visual inspection, and semi-quantitative clinical scales.
- There is no widely adopted quantitative, image-based reference standard for routine longitudinal disease monitoring.
- Image-based assessment tools and machine learning-based dermatology applications are an emerging but increasingly accepted approach.

### 7.3 Identification of Similar Devices and Comparable Approaches

The literature review identified several categories of comparable solutions but did not identify devices that combine smartphone-based home image capture, automated longitudinal quantification, and threshold-based flare risk alerting in a manner directly equivalent to EpiFlare.

| Comparator / approach | Description | Key reported performance | Relevance to this evaluation |
|----------------------|-------------|-------------------------|------------------------------|
| DIAU | Reference standard used in specialist settings | High reliability reported in validation studies | Reference standard comparator for EpiFlare investigations |
| Image-based dermatology platforms | Imaging + analysis platforms | Performance varies; typically lower agreement vs reference standards | Contextual expectations for image-based monitoring |
| Mobile AI monitoring tools | Smartphone-based image analysis | Often evaluated using RMSE/agreement and threshold classification metrics | Contextual comparator for metric selection |
| Research-stage automated imaging tools | Algorithmic estimation of inflammation markers | Feasibility shown; heterogeneous validation maturity | Supports plausibility of image-derived monitoring |

### 7.4 Implications for Selection of Performance Metrics

The use of RMSE for continuous IDM accuracy, percentage agreement within +/-2 IDM units, and sensitivity and specificity for high-risk (>99 IDM) detection is consistent with approaches reported in the literature.

### 7.5 Contribution of Literature to Overall Clinical Evaluation

The literature review informed identification of the clinical state of the art, supported the rationale for EpiFlare's intended clinical role, guided the selection of performance metrics, and provided contextual justification for acceptance criteria.

## 8. Clinical State of the Art

The clinical state of the art for disease activity monitoring in CDD was established based on the findings of the systematic literature review, supplemented by relevant clinical guidelines and expert clinical knowledge.

**Disease management and monitoring context:** Current clinical management relies on periodic in-person assessments, visual inspection, semi-quantitative scoring systems, and patient-reported symptoms. These approaches are limited by infrequent assessment, subjectivity, and inter-observer variability.

**Unmet clinical needs:** Need for more frequent monitoring; need for objective and standardised assessment; limited access to quantitative reference assessment; delayed identification of clinically relevant disease worsening; need for longitudinal trend visibility.

**Positioning of EpiFlare:** EpiFlare aligns with the evolving state of the art by providing a quantitative, continuous output (IDM), longitudinal tracking of disease activity trends, and automated flagging of elevated disease activity designed to prompt clinical review.

**State-of-the-art-informed performance expectations:** Clinically acceptable performance for a disease monitoring tool of this type is characterised by sufficient agreement with clinician-based reference assessment, sensitivity prioritised over specificity for flare risk identification, and performance robustness across a range of disease severities.

## 9. Clinical Data Summary and Appraisal

### 9.1 Predefined Acceptance Criteria

| Criterion | Threshold |
|-----------|-----------|
| Measurement accuracy (RMSE) | <=3 IDM units |
| Agreement with reference standard | >=60% of measurements within +/-2 IDM units |
| High-risk flare detection sensitivity (IDM >99) | >=85% |
| High-risk flare detection specificity (IDM >99) | >=90% |

### 9.2 Overview of Included Data

**1) Image database study summary (N = 500):**

| Metric | Result |
|--------|--------|
| RMSE (IDM) | 1.8 IDM units |
| Agreement within +/-2 IDM units | 78.4% |
| Mean bias | +0.4 IDM units |
| 95% limits of agreement | -3.3 to +4.1 IDM units |
| Sensitivity (IDM >99) | 90.0% (54/60) |
| Specificity (IDM >99) | 97.0% (427/440) |

**2) Prospective clinical performance study summary (N = 220):**

| Metric | Result |
|--------|--------|
| RMSE (IDM) | 2.6 IDM units |
| Agreement within +/-2 IDM units | 62.3% |
| Mean bias | +0.8 IDM units |
| 95% limits of agreement | -3.9 to +5.5 IDM units |
| Sensitivity (IDM >99) | 85.7% (30/35) |
| Specificity (IDM >99) | 93.0% (172/185) |

### 9.3 Interpretation of Performance Differences

The difference in performance between the two studies is interpreted as an expected effect of increased variability in real-world acquisition conditions, not as a loss of device functionality. Classification performance for clinically actionable thresholds remained robust across both studies.

### 9.4 Clinical Relevance and Imperfections

- Reduced continuous agreement under real-world conditions: RMSE values remained below the predefined acceptance threshold.
- Spectrum bias: Both studies included a higher proportion of patients with moderate to higher disease activity.
- Short-term evaluation: Long-term performance stability cannot yet be fully characterised.

### 9.5 Evidence Weighting and Conclusion

The clinical data demonstrate that EpiFlare accurately quantifies disease activity, reliably identifies patients at higher risk of flare, and maintains acceptable performance under conditions approximating real-world use.

## 10. Clinical Benefit Analysis

### 10.1 Overview of Intended Clinical Benefit

EpiFlare provides objective monitoring, identification of patients at increased flare risk, and longitudinal assessment of disease trends.

### 10.2 Evidence Supporting Clinical Benefit

Measurement accuracy and agreement with the DIAU reference standard indicate that IDM values reflect underlying disease activity with acceptable precision. Classification performance (sensitivity >=85.7%, specificity >=93.0%) supports reliable identification of patients likely to require clinical attention.

### 10.3 Clinical Relevance of Observed Performance Variability

Variability in continuous IDM values may affect fine-grained precision but does not materially impair the device's ability to detect clinically relevant changes or threshold exceedances.

### 10.4 Population-Level Considerations and Imperfections

Clinical benefit in patients with very mild or borderline disease activity is less well characterised. Planned PMCF activities are intended to further characterise clinical benefit across a broader patient population.

### 10.5 Summary Clinical Benefit Conclusion

EpiFlare provides clinical benefit by enabling objective and repeatable monitoring of disease activity, supporting earlier identification of disease worsening, and complementing clinical assessment with quantitative trend information. The magnitude of benefit is considered moderate, with a high degree of relevance to the intended use population.

## 11. Safety Analysis

### 11.1 Overview

As a non-invasive, software-only medical device, EpiFlare does not present risks of direct physical harm. Safety considerations primarily relate to clinical decision-making influenced by device outputs, potential performance limitations, and the reliability of automated alerts.

### 11.2 Identified Risks and Risk Control Effectiveness

**Risk 1: Incorrect interpretation of results leading to inappropriate clinical decisions**
- Risk controls: Professional-use restriction, IFU statements, demonstrated clinical performance
- Residual risk: Low and acceptable

**Risk 2: Performance limitations under poor image acquisition conditions**
- Risk controls: Image quality guidance, technical quality feedback mechanisms, planned PMCF
- Residual risk: Low

**Risk 3: False positive or false negative alerts**
- Risk controls: Sensitivity >=85.7%, specificity >=93.0%, alerts designed to prompt review not automated action
- Residual risk: Low and acceptable

### 11.3 Emerging and New Risks

No emerging or previously unidentified risks were observed during pre-market clinical investigations. Ongoing monitoring will be conducted through PMS and PMCF activities.

### 11.4 Side Effects

EpiFlare is a non-invasive software medical device and does not produce direct physiological side effects. All identified residual risks are considered acceptable in line with MDR Annex I, GSPR 23.1(e).

### 11.5 Safety Conclusions

The device demonstrates an acceptable safety profile. Identified risks are well-characterised, appropriately controlled, and supported by clinical performance data. Planned post-market activities provide adequate mechanisms to monitor long-term safety and emerging risks.

## 12. Benefit-Risk Assessment

### 12.1 Overview and Approach

The assessment integrates the totality of clinical evidence, including clinical performance data, demonstrated clinical benefits, identified risks, and residual uncertainties.

### 12.2 Summary of Clinical Benefits

The primary clinical benefit lies in EpiFlare's ability to provide quantitative, standardised, and longitudinal monitoring of disease activity, enabling earlier identification of increases in disease activity, identification of patients at higher flare risk, support for timely clinical review, and improved consistency and objectivity.

### 12.3 Summary of Clinical Risks

Identified risks are indirect and relate primarily to clinical decision-making influenced by device outputs. Key risks include incorrect interpretation of results, false alerts, and performance variability under suboptimal conditions. These risks are well-characterised, have low to moderate severity, and are mitigated through professional-use restrictions, demonstrated clinical performance, and ongoing monitoring.

No serious device-related adverse events were identified during pre-market clinical investigations.

### 12.4 Benefit-Risk Conclusion

Based on the available clinical evidence and risk management review, the overall benefit-risk balance of EpiFlare is favourable. The demonstrated clinical benefits outweigh the identified and residual risks when the device is used as intended. The benefit-risk profile is considered acceptable for continued conformity with MDR requirements.

## 13. Evaluation Limitations

- The prospective study was conducted in a simulated real-world setting rather than fully unsupervised home-use conditions.
- The available datasets include a higher proportion of patients with moderate to higher disease activity, introducing potential spectrum bias.
- Long-term clinical performance stability and safety data are not yet available at the time of this evaluation.

## 14. Conclusions

- **Safety:** EpiFlare demonstrates an acceptable safety profile, with no serious device-related adverse events identified and no unacceptable residual risks.
- **Performance:** Clinical evidence demonstrates that the device meets predefined performance criteria and performs consistently across evaluated study settings.
- **Benefit-risk:** The benefit-risk balance is favourable and acceptable for the intended purpose.
- **Regulatory conformity:** The clinical evaluation supports conformity with relevant General Safety and Performance Requirements, including GSPR 1, 3, and 23.

This clinical evaluation is complete, systematic, and based on sufficient and appropriate clinical evidence. Taken together, the available evidence supports the conclusion that EpiFlare is safe, performs as intended, and provides a clinically meaningful benefit when used in accordance with its intended purpose.

## 15. Post-Market Activities

### 15.1 PMCF Activities (High-Level Summary)

Planned PMCF activities include:

- Collection of real-world clinical performance data during routine use
- Monitoring of device performance across a broader and more diverse patient population
- Long-term monitoring of safety and performance trends
- Evaluation of potential spectrum bias and use-condition variability

### 15.2 PMS Activities (High-Level Summary)

Planned PMS activities include:

- Monitoring and evaluation of adverse events and serious incidents
- Complaint handling and trend analysis
- Ongoing performance monitoring using post-market data
- Periodic literature monitoring for relevant safety or performance information

## 16. Traceability to Technical File Documents

| Document | Document ID | Relationship to the CER |
|----------|------------|-------------------------|
| Device Description | SAI-EF-DEVD-001 | Defines device characteristics, intended purpose, patient population |
| Clinical Evaluation Plan (CEP) | SAI-EF-CEP-001 | Defines methodology, outcome measures, acceptability criteria |
| Literature Search Protocol | SAI-EF-LSP-001 | Specifies systematic search strategy |
| Literature Review Report | SAI-EF-LR-001 | Documents full results of systematic literature review |
| Image Database Study Report | SAI-EF-CSR-001 | Reports retrospective analytical performance study (N=500) |
| Prospective Clinical Performance Study Report | SAI-EF-CIR-002 | Reports prospective clinical performance study (N=220) |
| Clinical Investigation Plan | SAI-EF-CIP-001 | Defines study design, endpoints, population, statistical analysis |
| Risk Management Plan | SAI-EF-RMP-001 | Defines risk management process and risk acceptability criteria |
| Risk Management File | SAI-EF-RMF-001 | Documents identified hazards, risk analyses, risk controls |
| Risk Management Report | SAI-EF-RMR-001 | Summarises overall residual risk evaluation |
| Instructions for Use (IFU) | SAI-EF-IFU-001 | Defines conditions of intended use, user guidance, contraindications |
| PMCF Plan | SAI-EF-PMCF-001 | Defines PMCF activities to address residual uncertainties |
| PMS Plan | SAI-EF-PMS-001 | Defines broader PMS system |
| Clinical Evaluators' CVs | SAI-EF-CV-001 | Documents evaluator qualifications and suitability |
| Signed Declarations of Interest | SAI-EF-DOI-001 | Confirms evaluator independence |

## 17. References

1. Martinez R, et al. Cyclic inflammatory patterns and clinical progression in Cox-Dewar Dermatitis. Clinical and Experimental Dermatology. 2020;45(6):712-721.
2. European Dermatology Guidelines Working Group. European clinical guidelines for the diagnosis, monitoring, and management of Cox-Dewar Dermatitis. European Journal of Dermatology. 2020;30(4):389-402.
3. Smith J, et al. Validation of the Inflammatory Decompensation Marker (IDM) for quantitative assessment of inflammatory skin disease activity. Journal of Dermatological Science. 2018;91(2):156-165.
4. European Dermatology Registry. Epidemiology of inflammatory dermatological conditions in Europe: Annual report 2023. European Dermatology Registry; 2023.
5. Harrison K, et al. Sex differences and age of onset in Cox-Dewar Dermatitis: A multicentre observational study. British Journal of Dermatology. 2017;176(5):1243-1250.
6. Brown T, et al. Autoimmune comorbidities in patients with Cox-Dewar Dermatitis: Prevalence and clinical implications. Journal of Autoimmune Diseases. 2019;52:102-110.

> Fictional example only | Not regulatory advice | Beta content - incomplete and subject to change
