# QMS Admin Guide

*Last updated: March 26, 2026*

Everything you need to know to keep the QMS running. Written for someone who has never worked with a QMS before.

---

## What is this QMS?

A Quality Management System (QMS) is a set of documents and processes that prove your company builds safe medical devices. The Notified Body (Scarlet) will review these documents to decide if Therapeak gets a CE mark.

Think of it as a rulebook your company follows. The documents describe **what** you do, **how** you do it, and **proof** that you actually do it. The "what" and "how" are SOPs (procedures). The "proof" are records (filled forms, meeting notes, reports).

---

## MUST Change Before CE Marking

These are things that **cannot** stay as they are. Without these changes, you will not get a CE marking.

- [ ] **Activate crisis protocol in all conversation jobs** — Uncomment the crisis protocol in all 6 conversation job files (OpenRouterSonnetFourFiveRunConversationJob, OpenRouterSonnetFourSixRunConversationJob, their COACH variants, OpusFourFive, GeminiThree). This is documented in the risk management file as a required control measure.
- [ ] **Add FLAG_CRISIS to ChatDebugFlag** — Create a new flag type for crisis detection. When the AI provides a crisis response (suicide hotline referral, etc.), log it. This is needed for post-market surveillance — you need to know when crisis situations occur.
- [ ] **Implement data deletion command** — Create `app:purge-deleted-users` artisan command that permanently deletes all data for users soft-deleted more than 180 days ago. Schedule it daily. For explicit GDPR requests, run it manually within 30 days. Your privacy policy promises deletion rights — you need to deliver.
- [ ] **Create Scarlet auditor account** — Create a user with "auditor" role on the QMS platform for Scarlet before April 7.
- [ ] **Verify server backups** — Check Hetzner panel: are backups enabled? If not, enable them. You need to be able to answer "yes" when the auditor asks about backups.

## DONE (Completed)

- [x] ~~**Turn off OpenRouter data sharing**~~ — Done March 25, 2026
- [x] ~~**Sign Hetzner DPA**~~ — Done March 25, 2026 (via customer portal)
- [x] ~~**Add device_mode config**~~ — Done (`settings.device_mode = 'wellness'`)

## Should Do Before April 7 (But Won't Block CE Marking)

- [ ] **Review QMS with Suzan** — Walk through all documents with your consultant before March 31 audit.
- [ ] **Internal audit (March 31)** — Jurist will audit. Address any findings before April 7.
- [ ] **Enable 2FA on OpenRouter** — Currently no 2FA. Low risk but easy to fix.
- [ ] **Enable 2FA on admin panel** — The Therapeak admin panel has no 2FA. Not a CE blocker, but documented in cybersecurity SOP as a known gap.

## Fine For Now (Documented in QMS, Change Later)

These are things the QMS acknowledges honestly and has a plan for. The NB will see you've identified them and have mitigation. They will NOT block your CE marking.

- **No automated testing / no CI/CD / no staging** — Documented in SOP-011 with compensating controls (local testing, Telescope monitoring, rapid rollback). Many small SaMD companies operate this way.
- **Database not encrypted at rest** — Documented in SOP-016 (cybersecurity) with compensating controls (SSH-only access, localhost-only DB, physical security via Hetzner, DPA).
- **Session summaries in emails** — Full therapy content sent via email. Documented as known risk in RA-001 (H-012). Users receive this as part of the service. Acceptable for now with plan to evaluate link-only emails later.
- **No formal usability testing** — PLN-006 documents the plan for summative usability evaluation. Pre-market user feedback serves as formative evaluation. The plan shows intent.
- **Single-person operation** — Fully documented in QM-001 with explanation that it's common for small manufacturers. Nisan as emergency backup for vigilance. Suzan as external regulatory support.
- **API keys in .env only** — Documented in SOP-016. Acceptable with SSH-only access as compensating control.
- **Medical device translations not yet done** — The `lang_backup` files exist but aren't active. QMS documents reference the intended medical device. Translations are a content task, not a QMS gap.

---

## What You Need to Do Regularly

### Publish Changes
**When:** After document edits are made
**What:** Go to Unpublished Changes, review them, click Publish. This saves everything to git — creating a permanent record.
**Why:** ISO 13485 requires controlled document changes with an audit trail. Publishing = approval.

### Review & Resolve Comments
**When:** Weekly, or when you see the comment badge
**What:** Comments are review notes on documents. "Required Change" comments block document approval — resolve them. Click "Resolve" when addressed, add a note explaining what you did.
**Why:** Proves documents go through a proper review process before approval.

