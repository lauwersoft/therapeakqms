# Software Architectural Design and Detailed Design

Design specifications that define the structure of medical device software in terms of software items, interfaces, units, and SOUP, consistent with IEC 62304 expectations.

For the development of software with a "Class B" or "Class C" software safety classification, the software architectural design and detailed design documentation define the structure of a software architecture in terms of software items, interfaces, units, and SOUP and provide details on the intended design of these software components. The software architectural design involves structuring the medical device software into an architecture of software items and software interfaces, which may be purpose-built for the medical device or software of unknown provenance (SOUP).

Regulatory expectations under IEC 62304 require manufacturers to document the software architecture and detailed design for Class B and Class C software, including:

- The definition of software items and the decomposition of these software items into granular software units
- Definition of all interfaces (both internal between software items and software units, and external with other systems)
- Identification and management of SOUP components
- Consideration of how software components contribute to hazardous situations

For Class C software, additional requirements include documenting software item segregation needed to mitigate safety risks and providing detailed design for individual software units.

The architecture should also reflect secure design principles as outlined in IEC 81001-5-1, including defence-in-depth, trust boundary protection, and least privilege principles.

See the Deep dive section for a comprehensive list of the artefacts that should be included in your software architectural design and detailed design documentation, along with the recommended submission format.

## Minimum Requirements

When submitting your technical documentation, the following are the minimum requirements for this topic:

- **Software architecture structure**: The documentation must define the software architecture in terms of software items, interfaces, units, and SOUP, with clear relationships between components and external systems.
- **Software safety classification and segregation**: If choosing to leverage the segregation of risk in your architecture, each software item/unit must have an assigned software safety classification with supporting rationale. For Class C software, any segregation of software items needed to mitigate safety risks must be identified with rationale for how segregation achieves adequate safety.
- **Interface documentation**: All interfaces (internal between software items/units and external with other systems) must be clearly defined and documented, including security considerations for external interfaces as required by IEC 81001-5-1.
- **SOUP documentation**: The documentation must identify SOUP components, including requirements that SOUP must satisfy, and analysis of published SOUP anomaly lists.
- **Risk considerations**: The documentation must address how software architecture and design choices contribute to hazardous situations, including software item contributions, SOUP contributions, and analysis of technology choice impacts on risk.
- **Traceability and review**: The documentation must demonstrate traceability from software requirements to implementing items/units, from software units to software unit verification activities, and from software items to software integration verification activities, and evidence of review confirming the architecture implements all software requirements.

## Common Pitfalls

The following are common queries that are raised by Scarlet during assessment:

> **Warning:**
>
> - **Incomplete software architecture structure**: Documentation that fails to adequately decompose the software into software items, units, and interfaces, or lacks clear relationships between components and external systems.
> - **Missing or inadequate interface documentation**: Interfaces that are not clearly defined, particularly external interfaces that lack security considerations such as threat assessment, access controls, or validation mechanisms as required by IEC 81001-5-1.
> - **Insufficient SOUP documentation**: Missing or incomplete identification of SOUP components, including title, manufacturer, unique designator, requirements that SOUP must satisfy, or analysis of published SOUP anomaly lists and their contribution to hazardous situations.
> - **Inadequate risk considerations**: Documentation that fails to address how software architecture and design choices contribute to hazardous situations, including software item contributions, SOUP contributions, or analysis of technology choice impacts on risk.
> - **Missing traceability or review evidence**: Lack of traceability from software requirements to architecture implementation, or absence of evidence demonstrating review and acceptance of the software architecture and detailed design.

**A note on IEC 62304 compliant software architecture and detailed design:**

> **Warning:**
>
> - It is common for manufacturers to submit verbosely written software architecture and design documentation, that unfortunately lacks the correct content, definitions and traceability to align with the expectations of IEC 62304.
> - It is important that your software engineering team understand the expectations of IEC 62304 and the regulatory requirements for software architecture and detailed design so as to efficiently generate IEC 62304 compliant software architecture and detailed design documentation which avoids the above queries being raised during assessment.

## Deep Dive

### Desired Submission Format

Scarlet specifies desired submission formats for certain technical documentation to facilitate easier assessment. It is recommended that software architectural design and detailed design documentation are provided as follows:

- **Tabular format**: Provide the list of software items, units, interfaces and SOUP in a tabular document format, such as .XLSX.
- **Architecture diagram**: Provide a diagram of the software architecture, that highlights the relationships between the software items, units, interfaces, SOUP and external systems.
- **Key data fields**:
  - Software items:
    - Unique identifier
    - Name
    - Description
    - Software safety classification
    - Rationale for software safety classification
  - Software units:
    - Unique identifier
    - Name
    - Description
    - Software safety classification
    - Rationale for software safety classification
    - Detailed design *(for Class C only)*
  - Interfaces:
    - Unique identifier
    - Name
    - Description
    - Detailed design *(for Class C only)*
  - SOUP:
    - Unique identifier
    - Name
    - Manufacturer
    - Version
    - Description
    - SOUP requirements
    - Anomaly lists

### Expected Content: Architectural Design - Software Items

Software architectural design involves structuring the medical device software into an architecture of software items and software interfaces. The software items may be purpose-built for the medical device or software of unknown provenance (SOUP).

Each software item within the software architecture should contain:

