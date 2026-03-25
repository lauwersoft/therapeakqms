# Action Items for Sarp

Things YOU need to do that Claude Code cannot do for you. Ordered by priority.

---

## BEFORE March 31 (Internal Audit)

### 1. Sign Data Processing Agreement with Hetzner
Go to Hetzner's DPA page and sign it. This is a GDPR requirement for processing health data on their servers.

**What to do:**
- Visit: https://www.hetzner.com/legal/data-processing-agreement
- Sign/accept the DPA
- Save a copy (PDF) to share with auditor if asked
- Tell me once done so I can reference it in supplier management documents

### 2. Verify Backups
Check your Hetzner server panel — are backups enabled? How often?

**What to check:**
- Hetzner Cloud Console → your server → Backups
- Is it enabled? What frequency?
- Tell me the answers and I'll document it

### 3. Consider Adding Crisis Flag
Even though Anthropic handles crisis situations at the model level, adding a `FLAG_CRISIS` to your ChatDebugFlag model would let you track when crisis situations occur. This is useful for post-market surveillance.

**Not urgent** — but would strengthen your PMS data.

### 4. Create Scarlet Auditor Account
Create a user account on the QMS platform for Scarlet with the "auditor" role. They'll use this to review your QMS.

---

## BEFORE April 7 (NB Stage 1)

### 5. Review QMS Documents with Suzan
After I create all QMS documents, walk through them with Suzan. She knows what Scarlet expects.

### 6. Complete Internal Audit (March 31)
The jurist will audit. Address any findings before April 7.

### 7. Assign Software Version Number
We need a version for the CE-marked release. I'll use `1.0.0` in the technical documentation. If you want a different scheme, tell me.

---

## ONGOING (Admin Guide — will also be on a dedicated page in the QMS platform)

The QMS platform will have an admin-only page explaining everything below in simple terms. But here's the overview:

### What You Need to Do Regularly

| Task | How Often | What It Means | What You Do |
|------|-----------|---------------|-------------|
| **Publish changes** | When you/Claude make document edits | Git tracking for audit trail | Click "Publish" on the changes page |
| **Review complaints** | When they come in | Track user issues for safety | Log them in the complaint form |
| **Management review** | Every 6 months | Review if QMS is working | Fill in the management review form (I'll pre-fill most of it) |
| **Check comments** | Weekly | Review/resolve document feedback | Open the QMS, check unresolved comments |
| **Update documents** | When processes change | Keep QMS current | Edit the document or tell Claude to do it |
| **Post-market surveillance** | Quarterly | Collect safety/performance data | Review user feedback, fill in PMS report form |
| **Supplier review** | Annually | Check suppliers are still appropriate | Review supplier list, note any changes |
| **Internal audit** | Annually | Check QMS compliance | Hire an auditor (like the March 31 one) |
| **CAPA** | When issues arise | Fix problems systematically | Fill in CAPA form when something goes wrong |
| **Training** | When new processes/people join | Ensure everyone knows the QMS | Record training in the training form |

### What Claude Code Does For You
- Creates and updates all QMS documents
- Reads and responds to comments
- Creates records (filled forms, reports)
- Updates CONTEXT.md and THERAPEAK.md
- Maintains document cross-references

### What Suzan Does
- Reviews QMS documents for regulatory adequacy
- Advises on Scarlet's expectations
- Guides audit preparation
- Answers regulatory questions

---

## What Claude Code Will Handle

Everything else — all QMS documents, SOPs, plans, forms, risk assessments, clinical evaluation report, technical documentation structure — I will create. You just need to do the items above.
