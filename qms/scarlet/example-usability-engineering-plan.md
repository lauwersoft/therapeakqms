# Usability Engineering Plan

> **Disclaimer (Fictional Example -- Beta Release):** This document is part of a fictional example technical file provided by Scarlet for educational purposes only. Manufacturers remain responsible for generating evidence appropriate to their medical device.

## Document Control Information

| Field | Value |
|---|---|
| Title | Usability Engineering Plan |
| Document ID | SAI-EF-UEP-001 |
| Version | 1.0 |
| Publication date | 2025-12-15 |
| Author(s) | Dr. Vincent Osei |
| Approver(s) | Dr. Livia Tan |

### Change History

| Change ID | Version | Content changes |
|---|---|---|
| N/A | 1.0 | Initial publication |

## Table of Contents

1. Scope
2. Use specification
3. User interfaces
4. Usability-related risk analysis
5. User interface specification
6. User Interface Evaluation Plan
7. Change Management
8. Post-market Usability Monitoring
9. Approvals and Review
- Annex A: Usability Engineering Summative Evaluation Activities

## 1. Scope

This Usability Engineering Plan applies to the software medical device EpiFlare, developed by SkintelligentAI, currently classified as a Class IIa device under EU MDR 2017/745.

This Usability Engineering Plan is established in accordance with IEC 62366-1:2015+A1:2020 and defines the usability engineering activities to be performed for EpiFlare throughout its lifecycle. The plan ensures that:

- Use-related risks are systematically identified, analysed, and controlled, in accordance with ISO 14971:2019 risk management processes as defined in the Risk Management Plan.
- The EpiFlare user interface is designed and evaluated to minimize use errors that could lead to patient harm.
- Usability engineering activities are defined and executed to validate that the EpiFlare user interface is safe and effective for the intended users in the intended use environment.

### 1.1 Key Deliverables

Two usability engineering documents shall be delivered for EpiFlare:

1. **Usability Engineering Plan (this document)** -- Defines the hazard-related use scenarios, use errors and abnormal uses, and usability engineering formative and summative evaluation activities required to ensure that the EpiFlare user interface is safe and effective for the intended users in the intended use environment.
2. **Usability Engineering Report** -- Summarizes the usability engineering formative and summative evaluation activities conducted and demonstrates that the user interface can be used safely by intended users in the intended use environment, providing objective evidence for residual risk evaluation and risk control effectiveness verification. This report shall include, or reference, the usability engineering formative and summative evaluation activity reports.

All usability engineering documents shall be maintained and controlled in accordance with the organization's document control procedures. The Usability Engineering File shall be updated throughout the device lifecycle as new information becomes available or as the device is modified. All documents are maintained in the designated document management system and are accessible to all personnel with assigned usability engineering responsibilities.

### 1.2 References

This plan is prepared in accordance with the following standards and regulations:

- EN ISO 14971:2019 -- Application of risk management to medical devices
- EN 62366-1:2015+A1:2020 -- Application of usability engineering to medical devices
- IEC/TR 62366-2:2016 -- Guidance on the application of usability engineering
- Regulation (EU) 2017/745 on medical devices -- Annex I (GSPR)

This plan references the following documents:

- Risk Management Plan
- Use Specification
- Software Requirements Specification
- Software Architecture and Design Specification

## 2. Use Specification

See the Use Specification document for definition of the EpiFlare's:

- intended medical indication
- intended patient population
- intended user profiles
- intended use environment, and
- operating principle

## 3. User Interfaces

The following system components are within the usability engineering scope:

- EpiFlare Patient Mobile App
- EpiFlare Clinician Portal
- Notifications & communication channels
- EpiFlare Instructions for Use (IfU)

## 4. Usability-Related Risk Analysis

### 4.1 User Interface Characteristics

User interface characteristics are to be identified and documented in the Risk Management File.

### 4.2 Hazard-Related Use Scenarios

