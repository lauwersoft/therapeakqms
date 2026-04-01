# Splitting Devices and Multiple Device Assessment

## When Is It a Good Idea to Split My Device into Multiple Devices?

### Rationale

Although it may seem counterintuitive, splitting a device into one or more can make your regulatory journey easier in certain circumstances. This is likely to be the case when the intended purpose is broad, capturing multiple conditions, user groups, and modes of action in a single device.

### Options

A broad intended purpose statement can lead to multiple, distinct, clinical benefit claims. Here you have two options:

1. Create one set of technical documents that evidences multiple claims across the clinical evaluation activities, with risks captured and evaluated in a single hazard log.
2. Create a set of technical documents for each device with focused claims, with separate clinical evaluation activities and risk management files.

### Clinical Evidence Required

Naturally, we recognise that the first approach seems more appealing. Managing a technical documentation for one device is a lot of work, why would I want to manage two? In practice, evidencing multiple claims in one or two studies is very difficult and requires a large sample size to ensure enough statistical power for different endpoints to be evaluated.

Having two separate files will help to distinguish the claims that are most closely aligned with your commercial goals, from those that can be evidenced later in the post-market phase. Risk identification and mitigation is easier when more constraints are applied to the device's purpose.

### Device Changes

Two separate files also helps to define technical boundaries. For example, it may be advantageous to separate the software-as-a-medical device functions from the common accessory platform on which the platform is deployed. Changes to the platform would require minimal updates to technical documentation which could be addressed during a surveillance audit.

When you are thinking about documenting new functionality that does have a medical function, the modular approach adds further value still. If the new functionality expands the scope of an existing device, it can often be handled through a tightly scoped change request. If the change constitutes a new device, this can be documented and assessed without destabilising the certification of existing ones.

### Deciding Your Approach

A good place to start is to consider each feature in isolation: what does it do, who does it serve, and does it have a medical purpose? From this perspective, it becomes clear that a product may actually be composed of multiple devices.

For example:

- An algorithm that generates triage recommendations for one patient group may constitute a device.
- A second algorithm designed for another patient group could be a separate device.
- Supporting software modules may instead fall into the category of accessories or non-certifiable features.

Please reach out to the team if you think that you may benefit from splitting your device into multiple devices. We can help you navigate the complexity.

You may decide that your intended purpose is not overly broad in scope and that the details pertaining to the different modules of your device can be captured in a single set of technical documents. In this scenario, traceability is key.

With the risk management file for example, risks can be separated or tagged based on their medical module. These tags should be consistent throughout the file, from planning to verification and validation activities.

Below we have the example of two sets of hazardous sequences: one for a clinical coding module and the other for a transcription module pertaining to an ambient scribe. The separation of these two modules should be reflected in the usability activities that verify the risk control measures implemented for each risk identified.

**Clinical coding module hazardous sequences:**

| Risk ID | Hazard | Hazardous Situation | Harm |
|---|---|---|---|
| COD-01 | Incorrect AI-generated clinical code (e.g. wrong ICD-10/SNOMED code). | AI scribe assigns an incorrect diagnostic or procedure code to a clinical encounter without clinician detection prior to submission. | Patient receives incorrect treatment, such as inappropriate medication, or misses vital treatment. |
| COD-02 | AI coding bias / systematic under-coding or up-coding. | AI model exhibits systematic bias (e.g. related to patient demographics or specialty) leading to consistent miscoding across a patient population. | Population-level harm: inequitable care access, such as a demographic group receiving delays in cancer follow-up services. |
| COD-03 | Failure to capture a clinically significant diagnosis or procedure in coded output. | AI scribe omits a clinically relevant condition or procedure from the coded summary (e.g. a comorbidity or secondary diagnosis) that the clinician does not subsequently add. | Incomplete medical record; missed comorbidity affects care coordination, medication decisions, or chronic disease management. |

**Transcription module hazardous sequences:**

| Risk ID | Hazard | Hazardous Situation | Harm |
|---|---|---|---|
| TRN-01 | Speech recognition error producing clinically significant transcription inaccuracy. | Ambient microphone captures clinician speech but AI transcribes a word or phrase incorrectly (e.g. 'no dyspnea' transcribed as 'dyspnea'; incorrect drug name or dose), and the error is not detected before the note is finalised. | Clinician or subsequent provider acts on incorrect clinical note; potential for wrong medication dose. |
| TRN-02 | Inadvertent capture and transcription of third-party speech (e.g. patient statements misattributed as clinician documentation). | Ambient microphone records patient or accompanying person utterances that are incorporated into the clinical note as if they were clinician-documented observations or clinical decisions. | Inaccurate clinical documentation; document indicates that patient consented for an inappropriate treatment option that their family member was advocating. |
| TRN-03 | Ambient microphone failure or connectivity dropout causing incomplete transcription. | Technical failure (hardware fault, network interruption, software crash) results in partial or complete loss of the transcription for a clinical encounter without adequate warning to the clinician. | Clinician unaware that documentation is incomplete; clinical note submitted with missing information, leading to key investigations not being requested and subsequently a missed diagnosis. |

### Conclusion

When a device's intended purpose spans multiple conditions, user groups, or modes of action, splitting it into separate devices may simplify the regulatory pathway. A modular approach can reduce the clinical evidence burden, narrow the scope of risk management, and provide clearer technical boundaries for managing future changes -- though it requires additional upfront effort. Where a single set of technical documentation is maintained, rigorous traceability throughout risk management and clinical evaluation activities is essential.

## Assessment of Multiple Devices with Scarlet

If you would like certification of multiple medical devices, the process for that with Scarlet varies depending on two factors:

1. Whether this is your initial certification with Scarlet, or whether you are adding new devices.
2. The risk classification of your devices.

### Initial Certification

