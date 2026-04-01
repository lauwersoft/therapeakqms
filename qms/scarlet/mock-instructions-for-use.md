# Instructions for Use

> Disclaimer (fictional example - beta release): This document is part of a fictional example technical file provided by Scarlet for educational purposes only. Manufacturers remain responsible for generating evidence appropriate to their medical device.

## Document Control Information

| Field | Value |
|-------|-------|
| Title | Instructions for Use |
| Document ID | SAI-EF-IFU-001 |
| Version | 1.0 |
| Publication date | 2026-02-13 |
| Author(s) | Martin Hauer |
| Approver(s) | Dr. Livia Tan |

## 1. Abbreviations

| Abbreviation | Definition |
|-------------|------------|
| AES | Advanced Encryption Standard |
| APNs | Apple Push Notification service |
| CDD | Cox-Dewar Dermatitis |
| CE | Conformite Europeenne |
| CER | Clinical Evaluation Report |
| DIAU | Dermal Inflammation Assessment Unit |
| eIFU | Electronic Instructions for Use |
| EU MDR | European Union Medical Device Regulation (EU) 2017/745 |
| EUDAMED | European Database on Medical Devices |
| FCM | Firebase Cloud Messaging |
| GDPR | General Data Protection Regulation |
| HCP | Healthcare Professional |
| IDM | Inflammatory Decompensation Marker |
| IFU | Instructions for Use |
| MFA | Multi-Factor Authentication |
| ML | Machine Learning |
| PII | Personally Identifiable Information |
| PMCF | Post-Market Clinical Follow-Up |
| PMS | Post-Market Surveillance |
| RBAC | Role-Based Access Control |
| RMSE | Root Mean Square Error |
| SaMD | Software as a Medical Device |
| SD | Standard Deviation |
| SSCP | Summary of Safety and Clinical Performance |
| TLS | Transport Layer Security |
| UDI-DI | Unique Device Identification - Device Identifier |

## 2. General Information

| Field | Detail |
|-------|--------|
| Device Name | EpiFlare |
| Version | 2.0 |
| Basic UDI-DI | (01)00000000000000 |
| Device Classification | Class IIa (EU MDR 2017/745, Annex VIII, Rule 11) |
| Date of Issue | 2026-03-13 |
| eIFU Website | https://www.skintelligentai.com/epiflare/ifu |

EpiFlare is a software-based medical device (Software as a Medical Device, SaMD) designed to support longitudinal monitoring of inflammatory skin disease activity in adult patients with a confirmed diagnosis of Cox-Dewar Dermatitis (CDD). These instructions for use cover EpiFlare Version 2.0, including the patient mobile application, the clinician web portal, and the backend processing module.

## 3. Device and Manufacturer Details

### 3.1 Manufacturer

| Field | Detail |
|-------|--------|
| Legal Manufacturer | SkintelligentAI Ltd |
| Registered Trade Name | SkintelligentAI |
| Registered Address | 1 Lesion Lane, London, EC1V 2NX, England |
| Contact Person | Dr. Livia Tan, Chief operating officer |
| Email | regulatory@skintelligentai.com |
| Phone | +44 (0)1234 567890 |
| Website | https://www.skintelligentai.com |
| Company Structure | SkintelligentAI Ltd is a wholly owned subsidiary of SkintelligentAI Holdings plc. SkintelligentAI Ltd is the legal manufacturer of EpiFlare. |
| Registered Trade Marks | EpiFlare -- see https://www.ipo.gov.uk for public record |
| SRN (EUDAMED) | SRN-XXXXXXXX-XXXX |

### 3.2 Authorised Representative (EU)

Under EU MDR, as the manufacturer is based outside the European Union, the following authorised representative has been appointed:

| Field | Detail |
|-------|--------|
| Name | MedRep Europe GmbH |
| Address | Friedrichstrasse 42, 10117 Berlin, Germany |
| Contact | Dr. Anna Bergmann, Head of regulatory affairs & PRRC, a.bergmann@medrep-europe.de |

## 4. Clinical Performance

### 4.1 Clinical Benefits