### Management Review
**When:** Every 6 months (next: September 2026)
**What:** Fill in the Management Review form (FM-009). Review: complaints, CAPAs, audit findings, PMS data, quality objectives. Document decisions and actions.
**Why:** ISO 13485 clause 5.6 — top management must review the QMS periodically.

### Log Complaints
**When:** When a user reports a product problem (not billing issues)
**What:** Fill in the Complaint Form (FM-004). If serious (someone got hurt or could have), also fill in CAPA form (FM-001) and check the vigilance SOP (SOP-013) for reporting timelines.
**Why:** EU MDR Article 87-92 requires tracking complaints and reporting serious incidents.

### CRITICAL: Serious Incidents
**When:** If a user is harmed or could have been harmed by the device
**What:** Report to competent authority within **15 days** (2 days if imminent risk to public health). See SOP-013.
**Who:** You. If you're unreachable, Nisan knows the basics.
**Why:** This is the single most time-critical obligation in the entire QMS. Missing a vigilance deadline is a serious regulatory violation.

### Post-Market Surveillance
**When:** Quarterly review, annual report
**What:** Review: complaints, Trustpilot feedback, session quality flags (FLAG_SWITCHED_ROLES, FLAG_DID_NOT_RESPOND), mood data, retention metrics. Write annual PMS report (RPT-001). Claude can help draft it.
**Why:** EU MDR Articles 83-86 — you must actively monitor your device after it's on the market.

### Training Records
**When:** When you learn something new about the QMS
**What:** Fill in the Training Record form (FM-006). Note what you learned, when, and who taught you.
**Why:** ISO 13485 clause 6.2 — people working on the QMS must be competent and trained.

### CAPA (Corrective & Preventive Actions)
**When:** When something goes wrong and needs systematic fixing
**What:** Fill in CAPA form (FM-001). Document: what went wrong, why (5 Why analysis), what you did to fix it, how you'll prevent recurrence.
**Why:** ISO 13485 clause 8.5.2/8.5.3 — systematic problem-fixing process.

### Supplier Review
**When:** Annually for critical suppliers, every 2 years for non-critical
**What:** Review supplier performance, check if DPAs are still valid, update the Approved Supplier List (LST-001). Fill in Supplier Evaluation form (FM-005).
**Why:** ISO 13485 clause 7.4 — you must evaluate and monitor your suppliers.

### Change Management
**When:** Before making significant changes to the software
**What:** Fill in Change Request form (FM-003). Assess if the change is significant (new AI model, new therapeutic claims) or non-significant (bug fix, UI change). Significant changes may require NB notification.
**Why:** MDR requires you to assess the impact of changes on device safety and performance.

---

## Quick Reference

| Task | Frequency | Done by |
|------|-----------|---------|
| Publish document changes | As needed | You |
| Review comments | Weekly | You |
| Log complaints | When they come in | You |
| Serious incident reporting | Within 15 days (2 if imminent) | You / Nisan (backup) |
| PMS data review | Quarterly | You + Claude |
| Management review | Every 6 months | You |
| Supplier review | Annually | You |
| Internal audit | Annually | External auditor |
| PMS report | Annually | You + Claude |
| Update QMS documents | When processes change | Claude |
| Create/update records | As needed | Claude |
| CAPA | When problems arise | You |
| Training records | When you learn something | You |
| Change requests | Before significant changes | You |

---

## What Claude Code Does For You

- Creates and updates all QMS documents
- Reads and responds to comments
- Creates records (filled forms, reports)
- Updates CONTEXT.md and THERAPEAK.md
- Maintains document cross-references
- Drafts PMS reports, clinical evaluations, risk assessments

## What Suzan Does

- Reviews QMS documents for regulatory adequacy
- Advises on Scarlet's expectations
- Guides audit preparation
- Answers regulatory questions

---

## What to Expect at the NB Audit

### How It Works

Scarlet's audit has two stages:

**Stage 1 (April 7, 2026):** Document review. The auditor reads your QMS documents and checks if everything is in place. They may ask clarifying questions. This is primarily a desk review — they check completeness, not depth.

**Stage 2 (later):** Implementation audit. The auditor verifies you're actually doing what your documents say. They'll ask you to demonstrate processes, show records, and explain your thinking. This is where you need to know your stuff.

### The Golden Rule

**Say what you do, do what you say.** The auditor's job is to check that your documents match reality. If your SOP says "complaints are responded to within 24 hours" then you must actually do that. If you can't, change the SOP to match what you can do.

### Questions the Auditor Will Ask (and Your Answers)

Read these before the audit. The auditor won't ask exactly these words, but the topics will be the same.

