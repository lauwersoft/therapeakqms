# Pre-determined Change Control Plan (PCCP)

> Create a plan to reduce the regulatory burden for AI/ML-driven modifications throughout the product lifecycle.

A PCCP enables manufacturers to pre-define a bounded set of changes to an AI/ML software as a medical device (SaMD) and the protocols, evidence and risk controls that will govern these changes.

Once approved, these changes may be implemented without requiring separate, repeated change submissions, provided the manufacturer adheres to the authorised plan.

A PCCP can be submitted with the technical file submission at initial conformity assessment or as a change request. Please reach out to the team if you have any questions.

## Minimum Requirements

Scarlet operationalise PCCP requirements through three core components:

- **Description of changes** - Define the scope, boundaries, and nature of planned changes. This includes a catalogue of planned modifications, describing each substantial AI/ML software change.
- **Change protocol** - Specify the pre-defined metrics, acceptance criteria and validation methods for each planned change. Describe the planned datasets, including the source, eligibility criteria and intended population(s).
- **Impact assessment** - Detail an ISO 14971-aligned risk assessment for each planned modification, along with an assessment of cumulative risk over time. Confirm that the device remains compliant with GSPRs.

## Common Pitfalls

A PCCP may only include substantial changes that remain within the device's intended purpose. These are changes that may significantly affect performance, inputs, or model output, but fundamentally do not alter the intended purpose of the device.

**Example of a change that does not fit PCCP scope:**

An AI dermatology classifier of melanoma is updated to support the identification of eczema, representing a new clinical condition not covered in the intended purpose. This alters the device's intended purpose and therefore cannot be included in a PCCP; it requires a change request post-certification.

**Example of a change that fits PCCP scope:**

An AI dermatology classifier of melanoma expands its validated performance to include darker skin tones within the already approved adult population, supported by new dataset training and performance validation. The intended purpose (aid in identifying suspicious lesions in adults) is unchanged.

## Desired Format

To ensure traceability, map each change through this workflow: Description of change -> Change protocol -> Impact assessment.

This can be done with identifiers for each change, and it is recommended that a traceability matrix is used to clearly trace the Description of change and Change protocol activities.

We would like you to demonstrate integration with relevant technical documentation.

## Deep Dive

### Expected Content: Description of Changes

Include the following information, detailing the nature of the planned changes:

#### Intended Purpose Statement

A clear, objective intended purpose statement, with:

- Population characteristics.
- Clinical context.
- Safety-critical conditions and contraindications.
- Boundaries defining what cannot change through the PCCP.

#### Catalogue of Planned Modifications

A structured description of each planned substantial AI/ML software change, such as:

- Model retraining, optimisation, or architecture refinements
- Dataset expansion
- Input changes (e.g. adding new compatible hardware, imaging protocols, data formats)
- Performance improvements within the existing intended purpose
- Subpopulation expansions (if permitted within the intended use)

For each change type, the manufacturer must specify:

- Purpose of the change
- Expected impact on software behaviour
- Boundaries and constraints of the change
- The rationale that the change remains within the intended purpose

These changes should be specific, and be able to be verified and validated.

#### Explicit Inclusion and Exclusion Boundaries

Manufacturers must state all planned changes that are within the scope of the PCCP.

Examples of changes out of the scope of PCCP:

- Alterations to the intended purpose.
- Algorithmic changes that materially alter clinical use.

Scarlet will review changes and state whether these are deemed:

- Non-substantial and out of PCCP scope.
- Substantial but out of PCCP scope.
- Substantial and approved within PCCP.

### Expected Content: Change Protocol

This is the core operational mechanism of the PCCP. Submit a detailed protocol for each planned change covering:

#### Performance Evaluation Plans

Each planned change must have pre-defined metrics, acceptance criteria and validation methods, including:

- Technical performance metrics.
- Clinical performance metrics.
- Subpopulation-specific performance.
- Thresholds for acceptable degradation or improvement.
- Requirement to test on independent datasets.
- Comparison against pre-change baseline.
- Incorporate PMS/PMCF data, where relevant.

#### Data Management Practices

Describe the planned datasets including, as applicable (non-exhaustive list):

- Data sources (e.g. RWD, curated database or synthetic), eligibility criteria, intended populations with planned representativeness and diversity.
- Data curation, annotation standards, quality controls.
- Bias detection and mitigation processes.
- Security and integrity controls across the data lifecycle.
- Dataset partitioning (training, tuning, test).

#### Retraining Practices

Provide:

- Triggers for retraining (schedule-based, threshold-based, performance-based).
- Prohibited triggers (e.g., unsupervised auto-adaption in field unless validated).
- Process workflow (model development -> internal testing -> external validation).
- Human-in-the-loop requirements.
- Version control, documentation, and reproducibility measures.
- Retraining criteria and parameters to be predefined.

#### Update and Deployment Procedures

Document end-to-end update processes, including:

- Deployment pathway (e.g. staged rollout, full release).
- Rollback/fallback mechanisms, if required.
- Change documentation and release notes.
- User communication strategy.

A detailed description of expected elements within a change protocol can be seen in FDA, Marketing Submission Recommendations for a Predetermined Change Control Plan for Artificial Intelligence-Enabled Device Software Functions, Appendix A.

### Expected Content: Impact Assessment

Provide the following information:

#### Risk Assessment

An ISO 14971-aligned risk assessment should be performed for each planned substantial change, and include:

- Risks (hazards, hazardous situations and harms).
- Risk estimations and evaluations.
- Effect of the planned change on benefit-risk profile.
- Anticipated risk controls (with reference to verification and validation activities within the Change Protocol).

This should include any new risks identified and any changes to existing risks within the risk management file.

#### Assessment of Cumulative Risk

Address:

- How multiple PCCP changes interact.
- Whether cumulative drift in risks may occur (do risks change over time).
- Any specific monitoring triggers requiring regulatory notification and/or PCCP update.

#### PMS and PMCF Integration

Define:

- PMS signal thresholds that trigger regulatory notification, protocol adaptation or PCCP review.
- How real-world performance monitoring informs risk management (e.g. risk estimations and risk controls).
- Confirmation that the device remains compliant with GSPRs based on the Impact assessment.

## Review Process and Next Steps

### What to Expect from Scarlet During Review

Scarlet will:

- Assess completeness of the PCCP and identify gaps or ambiguous boundaries.
- Classify each planned change as:
  - Non-substantial.
  - Substantial but out of scope.
  - Substantial and approvable within PCCP.
- Confirm the PCCP's coherence with intended purpose and technical documentation.
- Provide formal approval or findings requiring modification of the plan.

### Following Approval

Once a PCCP has been approved, formally document the specific version of the PCCP authorised by Scarlet to ensure full traceability within the technical documentation and QMS records.

For any substantial changes that fall within the boundaries of the approved PCCP, a separate change request to Scarlet is not required. Please update Scarlet that a substantial change has occurred, in accordance with the approved PCCP, within 10 working days of implementation of the change.

Any substantial changes that are outside those defined boundaries require notification to Scarlet through a change request, prior to implementation of the change. Deviations will be assessed to determine whether further change assessment activities are required.

During routine surveillance assessments, Scarlet will review the substantial changes implemented under the approved PCCP to confirm they align with the agreed change protocols.