| Name | Description | Tasks | Response |
|---|---|---|---|
| Patient Image Capture - Successful Pathway | Patient uses mobile app to capture skin condition image in home environment. User: Patient with Condition Z. Environment: Home setting with smartphone. | Open EpiFlare mobile app; Navigate to image capture screen; Read on-screen instructions for proper image capture; Position device and capture image of affected area; Review captured image; Submit image for analysis; Wait for algorithm processing; Review algorithm results showing progression; Confirm delivery notification to healthcare professional | App displays image capture interface with instructions; Camera opens with positioning guidance; Image preview displayed for review; Image submitted successfully; Processing indicator displayed; Results screen shows progression detected; Confirmation message displayed indicating healthcare professional has been notified; Delivery status confirmation displayed |
| Patient Image Capture - Low Quality Image | Patient captures image but it fails quality validation. User: Patient with Condition Z. Environment: Home setting with smartphone. | Open EpiFlare mobile app; Navigate to image capture screen; Read instructions; Capture image of affected area; Submit image for analysis; Receive quality validation error | App displays image capture interface with instructions; Image captured and preview shown; Image submitted for validation; Quality check performed; Error message displayed stipulating that image quality is insufficient with guidance on improving lighting, focus, and visibility; User prompted to retry capture with guidance on improving image quality |
| Patient Image Capture - Algorithm Failure | Patient captures and submits image but algorithm processing fails. User: Patient with Condition Z. Environment: Home setting with smartphone. | Open EpiFlare mobile app; Navigate to image capture screen; Capture image following instructions; Submit image for analysis; Wait for algorithm processing; Receive algorithm failure notification | App displays image capture interface; Image captured successfully; Image submitted for analysis; Processing indicator displayed; Algorithm processing error occurs; Error message displayed stipulating that analysis is unavailable, healthcare professional has not been notified, and directing symptomatic users to contact their healthcare professional; User provided with contact information and escalation options |
| Patient Image Capture - No Progression Result | Patient captures image and receives result showing no progression detected. User: Patient with Condition Z. Environment: Home setting with smartphone. | Open EpiFlare mobile app; Navigate to image capture screen; Capture image following instructions; Submit image for analysis; Wait for algorithm processing; Review results showing no progression; Read disclaimer and safety information | App displays image capture interface; Image captured and submitted; Processing indicator displayed; Results screen shows no progression detected; Disclaimer displayed stipulating that healthcare professional has not been notified, result is subject to error and not to be used as standalone diagnosis, and directing symptomatic users to contact their healthcare professional; Contact information and escalation options provided |
| Patient Image Capture - Progression with Notification Failure | Patient captures image showing progression, but notification to healthcare professional fails. User: Patient with Condition Z. Environment: Home setting with smartphone. | Open EpiFlare mobile app; Navigate to image capture screen; Capture image following instructions; Submit image for analysis; Wait for algorithm processing; Review results showing progression detected; Receive notification failure error; Follow fallback escalation workflow | App displays image capture interface; Image captured and submitted; Processing indicator displayed; Results screen shows progression detected; Notification attempt fails; Error message displayed stipulating that progression was detected but healthcare professional was not notified due to technical issue, directing user to use fallback workflow; Fallback manual workflow interface displayed with contact options and escalation instructions |
| Clinician Alert Review and Diagnosis | Healthcare professional receives notification of patient alert and reviews case through web portal. User: Healthcare professional (GP, dermatologist). Environment: Clinic/office setting with workstation. | Receive notification of patient alert (email, SMS, or portal notification); Access EpiFlare Clinician Portal via web browser; Log in to portal; Navigate to alerts/notifications section; Select patient alert from list; Review patient information and case details; Review image analysis results and algorithm output; Review any additional patient-reported data; Select diagnosis from available options; Select follow-up treatment plan; Review and acknowledge disclaimer; Confirm diagnosis and treatment selection; Submit decision | Notification delivered via configured channel; Portal login screen displayed; Successful authentication; Dashboard/alerts list displayed; Alert details page loads with patient information; Image analysis results displayed with algorithm output; Patient data and history displayed; Diagnosis selection interface displayed with options; Treatment selection interface displayed; Disclaimer modal appears requiring confirmation that analysis results have been reviewed and understanding that tool is not to be used as standalone diagnosis tool; Confirmation checkbox required before submission; Submission successful - confirmation message displayed; Patient notification sent (if configured) |

### 4.3 Use Errors

