# Use Scenarios & Use Errors

> Documentation that identifies and describes hazard-related use scenarios, use errors, and abnormal uses that arise from users' interactions with the medical device.

IEC 62366-1 requires that users' interactions with the medical device are considered in risk analysis activities. This includes the systematic identification and documentation of hazard-related use scenarios, use errors, and abnormal uses. **Hazard-related use scenarios** describe situations in which normal or reasonably foreseeable use of the device could lead to exposure to a hazard. **Use errors** are user actions or inactions that differ from what was intended or expected and may result in harm. **Abnormal uses** are intentional actions or inactions that are counter to normal or reasonably foreseeable use and are beyond the manufacturer's reasonable means of risk control.

Regulatory expectations under IEC 62366-1 and ISO 14971 require manufacturers to comprehensively identify and document these usability-related risk elements as part of the risk analysis process. This analysis forms the foundation for subsequent risk control measures and usability engineering activities.

## Minimum Requirements

When submitting your technical documentation, the following are the minimum requirements for this topic:

- **Hazard-related use scenarios**: The documentation must identify and describe all situations in which normal or reasonably foreseeable use of the device could lead to exposure to a hazard, including all use scenarios by all users (clinical and non-clinical).
- **Use errors and abnormal uses**: The documentation must identify use errors (user actions or inactions that differ from intended or expected use and may result in harm) and abnormal uses (intentional actions counter to normal use beyond reasonable risk control), with clear justification for categorisation.
- **Traceability to hazardous situations**: The documentation must identify what hazardous situations and harms may result from the identified use errors, establishing clear traceability from use scenarios through use errors to potential harms.

See the [deep dive section](#deep-dive) for more details on the expected content and examples of these usability-related risk elements.

## Common Pitfalls

The following are common queries that are raised by Scarlet during assessment:

- **Insufficient use scenario detail**: Use scenarios that lack specific details about the sequence of tasks performed by the user, the specific use environment, or the medical device's resulting response.
- **Missing or inadequate use error identification**: Documentation that fails to identify use errors that could arise during use scenarios, or that does not clearly distinguish between use errors and abnormal uses.
- **Lack of justification for categorisation**: Documentation that categorises misuse into use errors and abnormal uses without clear justification for the categorisation.
- **Incomplete traceability to harms**: Documentation that fails to identify what hazardous situations and harms may result from identified use errors, breaking the traceability chain from use scenarios to potential patient harm.

## Desired Submission Format

Scarlet specifies desired submission formats for certain technical documentation to facilitate easier assessment. It is recommended that use scenarios, use errors and abnormal uses are defined as unique items in tabular format, and documented within the risk management file.

## Deep Dive

### Usability-Related Risk Elements

IEC 62366-1 dictates that the users' interactions with the medical device are considered in risk analysis activities. This includes the identification of:

- **Hazard-related use scenarios**: Any situations in which normal or reasonably foreseeable use of the device could lead to exposure to a hazard.
- **Use errors**: Any user actions or inactions that differ from what was intended or expected and may result in harm.
- **Abnormal uses**: Any intentional action or inaction that is counter to normal or reasonably foreseeable use and is beyond the manufacturer's reasonable means of risk control.

The following are guidelines for ensuring comprehensive risk analysis of the device's use and creating traceability between these records:

- Consider all use scenarios by all users, including non-clinical scenarios such as software installation, configuration and maintenance.
- For each scenario, describe the specific sequence of tasks performed by the user in a specific use environment and the medical device's resulting response.
- Consider what use errors, abnormal uses, hazards, and hazardous situations could arise during the specific sequence of tasks.
- Consider what hazardous situations and harms may result from the use errors.
- Carefully review and justify any categorisation of misuse into use errors and abnormal uses.

### Examples

**Use Scenarios:**

- A technician installs the software on the approved hardware and configures the software to connect to the care setting's EHR.
- The caregiver connects the patient to a heart rate monitor and configures the software to monitor the heart rate continuously. The caregiver sets upper and lower heart rate limits and enables the alert function.
- A clinician inspects the software's decision-support output and makes a clinical diagnosis based on the software recommendations, rationale, and associated data.

**Use Errors:**

- Under time pressure, the clinician ignores warning messages and proceeds with the examination.
- The clinician accepts the software's decision support statements without thoroughly analysing the rationale or associated data.
- The patient misinterprets the dosage units recommended by the software.

**Abnormal Use:**

- The clinician disconnects the hardware speaker to reduce alert fatigue.
- The clinician knowingly uses the medical device on a patient with contraindications.
