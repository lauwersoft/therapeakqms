# QMS Admin Guide — Operational Playbook

*Last updated: April 1, 2026*

Everything you need to know to keep the QMS running. Structured as "when X → do Y → fill Z."

---

## What is this QMS?

A Quality Management System (QMS) proves your company builds safe medical devices. Scarlet (Notified Body) reviews these documents to decide if Therapeak gets a CE mark.

The "what" and "how" = SOPs (procedures). The "proof" = records (filled forms, meeting notes, reports).

**Golden rule:** Say what you do, do what you say. If a SOP says you do X, you must actually do X. If you can't, change the SOP.

---

## Part 1: Before CE Marking (NOW)

These tasks must be completed before or as part of the CE marking submission.

### MUST Complete

- [ ] **Activate crisis protocol in all conversation jobs** — Uncomment crisis protocol in all 6 conversation job files. Documented in RA-001 as required control.
- [ ] **Add FLAG_CRISIS to ChatDebugFlag** — New flag type for crisis detection. Needed for PMS.
- [ ] **Implement `app:purge-deleted-users`** — Artisan command to permanently delete data for users soft-deleted >180 days ago. Schedule daily. For GDPR requests, run manually.
- [ ] **Create Scarlet auditor account** — "auditor" role on QMS platform.
- [ ] **Verify server backups** — Check Hetzner panel, enable if needed.
- [ ] **Run software verification tests** — Open TST-001 (test procedures) and RPT-006 (execution report) side by side. Go through each of the 23 tests, follow the steps, mark pass/fail in RPT-006. That's it.
- [ ] **Run software validation** — Execute validation activities from TRC-001 Matrix 4 (VAL-001 to VAL-041). Document in a validation report.
- [ ] **Create medical device translations** — The `lang_backup` files need to be activated for `DEVICE_MODE=medical`.

### DONE

- [x] ~~Turn off OpenRouter data sharing~~ — Done March 25, 2026
- [x] ~~Sign Hetzner DPA~~ — Done March 25, 2026
- [x] ~~Add device_mode config~~ — Done (`settings.device_mode = 'wellness'`)
- [x] ~~Create Use Requirements (SPE-003)~~ — Done April 1, 2026
- [x] ~~Rebuild Software Requirements (SPE-001)~~ — Done April 1, 2026
- [x] ~~Create GSPR Checklist (CHK-001)~~ — Done April 1, 2026
- [x] ~~Create Risk Management Report (RPT-002)~~ — Done April 1, 2026
- [x] ~~Create Verification Test Specs (TST-001)~~ — Done April 1, 2026
- [x] ~~Create Traceability Matrix (TRC-001)~~ — Done April 1, 2026

### Fine For Now (Documented Honestly)

These are acknowledged in the QMS with compensating controls. Will NOT block CE marking:

- **No automated testing / no CI/CD / no staging** — Documented in SOP-011 with compensating controls (local testing, Telescope monitoring, rapid rollback)
- **Database not encrypted at rest** — Documented in SOP-016 with compensating controls (SSH-only access, localhost-only DB, Hetzner physical security, DPA)
- **Session summaries in emails** — Documented as known risk H-012 in RA-001. Acceptable with plan to evaluate link-only emails later
- **No formal usability testing** — PLN-006 documents the plan. Pre-market feedback serves as formative evaluation
- **Single-person operation** — Documented in QM-001. Nisan as vigilance backup. Suzan as regulatory support
- **API keys in .env only** — Documented in SOP-016 with SSH-only access as compensating control

---

## Part 2: After CE Marking (Post-Market)

These tasks activate once the medical device is placed on the market (`DEVICE_MODE=medical`).

### Ongoing Tasks

#### Complaints

**When:** A user reports a product problem (not billing/subscription issues)

**Do:**
1. Fill in **FM-004** (Complaint Form) on the QMS platform
2. Classify: safety-related or non-safety-related
3. If safety-related → also check if it meets serious incident criteria (see Serious Incidents below)
4. If non-safety-related → investigate, fix if needed, respond to user
5. If you see a pattern of similar complaints → open a CAPA (see CAPA below)

**Fill:** FM-004 (Complaint Form)

---

#### Serious Incidents (MOST TIME-CRITICAL)

**When:** A user was harmed or could have been harmed by the device. Examples: AI encouraged self-harm, user reports worsening condition directly caused by device, complete service outage during active crisis situation.