| Name | Use scenario | Description | Hazardous Situation ID(s) |
|---|---|---|---|
| Patient fails to capture adequate quality image | Patient Image Capture - Low Quality Image | Patient captures image with insufficient lighting, focus, or visibility of affected area, resulting in poor quality image that produces unreliable IDM calculation | SAI-EF-HS-001, SAI-EF-HS-002, SAI-EF-HS-003, SAI-EF-HS-004, SAI-EF-HS-005, SAI-EF-HS-006 |
| Patient captures image of wrong body part | Patient Image Capture - Successful Pathway | Patient captures image of unaffected area or incorrect anatomical location, leading to analysis of wrong region and inaccurate IDM calculation | SAI-EF-HS-001, SAI-EF-HS-002, SAI-EF-HS-003, SAI-EF-HS-004, SAI-EF-HS-005, SAI-EF-HS-006 |
| Patient misinterprets "no progression" result and fails to contact healthcare professional | Patient Image Capture - No Progression Result | Patient receives "no progression detected" result and incorrectly interprets this as definitive diagnosis, failing to contact healthcare professional despite experiencing symptoms, leading to untreated condition progression | SAI-EF-HS-010, SAI-EF-HS-011, SAI-EF-HS-012 |
| Patient ignores error messages and fails to escalate | Patient Image Capture - Algorithm Failure, Patient Image Capture - Progression with Notification Failure | Patient receives error message indicating algorithm failure or notification failure but does not follow escalation instructions to contact healthcare professional, resulting in delayed or missed treatment | SAI-EF-HS-025, SAI-EF-HS-026, SAI-EF-HS-027, SAI-EF-HS-028, SAI-EF-HS-029, SAI-EF-HS-030 |
| Clinician misinterprets displayed data | Clinician Alert Review and Diagnosis | Clinician reviews patient alert but misinterprets analysis results (whether displayed normally or due to software failure), leading to incorrect clinical assessment | SAI-EF-HS-007, SAI-EF-HS-008, SAI-EF-HS-009, SAI-EF-HS-019, SAI-EF-HS-020, SAI-EF-HS-021 |
| Clinician fails to acknowledge disclaimer and uses tool as standalone diagnosis without proper clinical judgement | Clinician Alert Review and Diagnosis | Clinician bypasses or fails to properly acknowledge disclaimer requiring review of analysis results and understanding that tool is not standalone diagnosis, then relies solely on algorithm output without reviewing full patient context, history, or performing clinical assessment, treating tool as definitive diagnostic device rather than decision support tool | SAI-EF-HS-007, SAI-EF-HS-008, SAI-EF-HS-009, SAI-EF-HS-019, SAI-EF-HS-020, SAI-EF-HS-021 |
| Clinician selects incorrect diagnosis or treatment based on misinterpreted results | Clinician Alert Review and Diagnosis | Clinician reviews analysis results but selects wrong diagnosis or inappropriate treatment plan due to misunderstanding of algorithm output or failure to correlate with clinical presentation | SAI-EF-HS-007, SAI-EF-HS-008, SAI-EF-HS-009, SAI-EF-HS-019, SAI-EF-HS-020, SAI-EF-HS-021 |

### 4.4 Abnormal Uses

| Name | Use scenario | Description | Rationale |
|---|---|---|---|
| Patient uses app to monitor someone else's condition | Patient Image Capture - Successful Pathway | Patient uses their account to capture and submit images of another person's skin condition, not their own | This is abnormal use because the app is intended for patients to monitor their own condition. Using the app for another person violates the intended use specification and introduces risks of incorrect patient identification and data association. This is intentional misuse outside the device's intended purpose. |
| Patient uses app for conditions other than CDD | Patient Image Capture - Successful Pathway | Patient uses EpiFlare to monitor skin conditions other than Cox-Dewar Dermatitis (CDD), for which the device is not validated | This is abnormal use because the device is specifically validated and intended for CDD monitoring only. Using the device for other conditions is outside the intended medical indication and represents intentional use for an unvalidated purpose. |
| Clinician uses portal without proper medical training or credentials | Clinician Alert Review and Diagnosis | Non-qualified individual (e.g., administrative staff, untrained personnel) accesses clinician portal and attempts to review patient alerts or make diagnostic decisions | This is abnormal use because the clinician portal is intended for use by qualified healthcare professionals (GPs, dermatologists) with appropriate medical training and credentials. Access by unqualified personnel is intentional misuse that violates the intended user profile specification. |

## 5. User Interface Specification

User Interface Specifications are maintained for the following user interface components:

- EpiFlare Patient App
- EpiFlare Clinician Portal
- EpiFlare Instructions for Use (IfU)

