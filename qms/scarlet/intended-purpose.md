# Intended Purpose

Learn how to craft a meaningful intended purpose statement that can be evidenced and sets the foundation for a strong technical file.

The intended purpose defines what your device does, who it serves and its scope. It is a critical piece of the technical file that impacts many elements, including your evidence generation activities.

Clinical benefit claims are derived from the intended purpose and these form the basis of the objectives for a clinical evaluation. This guide will help to focus your intended purpose statement, giving clarity to your downstream activities.

## Minimum Requirements

An intended purpose statement should define the following:

- **Clinical condition** - Include the stage of the condition where relevant with sufficient detail so that any claims made can be evidenced, either with existing clinical data or data that will be generated through planned activities.
- **Patient population** - Specify age, clinical characteristics (e.g. health status, disease subtype) and any contraindications to use.
- **Intended users** - Encompasses both the clinicians that may engage with the device, with details of their specialist area where relevant, and the users that stand to benefit.
- **Clinical use context** - Define how the device is used within the healthcare provider's clinical workflow, including where in the patient journey the device is used and what actions are taken based on its output.

## Common Pitfalls

We often raise queries for the intended purpose statement and aim to address these early given the implications for downstream documentation. Avoid these pitfalls for a smoother regulatory journey:

> **Warning:** Manufacturers often try to play it safe with the wording for their intended purpose: the classic *"this product does **not** replace clinical judgement"* line, hedged user groups, or blanket caveats.
>
> Ironically, ambiguity only makes your regulatory life more difficult, increasing both the evidence generation and risk burden.
>
> Our heart sinks when we read an intended purpose statement like this:
>
> *"This tool assists clinicians in assessing patient risk for pulmonary embolism and should be used alongside clinical judgement. Not for paediatric use."*
>
> Another issue that we encounter is inconsistency between documents. For example, the intended purpose in the *Instructions for use* may indicate a different clinical workflow to the statement featured in the *Clinical evaluation plan*.

## Desired Submission Format

We are not too prescriptive about how things are presented, we love technical documentation in all its guises! However, adhering to these tips would delight us:

- Provide the information in text files.
- Ensure each expected device information item is clearly identifiable and searchable.
- Ensure consistency across documentation - key Device information elements (e.g. intended purpose statement) will feature in multiple places throughout the file.

## Deep Dive

### How to Define the Clinical Condition

With some devices, it is sufficient to simply name the condition, such as a continuous glucose monitor that aims to achieve target HbA1c within six months for Type 2 diabetics. Easy.

In other scenarios, you may need to specify the severity of the condition or the stage that is targeted, for example a convolutional neural network that analyses mammography images to detect *early* breast cancer.

It is useful to include information on both the prevalence of the disease and its trajectory in your technical file. This is not part of the intended purpose statement but should feature in the broader document.

Epidemiological data should be used, where possible, to indicate prevalence. This informs risk-benefit evaluation and justifies device utility. The natural course of the disease also informs subsequent risk-benefit analyses, providing details on clinical outcomes in the absence of your device.

### How to Define the Patient Population

Defining the patient population is critical for demonstrating conformity with general safety and performance requirements, supporting clinical evaluation, and identifying usability and risk factors.

The clinical characteristics are key here, including the condition, its stage (if relevant) and relevant risk factors. Take this example:

> *A smartphone application that supports the treatment of grade I essential hypertension for adults who are typically over 40-65 years old with mild obesity (BMI 25-30).*

Other relevant population traits include educational level or digital literacy, language requirements and access to healthcare infrastructure.

### Determining the Intended Users

In the case of a direct to consumer solution, the intended users will exclusively be the patient population who stand to benefit from your device.

Most solutions that we encounter involve a clinician as part of the workflow in which they are deployed. The roles of these individuals, qualifications and training required to use the device are relevant here.

Who uses the device and how its outputs are actioned will have an impact on its associated risk. For example, a device used to screen for malignant skin lesions will need to have a higher specificity (ability to rule out a malignant lesion) if utilised in primary care than if used by dermatologists.

### Detailing the Clinical Use Context

The clinical workflow has commercial as well as regulatory significance because a device could confer patient benefit but introduce friction that prohibits adoption. Outlining workflow will help to design data generation activities.

Here we have an example of an intended purpose statement with a clearly defined workflow, along with details of the device output and how this will be used:

> *For use by emergency department physicians to identify adults (>18 years) with suspected acute pulmonary embolism. The software analyses chest CT angiography images using a convolutional neural network and produces a binary 'high/low probability' output to prioritise cases for review; it is intended to support triage decisions in ED workflow and has been validated on adults 18-85 years with contrast-enhanced CT. Contraindicated for paediatric patients and non-contrast CTs.*

The manufacturer has proposed that the emergency department is where their device will add the most value in its current state. From this they plan to perform a prospective cohort study comparing a group that has used the device to support diagnosis of pulmonary embolism with the standard workflow.

The aim of this study is to evaluate the clinical utility of their device in prioritising cases for pulmonary embolism as part of their post-market clinical follow-up.

This does not mean that the device is limited to use in the emergency department and data from the proposed clinical utility study may support adoption in other areas, requiring an expansion of the intended purpose scope.