EpiFlare provides clinical benefit by supporting:

- Objective monitoring of disease activity in adult patients with Cox-Dewar Dermatitis (CDD) through quantitative IDM values derived from serial skin images.
- Identification of patients at increased risk of disease flare, using predefined IDM thresholds and trend-based changes to prompt timely clinical review.
- Longitudinal assessment of disease trends over time, enabling clinicians to detect changes in disease activity that may not be captured through episodic clinic-based assessment alone.

### 4.2 Performance Characteristics

Clinical performance has been evaluated through two pre-market evidence generation activities:

**Measurement accuracy:**

| Metric | Image Database Study (N=500) | Prospective Study (N=220) |
|--------|------------------------------|---------------------------|
| RMSE (IDM units) | 1.8 | 2.6 |
| Agreement within +/-2 IDM units | 78.4% | 62.3% |
| Mean bias (IDM units) | +0.4 | +0.8 |
| 95% Limits of agreement | -3.3 to +4.1 | -3.9 to +5.5 |

**Classification performance for high-risk detection (IDM >99):**

| Metric | Image Database Study (N=500) | Prospective Study (N=220) |
|--------|------------------------------|---------------------------|
| Sensitivity | 90.0% (54/60) | 85.7% (30/35) |
| Specificity | 97.0% (427/440) | 93.0% (172/185) |

**Validation dataset characteristics:**

- 720 total paired observations from clinical investigations.
- Reference standard: Dermal Inflammation Assessment Unit (DIAU).
- Patient population: Adult patients (>=18 years) with confirmed CDD.
- Greater representation of moderate to higher disease activity states.
- Both studies conducted with EpiFlare Version 2.0.
- Performance under fully real-world home-use conditions is being further evaluated through ongoing Post-Market Clinical Follow-Up (PMCF) activities.

### 4.3 IDM Value Interpretation

| IDM Range | Clinical Interpretation |
|-----------|------------------------|
| <50 | Generally not indicative of active dermal inflammation |
| 50-150 | Intermediate; repeat measurement and clinical correlation recommended |
| >100 | Associated with substantially increased risk of CDD flare |
| >300 (sustained) | Associated with increased risk of permanent dermal scarring |
| >1000 | Consider escalation of therapy (including immunomodulatory treatment) depending on clinical context |

These ranges are intended to support contextual clinical interpretation and highlight thresholds at which closer monitoring or clinical review may be warranted. They do not represent absolute diagnostic cut-offs and require clinician judgement.

## 5. Safety Information

### 5.1 Contraindications

EpiFlare must NOT be used in the following circumstances:

- Patients without a confirmed diagnosis of Cox-Dewar Dermatitis (CDD).
- Patients without supervision and responsibility of a qualified healthcare professional.
- Paediatric patients (under 18 years of age).

### 5.2 Warnings and Precautions

**Warnings:**

- EpiFlare does not provide a diagnosis and must not be used as the sole basis for treatment decisions.
- Image quality is essential for reliable outputs.
- Results must be interpreted by a qualified healthcare professional in the context of the patient's overall clinical presentation.

**Precautions:**

- Ensure the smartphone meets the minimum hardware and software requirements before use (see Section 8).
- Do not use EpiFlare in environments with extreme lighting conditions (direct sunlight or very dark rooms).
- Ensure a stable internet connection is available for image upload and alert delivery.
- Patients should capture images in a consistent manner (same body area, similar distance, adequate lighting) to support longitudinal comparison.
- Healthcare professionals should configure patient-specific alert thresholds based on the individual patient's clinical history and risk profile.

### 5.3 Residual Risks

The following significant residual risks have been identified through the risk management process (refer to SAI-EF-RMF-001 and SAI-EF-RMR-001):