See the Software Requirements Specification document for the user interface requirements specifications for the EpiFlare Patient Mobile App and Clinician Portal. See the Software Architecture and Design Specification document for the user interface architecture and design specifications for the EpiFlare Patient Mobile App and Clinician Portal. See the Instructions for Use document for the instructions for using the EpiFlare Patient Mobile App and Clinician Portal.

## 6. User Interface Evaluation Plan

The EpiFlare Usability Engineering Evaluation Plan covers both formative and summative evaluation.

### 6.1 Formative Evaluation

**Objectives**

Explore and refine the user interface; identify usability strengths, weaknesses and unanticipated use errors for both patient and clinician sides before design freeze.

**Methods (used iteratively)**

- Expert reviews / heuristic evaluations.
- Low- and high-fidelity prototype testing with think-aloud protocols.
- Cognitive walkthroughs of critical workflows (daily capture, alert review).
- Small-sample usability tests in simulated environments (home-like setting; clinic office).

**Participants**

- Representative patients across age ranges and digital literacy levels.
- Representative clinicians (GPs, dermatologists, practice nurses).

**Outputs**

Observed use errors and use difficulties, root-cause analysis, design recommendations, and decisions captured in usability engineering formative evaluation reports.

Updates to UI specification, hazard-related use scenarios, use errors and hazardous situations will be made to the Software Requirements Specification, Software Architecture and Design Specification, and Risk Management File as needed.

### 6.2 Summative Evaluation

**Objective:** Demonstrate that, for the selected hazard-related use scenarios, the EpiFlare user interface can be used safely by intended users in representative environments, providing objective evidence for residual risk evaluation and for risk control effectiveness verification.

**Selection of Use Scenarios for Summative Evaluation**

The selection criteria of use scenarios for summative evaluation are as follows:

- Use scenarios that are linked to use errors and hazardous situations which are deemed unacceptable without risk mitigations.
- Use scenarios that include information for safety and/or training to users as risk mitigation measures.

Applying these selection criteria, the following hazard-related use scenarios are selected for summative evaluation:

1. Patient image capture - low quality image
2. Patient image capture - algorithm failure
3. Patient image capture - no progression result
4. Patient image capture - progression with notification failure
5. Clinician Alert, review and diagnosis

**Test design**

- **Type:** Scenario-based usability test using production-equivalent software and realistic mock clinical data.
- **Environment:**
  - Home-like room with variable lighting, distractions and typical smartphone usage;
  - Clinic / office environment with typical workstation setup.

**Participants**

- **Patients:** minimum of 15 representing the main patient user groups (including some with lower digital literacy).
- **Clinicians:** minimum of 5 representing GPs and dermatologists with varied experience with digital tools.

**Activities**

Detailed usability engineering summative evaluation activities are defined in Annex A.

## 7. Change Management

Any change to EpiFlare that may impact usability (e.g. new measurement visualisations, new notification types, altered thresholds, major visual redesign, new platforms) will trigger:

1. Impact assessment on the already generated user interface characteristics and use specification, and an evaluation of whether new use scenarios shall be defined.
2. Update of Use Specification / UI Specification / hazard-related use scenarios where relevant.
3. Planning of additional formative or summative evaluations appropriate to the risk of the change.

## 8. Post-Market Usability Monitoring

Post-market usability monitoring procedures will:

- Capture and categorise usability-related complaints, incidents, near-misses, and feedback (e.g. difficulty capturing images, misunderstanding of feedback, missed alerts);
- Trend and evaluate these for signals of new or changed hazard-related use scenarios.

Significant signals will be handled through CAPA and design changes, with updates to the usability engineering file and new formative/summative activities as needed, satisfying MDR expectations to continuously assess usability in the post-market phase.

## 9. Approvals and Review

This plan will be:

- authored by the Clinical Evaluation & Usability Engineer function;
- reviewed by the risk management specialist and post-market surveillance analyst;
- approved by senior management and the regulatory affairs specialist.

It will be reviewed and updated at least:

- at design freeze;
- prior to first MDR conformity assessment;
- after major design or indication changes;
- during regular management review cycles.

## Annex A: Usability Engineering Summative Evaluation Activities

### SAI-EF-UE-SUM-001: Patient Image Capture

**Objective**

