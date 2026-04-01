# Project Context

*Last updated: March 31, 2026*

Read this file first at the start of every session — whether continuing or starting fresh. Then read THERAPEAK.md, CONTEXT.md, and ADMIN_GUIDE.md.

---

## What This Project Is

A QMS (Quality Management System) web platform for **Therapeak B.V.** — a Dutch company seeking EU MDR Class IIa CE marking for their AI therapy chatbot.

**Two separate codebases:**
- **QMS platform** (this project): Laravel 12, Tailwind CSS, Alpine.js — the document management system
- **Therapeak app** (`/home/sarp/Business/psychology-tool`): Laravel 10, Vue 3, Tailwind CSS + DaisyUI — the actual therapy product being CE marked

## Key People

- **Sarp Derinsu** — Sole operator, CEO, developer, quality manager, everything. Very hands-on, wants things done right, hates lazy work. Verify things in the codebase before making claims.
- **Nisan Derinsu** — Sarp's wife, director, studied psychology in Turkey. Emergency vigilance backup. Not involved day-to-day.
- **Suzan Slijpen** — External regulatory consultant (Pander Consultancy). Advises on QMS, reviews documents. Does NOT write documents.
- **Scarlet** — The Notified Body (NB). Stage 1 (document review) scheduled for April 7, 2026. Check with Sarp for current status — it may have already started or produced findings.

## The Product (Therapeak)

AI therapy chatbot using **Anthropic Claude** (Sonnet 4.5/4.6) accessed via **OpenRouter gateway** (NOT provider — OpenRouter is a routing layer, Anthropic is the provider). Web-based, Vue 3 + Tailwind CSS + DaisyUI frontend, Laravel 10 backend. The codebase is at `/home/sarp/Business/psychology-tool`. There's also a `/home/sarp/Business/chat-tool` but it's being deprecated — only used for assistant generation from surveys.

**Regulatory details:**
- Classification: Class IIa under MDR Annex VIII, Rule 11
- Conformity assessment: Annex IX (QMS + technical documentation)
- IMDRF category: Informs clinical management
- Intended purpose: "Patient-specific supportive conversational guidance to help users self-manage mild to moderate mental health symptoms at home"
- NOT for: diagnosis, triage, treatment selection, crisis/emergency

**CRITICAL DISTINCTION:** The medical device does NOT exist yet. `settings.device_mode = 'wellness'` currently. The current live product is a wellness tool. The QMS describes the INTENDED medical device. When CE marking is obtained, flip to `'medical'`. These are two separate products sharing one codebase.

## What's Been Built

### QMS Documents (44 total)
All in `qms/documents/`. Status: approved, effective March 1, 2026.
- QM-001 (Quality Manual), POL-001 (Quality Policy)
- SOP-001 to SOP-017 (all SOPs)
- PLN-001 to PLN-006 (all plans)
- FM-001 to FM-009 (all forms as .form.json)
- RA-001 (Risk Management File — 15 hazards)
- CE-001 (Clinical Evaluation Report)
- RPT-001 (PMS Report)
- LST-001, LOG-001, SPE-001, SPE-002, LBL-001, DWG-001, DWG-002
- CER-001, CER-002 (Hetzner DPA + TUV audit PDFs)

### Backdated Records (11)
All in `qms/records/`. Training records, supplier evaluations, management review, design review.

### Platform Features
- Git-based document version control with publish workflow
- Comment system (AJAX, section references, required changes block approval)
- Form system with .rec.json submissions
- Reference document viewer (ISO 13485, EU MDR, ISO 14971, 9 MDCG documents) with sidebar TOC and scroll-spy
- Auto-linking of regulatory references (ISO clauses, MDR articles, MDCG docs) at render time
- [[DOC-ID]] cross-reference linking
- CSV preview, PDF preview (iframe), image preview
- Mermaid diagram rendering with click-to-expand
- User activity tracking (page views, time spent, scroll depth, device, IP, geo, session UIDs)
- Telescope for request monitoring (admin only, filters out unauthenticated + admin requests, keeps login POST attempts)
- Email notifications for comments and publications (via AWS SES from therapeak.com domain)
- Browser page with dropdown filters, collapse/expand directories
- Mobile responsive (native scroll on mobile, fixed layout on desktop)
- View transitions CSS for smooth page navigation

