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
- [ ] **Set device_mode config** — Already done (`settings.device_mode = 'wellness'`). When ready to launch as medical device, flip to `'medical'`.
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
