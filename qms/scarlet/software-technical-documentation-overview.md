# Software Technical Documentation - Overview

Documentation that provides evidence of the software development activities and processes conducted in accordance with state-of-the-art medical device software standards and regulations.

## Applicable Regulatory Standards

The following are the key applicable regulatory standards for software development under EU MDR:

- IEC 62304:2005+A1:2015
- IEC 82304-1:2015
- IEC 81001-5-1:2021

## Expected Software Development Documents

The following are the expected documentation for software development under EU MDR:

- Use requirements
- Software development plans
- Software requirements
- Software safety classification
- Software architecture and design (for Software Safety Class B or C)
- AI model design (if applicable)
- Software verification
- Software release
- Validation plans
- Validation report

## Expected Traceability Evidence

It is essential that the software development documentation above are supported by traceability evidence to demonstrate that the completeness of software development activities and documentation from requirements analysis, through to software design, implementation, verification and validation. Software traceability must also demonstrate the interplay between software development and risk management documentation.

### Desired Submission Format

Scarlet specifies desired submission formats for certain technical documentation to facilitate easier assessment. It is recommended that software traceability is documented as multiple traceability matrices in tabular format, such as .XLSX.

### Expected Traceability

The following are the expected traceability for software development under EU MDR:

**Decomposition of use requirements into software requirements:**
- Use requirements -> Software requirements

**Implementation of software requirements into software architecture:**
- Software requirements -> Software modules/units

**Decomposition of the software architecture into granular software modules/units:**
- Software modules -> Software modules/units
- Software interfaces -> Software modules/units

**Verification of software units by software unit verification:**
- Software units -> Software unit test specifications and execution reports

**Verification of software module integration by software integration verification activities:**
- Software modules -> Software integration test specifications and execution reports

**Verification of software requirements by software requirements verification:**
- Software system -> Software system test specifications and execution reports

**Validation of use requirements by validation activities:**
- Use requirements -> Validation activities and reports

Additionally, traceability is expected between software development and risk management documentation:

**Contribution of software architecture and design to hazardous situations:**
- Software modules, units or SOUPs -> Hazardous situations

**Software risk controls to software implementation:**
- Risk controls -> Software requirements or architecture and design

**Risk control effectiveness verification, where applicable:**
- Risk controls -> Verification test specifications and execution reports or validation activities and reports