- A unique identifier/name.
- A description.
- An indication of whether the component is SOUP.
- The relationship to a parent item, if applicable.
- Relationships to interfacing components, through defined software interfaces.
- Relationships to external systems, through defined software interfaces, if applicable.
- A software safety classification.
- A rationale for the software safety classification (if different from the parent item).

### Expected Content: Architectural Design - Decomposition

It is required to decompose the software architecture into multiple levels of decomposed software items, with the lowest level of granularity denoted as software units. The software architectural design must also document the granular software interfaces between the decomposed software items/units, and interfaces between decomposed software items/units and external systems.

### Expected Content: Architectural Design - Interfaces

The software design must clearly define and document all interfaces, including both internal (between software items) and external (with other products, systems, or users) connections.

For robust security of software interfaces, IEC 81001-5-1 expects that the software design should:

- Identify whether each interface is internal, external, or both.
- Assess security implications for each external interface within the system's security context.
- Define potential users, the assets accessible through the interface, and any trust boundaries crossed.
- Document security assumptions, constraints, and relevant threats affecting interface use.
- Specify roles, privileges, and access controls required for interface operation.
- Describe security controls and validation mechanisms (e.g., input validation, error handling) that protect the interface and related assets.
- Identify any third-party software involved in implementing the interface and its security capabilities.
- Provide usage documentation for external interfaces.
- Indicate how the chosen design mitigates identified threats from the system's threat model.

### Expected Content: Architectural Design - SOUP

For software items identified as SOUP, it is required to specify the requirements that the SOUP must satisfy to ensure proper operation within the medical device software.

SOUP requirements should include:

- Functional requirements
- Performance requirements
- System hardware requirements
- System software requirements

The documentation should also include the identification and analysis of published SOUP anomaly lists, and the contribution of any known anomalies or vulnerabilities to hazardous situations.

### Expected Content: Architectural Design - Software Units

Each software unit within the software architecture should contain:

- A unique identifier/name.
- A description.
- The relationship to a parent item, if applicable.
- Relationships to interfacing components, through defined software interfaces.
- Relationships to external systems, through defined software interfaces, if applicable.
- A software safety classification.
- A rationale for the software safety classification (if different from the parent item).

### Expected Content: Secure Software Architecture & Design Principles

IEC 81001-5-1 Section 5.3 and 5.4 highlights the importance of secure software architecture & design principles. The documented software architecture and detailed design should reflect the consideration of best practices, such as:

- A defence-in-depth software architecture
- The identification and protection of trust boundaries
- Principle of least privilege
- Attack surface reduction
- Using proven secure items/design
- Secure design patterns
- Secure interface design
- Run-time validation of inputs
- Effective segregation of software units

### Expected Content: Architectural Design - Traceability

The following documentation traceability is required to verify the completeness of the software architecture and design:

- The traceability of each software requirement to the items/units that implement it.
- The traceability of each software unit to the software unit verification activities and test specification(s) that verify the unit's implementation.
- The traceability of each software item to the software item integration verification activities and test specification(s) that verify the integration of the software item.

Architectural diagrams may also be provided to illustrate the structure of the software architecture and the relationships between the components, internal and external to the software system.

### Expected Content: Architectural Design - Review

Evidence of review and acceptance of the software architecture and software detailed design is required. This confirms that the manufacturer has ensured that:

- The software architecture implements all software requirements.
- The software architecture has defined adequate interfaces for all software items/units and interfaces with the necessary external software/hardware systems.
- The software architecture has adequately documented SOUP components and intended operation.
- The software detailed design has decomposed the software architecture correctly and is free from contradiction.

### Expected Content: Contribution of Software to Risk

When conducting software architectural design and software detailed design, it is important to consider the implications that design choices may have on the risk profile of the medical device.

Risk estimation, evaluation, and control activities should be conducted to cover any risks identified by software architecture and detailed design choices. These risks should include cybersecurity-related risks such as assets, threats, vulnerabilities, threat sources and attack vectors. If these risks are not deemed acceptable, the software architecture and detailed design should be refactored to remove any unacceptable risk.

Software risk management activities should be conducted accordingly, and the following risk considerations should be documented:

- The contribution of technology choice to hazardous situations.
- The contribution of a software item, software interface or software unit to hazardous situations and the possible root causes of this.
- The identification and analysis of published SOUP anomaly lists, and the contribution of any known anomalies or vulnerabilities to hazardous situations.
- The contribution of the SOUP to hazardous situations and the possible causes of this.

### Expected Class C Content: Segregation of Software Items

For the development of software with a "Class C" software safety classification, it is required to identify any segregation of software items needed to mitigate a safety risk and provide a rationale for how this segregation achieves adequate safety.

### Expected Class C Content: Detailed Design - Software Units/Interfaces

For Class C software, detailed design documentation is required for individual software units and interfaces. This detailed design includes specific details to facilitate the correct implementation of the software units and interfaces.

## More Resources

- [Blog: How to structure and document your SaMD software architecture for efficient certification](https://scr.lt/td7r6j5s)
- [Blog: A recipe for good SOUP: Part 1 -- Defining external dependencies in SaMD](https://scr.lt/soupblog1)
- [Blog: A recipe for good SOUP: Part 2 -- Ensuring safety & performance in SaMD](https://scr.lt/soupblog2)
- [Blog: A recipe for good SOUP: Part 3 -- Large Language Models as SOUP](https://scr.lt/soupblog1)
