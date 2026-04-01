# Software Development Plans

Comprehensive plans that define the processes, activities, tasks, and deliverables for software development lifecycle activities in accordance with IEC 62304.

The software development plans are a set of foundational documents that establish the framework for all software lifecycle processes required under IEC 62304. They define or reference the processes for software development, software risk management, software configuration management, software verification, software maintenance, software problem resolution, and software documentation planning. These plans ensure that software development activities are conducted systematically, with appropriate controls and documentation throughout the software lifecycle, from initial development through maintenance and problem resolution.

Regulatory expectations under IEC 62304 require manufacturers to establish and maintain software development plans that address all applicable software lifecycle processes based on the software safety classification. The plan should clearly define the standards, methods, tools, and procedures to be used, and should be tailored to the specific software safety classification (Class A, B, or C) of the software items being developed. Higher software safety classifications require more rigorous processes and documentation, which should be reflected in the plan.

## Minimum Requirements

When submitting your technical documentation, the following are the minimum requirements for this topic:

- **Core lifecycle processes defined**: The plan must define or reference processes for software development, software risk management, software configuration management, software verification, software maintenance, software problem resolution, and software documentation planning.
- **Software safety classification considerations**: For Class B and Class C software, the plan must include more detailed processes, such as software unit verification, software integration, and integration verification. For Class C software, it must also describe the standards, methods, and tools used for development.
- **Organisational responsibilities**: The plan should indicate the organisations responsible for conducting development activities and software configuration management, as well as the design sites where development will be conducted.
- **Traceability**: The plan must define traceability between documentation deliverables.

See the Deep dive section for a comprehensive list of the topics to document in your software development plans.

## Common Pitfalls

The following are common queries that are raised by Scarlet during assessment:

> **Warning:**
>
> - **Missing or incomplete software risk management processes**: Plans that fail to define software risk management activities or do not adequately address risk analysis of software components, including SOUP, or risk control verification within the software.
> - **Inadequate software maintenance and problem resolution processes**: Plans that lack clear procedures for receiving feedback, evaluating software changes, monitoring risk control effectiveness, or handling security updates and SOUP vulnerabilities after software release.
> - **Incomplete software configuration management**: Plans that do not define processes for identifying configuration items, SOUP items, or maintaining historical records of controlled configurations, particularly for Class B and Class C software where development tools must also be controlled.
> - **Insufficient detail for software safety classification**: Plans that do not adequately address the increased rigour required for Class B or Class C software, such as missing software unit verification processes or inadequate description of development standards and tools for Class C software.

## Desired Submission Format

Scarlet specifies desired submission formats for certain technical documentation to facilitate easier assessment. It is recommended that software development plans are provided in **standard document formats**, such as *PDF* or *DOCX*. It is at your discretion if you wish to submit them as combined or individual documents.

## Deep Dive

### Expected Content: Software Development

The software development section should outline the software development activities, tasks, and deliverables. This includes:

- Indicating the organisations that will conduct the development and the design sites where development will be conducted.
- For Class C software, describing the standards, methods, and tools used to develop the software.
- Identifying a procedure for identifying categories of defects that may be introduced based on the selection of specific technology within software development, and ensuring these risks are encompassed in risk analysis activities.
- Establishing and maintaining secure coding standards.
- Establishing procedural and technical controls for protecting IT infrastructures used through the software development lifecycle.

### Expected Content: Software Risk Management

Define the software risk management activities, tasks, and deliverables, or reference the risk management plan(s) where these are covered.

Software risk management should include activities or tasks to:

- Perform threat modelling on the software architecture and its intended operating environment.
- Identify items within the software architecture that could contribute to hazardous situations and document the potential causes.
- Analyse, evaluate, and mitigate risks related to SOUP items in the software architecture.
- Define, implement, and verify risk controls within the software.
- Analyse software changes for new risks and impact on existing risk controls.

### Expected Content: Software Configuration Management

Define software configuration management activities and tasks:

- Indicate the organisations that are responsible for software configuration management activities.
- Define the software items to be controlled. For Class B or Class C software, this should also include software tools, items, and settings that are used to develop the medical device software.
- Define a process to identify configuration items and their versions.
- Define a process to identify SOUP items, inclusive of the title, manufacturer, and unique identification of the SOUP version used.
- Define a process for documenting software configuration, which ensures a historical record of controlled configurations.

### Expected Content: Software Verification

Define the software verification activities, tasks, and deliverables.

This should include:

- Software system testing activities and tasks.
- Software requirements verification activities and tasks.
- Risk control effectiveness verification activities and tasks.
- Security testing activities and tasks.
- For Class B or Class C software, activities and tasks for software unit verification, software integration, and integration verification (including regression testing).
- Identification and descriptions of any test environments, equipment, and tools required to facilitate verification testing.

### Expected Content: Software Maintenance

Include procedures for receiving and handling feedback after software release, and procedures for evaluating and modifying software after release. These procedures will overlap with the software risk management process, software problem resolution process, and software configuration management process and shall include:

- Procedures for monitoring risk control effectiveness and re-evaluating risk as required.
- Procedures for verifying updates before release (including security patches).
- Procedures for updating supporting documentation.
- Procedures for the timely delivery of security updates and software patching.
- Procedures for monitoring SOUP for defects and vulnerabilities and managing regular, and time-sensitive, security-related updates to SOUP.

### Expected Content: Software Problem Resolution

This should include software verification, software validation, and software maintenance activities.

Define a process for:

- Receiving notifications about problems and vulnerabilities.
- Identifying software problems and vulnerabilities.
- Documenting software problems and vulnerabilities.
- Analysing software problems and vulnerabilities and their causes.
- Documenting the outcome of the triage process.
- Initiating, performing, and verifying change requests required to resolve software problems and vulnerabilities.

### Expected Content: Software Documentation Planning

Outline the documentation deliverables to be produced with the development, risk management, verification, and maintenance activities and tasks.

### Expected Content: Traceability

Define the traceability between documentation deliverables.
