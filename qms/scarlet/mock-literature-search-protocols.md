# Literature Search Protocols

> Disclaimer (fictional example - beta release): This document is part of a fictional example technical file provided by Scarlet for educational purposes only. Manufacturers remain responsible for generating evidence appropriate to their medical device.

## Document Control Information

| Field | Value |
|-------|-------|
| Title | Literature Search Protocols |
| Document ID | SAI-EF-LSP-001 |
| Version | 1.0 |
| Publication date | 2026-01-15 |
| Author(s) | Dr. James Anderson; Neha Fleming |
| Approver(s) | Dr. Sarah Mitchell |

| Change ID | Version | Content changes |
|-----------|---------|-----------------|
| N/A | 1.0 | Initial publication |

## 1. Background

### 1.1 Prepared in Accordance With

This literature search protocol is prepared in accordance with:

- MDR Annex XIV Part A
- MEDDEV 2.7/1 Rev. 4, Section 8.2 and A4-A6

### 1.2 Protocol Details

- Date of protocol completion: 15 January 2025
- Protocol authors:
  - Dr. James Anderson, PhD, Clinical Research Specialist
  - Neha Fleming, Clinical Portfolio Manager
- Justification for authors experience: Dr. James Anderson has a PhD and is a first author of 3 systematic reviews; Neha Fleming has undertaken training in systematic review and is supervised by Dr. James Anderson
- Purpose: Define systematic approach for identifying and retrieving relevant scientific literature to support clinical evaluation of EpiFlare
- Summary of previous searches conducted: This is the initial comprehensive literature search for the clinical evaluation of EpiFlare Version 2.0. No previous searches have been conducted for this device version.
- Importance to risk management: This literature search will identify potential clinical hazards and risk mitigations in software-based solutions for monitoring inflammatory skin conditions which may be used to inform compliance with EN ISO 14971:2012. Additionally, defining accuracy of standard and alternative methods for monitoring will provide context for the benefit-risk profile of EpiFlare.
- Background/gaps in knowledge:
  - Need to establish state of the art for CDD monitoring and management
  - Need to identify evidence for similar devices or approaches to inform risk assessment and performance benchmarks

### 1.3 Research Questions (PICO Format)

**Question 1: State of the art for CDD monitoring and management**

- P (Population): Adult patients (>=18 years) with Cox-Dewar Dermatitis (CDD)
- I (Intervention): Current standard of care monitoring and management approaches (clinic-based assessment, DIAU, visual inspection, clinical judgment)
- C (Comparison): N/A (state of the art question)
- O (Outcome): Current monitoring and management practices, clinical guidelines, standard of care protocols

**Question 2: Clinical evidence for similar devices or approaches**

- P (Population): Adult patients (>=18 years) with inflammatory skin conditions, particularly CDD
- I (Intervention): Similar devices or approaches (image analysis software, software-based monitoring devices, telemedicine/remote monitoring solutions, quantitative assessment tools)
- C (Comparison): Standard of care (clinic-based assessment, DIAU, visual inspection)
- O (Outcome): Clinical performance (accuracy, sensitivity, specificity), safety outcomes, clinical utility, user experience, impact on patient outcomes

## 2. Search Strategy

### 2.1 Data Sources (Databases, Internet Sources, Non-Published Data)

| Source | Rationale | Research Questions |
|--------|-----------|-------------------|
| PubMed/MEDLINE | Comprehensive biomedical literature database, essential for systematic review as per MEDDEV 2.7/1 Rev. 4 requirements. Covers peer-reviewed publications in biomedical and life sciences. | Questions 1, 2 |
| EMBASE | Additional coverage of European and international literature, important for EU MDR submissions to ensure comprehensive coverage. Provides broader international scope than PubMed alone. | Questions 1, 2 |
| Cochrane Library | Systematic reviews and evidence synthesis provide high-quality evidence summaries. Contains systematic reviews, meta-analyses, and evidence-based clinical practice guidelines. | Question 1 |
| Clinical trial registries (ClinicalTrials.gov) | Identify ongoing and completed clinical trials that may not yet be published. Captures unpublished or recently completed studies. | Question 2 |
| Regulatory databases (EUDAMED, MHRA) | Safety and performance data on similar devices. Provides regulatory information on similar devices and safety data. | Question 2 |
| Professional society websites (EADV, BAD) | Clinical guidelines and position statements from leading dermatology professional organizations. Provides state of the art information and clinical practice recommendations. | Question 1 |
| Other literature sources (WHO publications) | Provide state of the art information. | Question 1 |
| Conference abstracts | Capture recent research findings and emerging evidence that may not yet be published in peer-reviewed journals. Includes presentations from major dermatology and medical device conferences. | Question 2 |

