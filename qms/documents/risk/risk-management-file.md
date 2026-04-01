---
id: "RA-001"
title: "Risk Management File"
type: "RA"
category: "technical"
version: "1.0"
status: "approved"
effective_date: "2026-03-01"
author: "Sarp Derinsu"
iso_refs:
  - "7.1"
mdr_refs:
  - "Annex I"
---

# Risk Management File

## 1. Purpose

This Risk Management File documents the complete risk management activities performed for Therapeak, an AI-powered Software as a Medical Device (SaMD) classified as Class IIa under EU MDR 2017/745, Rule 11. It constitutes the primary record required by ISO 14971:2019 and serves as objective evidence that the risk management process defined in [[SOP-002]] has been executed in accordance with [[PLN-001]].

This file contains:

- The risk acceptability criteria applied to Therapeak
- A comprehensive hazard identification and risk analysis
- Risk evaluation results before and after implementation of control measures
- Traceability between identified hazards, controls, and residual risks
- The overall residual risk assessment and benefit-risk determination
- Conclusions on the acceptability of the overall residual risk

As the core risk document for the Therapeak medical device, this file is maintained throughout the entire product lifecycle and updated whenever new hazards are identified, risk controls are modified, or post-market information necessitates re-evaluation.

## 2. Scope

This risk management file covers all identified hazards associated with the Therapeak medical device (software version 1.0, `DEVICE_MODE=medical`), including:

- **AI/ML-specific hazards:** Model output quality, role confusion, inappropriate therapeutic advice, prompt injection, model drift
- **Clinical hazards:** Crisis mishandling, harmful behavioral reinforcement, diagnostic misinterpretation, over-dependency
- **Software hazards:** System unavailability, data integrity failures, authentication weaknesses
- **Cybersecurity hazards:** Data breaches, unencrypted data transmission, unauthorized access
- **Usability hazards:** Language errors, age verification circumvention, user misunderstanding of device purpose
- **Operational hazards:** Complaint handling delays, inadequate change control

The scope encompasses normal use, reasonably foreseeable misuse, and hazardous situations arising from device failure or degradation.

### 2.1 Device Description

| Attribute | Detail |
|---|---|
| Device name | Therapeak |
| Manufacturer | Therapeak B.V. |
| Classification | Class IIa, Rule 11 (EU MDR 2017/745) |
| Intended purpose | Patient-specific supportive conversational guidance intended to help users self-manage mild to moderate mental health symptoms at home |
| Target population | Adults aged 19+ with mild to moderate anxiety, depression, OCD, trauma/stress-related disorders, or impulse control disorders |
| Use environment | Home use, unsupervised, via web browser |
| Primary AI model | Anthropic Claude (accessed via OpenRouter API gateway) |
| IMDRF category | Informs clinical management |

### 2.2 Related Documents

| Document | Reference |
|---|---|
| Risk Management Procedure | [[SOP-002]] |
| Risk Management Plan | [[PLN-001]] |
| CAPA Procedure | [[SOP-003]] |
| Complaint Handling Procedure | [[SOP-004]] |
| Post-Market Surveillance Procedure | [[SOP-009]] |
| Software Lifecycle Management Procedure | [[SOP-011]] |
| Clinical Evaluation Procedure | [[SOP-012]] |
| Vigilance and Field Safety Procedure | [[SOP-013]] |
| Cybersecurity Management Procedure | [[SOP-016]] |
| Change Management Procedure | [[SOP-017]] |

## 3. Risk Acceptability Criteria

The following risk acceptability criteria are defined in [[PLN-001]] and applied throughout this risk analysis. They are reproduced here for completeness.

### 3.1 Severity Levels

| Level | Category | Definition |
|---|---|---|
| S1 | Negligible | No injury or discomfort. Temporary inconvenience only. |
| S2 | Minor | Temporary discomfort or mild distress. Self-resolving without intervention. |
| S3 | Serious | Significant distress requiring professional intervention. Exacerbation of existing symptoms lasting days to weeks. |
| S4 | Critical | Severe psychological harm requiring emergency or sustained clinical intervention. Significant and prolonged worsening of mental health. |
| S5 | Catastrophic | Death or irreversible severe harm (e.g., self-harm resulting from device failure to escalate a crisis). |

### 3.2 Probability Levels

| Level | Category | Definition |
|---|---|---|
| P1 | Rare | Less than 1 in 100,000 sessions |
| P2 | Unlikely | 1 in 10,000 to 1 in 100,000 sessions |
| P3 | Possible | 1 in 1,000 to 1 in 10,000 sessions |
| P4 | Likely | 1 in 100 to 1 in 1,000 sessions |
| P5 | Frequent | Greater than 1 in 100 sessions |

### 3.3 Risk Acceptability Matrix

| Severity / Probability | P1 Rare | P2 Unlikely | P3 Possible | P4 Likely | P5 Frequent |
|---|---|---|---|---|---|
| **S5 Catastrophic** | ALARP | Unacceptable | Unacceptable | Unacceptable | Unacceptable |
| **S4 Critical** | ALARP | ALARP | Unacceptable | Unacceptable | Unacceptable |
| **S3 Serious** | Acceptable | ALARP | ALARP | Unacceptable | Unacceptable |
| **S2 Minor** | Acceptable | Acceptable | ALARP | ALARP | Unacceptable |
| **S1 Negligible** | Acceptable | Acceptable | Acceptable | Acceptable | ALARP |

### 3.4 Risk Acceptability Definitions

- **Acceptable:** Risk is broadly acceptable. No further risk reduction required, though improvements may still be considered.
- **ALARP (As Low As Reasonably Practicable):** Risk shall be reduced as far as reasonably practicable. A documented justification is required demonstrating that the cost or technical difficulty of further reduction is grossly disproportionate to the benefit. The residual risk must be accepted through benefit-risk analysis.
- **Unacceptable:** Risk is not acceptable under any circumstances. The hazard must be eliminated or the risk must be reduced to ALARP or Acceptable through design changes, protective measures, or information for safety.

### 3.5 Risk Control Priority Order

Per EU MDR 2017/745 Annex I, Section 4, risk control measures are applied in the following order of priority:

1. **Inherently safe design and manufacture** -- Eliminate or reduce risks through safe design (e.g., prompt engineering, age gating, safety instructions embedded in AI system prompts)
2. **Adequate protection measures** -- Including alarms and automated safeguards (e.g., session quality monitoring via ChatDebugFlags, role confusion detection, content moderation, crisis resource display)
3. **Information for safety** -- Warnings, training materials, and instructions for use (e.g., disclaimers that Therapeak is not for crisis use, IFU statements, homepage emergency messaging)

## 4. Hazard Identification and Risk Analysis

The following hazard analysis was conducted using Failure Mode and Effects Analysis (FMEA) methodology as defined in [[SOP-002]] Section 4.2. Each identified hazard is analyzed for severity and probability both before and after the application of risk control measures. Hazards are grouped by category for clarity.

### 4.1 AI Output Quality and Behavioral Hazards

#### H-001: AI Validates Self-Harming Behavior

