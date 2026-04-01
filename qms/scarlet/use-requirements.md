# Use Requirements Specifications

A set of requirements that an SaMD product must satisfy to ensure it can be used safely, effectively, and as intended by its target users throughout its lifetime.

IEC 82304-1 is an international standard that governs the safety and quality of health software products, including SaMD. One key concept this standard outlines is the determination and documentation of the medical device software's **use requirements**.

Use requirements are a set of high-level requirements that encompass a broad range of topics, including:

- The intended use and functionality of the medical device software
- The interfaces between the medical device and users or external systems
- Security, data privacy, and IT standards and regulations
- Processes and documentation that support the operation and use of the medical device through its lifecycle from release to maintenance to retirement

Once defined, use requirements serve as an input into the downstream SaMD development activities.

## Minimum Requirements

When submitting of your technical documentation, the following are the minimum requirements for this topic:

- **Two-levels of requirements**: IEC 82304-1 mandates the specification of product-level use requirements. These are decomposed into technical software requirements, in line with IEC 62304. As a result, it is wise for manufacturers to define and maintain two independent sets of requirement specifications.
- **Downstream traceability**: Scarlet will assess that all use requirements have been implemented and validated. Providing clear traceability from each use requirement to 1) its downstream implementation and to 2) validation activities can result in more efficient assessment.
- **Comprehensiveness**: Whilst most manufacturers are very aware of the use requirements related to their medical device's intended use and core functionality, it is common to see the unintended omission of other less obvious requirement categories expected by IEC 82304-1. Gaps in the expected coverage of use requirements can lead to:
  - Omissions of medical device development activities, software design, or regulatory documentation
  - An increased risk of safety/security issues with the medical device
  - Delays in certification, as queries are raised during technical assessments

To ensure you have a comprehensive set of use requirements, see the Deep dive section for a list of the requirements to consider.

> **Note:** Scarlet does not expect perfection for submission. If some requirement categories are erroneously omitted, these can be flagged and addressed during the assessment process.

## Common Pitfalls

The following are common queries that are raised by Scarlet during assessment:

> **Warning:**
>
> - **The omission of IEC 82304-1 from state-of-the-art standards**: IEC 82304-1 belongs in your state-of-the-art for SaMD.
> - **Insufficient coverage of requirements against expected categories**: See the Deep dive section for a list of the requirements to consider.
> - **Insufficient traceability to validation activities**: Validation that each use requirement has been satisfied is essential to support a claim that the device is secure, safe and effective for its intended purpose.

## Desired Submission Format

Scarlet specifies desired submission formats for certain technical documentation to facilitate easier assessment. It is recommended that use requirement specifications are documented as follows:

- **Tabular format**: Use a tabular document format, such as .XLSX.
- **Key data fields**:
  - Unique identifier
  - Requirement name
  - Requirement description
- **Traceability**:
  - Trace from each use requirement to implementation (e.g. software requirements, accompanying documents, etc)
  - Trace from each use requirement to validation activities

## Deep Dive

### Expected Use Requirements: Accompanying Document Requirements

This category focuses on requirements for documentation that accompanies the release of medical device software. This may include requirements to ensure that:

- Instructions for Use documentation that complies with IEC 82304 clause 7.2.2 and EU MDR Annex I, 23.1 is produced
- Documentation is produced to define the processes for installing, maintaining, updating, decommissioning, and disposing of the medical device software
- User training or information for safety documentation is produced to detail the safe use of the medical device software and its user interface
- The medical device software is appropriately labelled

### Expected Use Requirements: Applicable Regulation Requirements

This category may include use requirements to ensure that:

- Regional or international data protection regulations are met
- Regional or international patient-health-information regulations are met
- Industry-standard information security regulations are met
- State-of-the-art software-development practices are adhered to

### Expected Use Requirements: Installation, Update, or Decommission Requirements

This category may include use requirements that cover:

- The software distribution mechanism
- The software installation process, including the verification of integrity
- The process of integrating the medical device with other software or hardware systems
- The methods and frequency of software updates
- The software rollback process
- The conditions for software recall and the process to achieve it
- The methods of decommissioning the software and the transfer, retention, and/or irreversible deletion of data

### Expected Use Requirements: Intended Purpose Requirements

This category may include use requirements that address the features and functionality of the medical device that achieve its intended purpose.

### Expected Use Requirements: Interface Requirements

This category may include use requirements that describe:

- Human-to-machine user interface(s) of the medical device
- Machine-to-machine interface(s) between the medical device, accessory devices, and/or other external software or hardware systems

Note: The usability engineering processes defined in IEC 62366-1 should be utilised to establish the human-to-machine user interface requirements.

### Expected Use Requirements: Security Requirements

This category may include use requirements that describe:

- Authorised use and protections against unauthorised access
- Authentication mechanisms
- Health data integrity, privacy, and protection
- Protection against malicious intent
- Immunity from, or susceptibility to, unintended influence by other software using the same hardware resources
- Protection against unauthorised access and tampering of available documentation