Demonstrate that intended patients can successfully complete the image capture workflow and capture adequate quality images of their affected skin area using the EpiFlare mobile app, following on-screen instructions and guidance, and that the user interface effectively prevents or mitigates use errors related to poor image quality capture.

**Method**

Scenario-based usability test using production-equivalent EpiFlare mobile app software. Participants will be asked to complete image capture tasks in a simulated home environment using a provided smartphone device. The test will include scenarios where image quality validation errors occur.

**Scope**

- Use scenarios: Patient Image Capture - Low Quality Image
- Use errors: Patient fails to capture adequate quality image; Patient captures image of wrong body part
- Risk mitigations evaluated: Guidance on taking high-quality images; Errors when image quality is inadequate

**Environment & equipment**

- Home-like room with variable lighting conditions (simulating typical home environments with natural and artificial lighting)
- Provided test device matching participant's typical device type (iOS or Android)
- Test device with EpiFlare mobile app installed (production-equivalent version)
- Mock skin condition simulation aids (e.g., temporary skin markers, photographs, or anatomical models) to represent affected areas
- Video recording equipment to capture user interactions
- Screen recording software to capture app interactions

**Information/Training materials**

- Instructions for Use (IfU) document: Provided to participants 1 week before the test session. Participants will be asked to review the document but not required to memorize it.
- No formal training provided: Participants will rely on the app's on-screen instructions and guidance, simulating real-world use conditions.

**Participants**

Minimum of 15 patients representing:
- Age range: 18-80 years (with representation across age groups)
- Digital literacy levels: Including some participants with lower digital literacy
- Varied experience with mobile apps and smartphone cameras
- Representative of intended patient population with CDD skin conditions

**Test steps**

1. **Pre-test setup:** Participant provides informed consent, completes demographic questionnaire, receives pre-test briefing
2. **Familiarization:** Participant is given 5 minutes to explore the app interface (without capturing images)
3. **Task 1 - Successful image capture:** Participant is asked to capture an image of a simulated affected area following the app's instructions. Observer notes: whether instructions are read, how positioning guidance is followed, image capture process
4. **Task 2 - Capture image under suboptimal lighting conditions:** Participant attempts to capture image under suboptimal lighting conditions. Observer notes: response to quality validation error, understanding of error message, ability to follow guidance to improve image quality
5. **Task 3 - Capture image of a non-affected area:** Participant is asked to capture image of a non-affected area. Observer notes: response to quality validation error, understanding of error message, ability to follow guidance to improve image quality
6. **Post-test interview:** Semi-structured interview covering experience, understanding of instructions, perceived ease of use, and any difficulties encountered

**Data collection techniques**

- Direct observation: Trained observer notes user behaviors, use errors, hesitations, and task completion
- Think-aloud protocol: Participants verbalize their thoughts and actions during tasks
- Video recording: Full session recorded (with consent) for later analysis of user interactions and behaviors
- Screen recording: App interactions captured for detailed analysis of navigation patterns and interface usage
- Task completion metrics: Success/failure rates, time to complete tasks, number of attempts
- Use error documentation: Systematic recording of observed use errors aligned with defined use error categories
- Post-test questionnaire: Structured questionnaire covering usability, clarity of instructions, and confidence in use
- Semi-structured interview: Open-ended questions to capture user experience, understanding, and unanticipated issues

**Data analysis methods**

- Quantitative analysis: Task completion rates (successful image capture, successful retry after quality error); Time to complete tasks; Number of attempts before successful capture; Frequency of each identified use error type; Error message comprehension rates
- Qualitative analysis: Thematic analysis of think-aloud protocols and interview transcripts; Identification of patterns in use errors and difficulties; Analysis of user understanding of instructions and error messages
- Use error mapping: Each observed use error mapped to defined use error categories and associated hazardous situations
- Risk mitigation effectiveness: Evaluation of whether on-screen instructions, quality validation, and error messages effectively prevent or mitigate identified use errors
- Acceptance criteria evaluation: Comparison of results against pre-defined acceptance criteria for task completion and use error rates

### SAI-EF-UE-SUM-002: Patient's Response to Algorithm Output

**Objective**

Demonstrate that intended patients can correctly interpret algorithm output results (including progression detected, no progression detected, and algorithm failure scenarios), understand associated disclaimers and safety information, and appropriately respond by either acknowledging successful notification or following escalation instructions when errors occur.

**Method**