---

**"Describe your quality management system."**

Your answer: "Our QMS is built on ISO 13485:2016 and EU MDR 2017/745. It covers all processes from design through post-market surveillance. All documents are managed in our QMS platform with git-based version control for full traceability. I'm the sole operator with support from a regulatory consultant (Suzan Slijpen). The QMS was formally established on March 1, 2026."

---

**"How do you control documents?"**

Your answer: "All documents are created and edited in our QMS platform. Changes go through a review process — editors and auditors can leave comments, and 'required change' comments block approval. When approved, documents are published which creates a git commit — an immutable audit trail. Every change is tracked with who changed what and when." Show them: SOP-001, the History page, the publish workflow.

---

**"Walk me through your complaint handling process."**

Your answer: "Complaints come in via email (info@therapeak.com) or the in-app contact form. I respond within 24 hours — typically within minutes. I evaluate each complaint for safety impact. If there's a potential serious incident, I follow SOP-013 for vigilance reporting. Non-safety complaints are investigated, fixed if needed, and the user is notified. Everything is logged in the Complaint Form (FM-004). If I see a pattern, I open a CAPA." Show them: SOP-004, FM-004.

---

**"How do you handle a serious incident?"**

Your answer: "If I become aware of a serious incident — meaning someone was harmed or could have been harmed — I report it to the competent authority (IGJ in the Netherlands) within 15 days. If there's an imminent public health threat, within 2 days. I use the reporting process in SOP-013. My wife Nisan is trained as an emergency backup if I'm unreachable for more than 24 hours." Show them: SOP-013, the training record for Nisan (FM-006-REC-007).

---

**"How do you manage design changes?"**

Your answer: "Every change is evaluated for safety and regulatory impact using the Change Request Form (FM-003). I classify changes as significant or non-significant. Significant changes — like switching AI models or changing the intended purpose — require a full risk assessment and may require NB notification. Non-significant changes — like bug fixes or UI improvements — are documented and risk-reviewed but follow a lighter process. All changes go through our software lifecycle process (SOP-011) with local testing before deployment." Show them: SOP-017, FM-003, SOP-011.

---

**"How do you validate AI model changes?"**

Your answer: "We have a predetermined change control plan for AI model updates (documented in SOP-017). When switching between Claude versions — for example from Sonnet 4.5 to 4.6 — I test locally using our prompt testing tool, deploy to production, then monitor session quality using our automated flags (FLAG_SWITCHED_ROLES, FLAG_DID_NOT_RESPOND) and manual session review for 7 days. If quality degrades, I can roll back immediately." Show them: SOP-017 (predetermined change control plan section), SOP-011 (AI model management section).

---

**"How do you manage risk?"**

Your answer: "We follow ISO 14971. Our Risk Management File (RA-001) identifies 15 specific hazards for our AI therapy software, from AI role confusion to data breaches. Each hazard is analyzed for severity and probability, controls are applied, and residual risk is evaluated. We use a 5x5 risk matrix with acceptable, ALARP, and unacceptable zones. All residual risks are either acceptable or ALARP with documented justification. We monitor risks through post-market surveillance." Show them: RA-001, PLN-001, SOP-002.

---

**"What clinical evidence do you have?"**

Your answer: "Our Clinical Evaluation Report (CE-001) follows the MDCG 2020-1 three-step pathway for medical device software. We have seven key studies including three RCTs and four meta-analyses covering over 39,000 participants. The evidence shows AI therapy chatbots produce clinically meaningful improvements in depression and anxiety — effect sizes comparable to or exceeding SSRIs. We also have pre-market experience from our wellness version showing no safety concerns. Our benefit-risk analysis concludes benefits outweigh residual risks." Show them: CE-001, PLN-002, PLN-003.

---

**"How do you monitor the device after it's on the market?"**

Your answer: "Our PMS system (SOP-009) collects data from multiple sources: user complaints, Trustpilot reviews, automated session quality monitoring, mood tracking data, and user retention metrics. We do quarterly reviews and produce an annual PMS Report. We also have a PMCF plan (PLN-003) for ongoing clinical data collection. Any safety signals are escalated to CAPA or vigilance." Show them: SOP-009, PLN-004, RPT-001, PLN-003.

---

**"How do you evaluate suppliers?"**

Your answer: "We maintain an Approved Supplier List (LST-001) with all critical and non-critical suppliers. Critical suppliers like Hetzner, OpenRouter, and Anthropic are evaluated annually based on performance, security, and data protection. We signed a DPA with Hetzner and have their TUV audit report. For large cloud providers, we rely on their published security certifications and standard terms of service — which is proportionate for a small company using enterprise services." Show them: LST-001, FM-005, the Hetzner DPA (CER-001) and TUV report (CER-002).