| Risk | Description | Residual Risk Level |
|------|-------------|---------------------|
| Incorrect interpretation of results | Over-reliance on IDM values without clinical context could lead to delayed or unnecessary intervention. | Low (mitigated by mandatory HCP review) |
| Performance limitations under poor image conditions | Variability in image quality may affect IDM accuracy, particularly in home-use settings. | Low (mitigated by image quality validation and user guidance) |
| False positive alerts | May result in unnecessary clinical review. | Low (specificity >=93.0% in real-world conditions) |
| False negative alerts | May result in delayed identification of disease flare. | Low (sensitivity >=85.7% in real-world conditions) |
| False positive IDM leading to unnecessary immunotherapy | In rare cases, inaccurate IDM values could contribute to inappropriate treatment decisions. | Residual risk requires benefit-risk justification; mitigated by multi-layered controls including mandatory HCP review |
| Data confidentiality breach | Unauthorised access to patient health data could cause psychological distress. | Low (mitigated by encryption, authentication, RBAC, and monitoring) |

### 5.4 Known Limitations, Deficiencies, and Adverse Effects

**Known limitations:**

- EpiFlare is intended for disease monitoring only, not for establishing an initial diagnosis of CDD.
- The device requires a smartphone with a camera meeting minimum resolution specifications and an active internet connection.
- Performance may be affected by non-standard skin appearances (e.g. tattoos, scarring from other conditions, or concurrent skin disorders in the region of interest).

**Device deficiencies:**

- Reduced agreement with reference standard under real-world conditions (62.3% within +/-2 IDM units vs. 78.4% under controlled conditions).
- Higher proportion of validation data from moderate-to-high disease activity; less data from very mild disease states.
- Long-term performance stability not yet fully characterised (addressed through PMCF activities).

**Adverse device effects:** EpiFlare is a non-invasive software medical device and does not produce direct physiological side effects. Potential adverse impacts are indirect:

- Unnecessary treatment or investigations due to false positive IDM values.
- Delayed treatment due to false negative IDM values or missed alerts.
- Patient anxiety related to false positive alerts.
- No serious device-related adverse events were identified during pre-market clinical investigations.

### 5.5 Disclaimers

- The IDM algorithm output is subject to error. Results are probabilistic and should not be used as a standalone diagnostic tool.
- EpiFlare is intended to supplement, not replace, routine clinical assessment by a qualified healthcare professional.
- The manufacturer does not accept responsibility for clinical decisions made without appropriate clinical review of IDM values and patient context.
- Regular scheduled check-ups with a healthcare professional remain essential regardless of EpiFlare monitoring results.

## 6. Training and Qualifications

### 6.1 Training Requirements

**For patients:**

- No formal training is required. Patients should review these Instructions for Use prior to first use.
- The mobile application provides on-screen instructions and real-time guidance for image capture.
- Patients should familiarise themselves with the image capture guidance, result interpretation screens, error messages, and escalation procedures described in Section 7.

**For healthcare professionals:**

- Healthcare professionals should review these Instructions for Use and the clinician portal user guide prior to first use.
- No additional formal training is required; contextual guidance is provided within the portal.
- Training resources are available at: https://www.skintelligentai.com/epiflare/training

### 6.2 Qualification Requirements

**For patients (secondary users):**

- Adult patients (>=18 years) with a confirmed diagnosis of CDD, enrolled by a qualified healthcare professional.
- Basic smartphone literacy (ability to open an application, take a photo, and follow on-screen instructions).

**For healthcare professionals (primary users):**

- Qualified healthcare professionals (dermatologists or primary care physicians) with clinical competence to interpret disease activity data in the context of CDD.
- Ability to operate a standard web browser and manage a web-based clinical application.

### 6.3 Consulting Healthcare Professionals

Patients should contact their healthcare professional if:

- Experiencing symptoms of a CDD flare, regardless of EpiFlare results.
- The device displays an error or is unable to complete analysis.
- Alert delivery to the healthcare professional fails (follow on-screen fallback instructions).
- There is any uncertainty about EpiFlare results or their clinical significance.
- Routine clinical consultations are due.

## 7. Operational Procedures

### 7.1 Setup and First Use

**Patient mobile application:**

1. Download the EpiFlare app from the Apple App Store (iOS) or Google Play Store (Android).
2. Open the app and create an account using the enrolment code provided by your healthcare professional.
3. Complete secure authentication setup (password and biometric/MFA options).
4. Review the on-screen introduction and image capture guidance.