| Attribute | Detail |
|---|---|
| **Hazard ID** | H-001 |
| **Hazard** | AI validates self-harming behavior |
| **Cause** | AI model generates a response that normalizes, validates, or encourages self-harming behavior (e.g., cutting, substance abuse, eating disorders) in response to user disclosure. This could occur due to prompt ambiguity, model misinterpretation of context, or adversarial input. |
| **Harm** | User perceives AI validation as endorsement of self-harm, potentially escalating self-injurious behavior. Psychological harm ranging from reinforced harmful patterns to physical injury or death. |
| **Initial Severity** | S5 (Catastrophic) |
| **Initial Probability** | P2 (Unlikely) |
| **Initial Risk Level** | **Unacceptable** |

**Control Measures:**

| # | Type | Control Measure | Verification |
|---|---|---|---|
| C-001a | Inherent safety | 160-200+ static safety instructions embedded in every conversation job, including explicit prohibitions against validating harmful behavior | Review of prompt templates; tested during local prompt testing |
| C-001b | Inherent safety | Anthropic Claude's built-in safety layer refuses to validate or encourage self-harm at the model level, independent of Therapeak's prompts | Anthropic's published safety evaluations; observed behavior in pre-market sessions |
| C-001c | Inherent safety | Role enforcement ("You are the THERAPIST") repeated 10+ times per conversation job with reinforcement language, preventing the model from abandoning its therapeutic stance | Review of conversation job source code |
| C-001d | Protection | `FLAG_SWITCHED_ROLES` monitoring (GPT-4o) detects sessions where AI deviates from therapist role, enabling post-hoc review | ChatDebugFlag system operational verification |
| C-001e | Protection | Sarp Derinsu manually reviews 1-2 sessions per week for harmful patterns | Session review logs |
| C-001f | Information | Homepage states: "In emergencies, this site is not a substitute for immediate help. If you are in a crisis, call the national crisis line, dial emergency services, or visit the nearest emergency room." | UI review |
| C-001g | Information | Severe depression screening results display: "If you are having thoughts of self-harm, please contact a crisis helpline immediately." | UI review |

| Attribute | Detail |
|---|---|
| **Residual Severity** | S5 (Catastrophic) |
| **Residual Probability** | P1 (Rare) |
| **Residual Risk Level** | **ALARP** |
| **Justification** | The combination of Claude's built-in safety layer (which independently refuses to validate self-harm), 160-200+ prompt-level safety instructions, role enforcement, and post-session monitoring makes validation of self-harm extremely unlikely. The residual risk is accepted on the basis of benefit-risk analysis (Section 5). Further reduction would require restricting the device's therapeutic scope in a way that would eliminate its clinical benefit for the target population. |

---

#### H-002: AI Provides Inappropriate Therapeutic Advice

| Attribute | Detail |
|---|---|
| **Hazard ID** | H-002 |
| **Hazard** | AI provides inappropriate therapeutic advice (e.g., trivializes a serious condition, gives clinically contraindicated suggestions) |
| **Cause** | AI model generates advice that is therapeutically inappropriate for the user's situation -- e.g., suggesting "just think positive" to a severely depressed user, minimizing the severity of trauma, or recommending techniques contraindicated for the user's condition. Could result from insufficient context, model limitations, or prompt gaps. |
| **Harm** | User feels invalidated, loses trust in legitimate therapy, delays seeking professional help, or follows advice that worsens their condition. Potential for significant distress and symptom exacerbation. |
| **Initial Severity** | S3 (Serious) |
| **Initial Probability** | P3 (Possible) |
| **Initial Risk Level** | **ALARP** |

**Control Measures:**

| # | Type | Control Measure | Verification |
|---|---|---|---|
| C-002a | Inherent safety | Extensive therapeutic instructions in system prompts define appropriate therapeutic techniques, empathy requirements, and prohibitions against trivializing user concerns | Review of prompt templates (chat_room_instructions.txt, priority_chat_instructions.txt) |
| C-002b | Inherent safety | Dynamic personality descriptions (via PersonalityTypeService) provide 17 randomized personality traits ensuring varied but therapeutically grounded interaction styles | Code review of PersonalityTypeService |
| C-002c | Inherent safety | Previous session summaries and trial survey data included in context, giving the AI awareness of user's history and presenting concerns | Conversation job code review |
| C-002d | Inherent safety | Reports explicitly instructed to never advise about medication and state "this is not a medical document" / "not a diagnosis" | Report generation prompt review |
| C-002e | Inherent safety | Prompt design informed by clinical consultation (Nisan Derinsu, studied Psychological Counseling and Guidance) | Documented in THERAPEAK.md |
| C-002f | Protection | Manual session review by Sarp Derinsu (1-2 sessions per week) catches patterns of poor therapeutic quality | Session review logs |
| C-002g | Information | IFU states that all AI guidance is informational, not diagnostic, and users are never obligated to follow suggestions | IFU review |

| Attribute | Detail |
|---|---|
| **Residual Severity** | S3 (Serious) |
| **Residual Probability** | P2 (Unlikely) |
| **Residual Risk Level** | **ALARP** |
| **Justification** | The extensive prompt engineering with clinical consultation, combined with model-level capabilities of Claude for therapeutic conversation, reduces probability. IFU disclaimers and the user's ability to disregard advice mitigate severity. Residual risk accepted via benefit-risk analysis (Section 5). Post-market monitoring via [[SOP-009]] will track complaint data for patterns of inappropriate advice. |

---

#### H-003: AI Role Confusion (Responds as Patient Instead of Therapist)

| Attribute | Detail |
|---|---|
| **Hazard ID** | H-003 |
| **Hazard** | AI role confusion -- AI responds as the patient instead of the therapist, or breaks the therapeutic frame |
| **Cause** | The AI model loses its assigned role in the conversation, potentially triggered by long session context, adversarial user input, or model confusion. The AI begins responding from the patient's perspective, shares personal problems, or breaks character. |
| **Harm** | User receives non-therapeutic responses, loses trust in the platform, experiences confusion or distress. In severe cases, a role-confused AI could validate the user's problems in harmful ways or fail to provide appropriate therapeutic support. |
| **Initial Severity** | S3 (Serious) |
| **Initial Probability** | P3 (Possible) |
| **Initial Risk Level** | **ALARP** |

**Control Measures:**

| # | Type | Control Measure | Verification |
|---|---|---|---|
| C-003a | Inherent safety | "You are the THERAPIST" role enforcement repeated 10+ times throughout every conversation job with "deletion threat" reinforcement language | Conversation job source code review |
| C-003b | Inherent safety | Behavioral guardrails prohibit role-playing, games, and off-topic behavior that could lead to role blurring | Prompt template review |
| C-003c | Protection | `FLAG_SWITCHED_ROLES` automated monitoring via GPT-4o (`CheckSessionForSwitchedRolesJob`) analyzes transcripts for AI responding as patient instead of therapist | ChatDebugFlag system verification; test with known role-confusion transcripts |
| C-003d | Protection | Manual session review by Sarp Derinsu detects role confusion patterns not caught by automated monitoring | Session review logs |
| C-003e | Protection | Telescope live monitoring shows real-time session activity, enabling detection of anomalous behavior patterns | Telescope dashboard review |