**Do:**
1. Report to competent authority (IGJ in the Netherlands) within **15 days** — or **2 days** if imminent public health threat
2. Follow SOP-013 reporting process
3. Fill in FM-004 (Complaint Form) if not already done
4. Open a CAPA (FM-001) for the root cause
5. Consider: does this need a Field Safety Corrective Action?
6. Notify Scarlet if field safety corrective action is taken

**Fill:** FM-004, CAPA (FM-001), vigilance report per SOP-013

**Who:** You. If unreachable >24 hours, Nisan knows the basics (trained per FM-006-REC-007).

---

#### CAPA (Corrective & Preventive Actions)

**When:** Something goes systematically wrong — a pattern of complaints, an audit finding, a safety issue, a process failure.

**Do:**
1. Open **FM-001** (CAPA Form) on the QMS platform
2. Classify severity: Critical / High / Medium / Low
3. Root cause analysis: use the "5 Why" method
4. Define corrective action (fix the problem) and preventive action (prevent recurrence)
5. Implement the actions
6. After implementation, verify effectiveness (did it actually fix it?)
7. If risk assessment needs updating → update RA-001
8. Close the CAPA

**Fill:** FM-001 (CAPA Form). Update RA-001 if new hazards identified.

---

#### Session Quality Monitoring

**When:** Continuous — automated. You review periodically.

**Do:**
1. Check ChatDebugFlag records for FLAG_SWITCHED_ROLES and FLAG_DID_NOT_RESPOND
2. Review 1-2 therapy sessions per week for harmful patterns
3. If flag rates increase → investigate cause (model update? prompt issue?)
4. If systematic issue found → open a CAPA

**Where to look:** Database `chat_debug_flags` table, Telescope dashboard

---

#### Post-Market Surveillance

**When:** Quarterly data review, annual report

**Do (quarterly):**
1. Review: complaint trends, Trustpilot feedback, session quality flag rates, mood tracking trends, retention metrics
2. Check literature for new evidence on AI mental health tools
3. Check EUDAMED for field safety notices on similar devices
4. If signals found → escalate to CAPA or risk management

**Do (annually):**
1. Write PMS Report (update RPT-001)
2. Include: complaint summary, flag trends, clinical performance data, literature review, risk management input
3. Submit as part of management review input

**Fill:** RPT-001 (PMS Report), updated annually

---

#### Management Review

**When:** Every 6 months (first: September 2026 after CE marking)

**Do:**
1. Fill in **FM-009** (Management Review Form)
2. Review inputs: complaints, CAPAs, audit findings, PMS data, supplier reviews, quality objectives, training status, process changes, regulatory changes
3. Document decisions and actions taken
4. Review resource adequacy (still sustainable as one person?)

**Fill:** FM-009 (Management Review Form)

---

#### Change Management

**When:** Before making changes to the medical device software, prompts, models, infrastructure, or documentation

**Do for significant changes** (new AI model family, new therapeutic claims, architecture changes):
1. Fill in **FM-003** (Change Request Form) BEFORE implementing
2. Full risk assessment — update RA-001 if needed
3. Notify Scarlet (Notified Body)
4. Update technical documentation
5. Re-verify/re-validate as needed
6. Wait for Scarlet feedback before deploying (unless urgent safety fix)

**Do for non-significant changes** (bug fixes, UI improvements, minor prompt tweaks):
1. Fill in **FM-003** (simplified format)
2. Quick risk review (confirm no safety impact)
3. Test and deploy normally
4. Minor version increment

**Do for AI model updates within Claude family** (e.g., Sonnet 4.5 → 4.6):
1. Fill in **FM-003** referencing the predetermined change control plan (SOP-017 Section 4.4)
2. Test with representative conversation scenarios
3. Deploy (can use limited rollout)
4. Monitor for 7 days — check ChatDebugFlags and complaints
5. If any issues → escalate to full significant change evaluation

**Fill:** FM-003 (Change Request Form)

---

#### Supplier Review

**When:** Annually for critical suppliers (Hetzner, Anthropic, OpenRouter, OpenAI, Stripe, AWS SES). Every 2 years for non-critical.

**Do:**
1. Fill in **FM-005** (Supplier Evaluation Form) for each supplier
2. Check: DPA still valid? Service adequate? Any security incidents? Certifications current?
3. Update LST-001 (Approved Supplier List) if changes
4. Include findings in management review input

**Fill:** FM-005 (Supplier Evaluation Form), update LST-001

---

#### Training Records

