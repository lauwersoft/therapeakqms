# Software Verification Test Specifications and Execution Reports

Test specifications and execution reports that provide evidence of software verification activities, demonstrating that software requirements, module integrations, and unit implementations have been verified in accordance with IEC 62304.

Each software verification activity should consist of detailed test specifications and execution reports. The test specifications define the test setups and procedures, and the execution reports provide evidence of the execution of all test specifications. The required scope of verification activities is determined by the software safety classification of the software system, or of individual software items, if software segregation is utilised in the software architectural design.

Regulatory expectations under IEC 62304 require manufacturers to document test specifications and execution reports that verify software requirements, software module integrations, and software unit implementations based on the software safety classification.

- For Class A software, only software system tests must be documented.
- For Class B and Class C software, test specifications must also be documented to verify the implementation of software units and the integration of software modules.
- For Class C software, acceptance criteria for software unit implementation must address specific technical topics such as event sequence, data and control flow, fault handling, and boundary conditions.
- Additionally, IEC 81001-5-1 sets expectations for security testing that should be commensurate with the device's cybersecurity risk level.

## Minimum Requirements

When submitting your technical documentation, the following are the minimum requirements for this topic:

- **Test specifications**: Each verification activity must have documented test specifications, that contain the details to define the setup, execution and evaluation of each test. The scope must align with the software safety classification (Class A requires system tests only; Class B and C also require unit and integration test specifications).
- **Test execution reports**: Each test specification must have a corresponding execution report providing evidence of execution of test on the software under test, and evaluation of the result.
- **Evaluation**: All test specifications and execution reports must be evaluated for adequacy and completeness.
- **Traceability**: Test specifications must clearly identify the test subjects being verified (software requirements, module integrations, or software units).

See the Deep dive section for the expected content of test specifications and execution reports.

## Common Pitfalls

The following are common queries that are raised by Scarlet during assessment:

> **Warning:**
>
> - **Inadequate test specifications**: Test specifications that lack essential information such as test objectives, test setup details, test procedures, or clear pass/fail criteria, making it difficult to assess whether verification activities are adequate to verify the requirements, module integrations, or software units they are intended to verify.
> - **Insufficient scope of verification**: Test specifications that do not align with the software safety classification requirements, such as missing unit or integration test specifications for Class B or Class C software, or missing Class C-specific acceptance criteria for software unit implementation.
> - **Missing or inadequate test execution reports**: Execution reports that lack evidence of execution, do not document deviations or anomalies, or fail to provide clear conclusions with rationale for whether test objectives were achieved.
> - **Inadequate traceability**: Test specifications do not trace to the software requirements, module integrations, or software units that are being verified.

## Deep Dive

### Desired Submission Format

Scarlet specifies desired submission formats for certain technical documentation to facilitate easier assessment. It is recommended that software requirement specifications are documented as follows:

- **Tabular format**: Use a tabular document format, such as *.XLSX*.
- **Key data fields**:
  - Test specifications
    - Unique test specification identifier
    - Test objective
    - Test setup
    - Test procedure
    - Pass/fail criteria
  - Test execution reports
    - Unique test execution identifier
    - Test specification that was executed
    - Executor(s)
    - Test execution date
    - Software version(s) under test
    - Deviations
    - Anomalies (if applicable)
    - Test execution result
- **Traceability**:
  - For software unit verification, trace from each test specification to the software unit(s) that are being verified
  - For software integration verification, trace from each test specification to the software item(s) that are being integrated and verified
  - For software requirements verification, trace from each test specification to the software requirements that are being verified

### Expected Content: Scope of Verification

The required scope of verification activities is determined by the software safety classification of the software system, or of individual software items, if software segregation is utilised in the software architectural design.

**Software safety class A:**

- Only software system tests must be documented to verify all software requirements.

**Software safety class B and C:**

- Software system tests must be documented to verify all software requirements.
- Test specifications must be documented to verify the implementation of software units and the integration of software modules.

**Software safety class C (additional requirements):**

- Acceptance criteria for software unit implementation must address the following technical topics as appropriate:
  - Proper event sequence
  - Data and control flow
  - Planned resource allocation
  - Fault handling (error definition, isolation, and recovery)
  - Initialisation of variables
  - Self-diagnostics
  - Memory management and memory overflows
  - Boundary conditions

### Expected Content: Software Requirements Testing

Software requirements are primarily verified through system testing. This type of testing should include:

- Functional system tests
- Performance and scalability tests
- Boundary/edge condition, stress and malformed or unexpected input tests

System testing should include any external or third-party software your SaMD depends on, so as to verify the functionality, security and reliability of these software services.

If required, software requirements can incorporate lower-level, white-box testing.

### Expected Content: Security Testing

IEC 81001-5-1 sets the following expectations for security testing. The type and depth of security testing should be commensurate with the device's cybersecurity risk level, as defined through the cybersecurity risk assessment and threat modelling activities.

**Secure implementation verification:** This may include:

- Adherence to secure coding standards
- Static code analysis, such as static application security testing (SAST)
- Traceability of security requirements and security capabilities to software design and implementation

**Threat mitigation testing:** For each implemented risk control, test specifications should verify both the presence and effectiveness of the mitigation under defined conditions.

**Vulnerability testing:** This may include:

- Robustness testing using malformed or unexpected inputs to uncover security weaknesses
- Attack surface testing (e.g., access-control-lists, exposed interfaces and privilege escalation)
- Software composition analysis on third-party or open-source components
- Dynamic application security testing (DAST)

**Penetration testing:** Penetration testing is a structured, simulated cyberattack conducted by qualified personnel on your medical device software in an environment representative of the intended use environment. The objective is to evaluate overall system resilience, identify exploitable weaknesses, and verify that implemented controls effectively prevent or limit compromise.

### Expected Content: Test Specifications

Should contain a set of test specifications that each contain the following information:

- A unique name/identifier
- Indicate the verification activity(s) to which it relates
- A clear identification of the test subjects being verified. Depending on the verification activity, the test subjects may be software requirements, software module integrations, or software units
- Define the test objective
- Describe the test setup. For example, any environment, equipment, data, software tools, and settings which are required to execute the test
- Describe the steps within the test procedure
- Define the expected result, the pass/fail criteria and any expected performance-related criteria

Confirm that the manufacturer has evaluated all test specifications for adequacy and completeness with respect to the corresponding test subjects -- namely, software requirements, software module integrations, and software unit implementations, as applicable based on the software safety classification.

### Expected Content: Test Execution Reports

The test execution reports documentation should contain a set of execution reports that provide evidence of the execution of each test specification.

Each execution report should contain:

- A unique name/identifier
- The test specification that was executed
- The personnel who conducted the execution and the date the execution occurred
- The versions of software under test. This includes the medical device software and any supporting software or software tools required to facilitate the execution
- If the execution was performed with the planned test setup and procedure described in the test specification
- Description and justification of any deviations from the test specification
- Summary of any anomalies discovered during execution
- If the pass/fail and performance criteria were met
- Conclusion, including if the objectives of the test execution were achieved and rationale

Confirm that the manufacturer has evaluated all test execution reports for completeness and accuracy and deemed their conclusions acceptable based on objective evidence.

## More Resources

- [Blog: Test automation for SaMD within IEC 62304: How to be effective and stay compliant](https://scr.lt/1jee5k53)