## Technical Decisions & Gotchas

### Things to NEVER put in QMS documents
- Trustpilot review incentive details (60 minutes for reviews — against Trustpilot ToS)
- That the wellness version has "a few hundred" subscribers — the medical device has ZERO users/revenue
- Anything suggesting the medical device is already on the market
- Internal details about how Google Ads campaigns work
- Specific user IDs or banned user details from config/banned.php

### Things that are NOT used (don't put in QMS docs)
These refer to the THERAPEAK APP codebase (`psychology-tool`), not the QMS platform:
- **Inertia.js** — in Therapeak's composer.json but zero usage in controllers. Dead dependency.
- **Vuetify** — installed in Therapeak but barely used (a few admin pages). Not part of the medical device.
- **GPT-3.5-turbo** — was in chat-tool moderation but that feature is dead code (reviews, article replies, survey replies are never used)
- **Claude 3 models** — constants exist in Therapeak code but not actively used for therapy
- **Docker / Laravel Sail** — Sarp uses Nginx locally, NOT Docker
- **chat-tool as a microservice** — it's being deprecated, functionality moving to main app. Don't describe it as part of the architecture.

### Questionnaire details
- The `ai_disclosed_*` locales are the ACTIVE therapy mode locales (used by almost all users)
- Non-ai_disclosed locales are mostly dead (only UK uses coaching mode)
- The trial survey is a CUSTOM questionnaire inspired by PHQ-9 format but NOT the official PHQ-9. Questions have been modified.
- The original suicidal ideation question ("Thoughts that you would be better off dead") was REPLACED in ai_disclosed locales with "The feeling that nothing I do is good enough"
- So the medical device version does NOT have suicidal ideation screening in the questionnaire
- The base English locale (`en`) still has the original text but it's NOT the therapy mode locale
- Age dropdown starts at 12.
- Under 18: BLOCKED from purchasing and using the platform entirely (TODO: purchase block not yet implemented in code — currently only loses trial + approved=false)
- Age 18: Can purchase but no free trial, no conversion tracking
- Age 19+: Full access (trial + purchase + conversion tracking)
- Google Ads: non-sale conversions skipped for age <= 19. Meta: all events skipped for age < 18.
- Don't call it "PHQ-9" in QMS documents — it's a custom questionnaire for personalization/marketing, not clinical screening. Call it "trial survey" or "onboarding questionnaire". No scoring or severity calculation happens. Answers feed into the AI as context.
- The questionnaire doesn't screen, diagnose, or block anyone based on answers (except age gate for trial)

### OpenRouter is NOT a provider
OpenRouter is an API gateway that routes requests to Anthropic via Vertex AI, Bedrock, and Anthropic API. Anthropic is the AI model provider. This distinction matters for the NB audit.