## More Resources

### Article: Why IEC 82304-1 Belongs in Your State-of-the-Art for SaMD

Ask a medical device software manufacturer which state-of-the-art international standards they are applying to develop their devices; they will undoubtedly mention IEC 62304. This standard defines the life cycle requirements for medical device software. Initially published in 2006, it has become a cornerstone of high-quality medical device software development and was harmonised with the EU MDD. Adherence to this standard is standard practice for EU MDR and UK MDR certification of SaMD.

However, for SaMD, Scarlet maintains that adherence to IEC 62304 alone is insufficient. It should, at least, be paired with another important, yet commonly overlooked standard, IEC 82304-1.

**What is IEC 82304-1**

IEC 82304-1 is an international standard that defines requirements for manufacturers when developing safe and secure health software products designed to operate on general computing platforms and intended to be placed on the market without dedicated hardware; it explicitly excludes software that is part of medical electrical equipment. The scope of the standard covers the entire lifecycle of health software products, including design, development, validation, installation, maintenance, and disposal.

**Why IEC 62304 alone is not enough for SaMD**

As IEC 62304 was initially published in 2006, it predates the revolutions of smartphone technology and cloud computing that have given rise to the SaMD industry. At that time, the most common application of IEC 62304 was to guide the development of software subsystems in electromechanical medical device development. For many regulatory affairs professionals, this remains their primary exposure to IEC 62304.

In electromechanical medical device development, the IEC 60601-1 standard governs the overarching development of the medical device. It starts with user needs as input and defines system requirements and a system architecture containing subsystems, including software subsystems. Each subsystem requires its own decomposition of requirements, architecture, implementation and subsystem verification. Finally, the electromechanical medical device is evaluated through system integration verification and validation activities.

In this context, IEC 62304 is only responsible for managing the software subsystems, from software requirement analysis to verification activities. The standard does not govern the development of a complete medical device system and has no direct relationship to user needs or validation activities.

In SaMD development, where the target hardware is general computing platforms such as smartphones, personal computers and servers, the IEC 60601-1 standard is not applicable. This creates a gap in system and validation activities for SaMD development, which IEC 82304-1 resolves.

In the 2015 amendment of the IEC 62304 standard, Section 1.2 was updated to clarify the standard's field of application and recommend its use alongside IEC 82304-1 for SaMD system-level development and validation.

> This standard applies to the development and maintenance of MEDICAL DEVICE SOFTWARE when software is itself a MEDICAL DEVICE or when software is an embedded or integral part of the final MEDICAL DEVICE... This standard can be used in the development and maintenance of software that is itself a medical device. However, additional development activities are needed at the system level before this type of software can be placed into service. These system activities are not covered by this standard, but can be found in IEC 82304-1.... This standard does not cover validation and final release of the MEDICAL DEVICE, even when the MEDICAL DEVICE consists entirely of software.... Validation and other development activities are needed at the system level before the software and medical device can be placed into service. These system activities are not covered by this standard, but can be found in related product standards (e.g., IEC 60601-1, IEC 82304-1, etc.).
>
> [IEC 62304, Section 1.2]

### Article: How to Combine IEC 82304-1 and IEC 62304

Here is a summary of the interplay between IEC 82304-1 and IEC 62304 and how to apply them within your SaMD development process. In general, one can say that IEC 82304-1 describes the whole development process of an SaMD and makes use of IEC 62304 for specific Software life cycle processes.

1. Consider the use requirements of your SaMD (IEC 82304-1, section 4.2). These are a set of high-level requirements, which originate from the device's intended purpose, user needs and safety/security characteristics. They encompass a broad range of topics, including:
   1. The intended use and functionality of the medical device software.
   2. The interfaces between the medical device and users or external systems.
   3. Security, data privacy, and IT standards and regulations.
   4. Processes and documentation that support the operation and use of the medical device through its lifecycle from release to maintenance to retirement.

   Some of these use requirements will require software implementation, while others can be satisfied by processes, instructions for use, or other documentation accompanying the software.

2. Plan validation activities that aim to satisfy the use requirements. This may include system test protocols and leverage evidence from software verification, usability engineering or clinical evaluation activities (IEC 82304-1, sections 6.1, 6.2).
3. Decompose the use requirements into technical software requirements (IEC 62304, section 5.2).
4. Per 62304, design, implement and verify the software and generate a software release.
5. Collate the SaMD's identification details and accompanying documentation (IEC 82304-1, section 7).
6. Conduct validation activities and demonstrate that the SaMD is safe, secure and effective from the validation outcomes (IEC 82304-1, section 6.2, 6.3).
7. Document the planned post market activities (IEC 82304-1, section 8), including:
   1. Software maintenance and change management (IEC 62304 sections 6, 8).
   2. Re-validation.
   3. Post-market communication.
   4. Decommissioning and disposal of the SaMD.