Scenario-based usability test using production-equivalent EpiFlare mobile app software with simulated algorithm outputs. Participants will be presented with different algorithm result scenarios and evaluated on their understanding, interpretation, and response actions. The test will include scenarios with progression detected, no progression detected, algorithm failures, and notification failures.

**Scope**

- Use scenarios: Patient Image Capture - Algorithm Failure; Patient Image Capture - No Progression Result; Patient Image Capture - Progression with Notification Failure; Patient Image Capture - Successful Pathway (for progression detected with successful notification)
- Use errors: Patient misinterprets "no progression" result and fails to contact healthcare professional; Patient ignores error messages and fails to escalate
- Risk mitigations evaluated: Disclaimer to users regarding the reliability of the algorithm output; Guidance for users on software failure; Guidance for users on alert failure; Alert delivery confirmation

**Environment & equipment**

- Home-like room with typical smartphone usage environment
- Provided test device matching participant's typical device type (iOS or Android)
- Test device with EpiFlare mobile app installed (production-equivalent version) but with algorithm outputs pre-programmed to simulate different result scenarios
- Simulated algorithm outputs (pre-programmed test scenarios): Progression detected with successful notification; No progression detected; Algorithm failure; Progression detected with notification failure
- Video recording equipment (with participant consent)
- Screen recording software to capture result screen interactions
- Printed materials for post-test reference (if needed)

**Information/Training materials**

- Instructions for Use (IfU) document: Provided to participants 1 week before the test session. Participants will be asked to review the document but not required to memorize it.
- No formal training on result interpretation: Participants will rely on on-screen information, disclaimers, and error messages as they would in real-world use

**Participants**

Minimum of 15 patients representing:
- Age range: 18-80 years (with representation across age groups)
- Digital literacy levels: Including some participants with lower digital literacy
- Varied experience with mobile apps and smartphone cameras
- Representative of intended patient population with CDD skin conditions

**Test steps**

1. **Pre-test setup:** Participant provides informed consent (if new session), receives briefing on result interpretation scenarios
2. **Task 1 - Algorithm executed. No progression detected:** Participant captures an image of a simulated affected area (with no symptom progression) following the app's instructions. Participant reviews result screen showing no progression detected with disclaimer. Participant acknowledges disclaimer and continues with app use. Observer notes: reading of disclaimer, understanding of limitations, recognition of when to contact healthcare professional, interpretation of safety information, ability to acknowledge disclaimer and continue with app use
3. **Task 2 - Algorithm executed. Progression detected with successful notification:** Participant captures an image of a simulated affected area (with symptom progression) following the app's instructions. Participant reviews result screen showing progression detected with successful notification. Participant acknowledges notification and continues with app use. Observer notes: understanding of result, recognition of notification confirmation, interpretation of message, ability to acknowledge notification and continue with app use
4. **Task 3 - Algorithm executed. Software failure:** Participant captures an image of a simulated affected area (with symptom progression) following the app's instructions. Participant is presented with algorithm failure error message. Participant acknowledges error message and continues with app use. Observer notes: reading of error message, understanding of escalation instructions, recognition of need to contact healthcare professional, ability to locate contact information
5. **Task 4 - Algorithm executed. Progression with notification failure:** Participant captures an image of a simulated affected area (with symptom progression) following the app's instructions. Participant reviews result showing progression detected but notification failure. Participant acknowledges error and continues with app use. Observer notes: recognition of error, understanding of escalation instructions, recognition of need to contact healthcare professional, ability to locate contact information
6. **Task 5 - Algorithm executed. False negative result:** Participant captures an image of a simulated affected area (with symptom progression) following the app's instructions. Participant reviews result screen showing no progression detected with disclaimer. Participant acknowledges result and continues with app use. Observer notes: recognition of result, understanding of need to contact healthcare professional despite no progression detected, ability to locate contact information
7. **Post-test interview:** Semi-structured interview covering experience, understanding of instructions, perceived ease of use, and any difficulties encountered

**Data collection techniques**

