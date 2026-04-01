# Validation Plans

Documentation that defines a set of validation activities planned to provide objective evidence that the SaMD's use requirements are fulfilled, and thus the SaMD product is safe, secure, and effective for its intended purpose.

Validation plans define the validation activities planned to provide objective evidence that the SaMD's use requirements are fulfilled. Under IEC 82304-1, validation activities are distinct from software verification activities and focus on demonstrating that the complete SaMD product, as deployed in its intended use environment, satisfies its use requirements and is safe, secure, and effective for its intended purpose.

Regulatory expectations require manufacturers to plan validation activities comprehensively, ensuring that all use requirements are addressed through appropriate validation methods. The validation plan must clearly define the scope of each validation activity, the methods to be used, the acceptance criteria, and the personnel responsible for execution. Additionally, IEC 82304-1 and EU MDR require that validation personnel demonstrate appropriate independence from design and development personnel to ensure objective assessment of the device's performance against its use requirements.

## Minimum Requirements

When submitting your technical documentation, the following are the minimum requirements for this topic:

- **Validation activities definition**: The validation plan must define a set of validation activities, each specifying the use requirements in scope, the chosen validation method, a description of the activity, required test environments or equipment, any constraints, and clear acceptance criteria.
- **Personnel allocation and independence**: The validation plan must identify personnel allocated to design and perform validation activities, provide evidence of appropriate independence from design and development personnel, and include criteria for independence with justification.
- **Comprehensive coverage**: The validation plan must address all use requirements, ensuring that each requirement has a corresponding validation activity or is otherwise justified as not requiring validation.

## Common Pitfalls

The following are common queries that are raised by Scarlet during assessment:

> **Warning:**
>
> - **Incomplete traceability to use requirements**: Validation plans that do not address all use requirements. It is expected that validation activities comprehensively cover all use requirements and therefore clear traceability from each use requirement to planned validation activities is expected.
> - **Conflating verification and validation**: Software validation as defined by IEC 82304 is a distinct process from software verification and software system testing as outlined in IEC 62304. The former focuses on the validation of the SaMD product against its use requirements, whereas verification focuses on confirming correct implementation of software requirements. Conflating these processes can result in the following queries being raised during assessment:
>   - **Lack of independent validation personnel**: Validation activities are typically performed by independent personnel, who are not involved in the design, development and verification of the medical device software.
>   - **Inadequate validation tests**: Validation activities aim to validate the safety and effectiveness of the SaMD product within its intended use environment. Reliance on software verification tests, executed within a simulated software test environment, may be insufficient.
> - **Insufficient validation activity definition**: Validation activities that lack essential information such as the specific use requirements in scope, the chosen validation method, clear descriptions of the activity, required test environments or equipment, constraints, or acceptance criteria.
> - **Missing or inadequate personnel independence**: Validation plans that fail to demonstrate appropriate independence of validation personnel from design and development personnel, or that lack clear criteria for independence with justification.

## Desired Submission Format

Scarlet specifies desired submission formats for certain technical documentation to facilitate easier assessment. It is recommended that validation plans are provided as a standard document format, such as .DOCX or .PDF.

## Deep Dive

### Strategic Considerations: Utilising Various Sources of Evidence

Validation activities must comprehensively cover all defined use requirements. Given the broad scope of use requirements, it is not uncommon for manufacturers to utilise various sources of evidence to support validation activities.

For example, the following sources of evidence can be utilised to support validation activities:

- **End-to-end system-testing activities**: This can be used to validate the operation and functionality of the SaMD product in a test environment, that is representative of the intended use environment, with acceptance criteria linked to defined use requirements.
- **Analysis of software verification outcomes**: Where system-level testing has been performed as part of software verification, an independent validator can analyse the results of these tests and reuse this evidence to support the validation of use requirements, *provided the testing environment and conditions are representative of intended use*.
- **Analysis of clinical evaluation outcomes**: Where clinical evaluation activities have successfully demonstrated the clinical performance of the SaMD product, within its intended use, an independent validator can analyse the results of these activities and reuse this evidence to support the validation of use requirements.
- **Usability engineering summative evaluation**: Usability engineering activities demonstrate the safe use of the SaMD product under representative real-world use conditions. This evidence is commonly used to support the validation of use requirements.

### Strategic Considerations: Defining Separate Verification and Validation Activities

Validation as defined by IEC 82304 is a distinct process from software verification and software system testing as outlined in IEC 62304. The former focuses on the validation of the SaMD product against its use requirements, whereas verification focuses on confirming correct implementation of software requirements. It is necessary to define separate activities to satisfy both processes.

For software verification activities, manufacturers are encouraged to leverage modern software testing tools, frameworks and equipment to automate the execution of verification activities. This can significantly improve the efficiency and effectiveness of the verification process.

However the purpose of validation is to validate the safety and effectiveness of the medical device software within its intended use environment. Therefore, Scarlet expects validation activities to be performed in a test environment that is representative of the intended use environment, by personnel that are independent from the design and development of the medical device software.

Careful design of the validation activity setup, including the selection of hardware, test tools and interfaces to external systems, is necessary to ensure the validation activities are meaningful and defensible.

### Expected Content: Validation Activities

The validation plan must define a set of validation activities planned to provide objective evidence that the use requirements for the SaMD product are fulfilled.

For each validation activity, include:

- The specific use requirements in the scope of the validation activity.
- The chosen method of validation.
- A description of the validation activity.
- A description of any test environments, equipment or other systems required to execute the validation activity.
- Any constraints that could limit the feasibility of a validation activity. These could include technical feasibility, cost, time, personnel availability or qualifications, and contractual constraints.
- The acceptance criteria for the validation activity are to be deemed successful.

### Expected Content: Personnel

The validation plan must identify and allocate personnel for validation activities.

Include:

- A set of personnel allocated to design and perform validation activities. This may differ between validation activities.
- Evidence that the validation personnel are at an appropriate level of independence from the design and development personnel.
- Criteria for independence and justification for the allocation of personnel.

## More Resources

- [Blog: An IEC 82304 fix for software validation frustration](https://scr.lt/kkj1thb1)
