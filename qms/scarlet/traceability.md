# Technical Documentation: The Need for Clear Traceability

An explanation of the importance of traceability in your technical documentation, and recommended traceability in your SaMD documents.

The technical documentation required to be created and maintained for medical devices is comprehensive and highly interdependent. It spans numerous procedures, plans, design documents, activities, evaluations, and reports across diverse disciplines such as software engineering, information security, clinical evaluation, usability engineering and risk management.

Traceability within your technical documentation is vital for long-term regulatory compliance, increased cross-functional clarity, and accelerated assessment timelines.

## Why Is Traceability Important?

### For Manufacturers

Traceability supports the mapping of a device's development lifecycle. It aligns various team members, such as clinical evaluators, usability engineers, risk managers, software developers, and AI engineers, around a unified framework. It enables efficient maintenance of technical documentation during iterative development cycles and change management. It can also reduce the risk of omitting documentation that could delay certification.

Furthermore, medical device regulations require technical documentation to be "organised" and "searchable". See EU MDR Annex II for example:

> *The technical documentation and, if applicable, the summary thereof to be drawn up by the manufacturer shall be presented in a clear, organised, readily searchable and unambiguous manner.*

Traceability is a key method for meeting this requirement.

### For Assessors

Traceability offers a coherent narrative showing how all elements of the technical documentation interrelate to support the device's safety and performance. It demonstrates that the manufacturer has systematically conducted all required evaluations, verifications and validations and drawn all necessary conclusions. The manufacturer's inclusion of robust, easy-to-understand traceability in their technical documentation significantly reduces the assessment time.

## What Traceability Is Recommended?

Detailed traceability is essential across the following domains:

### Risk Management

**Hazardous sequences -> Risk estimation & evaluation -> Risk controls -> Residual risk evaluation -> Benefit-risk analysis**

Key risk elements are identified through risk analysis activities. These include device characteristics, use errors, abnormal uses, hazards, hazardous situations, and harms. Hazardous sequences are constructed from these risk elements by considering possible sequences in which a hazard may lead to a hazardous situation, which in turn may lead to harm.

For each hazardous sequence, it is essential to indicate the estimation and evaluation of risk, the implementation and verification of risk control measures, and the results of residual risk evaluation, including any benefit-risk analysis required to justify the acceptance of residual risk.

It is recommended that risk records be provided with granular traceability and only be grouped for efficiency when the hazard sequences, evaluations, and controls are very similar. Risk estimations and risk evaluations become more complicated if performed against grouped risks, and when risks are grouped, each hazardous sequence will inherit the highest risk evaluation level.

**Risk controls -> Risk control implementation -> Risk control effectiveness verification**

For each risk control, it is required to trace to:

- **The risk control implementation.** For safe design and protective measures, this may be software requirements (and software modules/units). For information for safety, this may be user training documentation or Instructions for Use content.
- **The risk control effectiveness verification.** This may be verification, validation or usability engineering reports.

### Software Design, Verification & Validation

**Use requirements -> Software requirements -> Validation activities -> Validation reports**

Use requirements originate from various sources: intended purpose statements, clinical or performance claims, safety/security characteristics, and state-of-the-art or regulatory requirements.

Once a comprehensive set of use requirements is established, it is required to show how each use requirement is implemented. Software-implemented use requirements shall be decomposed into software requirements, whereas the other requirements may inform the development of accompanying documentation or internal procedures.

For each requirement, it should be evident which validation activity(s) are used to validate the use requirement has been met.

**Software requirements -> Requirements verification activities -> Verification reports**

Software requirements are decomposed from either use requirements or risk control measures. Each software requirement must be traceable to verification activities and test specifications, with the corresponding verification reports used to verify the correct implementation of the software requirements.

**Software requirements -> Software items -> Software units -> Software unit & software integration verification activities -> Verification reports**

> Note: The following is only required for Software Safety Classification: Class B or Class C software.

Software requirements are transformed into a software architecture that describes the software's structure. The software architecture must consist of:

- Software items, including SOUP items.
- Interfaces between software items.
- Interfaces between software items and external software/hardware.
- The subdivision of the software items into smaller software items and software units.