### 2.2 Search Terms with Full Search Logic

**Question 1: State of the art for CDD monitoring and management**

PubMed/MEDLINE Search Strategy:

```
(("Cox-Dewar Dermatits") OR (CDD) OR (Inflammatory skin disease) OR (dermatitis))
AND
((monitoring) OR (management) OR (standard of care) OR (clinical practice) OR (guidelines) OR (diau) OR (dermal inflammation assessment unit))
Filters: from 2000/1/1 - 2026/1/1
```

EMBASE Search Strategy:

```
('cox-dewar dermatitis'/exp OR 'cox-dewar dermatitis' OR 'cdd' OR 'inflammatory skin disease' OR 'dermatitis'/exp OR 'dermatitis')
AND
('monitoring' OR 'management' OR 'standard of care' OR 'clinical practice' OR 'guidelines' OR 'diau' OR 'dermal inflammation assessment unit'/exp OR 'dermal inflammation assessment unit')
```

Cochrane Library Search Strategy:

| ID | Search |
|----|--------|
| #1 | cox-dewar dermatitis |
| #2 | cdd |
| #3 | inflammatory skin disease |
| #4 | dermatitis |
| #5 | #1 OR #2 OR #3 OR #4 |
| #6 | monitoring |
| #7 | management |
| #8 | standard of care |
| #9 | clinical practice |
| #10 | guidelines |
| #11 | DIAU |
| #12 | Dermal Inflammation Assessment Unit |
| #13 | #6 OR #7 OR #8 OR #9 OR #10 OR #11 OR #12 |
| #14 | #5 AND #13 with Cochrane Library publication date Between Jan 2000 and Jan 2026 |

Professional society websites:

- EADV: https://eadv.org/scientific/spotlights/ - search terms: "Cox-Dewar Dermatitis"
- EADV: https://eadv.org/publications/clinical-guidelines/ - search terms: "Cox-Dewar Dermatitis"
- BAD: https://www.bad.org.uk/guidelines-and-standards/clinical-guidelines - search terms: "Cox-Dewar Dermatitis"

Grey literature sources:

- WHO: https://www.who.int/publications/i - search terms: "Cox-Dewar Dermatitis"

**Question 2: Clinical evidence for similar devices or approaches**

PubMed/MEDLINE Search Strategy:

```
(("Cox-Dewar Dermatits") OR (CDD) OR (Inflammatory skin disease) OR (dermatitis))
AND
((image analysis) OR (software) OR (mobile application) OR (telemedicine) OR (remote monitoring) OR (quantitative assessment) OR (disease monitoring))
AND
((performance) OR (accuracy) OR (sensitivity) OR (specificity) OR (validation))
Filters: from 2000/1/1 - 2026/1/1
```

EMBASE Search Strategy:

```
('cox-dewar dermatits'/exp OR 'cox-dewar dermatits' OR 'cdd' OR 'inflammatory skin disease' OR 'dermatitis'/exp OR 'dermatitis')
AND
('image analysis' OR 'software' OR 'mobile application' OR 'telemedicine' OR 'remote monitoring' OR 'quantitative assessment' OR 'disease monitoring')
AND
('performance' OR 'accuracy' OR 'sensitivity' OR 'specificity' OR 'validation')
```

ClinicalTrials.gov Search Strategy:

```
Cox-Dewar Dermatitis | Completed, Terminated, Suspended, Withdrawn, Unknown status studies | Adult (18 - 64), Older adult (65+)
```

Conference abstracts: Manual review of abstract books 2022-2025 from https://eadv.org/scientific/abstract-books/

## 3. Inclusion Criteria

### 3.1 Question 1: State of the Art for CDD Monitoring and Management

- **Population, participants, conditions:** Adult patients (>=18 years) with Cox-Dewar Dermatitis (CDD); Healthcare professionals involved in CDD management
- **Intervention, exposure:** Current standard of care monitoring approaches (clinic-based assessment, DIAU, visual inspection, clinical judgment); Current disease management approaches; Clinical practice guidelines; Clinical protocols and standards
- **Outcomes of interest:** Current monitoring and management practices; Clinical guidelines and recommendations; Standard of care protocols; Clinical practice patterns
- **Setting:** Clinical settings (outpatient dermatology clinics, primary care); Healthcare facilities where CDD is managed
- **Study designs:** Clinical practice guidelines; Systematic reviews; Position statements from professional societies; Clinical protocols and standards; Expert consensus documents
- **Justification for all criteria:** Criteria designed to capture current state of the art for CDD monitoring and management; Focus on established clinical practices, guidelines, and standards; Inclusion of authoritative sources to establish state of the art

