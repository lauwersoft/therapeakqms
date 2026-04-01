# Risk Management File

> Create risk management documentation that supports a positive benefit-risk ratio across the lifecycle of your device.

This component of your technical file is key to showcasing that each individual device risk has been identified, estimated, evaluated, controlled and reviewed for residual risk. It provides clear traceability through the risk management process.

## Minimum Requirements

The content below outlines what should be included in the documentation of risk records. As emphasised in our Risk management plan guidance, things quickly get messy in the absence of a robust system.

- **Safety and security characteristics** of the medical device identified through the risk analysis activities.
- **Hazard-related use scenarios, use errors, and abnormal uses** identified during the usability-related risk analysis activities.
- **Hazards, hazardous situations, and associated harms** identified during the risk analysis activities.
- **Definition of hazardous sequences** (i.e. the logical sequence of events that define a progression from a hazard to a hazardous situation to harm).
- **Initial risk estimation** of each hazardous sequence, including probability and severity.
- **Risk evaluation outcome**, indicating whether each risk is acceptable in accordance with the established risk acceptability criteria policy, or whether risk control measures are required.
- **Risk control measures** intended to reduce risks that are deemed unacceptable, detailing their implementation and verification of their effectiveness.
- **Evaluation of residual risk** after the implementation of risk control measures.