**When:** When you or anyone else learns something relevant to the QMS (new regulation, new tool, audit training, etc.)

**Do:**
1. Fill in **FM-006** (Training Record Form)
2. Record: what was learned, when, evidence of competency
3. Update LOG-001 (Training Log) summary

**Fill:** FM-006 (Training Record Form), update LOG-001

---

#### Internal Audit

**When:** Annually

**Do:**
1. Schedule external auditor (independence required — cannot audit your own work)
2. Provide auditor with QMS platform access
3. Auditor fills in **FM-002** (Internal Audit Form)
4. Nonconformities → open CAPAs (FM-001)
5. Address findings before next Scarlet audit

**Fill:** FM-002 (Internal Audit Form), FM-001 for any CAPAs

---

#### Design Reviews

**When:** Before major software releases or significant design changes

**Do:**
1. Fill in **FM-007** (Design Review Form)
2. Review against: requirements (SPE-001), risk controls (RA-001), usability (PLN-006)
3. If risk assessment needs updating → update RA-001 and RPT-002

**Fill:** FM-007 (Design Review Form)

---

#### Traceability Maintenance

**When:** When adding/changing software requirements or risk controls

**Do:**
1. Update SPE-001 (add/modify requirements)
2. If new use requirement → update SPE-003
3. Update TRC-001 traceability matrix
4. Add/update verification test specification in TST-001
5. If risk controls changed → update RA-001 and the risk control matrix in TRC-001

**Fill:** SPE-001, SPE-003, TRC-001, TST-001 as needed

---

## Part 3: Publish & Document Control

**Publish changes:**
Go to Unpublished Changes → review → click Publish. This creates a git commit = permanent audit trail. ISO 13485 requires controlled document changes.

**Review comments:**
Check weekly. "Required Change" comments block document approval. Click "Resolve" when addressed, add a note.

---

## Part 4: Audit Preparation

### What Scarlet Audits

**Stage 1:** Document review — checks completeness. Primarily a desk review.
**Stage 2:** Implementation audit — verifies you're doing what documents say. Need to demonstrate processes and show records.

### Questions the Auditor Will Ask (and Your Answers)

**"Describe your quality management system."**

"Our QMS is built on ISO 13485:2016 and EU MDR 2017/745. It covers all processes from design through post-market surveillance. All documents are managed in our QMS platform with git-based version control for full traceability. I'm the sole operator with support from a regulatory consultant. The QMS was formally established on March 1, 2026."

Show: QM-001, the History page, the publish workflow.

---

**"How do you control documents?"**

"All documents are created and edited in our QMS platform. Changes go through a review process — editors and auditors can leave comments, and 'required change' comments block approval. When approved, documents are published which creates a git commit — an immutable audit trail."

Show: SOP-001, the History page, the publish workflow.

---

**"Walk me through your complaint handling process."**

"Complaints come in via email or the in-app contact form. I evaluate each for safety impact. If there's a potential serious incident, I follow SOP-013 for vigilance reporting. Non-safety complaints are investigated and fixed. Everything is logged in FM-004."

Show: SOP-004, FM-004.

---

**"How do you handle a serious incident?"**

"I report to the competent authority (IGJ) within 15 days — 2 days if imminent risk. I use SOP-013. My wife Nisan is trained as emergency backup."

