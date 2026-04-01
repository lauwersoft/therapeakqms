# Software Safety Classification

A determination of your software safety classification, as mandated by IEC 62304 Clause 4.3.

The determination of software safety classification is a process of categorising medical device software systems or components, based on the severity of potential harm that could result from a failure of the software.

A classification of your software as *Class A*, *Class B* or *Class C* is determined by identifying and estimating risks that could arise if the software failure occurs.

The classification directly determines the rigour and extent of software life-cycle activities required and the technical documentation to be generated from these activities.

A determination of software safety classification is expected to be included within your technical documentation, typically within your software development plan or risk management file.

> See the Deep dive section for strategic considerations when determining software safety classification.

## Minimum Requirements

When submitting your technical documentation, the following are the minimum requirements for this topic:

- **A determination of software safety classification**: A statement, typically within your software development plan or risk management file, that indicates if your software is deemed as ***Class A, Class B** or **Class C***.
- **A supporting rationale**: A statement that provides rationale to support your determination.
- **Consistency with your risk management file**: The software safety classification should be consistent with the estimations of risks resulting from software failures identified in your risk management file.

## Common Pitfalls

The following are common queries that are raised by Scarlet during assessment:

> **Warning:**
>
> - **No software safety classification provided**: This determination is considered mandatory for SaMD submissions. A query will be raised during assessment if it is not provided.
> - **Software safety classification is incongruent with risk management file**: It is common for manufacturers to aim for a lower software safety classification, to minimise the technical documentation required. However, Scarlet will cross-reference your software safety classification with the risks documented in your risk management file. For example, if you claim to be Class A, but have documented risks that result from software failure, are not mitigated by risks external to the software and can result in injury, then Scarlet will request clarification on this determination.
> - **Insufficient technical documentation provided for determined software safety classification**: *Class B* and *Class C* require deeper software architecture & design documentation and deeper software verification documentation. The level of technical documentation provided must be consistent with the software safety classification.

## Deep Dive

### Strategic Consideration: How to Determine Software Safety Classification

The process for determining software safety classification:

1. Consider if hazardous situations can result from the failure of the software. If not, the software is **Class A**.
2. If a hazardous situation can result from the failure of the software, consider if the hazardous situation(s) can be mitigated by risk controls **external** to the software.
3. If risk controls **external** to the software can effectively mitigate the hazardous situations, the software is **Class A**.
4. If risk controls **external** to the software are not able to effectively mitigate the hazardous situations, consider the severity of injury that could result from the failure of the hazardous situations.
5. If the severity of injury that could result from the failure of the hazardous situations is **non-serious**, the software is **Class B**.
6. If the severity of injury that could result from the failure of the hazardous situations is **serious or fatal**, the software is **Class C**.

> **Warning:** It is important that only risk controls **external** to the software are considered. Risk controls **internal** to the software may be rendered ineffective by the failure of the software. Therefore, they cannot be relied on to mitigate a hazardous situation arising from a software failure and should not be considered to determine the software safety classification.

### Strategic Consideration: Segregation of Risk

> Applying segregation of risk within your software architecture can be used strategically to reduce the depth of software development activities, and associated technical documentation, for lower risk software components.

- Higher class software requires a greater depth of software development activities and the generation of more extensive technical documentation
- IEC 62304 Annex B.4.3 promotes the partitioning of software items in your software architectural design based on risk, which can reduce the burden
- If software items can be logically segregated by any mechanism that prevents one software item from negatively affecting the other, independent software safety classification can be applied within the partitioned software architecture
- You can either:
  - Apply a single "worst-case" software safety classification to your entire software system and conduct a greater depth of software development activities
  - Invest time to implement risk segregation in your software architecture and benefit from a reduced scope of software development activities for lower risk software items

### Expected Documentation: Dependencies on Software Safety Classification

As mentioned above, the software safety classification directly determines the rigour and extent of software life-cycle activities required and the technical documentation to be generated from these activities.

To see a comprehensive list of the expected documentation dependent on software safety classification, consult *IEC 62304, Table A.1 -- Summary of requirements by software safety class*. Below is a high-level summary of the impact of the software safety classification on the expected documentation:

| Software Safety Classification | Expected Technical Documentation |
|---|---|
| **Class A** | All activities under IEC 82304-1 (i.e. use requirements, accompanying documentation & validation); Software planning activities (i.e. development, maintenance, risk, configuration & problem resolution); Software requirements and requirements verification; Software implementation; Identification of SOUP; Software release |
| **Class B** | All activities required for **Class A** software; Additional planning considerations; Software architectural design and verification; Deeper software risk management |
| **Class C** | All activities required for **Class B** software; Planning of software development standards, methods and tools; Software architectural design and detailed design |

## More Resources

- [Blog: The ABCs of software safety classification: Part 1 -- A closer look at IEC 62304 4.3](https://scr.lt/abcsofssc1)
- [Blog: The ABCs of software safety classification: Part 2 -- Bridging risk, rigour, and reality with ISO 14971 & IEC 62304](https://scr.lt/soupblog2)
- [Blog: The ABCs of software safety classification: Part 3 -- Classification in practice](https://scr.lt/soupblog1)