| Attribute | Detail |
|---|---|
| **Residual Severity** | S2 (Minor) |
| **Residual Probability** | P2 (Unlikely) |
| **Residual Risk Level** | **Acceptable** |
| **Justification** | The combination of aggressive role enforcement (10+ repetitions per job), automated detection via `FLAG_SWITCHED_ROLES`, and manual review creates multiple layers of defense. Even if role confusion occurs briefly, automated monitoring flags it for review. The user impact is reduced because a role-confused response is typically recognizable as anomalous, and the user can end the session or switch therapists. |

---

#### H-015: AI Reinforces Toxic or Abusive Behavior Patterns

| Attribute | Detail |
|---|---|
| **Hazard ID** | H-015 |
| **Hazard** | AI reinforces toxic or abusive behavior patterns in the user |
| **Cause** | User describes situations where they are the perpetrator of harmful behavior (e.g., manipulation, emotional abuse, controlling behavior) and the AI validates or normalizes these patterns rather than gently challenging them. Could also occur if the AI takes an overly empathetic stance without appropriate clinical boundary-setting. |
| **Harm** | User continues or escalates harmful behavior toward others, reinforced by perceived AI endorsement. Potential harm to third parties (partners, family members, children). User's own mental health may deteriorate as maladaptive patterns are reinforced. |
| **Initial Severity** | S4 (Critical) |
| **Initial Probability** | P3 (Possible) |
| **Initial Risk Level** | **Unacceptable** |

**Control Measures:**

| # | Type | Control Measure | Verification |
|---|---|---|---|
| C-015a | Inherent safety | Relationship protection instructions embedded in prompts: never encourage leaving relationships, always try to heal first, never demonize people, never label individuals as toxic/narcissistic | Prompt template review |
| C-015b | Inherent safety | Claude's built-in safety training includes nuanced handling of interpersonal situations, avoiding reinforcement of abusive patterns | Anthropic's published safety approach; observed behavior |
| C-015c | Inherent safety | 160-200+ therapeutic instructions guide the AI toward evidence-based therapeutic approaches that include appropriate boundary-setting and accountability | Conversation job code review |
| C-015d | Protection | Manual session review catches patterns where the AI fails to appropriately challenge harmful behavior | Session review logs |
| C-015e | Protection | Post-market surveillance via [[SOP-009]] tracks complaints and feedback related to AI advice quality | PMS records |
| C-015f | Information | IFU states that Therapeak provides informational guidance only and does not replace professional therapeutic judgment | IFU review |

| Attribute | Detail |
|---|---|
| **Residual Severity** | S3 (Serious) |
| **Residual Probability** | P2 (Unlikely) |
| **Residual Risk Level** | **ALARP** |
| **Justification** | The prompt-level relationship protection instructions specifically address this hazard. Claude's training includes sophisticated handling of complex interpersonal dynamics. The residual severity is reduced from Critical to Serious because the device's informational nature means users retain full autonomy and the AI cannot compel behavior. Post-market monitoring will track any patterns. Accepted via benefit-risk analysis (Section 5). |

---

### 4.2 Clinical Safety Hazards

#### H-005: User Interprets AI Output as Medical Diagnosis

| Attribute | Detail |
|---|---|
| **Hazard ID** | H-005 |
| **Hazard** | User interprets AI output as a medical diagnosis |
| **Cause** | Therapeak generates session reports containing clinical-style language (e.g., "Assessment findings," "Diagnosis" section, "Treatment plan"). Users may interpret this as an actual medical diagnosis, especially given the clinical presentation format. The AI may also use diagnostic-sounding language within therapy sessions. |
| **Harm** | User makes health decisions based on a perceived AI diagnosis (e.g., discontinues professional treatment, self-treats based on AI "diagnosis," avoids seeking proper diagnosis). Potential for delayed treatment of serious conditions. |
| **Initial Severity** | S4 (Critical) |
| **Initial Probability** | P4 (Likely) |
| **Initial Risk Level** | **Unacceptable** |

**Control Measures:**

| # | Type | Control Measure | Verification |
|---|---|---|---|
| C-005a | Inherent safety | Reports explicitly state "this is not a medical document" and "not a diagnosis" | Report template review |
| C-005b | Inherent safety | Report generation prompts instruct the AI to never advise about medication | Report generation prompt review |
| C-005c | Inherent safety | Clinical claims documentation clearly states: output is NOT intended to diagnose any condition, establish severity, triage urgency, recommend or select specific clinical interventions, or determine medication or treatment changes | Intended purpose statement review |
| C-005d | Information | IFU clearly states that Therapeak does not provide medical diagnoses and that clinical decisions remain the responsibility of the healthcare professional and/or user | IFU review |
| C-005e | Information | Homepage and platform disclaimers communicate the informational nature of the device | UI review |
| C-005f | Inherent safety | IMDRF classification as "informs clinical management" (not "diagnose or drive clinical management") is reflected throughout labeling | Labeling review |

| Attribute | Detail |
|---|---|
| **Residual Severity** | S3 (Serious) |
| **Residual Probability** | P2 (Unlikely) |
| **Residual Risk Level** | **ALARP** |
| **Justification** | Multiple layers of disclaimers, both within reports and in the IFU, reduce the probability that users will treat AI output as diagnosis. The residual severity is reduced from Critical to Serious because even if a user initially interprets output as diagnostic, the disclaimers create a reasonable opportunity for correction. Post-market surveillance will monitor for complaints indicating diagnostic misinterpretation. Accepted via benefit-risk analysis (Section 5). |

---

#### H-006: User Becomes Overly Dependent on AI Therapy

| Attribute | Detail |
|---|---|
| **Hazard ID** | H-006 |
| **Hazard** | User becomes overly dependent on AI therapy, substituting it for professional care |
| **Cause** | User relies exclusively on Therapeak for mental health management, does not seek professional help for worsening symptoms, develops an unhealthy emotional dependence on the AI therapist, or delays necessary clinical intervention. The subscription model and 24/7 availability may encourage dependency. |
| **Harm** | Delayed treatment of conditions that require professional intervention. Potential deterioration of mental health. User may develop false sense of security about their mental health management. |
| **Initial Severity** | S4 (Critical) |
| **Initial Probability** | P3 (Possible) |
| **Initial Risk Level** | **Unacceptable** |

**Control Measures:**

| # | Type | Control Measure | Verification |
|---|---|---|---|
| C-006a | Inherent safety | Session minutes are capped at 30 min/day (45 min max accumulation), preventing unlimited use | Application configuration review |
| C-006b | Inherent safety | Intended purpose explicitly states Therapeak "may be used standalone or as supplement to traditional therapy," positioning it as complementary | Intended purpose documentation |
| C-006c | Information | IFU states that Therapeak does not replace professional mental health care and recommends consulting healthcare professionals | IFU review |
| C-006d | Information | Contraindications clearly state the device is not for complex psychotic or dissociative disorders and not for emergency/crisis use | IFU and labeling review |
| C-006e | Inherent safety | Reports include recommendations section that can guide users toward professional resources | Report generation prompt review |
| C-006f | Protection | Post-market surveillance via [[SOP-009]] monitors for patterns of over-dependency in user feedback and complaints | PMS records |