### 3.2 Question 2: Clinical Evidence for Similar Devices or Approaches

- **Population, participants, conditions:** Adult patients (>=18 years) with inflammatory skin conditions, particularly CDD; Healthcare professionals using monitoring devices; Patients and clinicians as device users
- **Intervention, exposure:** Similar devices or approaches (image analysis software, software-based monitoring devices, telemedicine/remote monitoring solutions, quantitative assessment tools); Image analysis for disease monitoring; Software-based monitoring devices; Telemedicine/remote monitoring approaches
- **Comparison, controls:** Standard of care (clinic-based assessment, DIAU, visual inspection); Other monitoring approaches; Reference standards
- **Outcomes of interest:** Clinical performance (accuracy, sensitivity, specificity); Disease activity measurement accuracy; Classification performance; Clinical utility and outcomes; Safety and adverse events; User experience
- **Setting:** Clinical settings (outpatient, community care); Home use settings; Telemedicine settings
- **Study designs:** Clinical studies (prospective, retrospective); Clinical trials; Observational studies; Case series; Systematic reviews
- **Justification for all criteria:** Criteria designed to capture clinical evidence for similar devices or approaches; Focus on intended use population and clinical context; Inclusion of various study designs to capture comprehensive evidence on device performance and safety

## 4. Exclusion Criteria

### 4.1 Question 1: State of the Art for CDD Monitoring and Management

- Publications not in English (unless critical and translatable)
- Publications older than January 2000 (unless highly relevant or foundational guidelines)
- Publications not relevant to CDD monitoring or management
- Publications focused on other inflammatory skin conditions without CDD-specific, or CDD-relevant information
- Animal studies
- In vitro studies without clinical relevance
- Duplicate publications
- Publications that do not address current standard of care, guidelines, or clinical practice

Justification: Criteria ensure focus on current, relevant, and clinically applicable state of the art information for CDD monitoring and management. Older publications may be included if they represent foundational guidelines or standards that remain current.

### 4.2 Question 2: Clinical Evidence for Similar Devices or Approaches

- Publications not in English (unless critical and translatable)
- Publications older than January 2000 (unless highly relevant or foundational)
- Publications not relevant to similar devices, image analysis, software-based monitoring, or quantitative assessment in dermatology
- Publications focused on devices or approaches for non-dermatological conditions without relevance to inflammatory skin conditions
- Animal studies (unless highly relevant)
- In vitro studies without clinical relevance
- Duplicate publications
- Publications without clinical performance data, safety data, or clinical utility information
- Publications describing devices or approaches that are not similar to EpiFlare (e.g., diagnostic devices for unrelated conditions)

Justification: Criteria ensure focus on relevant, current, and clinically applicable evidence for similar devices or approaches. Studies must provide clinical performance, safety, or utility data to be included.

## 5. Search Methodology

**Details of methods (number of reviewers, handling of disagreement):**

- Removal of duplicate publications from search results
- Initial screening by title/abstract by two independent reviewers
- Full-text review for potentially relevant studies by two independent reviewers
- Disagreements resolved through discussion
- Systematic approach to selection process by following documented inclusion and exclusion criteria
- Review of original data source to understand duplication of data across studies

**Quality appraisal methods (methodological quality and relevance to the clinical evaluation):**

- Methodological quality and scientific validity: CASP (Critical Appraisal Skills Programme) checklists for relevant study design
- Relevance to clinical evaluation - same or similar: Intended use, Patient population, Mode of action, Use environment, Intended user, Indications

**Data extraction details and quality control:**

- Standardized data extraction forms
- Extraction of: study design, population, intervention, outcomes, results, limitations
- Independent data extraction by two reviewers
- Quality control checks and verification

**Narrative synthesis methods:**

- Thematic synthesis of findings
- Summary by research question
- Identification of consistent and conflicting findings
- Assessment of evidence quality and relevance

**Meta-analysis methods:**

- Meta-analysis not planned given expected heterogeneity
- Narrative synthesis approach
- If sufficient homogeneous data available, meta-analysis may be considered

> Fictional example only | Not regulatory advice | Beta content - incomplete and subject to change