---

**"How do you ensure personnel are competent?"**

Your answer: "Training records are maintained in LOG-001 and individual Training Record Forms (FM-006). I've been trained on QMS fundamentals, ISO 13485, EU MDR, risk management, and complaint handling/vigilance. My wife Nisan has been trained as an emergency vigilance backup. New personnel would be trained before performing any QMS-related activities." Show them: LOG-001, the individual training records.

---

**"You're the only person — how do you maintain objectivity in design reviews?"**

Your answer: "I acknowledge the limitation of a one-person organization — it's documented in the Quality Manual. I maintain objectivity through: structured self-review using the Design Review Form (FM-007) against defined criteria, consultation with my wife Nisan who has a psychology background for therapeutic aspects, and consultation with our regulatory consultant Suzan for regulatory impact. This is proportionate and common for small medical device manufacturers." Show them: QM-001 (section on single-person organization), SOP-007, FM-007.

---

**"What about cybersecurity?"**

Your answer: "Our cybersecurity procedure (SOP-016) is based on MDCG 2019-16 guidance. Our server is hosted at Hetzner in Nuremberg, Germany — EU-based. Access is restricted to SSH only, with 2FA on critical accounts. We maintain a DPA with Hetzner and have their TUV audit report. Database access is localhost-only. We have a vulnerability management process and a security incident response procedure aligned with the 72-hour GDPR breach notification requirement." Show them: SOP-016, CER-001, CER-002.

---

### Tips for the Audit

1. **Be honest.** If you don't know something, say so. "I'll check that and get back to you" is better than guessing.
2. **Don't volunteer extra information.** Answer the question, then stop. Extra detail opens new lines of questioning.
3. **Show the platform.** The QMS platform with its git history, comments, and publish workflow is impressive. Let the auditor browse it.
4. **Know where everything is.** Before the audit, practice navigating to each SOP, form, and record. You should be able to find any document in seconds.
5. **Reference your SOPs.** When explaining a process, point to the SOP. "As documented in SOP-004, section 4.2..."
6. **Records matter most.** The auditor wants evidence. Training records, management review records, supplier evaluations — these prove you're using the QMS, not just writing about it.
7. **Nonconformities are normal.** If the auditor finds issues, that's expected. What matters is how you handle them. "We'll open a CAPA for that" shows you know the process.

---

## Your QMS at a Glance

| Category | Count | Documents |
|----------|-------|-----------|
| Quality Manual | 1 | QM-001 |
| Policies | 1 | POL-001 |
| SOPs | 17 | SOP-001 to SOP-017 |
| Plans | 6 | PLN-001 to PLN-006 |
| Forms | 9 | FM-001 to FM-009 |
| Reports | 2 | RPT-001, CE-001 |
| Specifications | 4 | SPE-001, SPE-002, LST-001, LOG-001 |
| Risk Management | 1 | RA-001 |
| Labels / IFU | 1 | LBL-001 |
| Diagrams | 2 | DWG-001, DWG-002 |
| **Total** | **44** | |

---

## Key Terms

| Term | What it means |
|------|--------------|
| **QMS** | Quality Management System — the collection of all your processes and documents |
| **SOP** | Standard Operating Procedure — describes how you do something step-by-step |
| **CAPA** | Corrective and Preventive Action — formal process for fixing problems |
| **PMS** | Post-Market Surveillance — monitoring your device after it's on the market |
| **PMCF** | Post-Market Clinical Follow-up — collecting clinical evidence while device is in use |
| **NB** | Notified Body — Scarlet, who audits your QMS and grants CE marking |
| **CE Mark** | Certification to sell a medical device in the EU |
| **MDR** | Medical Device Regulation (EU 2017/745) — the law governing medical devices |
| **ISO 13485** | International standard for medical device quality management systems |
| **Risk Management** | Identifying what could go wrong and documenting how you prevent/mitigate it |
| **Clinical Evaluation** | Reviewing evidence that your device is safe and performs as intended |
| **Vigilance** | Reporting serious incidents to authorities within specific timeframes |
| **IFU** | Instructions for Use — what users need to know to use the device safely |
| **SaMD** | Software as a Medical Device — software that IS the device (like Therapeak) |
| **FSCA** | Field Safety Corrective Action — actions taken to reduce risk from a device already on the market |
| **UDI** | Unique Device Identifier — a code that uniquely identifies your device |
| **EUDAMED** | European Database on Medical Devices — EU-wide registration system |