| Attribute | Detail |
|---|---|
| **Residual Severity** | S3 (Serious) |
| **Residual Probability** | P2 (Unlikely) |
| **Residual Risk Level** | **ALARP** |
| **Justification** | The daily session time cap inherently limits usage intensity. The IFU, disclaimers, and intended purpose all communicate that Therapeak is complementary to, not a substitute for, professional care. Residual risk is accepted because restricting access further would reduce the therapeutic benefit for the target population (mild to moderate symptoms) who may genuinely benefit from self-management support. Accepted via benefit-risk analysis (Section 5). |

---

#### H-007: Crisis Situation Not Properly Handled (Suicidal User)

| Attribute | Detail |
|---|---|
| **Hazard ID** | H-007 |
| **Hazard** | Crisis situation (e.g., suicidal user) not properly handled by the device |
| **Cause** | A user in acute crisis (suicidal ideation, active self-harm, psychotic episode) engages with Therapeak. The AI fails to recognize the crisis, provides inadequate crisis response, continues normal therapeutic conversation, or fails to direct the user to emergency services. |
| **Harm** | User in crisis does not receive appropriate emergency referral. Delayed access to crisis intervention. In the worst case, self-harm or death if the user relied on the device during a crisis instead of contacting emergency services. |
| **Initial Severity** | S5 (Catastrophic) |
| **Initial Probability** | P3 (Possible) |
| **Initial Risk Level** | **Unacceptable** |

**Control Measures:**

| # | Type | Control Measure | Verification |
|---|---|---|---|
| C-007a | Inherent safety | Crisis handling delegated to Anthropic Claude's built-in safety layer, which recognizes crisis language, responds with empathy, directs users to emergency services and crisis hotlines, and refuses to continue normal conversation during a crisis. This is a design decision: Anthropic's safety training is more comprehensive than a custom prompt, covers edge cases, and is continuously updated by Anthropic's safety team. | Anthropic's published safety evaluations; testing with crisis scenarios during prompt testing |
| C-007b | Inherent safety | Contraindications explicitly state "Not for emergency/crisis use," setting user expectations before engagement | Labeling and IFU review |
| C-007c | Information | Homepage displays: "In emergencies, this site is not a substitute for immediate help. If you are in a crisis, call the national crisis line, dial emergency services, or visit the nearest emergency room." | UI review |
| C-007d | Information | Severe depression screening results trigger display of: "If you are having thoughts of self-harm, please contact a crisis helpline immediately." | Quiz result UI verification |
| C-007e | Inherent safety | Custom onboarding questionnaire includes depression/anxiety screening items (suicidal ideation item replaced with non-suicidal-ideation item in medical mode) -- the screening identifies users who may be at higher risk, informing AI context | Quiz configuration review |
| C-007f | Protection | Post-market surveillance via [[SOP-009]] and vigilance reporting via [[SOP-013]] ensure any crisis-related incidents are detected, reported, and acted upon | PMS and vigilance records |
| C-007g | Protection | Telescope live monitoring enables real-time visibility into platform activity | Telescope dashboard review |

| Attribute | Detail |
|---|---|
| **Residual Severity** | S5 (Catastrophic) |
| **Residual Probability** | P1 (Rare) |
| **Residual Risk Level** | **ALARP** |
| **Justification** | Claude's built-in safety layer provides robust crisis detection and response independent of Therapeak's prompts. The device is explicitly contraindicated for crisis use, and multiple information-for-safety measures direct users to emergency services before they engage with the AI. The residual probability is Rare because: (1) the device is contraindicated for crisis, reducing the likelihood of crisis users engaging; (2) Claude's safety layer reliably detects and responds to crisis language; (3) homepage messaging provides alternative crisis resources. The catastrophic severity cannot be eliminated because no software system can guarantee crisis intervention. Accepted via benefit-risk analysis (Section 5). The overall benefit of providing accessible mental health support to the target population outweighs this residual risk when the contraindication and safeguards are considered. |

---

### 4.3 Cybersecurity and Data Privacy Hazards

#### H-008: Data Breach Exposing Therapy Conversations

| Attribute | Detail |
|---|---|
| **Hazard ID** | H-008 |
| **Hazard** | Data breach exposing therapy conversations and health data |
| **Cause** | Unauthorized access to the production database (MariaDB on Hetzner) containing full therapy chat transcripts, session summaries, clinical-style reports, survey responses, and personal data. Could result from server compromise, credential theft, SQL injection, or third-party service breach. |
| **Harm** | Exposure of highly sensitive mental health data. Severe privacy violation for affected users. Potential for psychological distress, stigma, discrimination, relationship damage, or professional consequences if therapy content is disclosed. Regulatory consequences under GDPR. |
| **Initial Severity** | S4 (Critical) |
| **Initial Probability** | P2 (Unlikely) |
| **Initial Risk Level** | **ALARP** |

**Control Measures:**

| # | Type | Control Measure | Verification |
|---|---|---|---|
| C-008a | Inherent safety | Let's Encrypt SSL/TLS encryption for all data in transit | SSL certificate verification; HTTPS enforcement check |
| C-008b | Inherent safety | SSH access restricted to Sarp Derinsu only -- no other users have server access | Server access audit |
| C-008c | Inherent safety | Data Processing Agreement (DPA) signed with Hetzner (March 25, 2026), covering personal master data, communication data, and health data (Art. 9 GDPR) | DPA documentation |
| C-008d | Inherent safety | 2FA enabled on GitHub, Hetzner, Stripe, and AWS -- protecting critical infrastructure accounts | Account security audit |
| C-008e | Inherent safety | Data sharing at OpenRouter gateway turned OFF (March 25, 2026), minimizing data exposure to third parties | OpenRouter account settings verification |
| C-008f | Inherent safety | Laravel Sanctum (SPA authentication) and Laravel Passport (service-to-service) provide secure API authentication | Code review of authentication configuration |
| C-008g | Protection | Telescope live monitoring enables detection of anomalous access patterns | Telescope dashboard review |
| C-008h | Protection | Cybersecurity management per [[SOP-016]] defines ongoing security monitoring and incident response | Procedure review |

| Attribute | Detail |
|---|---|
| **Residual Severity** | S4 (Critical) |
| **Residual Probability** | P1 (Rare) |
| **Residual Risk Level** | **ALARP** |
| **Justification** | The restricted server access (single administrator), SSL/TLS encryption, 2FA on infrastructure accounts, and DPA with Hetzner collectively reduce breach probability to Rare. The severity remains Critical because therapy data is inherently highly sensitive. Residual risk is accepted because further reduction (e.g., database-level encryption at rest) is planned for future implementation but the current controls are proportionate to the threat level for a single-server, single-administrator architecture. Monitored via [[SOP-016]]. |

---

#### H-012: Session Data Sent via Unencrypted Email

| Attribute | Detail |
|---|---|
| **Hazard ID** | H-012 |
| **Hazard** | Session data sent via unencrypted email |
| **Cause** | Session summary emails contain the FULL therapy summary text in the email body (not just a notification link). Emails are sent via AWS SES (eu-north-1, Stockholm) using SMTP. Email is transmitted in cleartext between mail servers (no end-to-end encryption). Nine email types exist, including session reports and user reports containing health data. |
| **Harm** | Therapy content intercepted in transit or stored in plaintext on email servers. Third parties (email providers, network operators) could access sensitive mental health data. User's email account compromise would expose therapy summaries. |
| **Initial Severity** | S3 (Serious) |
| **Initial Probability** | P3 (Possible) |
| **Initial Risk Level** | **ALARP** |