If this is your initial certification with Scarlet, you will need to apply for:

- A Quality Management System (QMS) assessment certificate covering all the devices you want to put on the market; and
- A technical documentation assessment certificate for each class III device you want to put on the market (each class III device requires one application).

A technical documentation assessment will be conducted for each class III device, and each will result in the issuance of a technical documentation assessment certificate.

If there are devices of other classes, a sampling plan will be drafted for the rest of the devices.

Before the final review and decision-making regarding the issuance of the QMS certificate:

- One technical documentation assessment will be done on at least one representative device for each device group for class IIb devices.
- At least one representative device for class IIa devices will be assessed.

All technical documentation assessments shall be finished before issuing the QMS certificate covering the devices, as this is an input for the final review and decision-making.

### Adding New Devices

**Manufacturer requests change of existing QMS certificate**

If you (the device manufacturer) have an existing QMS certificate with Scarlet, you must inform Scarlet of a change in devices covered or substantial QMS change.

This will trigger the following actions from Scarlet:

1. We will ask for details so we can check that the devices are within the current device range covered by the certificate (defined as all categories and all groups of devices in the certificate).
2. If the device is not in the current device range, then a technical conformity assessment will need to be done the same way this would need to be done during an initial certification.
3. If the device is in the defined device range, the device will be added to the sampling plan.
4. We will determine the need for additional audits and verify whether after those changes the quality management system still meets the relevant requirements.
5. We will communicate the output of the above two steps.
6. If required, we will perform the additional audit and/or technical documentation assessment and will communicate the conclusions with the manufacturer.

If the new devices contain class III devices, the manufacturer needs to apply for a technical documentation assessment certificate for each class III device, and each assessment will result in the issuance of a technical documentation assessment certificate.

Scarlet will update the sampling plan associated with this QMS certificate (see Sampling Plans section for more information).

Finally, Scarlet will supplement the existing QMS certificate to add the new devices to the certificate coverage.

No new application is required to add class IIb and class IIa devices!

**Manufacturer requests a new conformity assessment process**

If you request a new conformity assessment process, you will need to submit for assessment as if it was an initial certification, even if an existing certificate exists between you and Scarlet.

### Sampling Plans for Multiple Devices

A sampling plan is a plan for assessing different devices during the validity period of a QMS certificate. Scarlet is required to have a sampling plan linked to each QMS certificate it issues as soon as the certificate covers more than one device.

#### EU MDR Regulatory Guidance

EU MDR regulatory guidance states that notified bodies (i.e. Scarlet) must have a sampling plan that outlines how they will perform a technical documentation assessment of at least 15% of devices under each risk category and EMDN code that a manufacturer produces over the validity period of a certificate.

**Sampling plan example:** If a certificate covered 100 class IIa devices, 100 Class IIb devices with EMDN code X and 100 class IIb with EMDN code Y, then at the end of the certificate's validity period, Scarlet should have performed at least:

- 15 assessments of different class IIa devices
- 15 assessments of different devices within EMDN code X
- 15 assessments of different devices within EMDN code Y

#### UK MDR Regulatory Guidance

The UK MDR asks approved bodies (i.e. Scarlet) to assess a representative sample of each "generic device group". A "generic device group" is defined as "a set of devices having the same or similar intended uses or commonality of technology allowing them to be classified in a generic manner not reflecting specific characteristics".

#### Initial Certification Sampling

When Scarlet issues a QMS certificate, it will also create a sampling plan that outlines how it will properly sample at least 15% of devices that are covered by the certificate at the time that was issued. To determine which devices are assessed, Scarlet uses a sampling heuristic that is in line with regulatory guidance.

#### Changes in the Sampling Plan

**Adding devices to an existing risk category or group**

If the device being added is in the same group or category as devices on the existing QMS certificate, the device is added to the list in the relevant category or device group. The sampling plan is then updated for that group or category.

During an update, the current planned assessment shall not be changed, unless a special case requires it or if this change is in favour of not already assessed devices. In this context a special case could constitute when vigilance information, or information provided to Scarlet, requires the assessment of a particular device. Another example of a special case would be if Scarlet considers the technology to be especially novel. In this case the device shall be sampled preferably and can be added to the sampling plan.

When updating a group or category, Scarlet will ensure:

- At least 15% of the devices within this group or category are to be assessed before the certificate expires.
- Each surveillance audit assesses at least one technical document. If there are only already assessed devices due to be assessed during the next surveillance audit, the assessment plan will be updated to prioritise assessing devices that are newly submitted during the surveillance audits. In the case of only already assessed devices being available at the time of surveillance, Scarlet will reassess a device that has already undergone a technical documentation assessment. In this case, we will focus on post-market surveillance data and anything related to re-validation and maintenance.

**Adding devices to a new risk category or group**

If a new device or devices are not in a category or group for which there is an existing sampling plan, a representative device will be assessed immediately. In addition, Scarlet will update the sampling plan for a manufacturer by doing the following:

- The device group or category will be added to the sampling plan.
- The device will be added to the newly created category or group.
- Scarlet will plan the assessment of this device group or category before the expiration of the certificate related to this sampling plan.

#### Example Sampling Heuristic

To ensure that sampling is performed on a representative sample of devices and in order to uphold our obligations as a notified body, Scarlet will follow a specific sampling heuristic. An example of a heuristic that we commonly use is below.

When choosing samples, the rules below shall be followed (in order):

1. Devices containing novel technology shall be assessed preferably (as opposed to devices with well-known technology). Here devices containing Artificial Intelligence elements should be prioritised.
2. Devices with higher inherent risk shall be assessed preferably.
3. Devices that have not been assessed already shall be prioritised.
4. If this heuristic yields multiple results, the devices to be assessed shall be chosen at random in the results.
