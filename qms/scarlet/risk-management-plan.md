# Risk Management Plan

> Define the risk management activities to be conducted throughout your device's development lifecycle.

The expectation is that risk management planning documentation covers all the medical device's life cycle phases. This information can be presented as one or more individual risk management plans.

> **Note:** Reach out to arrange a structured dialogue where we'll talk through a systematic approach to risk management.

## Minimum Requirements

Risk management plans should be limited to the scope of the medical device and should cover the following elements:

- **Risk analysis** - List the unique risk analysis activities with details of scope, methods (e.g. preliminary hazard analysis, event tree analysis) and strategy used to determine the probability of the occurrence of harm and its severity.
- **Risk evaluation** - Define the strategy for risk evaluation. This should align with the expectations of ISO 14971 and specify an approach that evaluates each risk, before determining residual risk once risk controls have been introduced.
- **Risk control** - Define the strategy for risk control. Controls should be implemented in priority order, before verifying their effectiveness.
- **Risk management process review** - List the scope of information that the chosen suitable authority considers when conducting the risk management process review.
- **Production and post-production activities** - Outline the activities planned to collect and review information related to the medical device during production and post-production phases, focusing on safety relevance.

See the [deep dive section](#deep-dive) for more information on each of these elements, including examples of what to include.

## Common Pitfalls

As with any planning exercise, the _Risk management plan_ falls short when it is produced too late in development, the scope is inadequate or responsibilities are unclear.

In the recommendations below, we'll use the example of a device that uses a convolutional neural network to analyse images for the detection of diabetic retinopathy.

### Inadequate Scope

- Incomplete: _Risk management plan_ only addresses the core AI algorithm.
- Plan should cover:
  - Software design and software failures: model drift, overfitting, training data bias, processing errors, integration errors.
  - Clinical risks: false positives/negatives, impact on clinical workflow.
  - Cybersecurity: data breach, insufficient access controls, infrastructure failure.
  - Usability-related risks: unclear output presentation, automation bias, inadequate user training.

### Missing Key Lifecycle Phases

- Plan should cover:
  - Installation/deployment: Cloud deployment failures, version control errors.
  - Training phase: Inadequate clinician training on limitations.
  - Maintenance: Algorithm updates that inadvertently reduce performance.
  - Decommissioning: Data retention and patient privacy during sunset.
  - Transport/Storage: Server migration causing data corruption.

### Unclear Roles and Responsibilities

- Insufficient: "The Quality Team will perform risk management".
- Plan should specify:
  - Engineering team: Identify technical hazards related to AI/ML.
  - Clinicians (ophthalmologists): Identify clinical hazards and evaluate harm severity.
  - Regulatory affairs: Ensure compliance and review residual risk acceptability.
  - Cybersecurity expert: Identify information security hazards.

## Desired Format

Narrative documents, such as the _Risk management plan_ and the _Risk management report_, should be submitted as text documents.

- Use text files (such as .DOCX, .PDF) for your main reports: _Risk management plan_ and _Risk management report_.
- Use spreadsheets (such as .XLSX) for detailed data like hazards, risk estimations, and controls. This keeps everything clear and easy to trace.

## Deep Dive

### Expected Content: Risk Analysis

Risk analysis activities should encompass the entire device lifecycle. For example, an analysis may be focused on the usability of a device or may originate from a device change request.

This can quickly become overwhelming, particularly if you have multiple devices. A robust risk management system will relieve some of the burden. For each cycle of risk management, one or more risk analyses may be conducted.

The following details should be included for each risk analysis activity:

- The person responsible for conducting the activity.
- The scope of the activity.
- The method(s) used to conduct the risk analysis. For example:
  - Preliminary Hazard Analysis (PHA).
  - Fault Tree Analysis (FTA).
  - Event Tree Analysis (ETA).
  - Failure Mode and Effects Analysis (FMEA).
  - Hazard and Operability Study (HAZOP).
  - Hazard Analysis and Critical Control Point (HACCP).
  - Threat / Risk Analysis (TRA).
  - Threat Modelling.
- The strategy used to estimate the probability of the occurrence of harm. This strategy should:
  - Describe the risk estimation approach.
  - Indicate if the approach is quantitative, semi-quantitative or qualitative.
  - List the possible classifications of the risk estimation. The classifications should include a common term for the level (e.g. frequent, infrequent) and a description of the term for clarity.
- The strategy used to estimate the probability of the severity of harm. This strategy should:
  - Describe a qualitative risk estimation approach.
  - List the possible classifications of the risk estimation. The classifications should include a common term for the level (e.g. fatal, negligible) and a description of the term for clarity.

### Expected Content: Risk Evaluation

While not a requirement, a risk evaluation policy is one that will always land well with us. A suitable authority should create this policy, and it should contain the following information:

- The personnel who created the policy.
- A description of a risk evaluation framework with criteria based on applicable regulations, standards, state-of-the-art and the input of expert stakeholders.

Define the strategy for risk evaluation. This should align with the expectations of ISO 14971 and specify an approach that:

- Evaluates each risk based on the risk evaluation policy and the determinations of probability/severity of harm from the risk estimation process.
- Determines each risk as "acceptable" or "unacceptable".
- Prompts the definition of risk controls to mitigate "unacceptable" risks.
- Re-evaluates the residual risk after adding risk controls.
- Prompts a benefit-risk analysis for any risks that risk controls cannot reasonably mitigate.

### Expected Content: Risk Control

Here we are looking for a risk control strategy that aligns with the expectations of ISO 14971, with an approach that:

- Continually adds risk controls when risk is deemed unacceptable, to reduce risk as far as possible without adversely affecting the benefit-risk ratio, until it is no longer advantageous or feasible.
- Implements risk controls in the following priority order:
  - Safe design.
    - When calculating and displaying insulin dosage, restrict the range to 0 - 10 units to reduce the likelihood of recommending an overdose.
  - Protective measure.
    - Trigger visual and audible alarms when the heart rate exceeds 140 bpm to alert the caregiver to a potential tachycardic event.
  - Information for safety.
    - Provide targeted training to clinical users on interpreting the software's analytics graphs to ensure an accurate understanding of its decision-support recommendations.
  - Training to users.
- Defines the methods proposed to verify the implementation of risk controls.
  - For software-implemented risk controls, this can be achieved by defining software requirements.
  - For information for safety, link to the corresponding software requirement if the information is provided within the product or link to accompanying documentation if provided in the IFU, training materials or other process instructions.
- Defines the methods proposed to verify the effectiveness of risk controls. This may be achievable by referencing evidence from other regulatory activities, such as software verification, usability engineering evaluation, clinical evaluation or validation.

### Expected Content: Risk Management Review Process

List the scope of information that the chosen suitable authority considers when conducting the risk management process review. This should include:

- The risk acceptability criteria policy;
- The adequacy and competence of risk management resources;
- The accurate implementation of the _Risk management plan_; and
- The impact of post-market surveillance data on risk management.

### Expected Content: Production and Post-Production Activities

List the planned production & post-production risk management activities, or provide a link to the _Post-market surveillance plan_ documentation if this information resides there.

The goal here is to establish a system to actively collect and review information related to the medical device during production and post-production phases, focusing on safety relevance. This could include:

- Plans to collect information from **passive** sources, such as complaints, adverse events and incidents.
- Plans to collect information from **active** sources, such as field evaluations and PMCF studies.
- Define the extent and nature of monitoring appropriate to the device.
- Define the monitoring activities and PMCF study activities.
- Specify the frequency of review.