**Control Measures:**

| # | Type | Control Measure | Verification |
|---|---|---|---|
| C-012a | Inherent safety | AWS SES (eu-north-1, Stockholm) used for email delivery -- EU-based, GDPR-compliant infrastructure | AWS SES configuration review |
| C-012b | Inherent safety | SMTP transport uses TLS (opportunistic encryption) for the Therapeak-to-SES connection | SMTP configuration review |
| C-012c | Information | Privacy policy informs users that email communications are used and describes data handling practices | Privacy policy review |
| C-012d | Information | Users can access all reports and summaries within the platform (email is a convenience notification, not the sole access method) | UI review |

| Attribute | Detail |
|---|---|
| **Residual Severity** | S3 (Serious) |
| **Residual Probability** | P2 (Unlikely) |
| **Residual Risk Level** | **ALARP** |
| **Justification** | While email inherently lacks end-to-end encryption, the use of TLS for the SMTP connection to AWS SES and EU-based email infrastructure reduces interception probability. The residual risk is accepted because email notification of session summaries provides significant usability benefit (users can review sessions without logging in) and the probability of targeted interception of a specific user's therapy emails is low. Future risk reduction: consider replacing full summary text in emails with notification links requiring authentication. Monitored via [[SOP-016]]. |

---

### 4.4 System Availability and Infrastructure Hazards

#### H-009: System Unavailability During User Crisis

| Attribute | Detail |
|---|---|
| **Hazard ID** | H-009 |
| **Hazard** | System unavailability during user crisis |
| **Cause** | The Therapeak platform becomes unavailable (server outage, AI provider outage, database failure, queue processing failure) at the moment a user in distress attempts to use it. User may have no alternative coping mechanism immediately available. |
| **Harm** | User in distress cannot access the platform for support. If the user is in crisis and has no other resources, the unavailability could contribute to an adverse outcome. Frustration and loss of trust even for non-crisis users. |
| **Initial Severity** | S4 (Critical) |
| **Initial Probability** | P2 (Unlikely) |
| **Initial Risk Level** | **ALARP** |

**Control Measures:**

| # | Type | Control Measure | Verification |
|---|---|---|---|
| C-009a | Inherent safety | Multi-provider AI fallback strategy: Primary (Anthropic Claude Sonnet 4.5) with 3 retry attempts, then fallback to Claude Opus 4.5, then additional fallback models (Sonnet 4 -> Sonnet 3.7 -> Opus 4). Requests are routed via the OpenRouter gateway through multiple infrastructure providers (Vertex AI, Amazon Bedrock, Anthropic API) for provider-level redundancy. | Conversation job code review; fallback configuration audit |
| C-009b | Inherent safety | 99.9% availability target — achievable due to multi-provider routing (Vertex AI, Bedrock, Anthropic API via OpenRouter gateway) | Uptime monitoring records |
| C-009c | Inherent safety | No self-caused outages to date; all historical outages from external services only | Incident history review |
| C-009d | Protection | Telescope live monitoring shows real-time platform health; Sarp monitors even outside work hours (evenings, weekends) | Telescope dashboard; monitoring schedule |
| C-009e | Protection | Redis-backed queue processing (Laravel Horizon) with persistent job storage prevents message loss during transient failures | Queue system configuration review |
| C-009f | Information | Contraindication states "Not for emergency/crisis use" -- sets expectation that the device is not an emergency service | IFU and labeling review |
| C-009g | Information | Homepage provides emergency contact information (crisis lines, emergency services) accessible even if the therapy chat is unavailable | UI review |

| Attribute | Detail |
|---|---|
| **Residual Severity** | S3 (Serious) |
| **Residual Probability** | P1 (Rare) |
| **Residual Risk Level** | **Acceptable** |
| **Justification** | The multi-layer fallback architecture (multiple AI providers, multiple Claude versions, multi-cloud routing) makes complete system unavailability a Rare event. The explicit contraindication for crisis use and the presence of emergency resources on the homepage reduce both the probability that a crisis user relies solely on Therapeak and the severity of unavailability when it occurs. The residual severity is Serious (rather than Critical) because the device is not positioned as an emergency service and users are informed of this. |

---

#### H-010: AI Model Change Degrades Therapeutic Quality

| Attribute | Detail |
|---|---|
| **Hazard ID** | H-010 |
| **Hazard** | AI model change degrades therapeutic quality |
| **Cause** | An upstream AI model update (Anthropic updates Claude behavior) or an intentional model switch (e.g., Sonnet 4.5 to Sonnet 4.6) introduces degraded therapeutic output quality — less empathetic, less contextually appropriate, more generic, or clinically inappropriate responses. |
| **Harm** | Users receive lower-quality therapeutic support without warning. Possible inappropriate advice (see H-002), role confusion (see H-003), or failure to handle sensitive topics appropriately. Systematic quality degradation affecting all users simultaneously. |
| **Initial Severity** | S3 (Serious) |
| **Initial Probability** | P3 (Possible) |
| **Initial Risk Level** | **ALARP** |

**Control Measures:**

| # | Type | Control Measure | Verification |
|---|---|---|---|
| C-010a | Inherent safety | Model changes tested locally using prompt-testing tool (routes/prompt-testing.php) before production deployment | Test records |
| C-010b | Inherent safety | Post-deployment monitoring: 1-2 sessions manually reviewed for quality after each model change | Session review logs |
| C-010c | Inherent safety | Multiple conversation job variants exist for different models (`OpenRouterSonnetFourFiveRunConversationJob`, `OpenRouterSonnetFourSixRunConversationJob`, etc.), enabling rapid rollback to a previous model | Code review of job variants |
| C-010d | Protection | `FLAG_SWITCHED_ROLES` and `FLAG_DID_NOT_RESPOND` automated monitoring detects quality degradation signals across all sessions | ChatDebugFlag operational data |
| C-010e | Protection | Change management per [[SOP-017]] requires documented evaluation before production changes | Change management records |
| C-010f | Protection | Post-market surveillance via [[SOP-009]] tracks user complaints that may indicate quality degradation | PMS records |

| Attribute | Detail |
|---|---|
| **Residual Severity** | S3 (Serious) |
| **Residual Probability** | P2 (Unlikely) |
| **Residual Risk Level** | **ALARP** |
| **Justification** | The local testing and post-deployment monitoring reduce the probability that a degraded model remains in production undetected. Automated monitoring flags (role confusion, non-response) provide population-level quality signals. Rollback capability limits the duration of any quality degradation. Residual risk accepted because the nature of AI model updates means that some degree of behavioral change is inherent to the technology. Ongoing monitoring via [[SOP-009]] and change control via [[SOP-017]] provide continuous risk management. |

---

### 4.5 Prompt Injection and Adversarial Use Hazards

#### H-011: Prompt Injection Causes Harmful AI Behavior