Show: SOP-013, FM-006-REC-007 (Nisan's training record).

---

**"How do you manage design changes?"**

"Every change is evaluated using FM-003. I classify as significant or non-significant per MDCG 2020-3. Significant changes require full risk assessment and NB notification. We have a predetermined change control plan for AI model updates within the Claude family."

Show: SOP-017, FM-003.

---

**"How do you manage risk?"**

"We follow ISO 14971. Our risk management file identifies 15 hazards for AI therapy software. Controls are applied in priority order: safe design, protective measures, information for safety. All residual risks are acceptable or ALARP with documented justification."

Show: PLN-001, RA-001, RPT-002.

---

**"What clinical evidence do you have?"**

"Our Clinical Evaluation Report follows MDCG 2020-1. We have seven key studies including three RCTs covering 39,000+ participants. Pre-market experience from our wellness version shows no safety concerns."

Show: CE-001, PLN-002, PLN-003.

---

**"How do you monitor the device after it's on the market?"**

"Our PMS system collects data from complaints, Trustpilot, session quality flags, mood tracking, and retention metrics. Quarterly reviews and annual PMS Report. PMCF plan for ongoing clinical data."

Show: SOP-009, PLN-004, RPT-001.

---

**"How do you ensure traceability?"**

"We maintain a traceability matrix (TRC-001) with four matrices: use requirements to software requirements, software requirements to verification tests, risk controls to requirements to verification, and use requirements to validation. All chains are complete with no gaps."

Show: TRC-001, SPE-003, SPE-001, TST-001.

---

**"You're the only person — how do you maintain objectivity?"**

"I use structured self-review with the Design Review Form against defined criteria. I consult with Nisan (psychology background) for therapeutic aspects and Suzan (regulatory consultant) for regulatory impact. This is proportionate for a small manufacturer."

Show: QM-001, SOP-007, FM-007.

---

**"What about cybersecurity?"**

"Our procedure (SOP-016) is based on MDCG 2019-16. Server is at Hetzner in Nuremberg, Germany. SSH-only access, 2FA on critical accounts. DPA signed, TUV audit report on file. Localhost-only database."

Show: SOP-016, CER-001, CER-002.

---

### Tips

1. Be honest. "I'll check that" is better than guessing.
2. Don't volunteer extra information.
3. Show the QMS platform — git history, comments, publish workflow.
4. Know where everything is. Practice finding documents fast.
5. Reference your SOPs: "As documented in SOP-004, section 4.2..."
6. Records matter most. Training records, management review, supplier evaluations = proof.
7. Nonconformities are normal. "We'll open a CAPA for that" = correct response.

---

## Quick Reference: All Forms and When to Use Them

| Form | When to Use | Triggered By |
|------|------------|-------------|
| FM-001 (CAPA) | Systematic problem needs fixing | Complaints, audits, PMS signals, process failures |
| FM-002 (Internal Audit) | Annual internal audit | Audit schedule |
| FM-003 (Change Request) | Before changing the device | Software changes, model updates, infrastructure changes |
| FM-004 (Complaint) | User reports a product problem | User contact, Trustpilot review |
| FM-005 (Supplier Evaluation) | Annual/biennial supplier review | Review schedule |
| FM-006 (Training Record) | Someone learns something QMS-relevant | Training events, self-study |
| FM-007 (Design Review) | Before major releases or design changes | Development milestones |
| FM-008 (Software Release) | Before releasing a new version | Release decision |
| FM-009 (Management Review) | Every 6 months | Review schedule |

---

## Your QMS at a Glance

| Category | Count | Key Documents |
|----------|-------|-----------|
| Quality Manual | 1 | QM-001 |
| Policies | 1 | POL-001 |
| SOPs | 17 | SOP-001 to SOP-017 |
| Plans | 6 | PLN-001 to PLN-006 |
| Forms | 9 | FM-001 to FM-009 |
| Reports | 6 | RPT-001 to RPT-005, CE-001 |
| Specifications | 9 | SPE-001 to SPE-003, LST-001, LOG-001, CHK-001, TRC-001, DOC-001, CTX-001 |
| Test Specifications | 1 | TST-001 |
| Risk Management | 1 | RA-001 |
| Labels / IFU | 1 | LBL-001 |
| Diagrams | 2 | DWG-001, DWG-002 |
| **Total** | **54** | (45 markdown + 9 form JSON) |

---

## What Claude Code Does For You

- Creates and updates QMS documents
- Reads and responds to comments
- Creates records (filled forms, reports)
- Maintains cross-references and traceability
- Drafts PMS reports, clinical evaluations, risk assessments

## What Suzan Does

- Reviews QMS documents for regulatory adequacy
- Advises on Scarlet's expectations
- Guides audit preparation
- Answers regulatory questions

---

## Key Terms

| Term | What it means |
|------|--------------|
| **QMS** | Quality Management System |
| **SOP** | Standard Operating Procedure |
| **CAPA** | Corrective and Preventive Action |
| **PMS** | Post-Market Surveillance |
| **PMCF** | Post-Market Clinical Follow-up |
| **NB** | Notified Body (Scarlet) |
| **CE Mark** | EU medical device certification |
| **MDR** | Medical Device Regulation (EU 2017/745) |
| **ISO 13485** | Medical device QMS standard |
| **IFU** | Instructions for Use |
| **SaMD** | Software as a Medical Device |
| **FSCA** | Field Safety Corrective Action |
| **UDI** | Unique Device Identifier |
| **EUDAMED** | European Database on Medical Devices |
| **GSPR** | General Safety and Performance Requirements |