See the [deep dive section](#deep-dive) for more information on each of these elements, including examples of what to include.

## Common Pitfalls

Risk can be interpreted and calculated very differently so it is no surprise that shortfalls are common when it comes to risk management documentation.

Avoid the following pitfalls for which we commonly raise queries.

### No Device Characteristics Provided

We are looking for evidence of traceability across the risk management process:
Characteristics -> Hazards -> Hazardous Situations -> Harms -> Risk Analysis -> Risk Evaluation -> Risk Controls -> Residual Risk Assessment.

This chain is compromised in the absence of device characteristics.

### Poorly Defined Hazards, Hazardous Situations and Harms

Manufacturers regularly make mistakes such as calling a malfunction of a device a hazardous situation when it would be a hazard, or listing radiation harm in the hazards column even though that is actually a harm.

### Insufficient Consideration of Hazardous Sequences

Often we are presented with a single hazard and hazardous sequence that culminates in a specific harm. This may be adequate but in many cases, a hazard can lead to multiple hazardous situations. Similarly, a hazardous situation can lead to multiple differing harms.

This creates several permutations. Each possible sequence should be documented and individually estimated and evaluated.

### Underestimation of Risk

Even if highly unlikely, ignoring severe or catastrophic risks compromises your integrity and commitment to safety. It is better to acknowledge these and mitigate for them than to omit them in the hope that they will not occur.

### Over-reliance on Human-in-the-Loop

Naturally, having a human-in-the-loop serves as a checkpoint to capture erroneous outputs. However, humans are not infallible and their presence does not negate a risk.

## Desired Format

This is by no means a requirement however, representing risk elements in a risk traceability matrix reflects a systematic process that makes it much easier to navigate your risk management file.

The following traceability is recommended to be documented within or alongside your risk records documentation:

- For each risk element arising from risk analysis (e.g. safety/security characteristics, use errors, hazard-related use scenarios, hazards, hazardous situations and harms):
  - Identify the risk analysis activity in which it was identified.
- For each hazardous sequence, ensure traceability between:
  - If applicable, indicate the usability or cybersecurity-related risk element(s) from which the hazardous sequence may arise.
  - The hazard, hazardous situation, harm and logical events which constitute the hazardous sequence.
  - The risk estimation.
  - The risk evaluation.
  - If applicable, any risk controls applied.
  - If applicable, the residual risk evaluation.
  - If applicable, the benefit-risk justification.
- For each risk control, ensure that there is clear traceability between:
  - The defined risk control.
  - The method of implementation.
  - The method of effectiveness verification.

## Deep Dive

### Safety Characteristics

ISO 14971 5.3 states:

_"For the particular medical device being considered, the manufacturer shall identify and document those qualitative and quantitative characteristics that could affect the safety of the medical device. Where appropriate, the manufacturer shall define limits of those characteristics."_

- Example qualitative safety characteristics:
  - The device is used in an ICU.
  - The device is used by authorised clinicians.
  - The device provides output and warnings for users.
- Example quantitative safety characteristics:
  - The device processes and displays updated heart rate and temperature readings at intervals no greater than 5 seconds.
  - The device generates an alert if the measured heart rate exceeds 180 bpm or falls below 40 bpm.
  - The device warns the user if body temperature readings exceed 38.5 C or fall below 35.5 C.

IEC 62366, 5.2 requires you to identify, as part of the risk analysis, all parts and characteristics of the user interface that are related to the safety of the medical device.

For software, this includes:

- Display characteristics (font size, colour coding, contrast).
- User interaction patterns.
- Feedback mechanisms.
- Error messages and warnings.
- Data input validation.

> **Note:** ISO/TR 24971, Annex A provides a collection of helpful questions that can assist in identifying safety characteristics of your medical device.

### Security Characteristics

IEC 81001-5-1 is the foundational cybersecurity standard specifically tailored for health software.

For SaMD in particular, security characteristics are critically important because modern healthcare relies heavily on connected medical devices and health software. These include data communication interfaces, trust boundaries, attack vectors, network characteristics, operating systems and software architecture, which may lead to safety risks.

Examples of security characteristics:

- The device handles and stores confidential patient data.
- The device sends and receives data on an IT network.
- The device detects and handles corrupted or incomplete input data.

> **Note:** It is recommended first to document the product security context (see IEC 81001-5-1 7.1.2) and consider the list of common characteristics provided in IEC 81001-5-1, 7.2.

### Hazards, Hazardous Situations, and Associated Harms

Let's start with some definitions from ISO 14971:

- **Hazard** - Potential source of harm.
- **Hazardous situation** - Circumstances in which people, property or the environment are exposed to one or more hazards.
- **Harm** - Injury or damage to the health of people, or damage to property or the environment.

The key here is that a hazard cannot result in harm in the absence of a hazardous situation. The progression is: **Hazard -> Hazardous Situation -> Harm**.

Let's now illustrate this with the example of diagnostic imaging analysis software that uses a convolutional neural network to detect diabetic retinopathy.

**Hazard**: Algorithm provides incorrect diagnostic output (false negative - fails to detect diabetic retinopathy).

**Hazardous sequence of events**:

1. Software processes retinal image with early-stage retinopathy.
2. Algorithm fails to identify pathological features.
3. Software outputs "No diabetic retinopathy detected".
4. Clinician reviews and relies on the software result.
5. Clinician does not order further testing or treatment.

**Hazardous situation**: Patient with diabetic retinopathy is exposed to delayed or missed diagnosis.

**Harm**: Disease progression leading to vision impairment or blindness (severe irreversible harm).

### Hazardous Sequences

Hazardous sequences are potential sequences of events in which a hazard gives rise to a hazardous situation and, subsequently, leads to harm.

A hazardous sequence risk record should include the following documentation:

- Identify the originating hazard.
- Identify a hazardous situation that could arise from this hazard.
- Describe a possible sequence of events that could lead from the hazard to the hazardous situation.
- Identify the entity at risk in this hazardous situation (e.g., health care professionals, patients, property, or the environment).
- Describe a possible sequence of events in this hazard situation that could result in harm to a person or entity.

> **Note:** It is possible that a hazard may lead to one or more hazardous situations, and a hazardous situation may lead to one or more harms. Consider each hazardous sequence independently because the same risk estimation is unlikely to be applicable for unrelated sequences.

### Risk Estimation

This is where the effort put into your risk management planning pays off. The methods that you specified, outlining how the probability of harm occurrence and severity for each hazardous sequence will be estimated, will be applied here. Risk estimation should include examination of:

- The circumstances in which the hazard is present.
- The sequences of events that lead from the hazard to the hazard situation.
- The probability of the hazardous situation occurring.
- The probability that the hazardous situation leads to harm.
- The severity of the harm that could result.

Risk levels are commonly estimated using qualitative scales for both:

- Probability (e.g., frequent, probable, occasional, remote, improbable), and
- Severity (e.g., critical, major, moderate, minor, negligible).

When assessing the probability of harm, multiple factors must be considered:

- The likelihood of the hazard being present,
- The sequence of events that could lead to a hazardous situation, and
- The probability that the hazardous situation results in harm.

This multi-step consideration is particularly important when evaluating software failures. Because there is no universally accepted method to quantitatively estimate the probability of software failure, it is often advisable to assume the probability of failure is 1 (i.e., the failure will occur under certain conditions).

Risk estimation can then focus on the likelihood of the surrounding conditions and events in the hazardous sequence. The combined product of these event probabilities determines the overall probability of the harm occurring.

### Risk Evaluation

Once a risk estimation score has been determined for a hazardous sequence, it's up to you to decide whether the level of risk is acceptable. Your risk acceptability policy and risk acceptability criteria (within your risk management plan) should be used to evaluate the acceptability of risks.

A common method for evaluating risk is to define a risk level matrix, which classifies risks either qualitatively (e.g. using terms such as "high", "medium", or "low"), or quantitatively with numerical scores (e.g. 1-5). The assigned risk level is typically based on the combination of the probability (likelihood of occurrence) and severity (potential impact).

| Severity / Probability | Frequent      | Probable      | Occasional | Remote       | Improbable   |
| ---------------------- | ------------- | ------------- | ---------- | ------------ | ------------ |
| **Critical**           | Very High (5) | Very High (5) | High (4)   | High (4)     | Medium (3)   |
| **Major**              | Very High (5) | High (4)      | High (4)   | Medium (3)   | Medium (3)   |
| **Moderate**           | High (4)      | High (4)      | Medium (3) | Medium (3)   | Low (2)      |
| **Minor**              | Medium (3)    | Medium (3)    | Low (2)    | Low (2)      | Very Low (1) |
| **Negligible**         | Low (2)       | Low (2)       | Low (2)    | Very Low (1) | Very Low (1) |

An example risk acceptability policy for such a risk evaluation approach could be:

_Risks classified as **Very High** or **High** must be mitigated through design and process risk control measures._

_**Medium** risks can be mitigated through design and process risk control measures or require documented rationale for acceptability._

_**Very Low** or **Low** risks are considered acceptable and do not require risk mitigation._

_All residual risks are reviewed collectively for overall risk acceptability._

### Risk Controls

Risk control measures must be defined and documented for hazardous sequences with an unacceptable level of risk following initial risk estimation.

Three types of risk controls are defined by ISO 14971, listed below in priority order.

- _Inherently safe design_ of the medical device to eliminate a hazard, reduce the probability of harm occurrence, or reduce the severity of harm.
- Introducing _protective measures_ to prevent the occurrence of a hazardous situation or prevent a hazardous situation from leading to harm.
- Provide _information for safety_, such as:
  - Warning labels.
  - Instructions for correct use, installation and maintenance.
  - Documenting contraindications.
  - User training.

#### Evidence of Implementation and Effectiveness Verification

"Safe design" and "protective measures" risk controls, as well as some "information for safety" risk controls, require software implementation within medical device software.

- The implementation method should include links to the relevant software requirements and design specifications.
- The evidence of effectiveness verification for these types of risk controls is commonly software verification execution reports.

Some "information for safety" risk controls require the creation of accompanying documentation.

- The implementation method should include links to accompanying documentation released with the medical device software.
- The evidence of effectiveness verification for these risk controls is commonly usability engineering summative evaluation reports and/or validation reports.

#### Documenting Risk Controls

A well-documented risk control measure should include:

- A unique identifier for traceability.
- A reference to the hazardous sequence(s) it addresses.
- The type of risk control (safe design, protective measures or information for safety).
- A clear, actionable and testable description of the risk control.
- The implementation method and supporting evidence (e.g. software requirements and software items, or published instructions for use).
- The method for verification effectiveness, and supporting evidence (e.g. results from software verification, usability evaluation or validation activities).

#### Examples

To ensure adequate implementation and effectiveness verification, the description of risk controls must be clear, actionable and testable. Below are some examples of risk control measures for each of the possible risk control types:

1. _**Safe design:**_ When calculating and displaying insulin dosage, restrict the range to 0 - 10 units to reduce the likelihood of recommending an overdose.
2. _**Protective measures:**_ Trigger visual and audible alarms when the heart rate exceeds 140 bpm to alert the caregiver to a potential tachycardic event.
3. _**Information for safety:**_ Provide targeted training to clinical users on interpreting the software's analytics graphs to ensure an accurate understanding of its decision-support recommendations.

### Residual Risk Evaluation

After evaluating the effectiveness of each risk control, the associated residual risk evaluation should be determined and documented. This requires re-estimating the probability of harm occurrence and the severity of harm, taking into account the effectiveness of the implemented risk control measures.

The residual risk evaluation should be documented with the following information:

- A reference to the hazardous sequence(s) being evaluated.
- A description of the residual risk.
- The residual risk estimation after the risk controls have been considered, including the probability of harm occurrence and the severity of harm.
- The residual risk evaluation level.
- A determination of whether the risk is acceptable or unacceptable.
- A rationale supporting the acceptability determination may include benefit-risk analysis if required.

#### Benefit-Risk Analysis

Where risk controls cannot adequately mitigate a hazardous sequence, a benefit-risk analysis should be documented to determine if the clinical benefit outweighs the residual risk of harm.

The benefit-risk analysis should consider clinical, technical, and user-centred evidence and may include:

- An explanation of the residual risks that remain after all feasible risk control measures have been applied.
- A clear description of the intended medical benefits of the device to the targeted patient population.
- A comparison of the benefits versus the residual risks.
- Reference support evidence, such as:
  - Clinical investigations or evaluations
  - Post-market surveillance data
  - Published scientific literature
  - Expert clinical opinion
- Consideration of alternative treatments or devices, demonstrating that the benefit-risk profile of this medical device is comparable or favourable.
- A clear statement of the conclusion of the analysis, indicating whether the benefits outweigh the risks.

#### Example

- **Residual risk:** The AI algorithm may misclassify rare or atypical images, potentially leading to incorrect diagnostic suggestions.
- **Risk evaluation:** Medium (remote probability, major severity)
- **Benefit-Risk justification:** The AI algorithm significantly reduces clinicians' workload and improves detection speed in common diagnostic cases. The algorithm correctly classified 96.8% of test cases in clinical validation. Misclassification risk has been comprehensively mitigated by the following reasonable means:
  - Providing clinicians with confidence scores and supporting diagnostic analytics.
  - Labelling the software as decision-support only, not diagnostic.
  - Requiring final clinical review and decision-making.
  - Delivering targeted training on AI output interpretation and override behaviour.

In addition, post-market surveillance activities are planned to monitor for misclassification trends, including:

- Monitoring and investigation of user-reported errors
- Periodic review of low-confidence and overridden cases
- Inclusion of flagged edge cases in future model retraining cycles

Given the significant clinical efficiency gained, implementation of reasonable risk controls and the proposal for post-market feedback loops, the residual risk is considered acceptable.