| Attribute | Detail |
|---|---|
| **Hazard ID** | H-011 |
| **Hazard** | Prompt injection causes harmful AI behavior |
| **Cause** | A user crafts adversarial input designed to override the system prompt, extract system instructions, or manipulate the AI into generating harmful content (e.g., bypassing safety instructions, generating content outside therapeutic scope, revealing prompt details). |
| **Harm** | AI generates content that could be harmful to the user (bypassed safety measures), breaches confidentiality of system design, or produces responses outside therapeutic scope. Could enable any of the harms described in H-001 through H-003. |
| **Initial Severity** | S4 (Critical) |
| **Initial Probability** | P3 (Possible) |
| **Initial Risk Level** | **Unacceptable** |

**Control Measures:**

| # | Type | Control Measure | Verification |
|---|---|---|---|
| C-011a | Inherent safety | 160-200+ static instructions per conversation job create a robust system prompt context that is difficult to override through user input | Prompt template review; adversarial testing during prompt testing |
| C-011b | Inherent safety | Role enforcement repeated 10+ times with reinforcement language provides redundancy against partial prompt override | Conversation job code review |
| C-011c | Inherent safety | Anthropic Claude's built-in prompt injection resistance -- Claude is trained to maintain system prompt instructions even under adversarial pressure | Anthropic's published safety evaluations |
| C-011d | Inherent safety | Behavioral guardrails prohibit off-topic behavior, role-playing, and meta-discussion of AI nature, limiting the attack surface for prompt injection | Prompt template review |
| C-011e | Protection | `FLAG_SWITCHED_ROLES` monitoring detects if prompt injection successfully causes role deviation | ChatDebugFlag system data |
| C-011f | Protection | Manual session review catches anomalous behavior patterns that may indicate successful injection | Session review logs |

| Attribute | Detail |
|---|---|
| **Residual Severity** | S3 (Serious) |
| **Residual Probability** | P2 (Unlikely) |
| **Residual Risk Level** | **ALARP** |
| **Justification** | The extensive system prompt (160-200+ instructions), redundant role enforcement, and Claude's built-in injection resistance make successful prompt injection unlikely. Even if partial override occurs, the automated monitoring flags behavioral deviations. Residual severity is Serious (reduced from Critical) because Claude's safety layer operates independently of the system prompt, meaning core safety behaviors (crisis handling, refusal to generate harmful content) are maintained even under injection. Accepted via benefit-risk analysis (Section 5). |

---

### 4.6 Usability and Access Control Hazards

#### H-004: AI Generates Content in Wrong Language

| Attribute | Detail |
|---|---|
| **Hazard ID** | H-004 |
| **Hazard** | AI generates content in wrong language |
| **Cause** | The AI responds in a language different from the user's selected locale. Could be caused by: language context not correctly passed to the conversation job, model defaulting to English for less common languages, code-switching within a session, or language confusion when user writes in a different language than their locale setting. |
| **Harm** | User cannot understand therapeutic guidance. Therapeutic rapport disrupted. User may miss safety-critical information (e.g., crisis resources). Particular concern for users with limited English proficiency who selected a non-English locale. |
| **Initial Severity** | S2 (Minor) |
| **Initial Probability** | P3 (Possible) |
| **Initial Risk Level** | **ALARP** |

**Control Measures:**

| # | Type | Control Measure | Verification |
|---|---|---|---|
| C-004a | Inherent safety | Chat room context includes explicit language specification passed to every conversation job, instructing the AI to respond in the user's selected language | Conversation job code review |
| C-004b | Inherent safety | 20+ locale translations available, with locale determined during onboarding | Locale configuration review |
| C-004c | Inherent safety | Claude demonstrates strong multilingual capabilities across major European languages | Pre-market session quality observation |
| C-004d | Protection | User can switch therapist or contact support if language issues persist | Platform feature review |
| C-004e | Protection | Post-market feedback via Trustpilot reviews and contact messages captures language quality issues | PMS records |

| Attribute | Detail |
|---|---|
| **Residual Severity** | S2 (Minor) |
| **Residual Probability** | P2 (Unlikely) |
| **Residual Risk Level** | **Acceptable** |
| **Justification** | The explicit language instruction in every conversation job and Claude's multilingual capabilities make wrong-language responses unlikely. When they occur, the impact is Minor (temporary disruption, user can resend message or switch therapist). Post-market monitoring will track language-related complaints to identify patterns requiring action. |

---

#### H-013: User Under 19 Accesses the Platform

| Attribute | Detail |
|---|---|
| **Hazard ID** | H-013 |
| **Hazard** | User under 19 accesses the platform |
| **Cause** | A minor (under 18) circumvents the age gate by reporting a false age of 19 or older during the trial survey. The age dropdown starts at 12, but users selecting 18 or under are blocked from free trial and payment. A minor could also use an adult's account. |
| **Harm** | Minors exposed to AI therapy without parental consent or oversight. Therapeutic approaches designed for adults may be inappropriate for adolescents. Potential for harm if AI addresses adult-oriented topics (relationships, substance use) with a minor. Regulatory non-compliance. |
| **Initial Severity** | S4 (Critical) |
| **Initial Probability** | P2 (Unlikely) |
| **Initial Risk Level** | **ALARP** |

**Control Measures:**

| # | Type | Control Measure | Verification |
|---|---|---|---|
| C-013a | Inherent safety | Age gate blocks users who report age 18 or under from accessing free trial or payment -- effectively requiring reported age 19+ (age 18 blocked as buffer against minors claiming to be 18) | QuizService.php code review; UI testing |
| C-013b | Inherent safety | Age dropdown starts at 12 but all selections 18 and under result in access denial | UI testing with edge cases |
| C-013c | Information | Terms of Service define minimum age requirement | ToS review |
| C-013d | Information | Privacy policy addresses data processing for minors | Privacy policy review |
| C-013e | Protection | Post-market surveillance via [[SOP-009]] monitors for indicators of minor usage (e.g., user complaints mentioning their child, language patterns) | PMS records |

| Attribute | Detail |
|---|---|
| **Residual Severity** | S3 (Serious) |
| **Residual Probability** | P2 (Unlikely) |
| **Residual Risk Level** | **ALARP** |
| **Justification** | The age gate with the 19+ threshold (blocking 18 as a buffer) is a proportionate control for a self-reported age system. Complete age verification (e.g., ID checks) would be disproportionately burdensome and is not standard practice for SaMD in this category. Residual severity is Serious (reduced from Critical) because Claude's therapeutic approach is generally appropriate across ages and the device addresses universal mental health concerns. Accepted via benefit-risk analysis (Section 5). Post-market monitoring will track any evidence of minor access. |

---

### 4.7 Operational and Process Hazards

#### H-014: Delayed Response to Serious Complaint

| Attribute | Detail |
|---|---|
| **Hazard ID** | H-014 |
| **Hazard** | Delayed response to serious complaint |
| **Cause** | A user submits a complaint about a safety-critical issue (e.g., harmful AI response, crisis mishandling, data breach) via info@therapeak.com or the in-app contact form, but the response is delayed beyond the acceptable timeframe. Could occur if Sarp Derinsu (sole support person) is unavailable due to illness, travel, or high volume. No backup support person exists for routine complaints. |
| **Harm** | User who experienced a harmful interaction does not receive timely acknowledgment or corrective action. Potential for continued harm if the root cause remains unaddressed. User distress compounded by feeling ignored. Regulatory non-compliance if a vigilance-reportable event is not escalated in time. |
| **Initial Severity** | S3 (Serious) |
| **Initial Probability** | P2 (Unlikely) |
| **Initial Risk Level** | **ALARP** |