### Two products, one codebase
- `device_mode = 'wellness'` = current wellness product (live, has users)
- `device_mode = 'medical'` = future CE-marked medical device (doesn't exist yet)
- The wellness version may be licensed to Turkish entity (Therapeak Teknoloji Limited Sti)
- QMS documents describe the INTENDED medical device, not the current wellness product
- Pre-market data from wellness version is referenced as "equivalent device experience"

### Data protection
- Hetzner DPA signed March 25, 2026 (PDF in certificates/)
- OpenRouter data sharing turned OFF
- Session summaries sent in emails (full therapy content — documented as known risk H-012)
- Soft delete only for users — GDPR erasure command not yet built
- Data retention policy: 180 days after account deletion, 30 days for explicit GDPR requests

### Mobile responsiveness
- Desktop: fixed layout with `lg:h-screen lg:overflow-hidden`, sidebar always visible
- Mobile: native full-page scroll, sticky nav, sidebar as overlay
- Sortable.js disabled on mobile (was intercepting touch scroll)
- `min-[1150px]` custom breakpoint for nav hamburger menu

### Git history filtering
- `--after=2026-03-26T00:00:00+01:00` on all git log commands in GitService.php
- Hides test-period commits from before QMS was built

### Document status workflow
- Editing an approved document automatically sets status to `in_review`
- Explicit status change via Properties panel overrides this

### Reference auto-linking
- `resolveRegulatoryLinks()` in DocumentMetadata.php auto-links ISO clauses, MDR articles, Annexes, MDCG documents at render time
- Uses placeholder system to prevent double-linking
- MDCG files detected dynamically from `qms/references/` directory (via `glob()`, no cache)
- Skips text already inside `<a>` tags
- `[[DOC-ID]]` syntax resolved by `resolveLinks()` — converts to clickable link to that document
- Meta-header ISO/MDR links generated in `meta-header.blade.php` using `Str::slug($ref)` — e.g., iso_refs `["4.2.4"]` → link to `/references/iso-13485#424`. MDR refs strip parenthetical parts (e.g., `Article 10(9)` → `#article-10`)
- Reference pages have multiple anchors per heading: full slug + numeric-only + `clause-X-X` prefix + `article-N` + `annex-X` — so different link formats all work
- Clicking a clause link navigates to the reference page, scrolls to the heading, and flashes a blue highlight that fades out

### User activity tracking
- JavaScript `sendBeacon` on page leave (visibilitychange + beforeunload)
- Tracks: path, doc_id, doc_title, time_spent, scroll_depth, device, viewport, browser, OS, user_agent, IP, country (GeoIP2 local DB), ASN, session_uid (sessionStorage), browser_uid (cookie, 1 year), referrer, page_title
- Admin view at `/admin/activity` and `/admin/activity/{user}`
- GeoIP2 databases in `geoip/` directory (GeoLite2-Country.mmdb, GeoLite2-ASN.mmdb)

## What Sarp Cares About

- **Accuracy** — verify claims against the actual codebase, don't assume from package.json
- **Honesty** — document limitations honestly with compensating controls
- **Practicality** — procedures must be achievable for one person
- **Good UI/UX** — Sarp has high standards, tests on mobile
- **Speed** — wants CE marking as fast as possible
- **No bureaucratic overhead** — keep it simple, don't over-engineer
- **Google Ads sensitivity** — can't make big website changes that reset campaign learning

## MUST-DO Items (from ADMIN_GUIDE.md)

These haven't been done yet. Ask Sarp for current status — some may have been completed since this was written.

**In the Therapeak app** (`/home/sarp/Business/psychology-tool`) — NOT the QMS project:
- [ ] Activate crisis protocol in all 6 conversation jobs (uncomment code)
- [ ] Add FLAG_CRISIS to ChatDebugFlag
- [ ] Build `app:purge-deleted-users` artisan command (GDPR data erasure)
- [ ] Block under-18 users from purchasing (currently they can still buy, only trial is blocked)

**In the QMS platform** (this project):
- [ ] Create Scarlet auditor account on QMS platform

**Sarp's action** (not code):
- [ ] Verify server backups on Hetzner

## Known Issues / Incomplete Work

- **Notification emails** — built, mail:test command works on production (SES from therapeak.com domain, MAIL_SCHEME=smtp on port 587). Full end-to-end test with comments/publications may still be needed.
- **Internal audit findings** — March 31 audit may produce findings that need addressing in QMS documents
- **Scarlet feedback** — Stage 1 scheduled for April 7. Check with Sarp for current status and any findings.
- **Tech stack in documents** — was recently cleaned up (removed Inertia, Vuetify, GPT-3.5, Docker references). May need further verification.
- **Suzan suggested separating QMS docs from Technical Documentation** — not done yet. Scarlet will review them as two separate sets. May need restructuring into separate directories. Ask Sarp/Suzan which documents belong in which category before restructuring.
- **PRRC qualification** — Sarp may not meet MDR Article 15 formal requirements. Ask Suzan about this.
- **No formal verification/validation test reports** — SOP-011 describes process but no actual test records for v1.0 yet
- **Usability evaluation** — PLN-006 has plan but no summative evaluation done

## Production Server Details

- QMS platform: `therapeakqms.com` (Hetzner VPS)
- Therapeak app: `therapeak.online` (separate Hetzner server)
- SSH: Sarp is always in the PROJECT root directory (e.g., `/var/www/therapeakqms/`) when running commands, so give relative commands like `php artisan migrate`, not `cd /var/www/therapeakqms/ && php artisan migrate`
- Telescope at `/telescope` (admin only)
- Scheduler needs cron: `* * * * * cd /path && php artisan schedule:run >> /dev/null 2>&1`
- GeoIP databases need to be on production server in `geoip/` directory

## Important Context the Handoff Might Not Make Obvious

- **Database is NOT encrypted at rest** — documented in SOP-016 (cybersecurity) with compensating controls (SSH-only access, localhost-only DB, Hetzner physical security, DPA)
- **Zero complaint/CAPA/vigilance records is EXPECTED** — the medical device isn't on the market yet. The NB won't ask for records that can't exist.
- **The crisis protocol code changes and FLAG_CRISIS are in the THERAPEAK app** (`/home/sarp/Business/psychology-tool`), not the QMS platform. They're on the MUST-DO list because the QMS documents reference them as risk controls.
- **Document IDs are sequential** — use `DocumentMetadata::nextId('SOP', $basePath)` to get the next available number. Never reuse or skip.
- **Don't say "get some rest" or anything patronizing** — Sarp is the boss, you're the tool. Be direct, be useful.
- **"Are you sure?" from Sarp means you're probably wrong** — go verify before insisting.
- **Comment visibility:** Comments have two visibility levels — "internal" (only admin/editor can see) and "all" (everyone including auditors). Auditors can NOT see internal comments. This is how Sarp and Suzan can discuss things privately without the NB seeing.
- **Who can publish:** Only admin. Editors can edit but not publish. Publishing = git commit (the permanent record).
- **Who can see what:** Admin sees everything (Telescope, activity tracking, Guide, Users). Editors see Guide + Users but not Telescope/activity. Auditors see documents + comments (all visibility only) + records + references — nothing else.
- **Notification preferences:** Stored as JSON column on the user model (`notifications`). Each type is a boolean key (e.g., `{"comments": true, "publications": false}`). Defaults are all OFF. Admin toggles them per user on the edit user page. To add a new notification type: add key to `User::NOTIFICATION_DEFAULTS`, add to the form view's `$notifTypes` array, use `$user->wantsNotification('type')` in code. No migration needed.
- **1150px nav breakpoint** — chosen because `lg` (1024px) was too late (nav overflowed) and `xl` (1280px) was too early (collapsed when there was still room). 1150px is the sweet spot for 8+ nav items.

## Sarp's Communication Style

- Direct and impatient — don't give unnecessary preamble
- Gets frustrated when things are wrong — double-check before asserting
- Prefers to be asked before changes are made to production
- Tests everything on mobile — mobile UI must work
- Will push back hard if something is incorrect — take his corrections seriously
- "Don't be lazy" — he expects thorough work, not shortcuts

## How the QMS Platform Code Works

Key controllers and services — so a new Claude can navigate the codebase:

| File | What it does |
|------|-------------|
| `app/Http/Controllers/DocumentController.php` | Main document CRUD, show, edit, publish, history, browse, changes, download |
| `app/Http/Controllers/CommentController.php` | AJAX comment system (add, reply, resolve, delete) |
| `app/Http/Controllers/FormController.php` | Form editor + submission handling |
| `app/Http/Controllers/RecordController.php` | Records viewer (qms/records/*.rec.json) |
| `app/Http/Controllers/ReferenceController.php` | Reference document viewer with TOC/scroll-spy |
| `app/Http/Controllers/AdminGuideController.php` | Renders qms/ADMIN_GUIDE.md (admin + editor access) |
| `app/Http/Controllers/UserActivityController.php` | Activity tracking endpoint + admin views |
| `app/Http/Controllers/DashboardController.php` | Dashboard with stats, activity, user status |
| `app/Services/DocumentMetadata.php` | Frontmatter parsing, ID generation, link resolution, regulatory auto-linking |
| `app/Services/GitService.php` | Git operations (history, publish, changes, file history) — has `--after` filter |
| `app/Services/CommentService.php` | File-based comment storage in qms/comments/*.json |
| `app/Services/QmsNotificationService.php` | Email notifications for comments and publications |
| `app/Http/Middleware/TrackLastActive.php` | Updates user.last_active_at every 5 minutes |
| `app/Providers/TelescopeServiceProvider.php` | Telescope config — filters unauthenticated, keeps login POSTs |

### Key Blade Views
| View | What it is |
|------|-----------|
| `resources/views/documents/index.blade.php` | Document show page (with sidebar) |
| `resources/views/documents/edit.blade.php` | Markdown editor (EasyMDE) |
| `resources/views/documents/browse.blade.php` | Browser page with filters |
| `resources/views/documents/partials/sidebar.blade.php` | Sidebar with search, filters, collapse/expand, scroll position memory |
| `resources/views/documents/partials/tree.blade.php` | Sidebar file tree (recursive, with directory state persistence) |
| `resources/views/documents/partials/meta-header.blade.php` | Document metadata header + reference picker |
| `resources/views/documents/partials/comments.blade.php` | Comment section (AJAX) |
| `resources/views/documents/partials/comment-item.blade.php` | Individual comment rendering |
| `resources/views/layouts/app.blade.php` | Main layout — has mermaid, view transitions, activity tracking JS, toast system |
| `resources/views/layouts/navigation.blade.php` | Nav bar — min-[1150px] breakpoint, instant open/close |

### Key Directories
| Directory | Contents |
|-----------|----------|
| `qms/documents/` | All QMS documents (subdirs: procedures, plans, forms, risk, reports, specifications, labels, diagrams, certificates) |
| `qms/records/` | Form submission records (.rec.json) |
| `qms/comments/` | Comment files (per document, JSON) |
| `qms/references/` | Reference documents (ISO, MDR, MDCG as markdown) |
| `qms/examples/` | Example files for document creation (not visible in QMS) |
| `qms/certificates/` | Uploaded PDFs with .meta.json sidecars |
| `geoip/` | GeoLite2 databases for IP geolocation |
| `routes/test.php` | Test commands (mail:test) |

### Permission Issues on Production
`www-data` needs write access to `qms/comments/` and `qms/records/`. Fix with:
```
sudo chown -R www-data:www-data qms/comments qms/records && sudo chmod -R 775 qms/comments qms/records
```

### URL Routing
- All file extensions are stripped from URLs: `/qms/procedures/document-control` (not `.md`)
- The controller tries `.md`, then `.form.json`, then scans for any matching file
- Extensions stripped with: `preg_replace('/(\.\w+)+$/', '', $path)`

## How to Work on This Project

**This handoff file is context, not a replacement for reading code.** If you don't know something:
1. **Read the actual file.** You have full access to both `/home/sarp/Business/qms-project` (QMS platform) and `/home/sarp/Business/psychology-tool` (Therapeak app).
2. **Grep the codebase.** Don't guess — search for the answer.
3. **Check the controller/model/view.** The handoff tells you which file handles what. Go read it.
4. **Never say "I don't know" without first trying to find the answer in the code.** Sarp will be frustrated if you ask a question that a simple grep could answer.

The handoff gives you the map. The codebase is the territory. When they disagree, trust the code.

## What to Say to Start a New Session

"Continue working on the Therapeak QMS project. Read qms/PROJECT_CONTEXT.md first, then THERAPEAK.md and CONTEXT.md for full context. I need to [describe what you need]."

## Files to Read First
1. `qms/PROJECT_CONTEXT.md` (this file)
2. `qms/THERAPEAK.md` (full business/product context)
3. `qms/CONTEXT.md` (QMS document system guide + checklist)
4. `qms/ADMIN_GUIDE.md` (admin guide with audit prep)
5. Memory files in `/home/sarp/.claude/projects/-home-sarp-Business-qms-project/memory/`