**Clinician portal:**

1. Navigate to the EpiFlare clinician portal URL provided by your organisation's IT department.
2. Log in using provided credentials with multi-factor authentication (MFA).
3. Configure alert delivery preferences (in-app, email, and/or SMS).
4. Set default and patient-specific IDM alert thresholds as appropriate.

### 7.2 Routine Use - Image Capture (Patients)

1. Open the EpiFlare mobile app.
2. Navigate to the image capture screen.
3. Follow the on-screen instructions for positioning, distance, and lighting.
4. Capture and review the image of the affected skin area.
5. Submit the image for analysis.
6. Wait for processing (typically <30 seconds).
7. Review the result:
   - **No progression detected:** A disclaimer will be displayed. Contact your healthcare professional if you are experiencing symptoms.
   - **Progression detected (alert sent):** Your healthcare professional has been notified.
   - **Progression detected (notification failure):** Follow the on-screen fallback escalation instructions.
   - **Algorithm failure:** An error message will be displayed. Contact your healthcare professional if you are experiencing symptoms.

### 7.3 Routine Use - Alert Review (Healthcare Professionals)

1. Receive alert notification (via configured channel: in-app, email, or SMS).
2. Log in to the EpiFlare clinician portal.
3. Navigate to the alerts/notifications section and select the patient alert.
4. Review patient information, image analysis results, IDM value, and longitudinal trend data.
5. Acknowledge the disclaimer confirming that the tool is a decision-support aid.
6. Record your clinical assessment, diagnosis, and treatment plan and submit.

### 7.4 System Messages

| Message Type | Example | User Action |
|-------------|---------|-------------|
| Image quality error | "Image quality is insufficient. Please improve lighting, focus, and ensure the affected area is visible." | Re-capture image following the guidance provided |
| Algorithm processing error | "Analysis is currently unavailable. Your HCP has NOT been notified." | Contact HCP if symptomatic; retry later |
| Notification failure | "Progression was detected but your HCP could NOT be notified." | Follow on-screen fallback escalation instructions |
| No progression detected | "No progression detected. This result is subject to error." | Contact HCP if symptomatic |
| System maintenance | "EpiFlare is undergoing scheduled maintenance." | Wait for maintenance to complete; contact HCP if urgent |
| Critical security update | "A critical security update is available." | Update the application immediately |

### 7.5 Maintenance and Updates

- Mobile application updates: Delivered through the Apple App Store and Google Play Store. Critical security updates may restrict functionality until installed.
- Backend and portal updates: Deployed by the manufacturer via rolling deployment with minimal disruption (<5 minutes, with 24-hour advance notification).
- No user-performed hardware maintenance is required.
- System availability target: >=99.5% uptime.

### 7.6 Handling, Storage, and Decommissioning

EpiFlare is a software-only medical device. No physical handling or storage conditions apply.

**Decommissioning:**

- Mobile app: Log out, then uninstall the app from your smartphone. Cached data is removed upon uninstallation.
- Clinician portal: Log out and close the browser; no local data is stored. IT administrators may deactivate user accounts.
- Data erasure: To request deletion of personal data under GDPR Article 17, contact: privacy@skintelligentai.com. Patient data will otherwise be retained in accordance with applicable regulatory and data retention requirements.

### 7.7 Serious Incident Reporting

In accordance with EU MDR 2017/745, Article 87:

Any serious incident that has occurred in relation to the device should be reported to the manufacturer and to the competent authority of the Member State in which the user and/or patient is established.

**To report an incident to the manufacturer:**

- Email: info@skintelligentai.com
- Phone: +44 (0)1234 567891
- Online: https://www.skintelligentai.com/epiflare/report-incident
- In-app: Use the "Report an issue" function in the EpiFlare mobile app or clinician portal.

When reporting, please include: device name and version, description of the incident, date and time, patient information (anonymised where appropriate), and any relevant screenshots or IDM values.

## 8. Technical Information

### 8.1 Installation and Setup