**Control Measures:**

| # | Type | Control Measure | Verification |
|---|---|---|---|
| C-014a | Inherent safety | Complaint response target within 24 hours -- Sarp's typical response time is 5-10 minutes, maximum 8 hours on busy days, never exceeding 24 hours | Complaint response time records |
| C-014b | Inherent safety | Complaints requiring fixes are labelled "Needs-fix" in email, ensuring traceability and follow-up | Email label audit |
| C-014c | Protection | Complaint handling per [[SOP-004]] defines structured complaint processing, classification, and escalation procedures | Procedure review |
| C-014d | Protection | Vigilance reporting per [[SOP-013]] ensures safety-critical complaints are escalated to regulatory authorities within required timeframes | Procedure review |
| C-014e | Protection | Wife (Nisan Derinsu, director) designated as emergency backup for serious incident reporting if Sarp is unreachable | Backup designation documentation |
| C-014f | Protection | FAQ popup on contact page answers common questions automatically, reducing volume and ensuring Sarp's attention is available for serious complaints | UI review |

| Attribute | Detail |
|---|---|
| **Residual Severity** | S3 (Serious) |
| **Residual Probability** | P1 (Rare) |
| **Residual Risk Level** | **Acceptable** |
| **Justification** | Sarp's demonstrated track record of rapid response (typically 5-10 minutes), the structured complaint handling process per [[SOP-004]], and the emergency backup designation reduce the probability of delayed response to Rare. The FAQ popup reduces complaint volume, ensuring capacity for serious issues. The vigilance procedure per [[SOP-013]] provides a separate escalation path for safety-critical events. |

---

## 5. Risk Analysis Summary Table

The following table provides a consolidated view of all identified hazards, their initial and residual risk levels.

| ID | Hazard | Initial S | Initial P | Initial Risk | Residual S | Residual P | Residual Risk |
|---|---|---|---|---|---|---|---|
| H-001 | AI validates self-harming behavior | S5 | P2 | Unacceptable | S5 | P1 | ALARP |
| H-002 | AI provides inappropriate therapeutic advice | S3 | P3 | ALARP | S3 | P2 | ALARP |
| H-003 | AI role confusion | S3 | P3 | ALARP | S2 | P2 | Acceptable |
| H-004 | AI generates content in wrong language | S2 | P3 | ALARP | S2 | P2 | Acceptable |
| H-005 | User interprets AI output as medical diagnosis | S4 | P4 | Unacceptable | S3 | P2 | ALARP |
| H-006 | User becomes overly dependent on AI therapy | S4 | P3 | Unacceptable | S3 | P2 | ALARP |
| H-007 | Crisis situation not properly handled | S5 | P3 | Unacceptable | S5 | P1 | ALARP |
| H-008 | Data breach exposing therapy conversations | S4 | P2 | ALARP | S4 | P1 | ALARP |
| H-009 | System unavailability during user crisis | S4 | P2 | ALARP | S3 | P1 | Acceptable |
| H-010 | AI model change degrades therapeutic quality | S3 | P3 | ALARP | S3 | P2 | ALARP |
| H-011 | Prompt injection causes harmful AI behavior | S4 | P3 | Unacceptable | S3 | P2 | ALARP |
| H-012 | Session data sent via unencrypted email | S3 | P3 | ALARP | S3 | P2 | ALARP |
| H-013 | User under 19 accesses the platform | S4 | P2 | ALARP | S3 | P2 | ALARP |
| H-014 | Delayed response to serious complaint | S3 | P2 | ALARP | S3 | P1 | Acceptable |
| H-015 | AI reinforces toxic/abusive behavior patterns | S4 | P3 | Unacceptable | S3 | P2 | ALARP |

### 5.1 Risk Level Distribution After Controls

| Risk Level | Count | Hazard IDs |
|---|---|---|
| **Unacceptable** | 0 | -- |
| **ALARP** | 11 | H-001, H-002, H-005, H-006, H-007, H-008, H-010, H-011, H-012, H-013, H-015 |
| **Acceptable** | 4 | H-003, H-004, H-009, H-014 |

No residual risks remain at the Unacceptable level. All hazards have been reduced to ALARP or Acceptable.

## 6. Overall Residual Risk Assessment

### 6.1 Assessment Summary

After the application of all risk control measures documented in Section 4, the overall residual risk profile of the Therapeak medical device is assessed as follows:

**All 15 identified hazards have been reduced to either ALARP (11 hazards) or Acceptable (4 hazards).** No unacceptable residual risks remain.

The highest residual risks are associated with:

1. **H-001 (AI validates self-harming behavior)** and **H-007 (Crisis not properly handled)** -- both retain Catastrophic severity (S5) at Rare probability (P1), classified as ALARP. These represent the inherent risk floor for any AI-based mental health technology: no software system can provide an absolute guarantee against harmful output or guarantee proper crisis intervention in all circumstances.

2. **H-008 (Data breach)** -- retains Critical severity (S4) at Rare probability (P1), classified as ALARP. This reflects the inherent sensitivity of therapy data and the reality that no system can eliminate breach risk entirely.

### 6.2 Cumulative Risk Consideration

The hazard analysis identifies potential interactions between hazards that could amplify risk:

- **H-011 (Prompt injection) could trigger H-001, H-002, or H-003:** This is mitigated because Claude's safety layer operates independently of the system prompt, maintaining core safety behaviors even if the system prompt is partially overridden.
- **H-009 (System unavailability) combined with H-007 (Crisis):** This is mitigated by the contraindication for crisis use and the presence of emergency resources on the homepage that remain accessible even during system issues.
- **H-010 (Model change) could trigger H-002 or H-003:** This is mitigated by the testing protocol, post-deployment monitoring, and rollback capability.

No identified hazard interactions create an unacceptable cumulative risk.

### 6.3 Comparison with State of the Art

The risk control measures implemented for Therapeak are consistent with or exceed the state of the art for AI-based mental health SaMD:

- **Prompt engineering:** 160-200+ safety instructions per session exceeds typical industry practice for conversational AI safety.
- **Model-level safety:** Delegation of crisis handling to Anthropic Claude's built-in safety layer leverages the most advanced commercially available AI safety training.
- **Automated monitoring:** The `FLAG_SWITCHED_ROLES` and `FLAG_DID_NOT_RESPOND` systems provide population-level quality monitoring that is uncommon in comparable products.
- **Multi-provider fallback:** The multi-layer infrastructure redundancy (requests routed via OpenRouter gateway through Vertex AI, Bedrock, and Anthropic API) provides availability guarantees exceeding single-provider architectures.

## 7. Benefit-Risk Analysis

### 7.1 Clinical Benefits

Therapeak provides the following clinical benefits to its target population (adults 19+ with mild to moderate mental health symptoms):