- Direct observation: Trained observer notes user behaviors, reading patterns, comprehension indicators, and response actions
- Think-aloud protocol: Participants verbalize their understanding and interpretation of results and messages
- Video recording: Full session recorded (with consent) for analysis of reading behavior and comprehension
- Screen recording: Result screen interactions captured to analyze time spent reading, scrolling patterns, and information access
- Comprehension assessment: Structured questions after each scenario to assess understanding: What does this result mean? Has your healthcare professional been notified? What should you do next? When should you contact your healthcare professional?
- Use error documentation: Systematic recording of misinterpretations, failures to escalate, and other use errors
- Post-test questionnaire: Structured questionnaire covering: Clarity of result displays; Understanding of disclaimers; Confidence in interpreting results; Clarity of error messages and escalation instructions
- Semi-structured interview: Open-ended questions to capture understanding, concerns, and unanticipated interpretations

**Data analysis methods**

- Quantitative analysis: Comprehension rates for each result type and scenario; Correct interpretation rates (understanding of result meaning, notification status, required actions); Appropriate escalation action rates (correctly identifying when to contact healthcare professional); Time spent reading disclaimers and error messages; Frequency of each identified use error type; Error message comprehension and action rates
- Qualitative analysis: Thematic analysis of think-aloud protocols and interview responses; Identification of common misinterpretations and misunderstandings; Analysis of factors influencing correct vs. incorrect interpretation; Patterns in how disclaimers and error messages are understood
- Use error mapping: Each observed use error mapped to defined use error categories (misinterprets "no progression" result, ignores error messages) and associated hazardous situations
- Risk mitigation effectiveness: Evaluation of whether disclaimers, error messages, and escalation instructions effectively prevent or mitigate identified use errors
- Acceptance criteria evaluation: Comparison of results against pre-defined acceptance criteria for: Minimum comprehension rates for result interpretation; Minimum rates of appropriate escalation actions; Maximum acceptable use error rates

### SAI-EF-UE-SUM-003: Clinician Alert, Review and Diagnosis

**Objective**

Demonstrate that intended healthcare professionals can safely and effectively receive patient alerts, access the EpiFlare Clinician Portal, review patient information and algorithm analysis results, and make appropriate diagnostic and treatment decisions while properly acknowledging disclaimers and understanding the tool's limitations as a decision support device rather than standalone diagnostic tool.

**Method**

Scenario-based usability test using production-equivalent EpiFlare Clinician Portal software with realistic mock patient cases and algorithm outputs. Participants will complete the full workflow from receiving an alert through reviewing patient information, interpreting algorithm results, and making diagnostic and treatment decisions. The test will include scenarios with normal algorithm outputs and false positive results.

**Scope**

- Use scenarios: Clinician alert, review and diagnosis
- Use errors: Clinician misinterprets displayed data; Clinician fails to acknowledge disclaimer and uses tool as standalone diagnosis; Clinician selects incorrect diagnosis or treatment based on misinterpreted results
- Risk mitigations evaluated: Guidance on correct use for healthcare professionals; Enforce confirmation of healthcare professional interpretation

**Environment & equipment**

- Clinic/office environment with typical workstation setup: Desktop or laptop computer with standard monitor; Typical office furniture and setup; Simulated clinic environment with appropriate lighting
- Test workstation with EpiFlare Clinician Portal installed (production-equivalent version) with mock patient cases and algorithm outputs pre-programmed to simulate different result scenarios
- Web browser (Chrome, Firefox, or Edge) as specified for the portal
- Mobile test device for receiving SMS and email notifications
- Mock patient cases with realistic clinical data: Patient demographics and history; Skin images and algorithm analysis results; IDM values and trend data; Patient-reported symptoms and data
- Simulated notification system (email or portal notification)
- Video recording equipment (with participant consent) for session recording
- Screen recording software to capture portal interactions
- Printed reference materials (if needed for post-test)

**Information/Training materials**

- Instructions for Use (IfU) document: Provided to participants 1 week before the test session. Participants will be asked to review the document but not required to memorize it.
- No formal training on algorithm interpretation: Participants will rely on the portal's interface, disclaimers, and their clinical judgement as they would in real-world use

**Participants**

Minimum of 5 healthcare professionals representing:
- General Practitioners (GPs)
- Dermatologists
- Varied experience levels with digital health tools and clinical decision support systems
- Varied experience with EpiFlare or similar monitoring tools
- All participants should be actively practicing clinicians familiar with CDD or similar inflammatory skin conditions

**Test steps**

