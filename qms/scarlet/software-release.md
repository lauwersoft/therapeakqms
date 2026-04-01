# Software Release

Documentation that confirms the release of a verified software version, including the methods to create, archive and deliver the software, and any documentation which is required to accompany the release.

The software release documentation serves as the formal record of a software release. It must indicate the software version(s) for release, and the methods to create, archive and deliver this software. It must provide the necessary information to confirm the completion of the verification of this software version by summarising execution outcomes, anomalies, and residual risk, or reference a software verification summary report that provides this information.

Any documentation which is required to accompany the release should be provided, such as a software bill of materials (SBOM), instructions for use document, installation manual, user training documentation, software maintenance plan, etc.

## Minimum Requirements

When submitting your technical documentation, the following are the minimum requirements for this topic:

- **Software release version**: The software release documentation must include a unique identifier for the software release and versions of all software included in the release, including the medical device software and related configuration items or settings.
- **Software verification outcomes**: The software release documentation must provide the conclusions of the verification activities, which confirm the suitability for release and references known anomalies and residual risk.
- **Accompanying documentation**: The software release documentation must be accompanied by any documentation required to install and use the software. This can include instructions for use, installation manual, user training documentation, software maintenance plan, etc.

## Common Pitfalls

The following are common queries that are raised by Scarlet during assessment:

> **Warning:**
>
> - **Missing or incomplete verification conclusions**: Release documentation that lacks a confirmation of the suitability for release may result in a query being raised during assessment.
> - **Missing or incomplete accompanying documentation**: Software release documentation that fails to define all required accompanying documentation.
> - **Inadequate delivery procedures**: Documentation that lacks clear procedures for reliably delivering software to the point of use, or fails to address methods for creation, replication, labelling, packaging, protection, storage, deployment and archival.

## Desired Submission Format

Scarlet specifies desired submission formats for certain technical documentation to facilitate easier assessment.

It is recommended that software release documentation is provided as a standard document format, such as .DOCX or .PDF. Supplementary documentation, such as software bill of materials (SBOM), may be provided in tabular format.

## Deep Dive

### Expected Content: Software Versioning

The software release documentation should include:

- A unique identifier of the software release.
- Versions of all software included in the release. This may include the medical device software and related configuration items or settings. Extensive configuration items can be provided in a standalone software bill of materials (SBOM) document.

### Expected Content: Accompanying Documentation

A definition of all documentation that is required to accompany the release. The documentation provided should match the accompanying documentation requirements defined in the use requirements specifications.

It may include:

- The instructions for use document
- Installation manual
- Software release notes
- Security update information
- User training documentation and maintenance update documentation
- Secure operation guidelines (e.g. IT environment hardening instructions, release integrity verification mechanism and control mechanism for cryptographic keys)
- Safe, secure decommissioning guidelines
- Software bill of materials
- Software maintenance plan

The accessibility options for each document should be included. This may include website links and must include a means through which a customer can request a paper copy.

### Expected Content: Delivery Procedures

Procedures to reliably deliver the software to the point of use. This may include methods for creation, replication, labelling, packaging, protection, storage, deployment and archival.

### Expected Content: Verification Outcomes

The software release documentation must provide the conclusions of the verification activities, which confirm the suitability for release and references known anomalies and residual risk. Alternatively, it may reference a software verification summary report that provides this information.

The verification outcomes should include:

- The software version(s) that were under consideration for release
- The scope of this verification by referencing the software requirements documentation, the software architecture & design, planned verification activities, detailed test specifications, and execution reports
- Granular traceability from the software requirements, modules, and units to verification activities and test specifications, including:
  - Traceability of each software requirement to requirements verification test specifications
  - Traceability of each software module to module integration verification test specifications *(Class B and C only)*
  - Traceability of each software unit to software unit verification test specifications *(Class B and C only)*
- If verification activities were performed iteratively across multiple software versions, include a justification for the applied regression testing strategy
- A summary of the outcomes of each verification activity
- A list of known defects and vulnerabilities in the released software
- Evidence that a residual risk analysis has been conducted concerning any software anomalies identified during verification, and indicate if the residual risk is acceptable
- A conclusion on the suitability for release of the software version(s)