| Benefit | Description |
|---|---|
| **Accessibility** | 24/7 availability removes barriers of scheduling, geography, waiting lists, and cost that prevent access to human therapy. Mental health care demand significantly exceeds supply globally. |
| **Affordability** | Subscription pricing (e.g., EUR 59-79/4 weeks) is substantially lower than traditional therapy, making mental health support accessible to users who cannot afford regular sessions. |
| **Reduced stigma** | AI-based interaction removes the social barrier of disclosing mental health struggles to another person, encouraging engagement by users who would not otherwise seek help. |
| **Consistency** | AI provides consistent therapeutic quality without therapist burnout, bad days, or scheduling conflicts. Available in 20+ languages. |
| **Self-management support** | Structured therapeutic sessions, mood tracking, and clinical-style reports support users in understanding and managing their symptoms, potentially complementing professional care. |
| **Immediacy** | No waiting lists, no appointment scheduling. Users can access therapeutic support at the moment they need it. |

### 7.2 Benefit-Risk Determination

For each ALARP residual risk, the benefit-risk balance is evaluated:

| Hazard | Residual Risk | Benefit-Risk Justification |
|---|---|---|
| H-001 (AI validates self-harm) | S5/P1 ALARP | The benefit of accessible mental health support for the target population (mild to moderate symptoms) outweighs the extremely rare risk of self-harm validation, given the multiple safety layers (Claude's built-in safety, 160-200+ prompt instructions, monitoring). Restricting the device to eliminate this risk would remove all therapeutic benefit. |
| H-002 (Inappropriate advice) | S3/P2 ALARP | The benefit of personalized therapeutic guidance for users who may not access traditional therapy outweighs the unlikely risk of inappropriate advice, particularly given the IFU statements that all guidance is informational and user-discretionary. |
| H-005 (Diagnostic misinterpretation) | S3/P2 ALARP | The benefit of clinical-style reports that help users understand their symptoms and share information with healthcare professionals outweighs the risk of misinterpretation, given the multiple disclaimers embedded in reports and the IFU. |
| H-006 (Over-dependency) | S3/P2 ALARP | The benefit of accessible, affordable mental health support outweighs the risk of over-dependency, given daily session time caps, IFU guidance on complementary use, and the reality that many users in the target population have no alternative support. |
| H-007 (Crisis mishandling) | S5/P1 ALARP | The benefit of providing mental health support to the broad target population outweighs the extremely rare risk of crisis mishandling, given the explicit contraindication for crisis use, Claude's robust crisis detection and response, and the homepage emergency resources. Restricting the device to eliminate this risk would remove therapeutic benefit for the vast majority of users who are not in crisis. |
| H-008 (Data breach) | S4/P1 ALARP | The benefit of storing therapy data to enable continuity of care (session summaries, reports, progress tracking) outweighs the rare risk of breach, given the security controls in place. Eliminating data storage would remove the ability to provide longitudinal therapeutic support. |
| H-010 (Model change degradation) | S3/P2 ALARP | The benefit of maintaining and improving AI therapeutic capabilities through model updates outweighs the risk of temporary quality degradation, given the testing and rollback capabilities. |
| H-011 (Prompt injection) | S3/P2 ALARP | The benefit of providing a text-based therapeutic interface (essential to the device's function) outweighs the risk of prompt injection, given Claude's built-in injection resistance and the extensive system prompt. |
| H-012 (Unencrypted email) | S3/P2 ALARP | The benefit of email notifications (enabling users to review sessions without logging in, supporting engagement) outweighs the risk of email interception, given opportunistic TLS and EU-based infrastructure. |
| H-013 (Minor access) | S3/P2 ALARP | The age gate with 19+ threshold provides proportionate protection. The benefit of accessible registration (without onerous ID verification) outweighs the risk of determined circumvention by minors. |
| H-015 (Toxic behavior reinforcement) | S3/P2 ALARP | The benefit of providing empathetic therapeutic support outweighs the risk of inadvertent reinforcement, given the prompt-level relationship protection instructions and Claude's nuanced handling of interpersonal dynamics. |

### 7.3 Conclusion

**The overall residual risk of the Therapeak medical device is acceptable when weighed against the clinical benefits.**

The device addresses a significant unmet need -- accessible, affordable mental health support for adults with mild to moderate symptoms -- and the risk control measures reduce all identified risks to ALARP or Acceptable. The highest residual risks (H-001, H-007) are inherent to the nature of AI-based mental health technology and cannot be eliminated without removing the device's therapeutic function entirely.

The benefit-risk balance is favorable because:

1. The target population (mild to moderate symptoms, home use) represents a lower-acuity user group where the consequences of AI limitations are generally less severe than in acute or clinical settings.
2. The device is explicitly positioned as informational and complementary, not as a replacement for professional care.
3. Multiple independent safety layers (Anthropic's model-level safety, Therapeak's prompt engineering, automated monitoring, manual review) provide defense in depth.
4. Post-market surveillance via [[SOP-009]] ensures regular monitoring and timely response to emerging risks.

## 8. Post-Market Risk Monitoring

Risk management is a continuous activity throughout the product lifecycle. The following mechanisms ensure ongoing risk monitoring and updating of this risk management file:

| Mechanism | Frequency | Responsibility | Reference |
|---|---|---|---|
| Session quality monitoring (ChatDebugFlags) | Continuous (automated) | System (automated), reviewed by Sarp Derinsu | ChatDebugFlag system |
| Manual session review | 1-2 sessions per week | Sarp Derinsu | Session review logs |
| Telescope live monitoring | Continuous | Sarp Derinsu | Telescope dashboard |
| Complaint analysis | Per complaint, summary quarterly | Sarp Derinsu | [[SOP-004]] |
| Post-market surveillance reporting | Annually (PSUR) | Sarp Derinsu | [[SOP-009]] |
| Vigilance reporting | Per event | Sarp Derinsu (backup: Nisan Derinsu) | [[SOP-013]] |
| Risk management file review | At least annually and before each release | Sarp Derinsu | [[PLN-001]] |
| Trustpilot and user feedback monitoring | Ongoing | Sarp Derinsu | [[SOP-009]] |

When post-market information indicates a new hazard or a change in risk level for an existing hazard, this risk management file shall be updated following the process defined in [[SOP-002]] and change control per [[SOP-017]].

## 9. Risk Management File Review Record

| Review Date | Reviewer | Outcome | Notes |
|---|---|---|---|
| 2026-03-01 | Sarp Derinsu | Approved | Initial risk management file for software version 1.0 release. All residual risks acceptable or ALARP with positive benefit-risk balance. |

## 10. References

| Document | ID |
|---|---|
| Risk Management Procedure | [[SOP-002]] |
| Risk Management Plan | [[PLN-001]] |
| CAPA Procedure | [[SOP-003]] |
| Complaint Handling Procedure | [[SOP-004]] |
| Post-Market Surveillance Procedure | [[SOP-009]] |
| Software Lifecycle Management Procedure | [[SOP-011]] |
| Clinical Evaluation Procedure | [[SOP-012]] |
| Vigilance and Field Safety Procedure | [[SOP-013]] |
| Cybersecurity Management Procedure | [[SOP-016]] |
| Change Management Procedure | [[SOP-017]] |
| ISO 14971:2019 | Medical devices -- Application of risk management to medical devices |
| ISO/TR 24971:2020 | Medical devices -- Guidance on the application of ISO 14971 |
| EU MDR 2017/745 | Regulation on medical devices, Annex I (General Safety and Performance Requirements) |
| MDCG 2019-16 rev.1 | Guidance on cybersecurity for medical devices |
| MDCG 2019-11 | Guidance on qualification and classification of software |