The implementation and acceptance of software units must be verified by software unit verification activities, and the integration of software units and software items into a cohesive software system must be verified by software integration verification activities.

In aggregate, the following traceability is required:

- Trace each software requirement to the software item(s)/unit(s) that implement it.
- Trace the "parent/child" relationship between software items and the items/units to which they are subdivided.
- Trace between software interfaces and the software items/units or external software components they connect.
- Trace each software unit to the software unit verification activity(s) and report(s) which verify its implementation.
- Trace each software item to the software integration verification activity(s) and report(s) which verify its implementation.

**Software architecture -> Risk analysis**

> Note: The following is only required for Software Safety Classification: Class B or Class C software.

As required by IEC 62304 Clause 7.1, you must analyse the design of software items/units in your architecture, including SOUP, and determine if they contribute to the hazardous situations. Therefore, showing clear traceability from the software items/units to hazardous situations defined in your risk management is advisable. This traceability is vital for supporting any risk segregation strategy within your software architecture.

### Usability Engineering

**Use scenarios -> Usability engineering summative evaluation activities -> Usability engineering summative evaluation reports**

(optional) -> Usability engineering formative evaluation activities -> Usability engineering formative evaluation reports

Usability-related risk analysis activities identify use errors and hazard-related use scenarios, which inform the scope of usability engineering activities.

IEC 62366-1 Clause 5.5 requires that you justify the selection of hazard-related use scenarios for usability engineering summative evaluation. Clause 5.7 requires that summative evaluation plans be defined for each selected hazard-related use scenario.

Therefore, it is recommended that there is clear traceability from each hazard-related use scenario in the scope of the summative evaluation and the activities/reports concerning this use scenario.

> Note: Similar evidence and traceability of formative usability engineering activities and reports conducted during development are highly recommended but not mandated.

**User training & information for safety -> Usability engineering summative evaluation activities -> Usability engineering summative evaluation reports**

Where user training and information for safety are chosen as risk control measures, evidence of the implementation and effectiveness verification of these risk controls is required.

The effectiveness of user training and information for safety is commonly evaluated through usability engineering activities.

Therefore, it is recommended that traces be documented from user training and information for safety risk control measures to usability evaluation summative activities/reports.

## How Should I Create and Manage Traceability and Provide This to Scarlet?

Maintaining structured, auditable traceability artefacts is critical to ensure regulatory readiness and enable efficient assessment. Here's how to manage this process effectively:

### Create Uniquely Identifiable Technical Data Items

When creating any technical data item that requires granular traceability, accompany the data item with a unique identifier. This way, the technical data item can be succinctly referenced by this identifier in traceability matrices.

### Implement Procedures to Review and Approve Traceability

Due to the complexity, missing pieces of required traceability and leaving gaps in your technical documentation are common. Also, during iterative development, traceability can easily become out of sync. These risks can be mitigated by regularly reviewing traceability matrices, especially before major submission milestones, and following a robust internal approval process before finalising technical documentation.

### Export Traceability Matrices

For submission to Scarlet (or any Conformity Assessment Body), prepare clean exports of traceability matrices that include:

- Titles and headers that identify the scope of the traceability matrix.
- Identifiers and descriptors for the technical data items in the matrix.
- Relational links between traced technical data items.
- Where possible, include hyperlinks to the location of the technical data items within the technical documentation.

### Use an Advanced Documentation System

Consider investing in an advanced documentation system, such as commercial eQMS software applications, which provides functionality to:

- Create discrete data items such as requirements, risks, test specifications and reports.
- Define relational links between data items.
- Provide visual representations of traced items.
- Automatically perform traceability gap analysis in your technical documentation.
- Notify you when an item has changed, which may impact traced items.
- Generate trace matrices on demand.

Whilst these systems can be costly to purchase and configure, their functionality can significantly reduce the overhead of creating and maintaining technical documentation and safeguard against unintended traceability errors or gaps.

By embedding traceability into your documentation early in your medical device development, you build a foundation for long-term regulatory compliance, increase cross-functional clarity, and accelerate assessment timelines.