**Patient mobile application:**

1. Open the Apple App Store (iOS) or Google Play Store (Android).
2. Search for "EpiFlare" and install.
3. Open the application and follow the setup instructions (see Section 7.1).
4. Verification: Confirm the login screen displays correctly, authentication completes, the image capture screen opens, and the version number matches 2.0 (Settings > About).

**Clinician portal:**

1. Open a supported web browser and navigate to the portal URL.
2. Log in with credentials and configure MFA.
3. Verification: Confirm the dashboard loads within 5 seconds, alert notifications are configured and a test notification is received, and patient records are accessible.

**Backend processing module:** Hosted and maintained by the manufacturer on cloud infrastructure. No installation action is required by users.

### 8.2 Hardware and Software Requirements

**Patient mobile application:**

| Requirement | Specification |
|------------|---------------|
| Device | Smartphone (iPhone 8 or later; Android device meeting Google Play requirements) |
| Camera | Rear camera >=12 megapixels |
| Storage | >=100 MB available storage |
| OS - iOS | Version 14.0 or later |
| OS - Android | Version 8.0 (Oreo) or later |
| Connectivity | Wi-Fi or mobile data (3G/4G/5G) |

**Clinician portal:**

| Requirement | Specification |
|------------|---------------|
| Device | Desktop or laptop with standard monitor |
| Web browser | Chrome, Firefox, Safari, or Microsoft Edge - latest 2 major versions (JavaScript and cookies enabled) |
| Connectivity | Broadband internet recommended |

### 8.3 Network and Infrastructure

| Specification | Requirement |
|--------------|-------------|
| Protocols | HTTPS (TLS 1.2 or higher) |
| Ports | 443 (HTTPS) |
| Bandwidth | Minimum 1 Mbps upload (broadband recommended) |
| Wi-Fi compatibility | Standard Wi-Fi (802.11 a/b/g/n/ac/ax) |
| Firewall | Allow outbound HTTPS to EpiFlare backend services |
| Proxy/VPN | Must support HTTPS pass-through |

**Critical dependencies:**

| Dependency | Description |
|-----------|-------------|
| Internet connectivity | Required for image upload, analysis, alert delivery, and portal access |
| Smartphone camera | Required for image capture (patient mobile app) |
| Push notification services (APNs/FCM) | Required for real-time mobile alert delivery |
| Email delivery service (SendGrid/AWS SES) | Required for email alert delivery |
| SMS delivery service (Twilio) | Required for SMS alert delivery |

### 8.4 System Interfaces

| Interface | Protocol | Description |
|-----------|----------|-------------|
| Mobile <-> Backend | HTTPS (TLS 1.2+) | REST API for image upload, authentication, data retrieval |
| Portal <-> Backend | HTTPS (TLS 1.2+) | REST API for IDM data, alerts, patient management |
| Push notifications | APNs (iOS) / FCM (Android) | Real-time mobile alert delivery |
| Email alerts | HTTPS API (SendGrid/AWS SES) | Email-based clinical alert delivery |
| SMS alerts | HTTPS API (Twilio) | SMS-based clinical alert delivery |

### 8.5 Configuration

| Configuration | User | Details |
|--------------|------|---------|
| Alert thresholds | HCP | Set patient-specific IDM thresholds via clinician portal (default: >99 or >2 SD serial increase) |
| Alert delivery channels | HCP | Configure preferred channels (in-app, email, SMS) |
| Session timeout | System default | Mobile app: 30 min idle; Portal: 15 min idle (configurable by IT administrator) |
| MFA | HCP | Multi-factor authentication required for all HCP accounts |

Start-up: Open the app or portal and authenticate. Shutdown: Log out or close the session; automatic timeout applies after the configured idle period.

### 8.6 Security

| Security Feature | Configuration |
|-----------------|---------------|
| Authentication | Password-based; MFA required for HCPs |
| Session timeout | Mobile: 30 min; Portal: 15 min |
| Account lockout | After 5 consecutive failed login attempts |
| Encryption in transit | TLS 1.2+ for all communications |
| Encryption at rest | AES-256 for all patient data |
| Role-based access control | Patient role; HCP role; Admin role |
| Audit logging | All authentication events, data access, and security-relevant actions logged |

