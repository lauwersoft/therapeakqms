# QMS Admin Guide

*Last updated: March 25, 2026*

Everything you need to know to keep the QMS running. Written for someone who has never worked with a QMS before.

---

## What is this QMS?

A Quality Management System (QMS) is a set of documents and processes that prove your company builds safe medical devices. The Notified Body (Scarlet) will review these documents to decide if Therapeak gets a CE mark.

Think of it as a rulebook your company follows. The documents describe **what** you do, **how** you do it, and **proof** that you actually do it. The "what" and "how" are SOPs (procedures). The "proof" are records (filled forms, meeting notes, reports).

---

## Action Items Before April 7

- [ ] **Sign Hetzner DPA** — Go to hetzner.com/legal/data-processing-agreement and sign it. Required for GDPR.
- [ ] **Verify server backups** — Check Hetzner panel: are backups enabled? What frequency?
- [ ] **Create Scarlet auditor account** — Create a user with "auditor" role on the QMS platform for Scarlet.
- [ ] **Review QMS with Suzan** — Walk through all documents with your consultant before March 31 audit.
- [ ] **Internal audit (March 31)** — Jurist will audit. Address any findings before April 7.

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
**When:** Every 6 months
**What:** Review if the QMS is working. Any complaints? Any CAPAs? Any audit findings? Fill in the Management Review form.
**Why:** ISO 13485 clause 5.6 — top management must review the QMS periodically.

### Log Complaints
**When:** When a user reports a product problem (not billing issues)
**What:** Log it in the Complaint Form. If serious (someone got hurt or could have), also fill in the CAPA form.
**Why:** EU MDR Article 87-92 requires tracking complaints and reporting serious incidents.

### Post-Market Surveillance
**When:** Quarterly review, annual report
**What:** Collect data about how the device performs: user feedback, mood rating trends, complaints, AI quality. Write a brief PMS report. Claude can help draft it.
**Why:** EU MDR Articles 83-86 — you must actively monitor your device after it's on the market.

### Training Records
**When:** When you learn something new about the QMS
**What:** When Suzan teaches you something, or when a process changes — record it in the Training Form. Note what you learned, when, and who taught you.
**Why:** ISO 13485 clause 6.2 — people working on the QMS must be competent and trained.

### CAPA (Corrective & Preventive Actions)
**When:** When something goes wrong and needs fixing
**What:** Open a CAPA. Document: what went wrong, why (root cause), what you did to fix it, how you'll prevent recurrence.
**Why:** ISO 13485 clause 8.5.2/8.5.3 — systematic problem-fixing process.

---

## Quick Reference

| Task | Frequency | Done by |
|------|-----------|---------|
| Publish document changes | As needed | You |
| Review comments | Weekly | You |
| Log complaints | When they come in | You |
| PMS data review | Quarterly | You + Claude |
| Management review | Every 6 months | You |
| Supplier review | Annually | You |
| Internal audit | Annually | External auditor |
| PMS report | Annually | You + Claude |
| Update QMS documents | When processes change | Claude |
| Create/update records | As needed | Claude |
| CAPA | When problems arise | You |
| Training records | When you learn something | You |

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