1. **Pre-test setup:** Participant provides informed consent, completes demographic and experience questionnaire, receives pre-test briefing and clinical context
2. **Familiarization:** Participant is given 10 minutes to explore the portal interface and navigation (without reviewing specific patient cases)
3. **Task 1 - Alert receipt, analysis review and diagnosis confirmation - Valid condition progression:** Participant receives simulated patient alert notifications (email and SMS). Participant accesses and logs into the Clinician Portal. Participant acknowledges disclaimer. Participant reviews patient information and algorithm output. Participant makes diagnosis and treatment decision. Observer notes: interpretation of alert, ease of access to portal, adherence to the disclaimer, review of the analysis and algorithm output, ability to make a diagnosis and treatment decision
4. **Task 2 - Alert receipt, analysis review and diagnosis confirmation - False positive result:** Participant receives simulated patient alert notifications (email and SMS). Participant accesses and logs into the Clinician Portal. Participant acknowledges disclaimer. Participant reviews patient information and algorithm output. Participant recognises the false positive result and overrules the algorithm output when making a diagnosis and treatment decision. Observer notes: interpretation of alert, ease of access to portal, adherence to the disclaimer, review of the analysis and algorithm output, recognition of false positive result, ability to overrule the algorithm output
5. **Post-test interview:** Semi-structured interview covering: Experience with portal interface and navigation; Understanding of algorithm outputs and limitations; Interpretation of disclaimer and tool limitations; Confidence in using tool as decision support; Factors influencing diagnostic and treatment decisions; Any concerns or difficulties encountered

**Data collection techniques**

- Direct observation: Trained observer (preferably with clinical background) notes user behaviors, navigation patterns, information review behaviors, and decision-making processes
- Think-aloud protocol: Participants verbalize their thoughts, interpretations, and decision-making rationale during tasks
- Video recording: Full session recorded (with consent) for later analysis of user interactions and behaviors
- Screen recording: Portal interactions captured for detailed analysis of navigation patterns, information access, and interface usage
- Task completion metrics: Success/failure rates, time to complete tasks, navigation efficiency
- Use error documentation: Systematic recording of observed use errors: Misinterpretation of displayed data; Failure to acknowledge disclaimer; Use as standalone diagnosis without clinical judgement; Incorrect diagnosis or treatment selection
- Decision rationale capture: Participants explain their diagnostic and treatment decisions to assess use of clinical judgement vs. over-reliance on algorithm
- Post-test questionnaire: Structured questionnaire covering: Usability of portal interface; Clarity of algorithm outputs; Understanding of disclaimer and tool limitations; Confidence in tool as decision support; Appropriateness of interface for clinical decision-making
- Semi-structured interview: Open-ended questions to capture clinical decision-making process, understanding of tool limitations, and unanticipated issues

**Data analysis methods**

- Quantitative analysis: Task completion rates (successful alert review, diagnosis selection, treatment selection, submission); Time to complete tasks and workflow efficiency; Disclaimer acknowledgment rates; Frequency of each identified use error type; Appropriate use of clinical judgement indicators; Diagnosis and treatment selection appropriateness (evaluated by clinical expert)
- Qualitative analysis: Thematic analysis of think-aloud protocols and interview transcripts; Identification of patterns in use errors and decision-making processes; Analysis of factors influencing appropriate vs. inappropriate tool use; Understanding of disclaimer and tool limitations; Clinical decision-making patterns and use of algorithm output
- Use error mapping: Each observed use error mapped to defined use error categories and associated hazardous situations: Misinterprets displayed data -> SAI-EF-HS-007 to SAI-EF-HS-012, SAI-EF-HS-019 to SAI-EF-HS-024; Fails to acknowledge disclaimer and uses as standalone diagnosis -> SAI-EF-HS-007 to SAI-EF-HS-012; Selects incorrect diagnosis or treatment -> SAI-EF-HS-007 to SAI-EF-HS-012
- Risk mitigation effectiveness: Evaluation of whether disclaimer, interface design, and information presentation effectively prevent or mitigate identified use errors
- Clinical decision-making analysis: Assessment of whether participants appropriately use tool as decision support rather than standalone diagnosis, incorporating clinical judgement and patient context
- Acceptance criteria evaluation: Comparison of results against pre-defined acceptance criteria for: Minimum task completion rates; Maximum acceptable use error rates; Minimum rates of appropriate clinical judgement application; Disclaimer acknowledgment and understanding rates

> **Fictional example only | Not regulatory advice | Beta content -- incomplete and subject to change**