**Security updates:** Mobile patches distributed through app stores; backend patches deployed by the manufacturer via rolling deployment. Users are notified of available updates. Software update integrity is validated via code signing. Rollback mechanisms are available in case of update failure.

**Security alerts:** In the event of an urgent security issue, users will be notified via in-app notification, email, and/or SMS. Critical updates may restrict functionality until applied. Contact: security@skintelligentai.com / +44 (0)1234 567892.

### 8.7 Failure Handling

If EpiFlare detects a failure that compromises security or data integrity:

- The system logs the failure and generates an internal alert to the manufacturer's operations team.
- Affected functionality may be temporarily suspended to prevent data exposure.
- Users are notified via system status messages in the app/portal.
- Patient monitoring will be interrupted during any outage; patients experiencing symptoms should contact their healthcare professional directly.
- No patient data will be lost; data integrity is maintained through transaction management and backup procedures.

## 9. Traceability to Technical Documentation

The following table provides key linkages between this IFU and other documents within the EpiFlare technical file. This traceability ensures alignment of safety information, intended purpose, performance claims, and risk controls across the technical documentation.

| IFU Section | Related Document | Document ID | Linkage Description |
|------------|-----------------|-------------|---------------------|
| 2. General information | Device Description | SAI-EF-DEVD-001 | Device identification, version, UDI-DI, classification |
| 3. Manufacturer details | Device Description | SAI-EF-DEVD-001 | Manufacturer and company structure details |
| 4. Clinical performance | Clinical Evaluation Report | SAI-EF-CER-001 | Clinical benefits, performance data, IDM interpretation, acceptance criteria |
| 4. Clinical performance | Clinical Evaluation Plan | SAI-EF-CEP-001 | Pre-defined acceptance criteria and evaluation methodology |
| 4. Clinical performance | Clinical Investigation Plan | SAI-EF-CIP-001 | Clinical investigation design and endpoints |
| 5.2 Warnings & precautions | Risk Management File | SAI-EF-RMF-001 | Risk controls: SAI-EF-RSK-CTRL-001 to -010 |
| 5.3 Residual risks | Risk Management Report | SAI-EF-RMR-001 | Overall residual risk evaluation, benefit-risk analysis |
| 5.1 Contraindications | Device Description | SAI-EF-DEVD-001 | Contraindications, indications, and intended patient population |
| 5.4 Known limitations | Clinical Evaluation Report | SAI-EF-CER-001 | Evaluation limitations and performance variability |
| 6. Training & qualifications | Usability Engineering Plan | SAI-EF-UEP-001 | User profiles, use specification, formative/summative evaluation |
| 7. Operational procedures | Use Requirements Specification | SAI-EF-UREQ-001 | Functional use requirements (UR-001 to UR-008) |
| 7. Operational procedures | Usability Engineering Plan | SAI-EF-UEP-001 | Hazard-related use scenarios and expected user workflows |
| 7.4 System messages | Risk Management File | SAI-EF-RMF-001 | Risk controls: SAI-EF-RSK-CTRL-002, -008, -010 |
| 7.7 Incident reporting | Post-Market Surveillance Plan | SAI-EF-PMS-001 | Vigilance and incident reporting procedures |
| 8.1-8.2 Installation & Requirements | Software Architecture and Design | SAI-EF-SADS-001 | Software modules, SOUP components, platform requirements |
| 8.3-8.4 Network & Interfaces | Software Architecture and Design | SAI-EF-SADS-001 | System interfaces, communication protocols |
| 8.5-8.6 Configuration & Security | Software Requirements Specification | SAI-EF-SWREQ-001 | Cybersecurity requirements: SR-052 to SR-062 |
| 8.6 Security | Risk Management File | SAI-EF-RMF-001 | Security risk controls: SAI-EF-RSK-CTRL-007, -014 to -023 |

> Fictional example only | Not regulatory advice | Beta content - incomplete and subject to change
