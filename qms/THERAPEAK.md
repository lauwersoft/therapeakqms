# Therapeak Business Context

This file contains all information about how Therapeak operates as a business and product. Used by Claude Code when creating QMS documents.

---

## Company

- **Legal name:** Therapeak B.V.
- **KvK:** 96490713
- **Address:** Lange Lauwerstraat 207, 3512VH Utrecht, Netherlands
- **Legal form:** Dutch B.V. (private limited company)
- **Ownership:** 100% owned by Novacore Holding B.V. (50/50 Sarp Derinsu & wife)
- **Management:** Sarp Derinsu (sole operator, full control)
- **Directors on payroll:** Sarp Derinsu (Directeur), wife (Directeur)
- **FTE:** 1 FTE + 1 external regulatory consultant (Suzan Slijpen)
- **Related entities:**
  - Novacore Holding B.V. — parent holding company
  - Therapeak Teknoloji Limited Sti (Turkey) — personally owned by Sarp & wife, potential licensing target for non-EU wellness version

## Regulatory Status

- **Current market status:** On the EU market as a **wellness device** (not yet CE marked as medical device)
- **Target:** CE marking as Class IIa medical device under EU MDR 2017/745
- **Notified Body:** Scarlet (scarlet.cc)
- **NB engagement start:** April 7, 2026 (Stage 1 — document review)
- **Internal audit:** March 31, 2026 (conducted by jurist hired by consultant Suzan Slijpen)
- **NB contract:** Signed. Initial fee €100,000 + ~€3,200/month ongoing (indefinite, covers updates/surveillance)
- **NB access:** Scarlet will have access to the QMS and review changes ongoing
- **Consultant:** Suzan Slijpen — regulatory affairs consultant, collaborating on QMS build
- **Classification:** Class IIa under MDR Annex VIII, Rule 11
- **Conformity assessment route:** Annex IX (QMS + technical documentation assessment)
- **MDA code:** MDA 0315
- **EMDN code:** V92 (medical device software not included in other classes)
- **Countries blocked:** Netherlands (NL), Turkey (TR) — blocked from checkout
- **No prior NB experience**

## Product: Therapeak

### What It Is
Therapeak is an AI-powered therapy platform that provides mental health support through conversational AI. Users chat with AI-generated therapists in timed therapy sessions via text. The platform generates session summaries, user reports, and mood tracking.

### Intended Purpose (for MDR)
"Therapeak provides patient-specific supportive conversational guidance intended to help users self-manage mild to moderate mental health symptoms at home."

### Target Conditions
Mild to moderate mental health conditions:
- Anxiety disorders
- Obsessive-compulsive disorders
- Trauma or stress-related disorders
- Disorders related to impulse control
- Depression

### Target Patient Population
- Adults with mild to moderate mental health issues
- Home use environment
- May be used standalone or as supplement to traditional therapy

### Contraindications
- Complex psychotic or dissociative disorders
- Possibly less useful for neurobiological and neurocognitive disorders
- Not for emergency/crisis use

### Intended Users
- Patients directly (no healthcare professional intermediary required)
- Output may be shared with healthcare professionals to support remote monitoring, consultation preparation, and follow-up

### Device Inputs
- Patient-reported information (text messages, questionnaires, symptom input from trial survey)

### Device Outputs
- Patient-specific conversational guidance and recommendations
- Alerts and insights
- Session reports and summaries
- The output is informational coaching content, reflective prompts, structured coping suggestions
- Summaries of user-reported concerns that can optionally be shared with a healthcare professional

### Clinical Claims
- The output is NOT intended to diagnose any condition, establish severity, triage urgency, recommend or select specific clinical interventions, or determine medication or treatment changes
- Any clinical decisions remain the responsibility of the healthcare professional and/or user
- The software supports understanding and monitoring and may assist clinician-patient discussions but does NOT drive or replace clinical decision-making
- Hence: "informs clinical management" (IMDRF category)

### Risk Classification Justification
Class IIa under Rule 11 because:
- Software provides information used for therapeutic decisions (self-management)
- Intended for mild-to-moderate symptoms, home use
- Reasonably foreseeable harm from erroneous outputs is primarily minor/reversible (e.g., transient distress, unhelpful coping suggestion)
- NOT for diagnosis, triage, emergency/crisis, or directing specific treatment changes
- Mitigated through limitations-of-use, safety messaging, escalation pathways, monitoring, controlled updates

## Technical Architecture

### Tech Stack
- **Backend:** PHP 8.2, Laravel 10
- **Frontend:** Vue 3 + Inertia.js (SPA), Vuetify 3, Tailwind CSS + DaisyUI
- **Database:** MariaDB 10
- **Cache/Queue:** Redis, Laravel Horizon
- **Real-time:** Soketi (self-hosted Pusher-compatible WebSocket)
- **Build:** Vite 4
- **Containerization:** Docker Compose (Laravel Sail) for development

### AI Models
- **Primary therapy chat:** Claude Sonnet 4.5 via OpenRouter (with reasoning tokens)
- **AB test variant:** Claude Sonnet 4.6
- **Fallbacks:** Claude Sonnet 4, Claude Opus 4, Claude 3.7 Sonnet
- **Content generation (chat-tool):** Claude 3 (Haiku/Sonnet/Opus) + GPT-4/GPT-4o/GPT-3.5
- **Image generation:** Fal.ai (Flux Pro) for AI therapist avatar photos
- **Content moderation:** GPT-3.5

### System Architecture
Two applications:
1. **psychology-tool** (main Therapeak app) — user-facing, therapy sessions, payments
2. **chat-tool** (backend microservice) — AI content generation, translations, profiles, moderation

Communication: chat-tool exposes an API that psychology-tool calls via HTTP. Webhook system for async results.

### AI Therapy Flow
1. User completes trial survey (concerns, age, gender, language)
2. AI therapist generated/matched based on survey data (personalized profile, photo, bio)
3. User starts timed therapy session (deducts minutes from balance)
4. User sends message → dispatched to chat queue
5. Conversation job constructs system prompt with:
   - Static therapeutic instructions (role, approach, formatting, crisis protocol)
   - Chat room context (therapist details, user details, language, session time remaining)
   - User's survey answers
   - Previous session summaries
   - User profile text
6. Message sent to OpenRouter API → response saved as assistant message → broadcast via WebSocket
7. After session: SummarizeTherapySessionJob creates session summary
8. CreateUserReportJob generates clinical-style report (presenting problem, diagnosis, treatment plan, progress notes, recommendations)

### AI Modes (same app, feature flag)
- **Therapy mode** (ai_disclosed=true): Presented as "AI therapy" — the mode that will be CE marked
- **Coaching mode** (ai_disclosed=false): Presented as "AI coaching" — avoids medical device classification in markets without MDR requirements
- Both modes run in the same deployment, controlled by a feature flag in config
- No separate deployment/domain for the medical device version

**Per-country mode logic (from `AiDisclosureMiddleware`):**
- `COACHING_DEFAULT_LANGUAGE_CODES`: `['uk']` — UK defaults to coaching mode for new visitors
- `COACHING_ACTIVE_LANGUAGE_CODES`: `['uk', 'it', 'de']` — UK, Italy, Germany support coaching mode
- All other language codes: default to therapy mode (ai_disclosed=true)
- Users can also be assigned a `site_type` (therapy or coaching) via query param or guest user record
- Logged-in users: respect their `user.ai_disclosed` database flag
- The middleware also handles per-country Stripe pricing tiers

**Why coaching mode for UK/DE/IT:**
- UK (MHRA): Released guidance that "AI therapist"/"AI therapy" language automatically classifies software as a medical device
- Germany/Italy: Preemptive measure, similar regulatory sensitivity
- Other EU countries: No such restriction yet, so therapy mode is used

**Historical context:** The `ai_disclosed` flag originally meant "disclose that this is AI" vs "don't disclose." It has evolved to mean "therapy mode" vs "coaching mode." The naming in the codebase reflects the original purpose.

**For CE marking:** Once certified, all EU markets will use therapy mode with full medical terminology. The coaching mode remains available for non-EU markets or markets without MDR requirements.

### Software Version
- No formal version numbering exists currently
- For QMS/technical documentation: will assign version 1.0 to the CE-marked release
- Version tracking to be implemented as part of the QMS software lifecycle process

### Key Safety Features (in AI prompts)
- Crisis protocol: when user expresses suicidal ideation, AI provides emergency resources
- Strong instructions against inappropriate advice
- Relationship neutrality rules
- Session time awareness
- Max 400 output tokens with 500 reasoning tokens per response

## Data & Privacy

### Data Collected
**Personal data:** Name, email, DOB, gender, timezone, locale, social login IDs, IP addresses
**Health/wellness data:** Trial survey responses, full chat transcripts, session summaries, clinical-style reports, mood ratings, user self-description
**Tracking:** Google Ads, Meta pixel, TikTok pixel, affiliate tracking

### Data Storage
- **Production server:** Hetzner, Nuremberg, Germany
- **Database:** MariaDB — therapy chat data is NOT encrypted at rest
- **Backups:** Believed to be in place (needs verification)
- **Data processing agreement:** None currently with Hetzner (NEEDS TO BE DONE)
- **Access:** Sarp Derinsu has full production access; wife has access but doesn't use it

### Authentication
- Email/password registration (Laravel Breeze)
- Email verification with 4-digit codes
- Social login: Google, Microsoft, Facebook, TikTok
- API auth: Laravel Sanctum (SPA) + Laravel Passport (service-to-service)

### External Services
- OpenRouter (AI gateway → Anthropic Claude)
- Anthropic Claude API (direct, in chat-tool)
- OpenAI API (content generation, moderation)
- Fal.ai (image generation)
- Stripe (payments, subscriptions)
- Google/Microsoft/Facebook/TikTok OAuth
- Google Ads API, Meta Conversions API, TikTok Events API
- ExchangeRate API, ipapi.co (geolocation)
- AWS S3 (storage)
- Soketi (WebSocket)

## Payment & Subscription
- **Provider:** Stripe (via Laravel Cashier)
- **Currency:** EUR
- **Model:** Subscription with minute allocation
  - 30 min/day (max 45), allocated per 4-week interval
  - Trial: 10 free minutes
  - Additional minutes purchasable (15-240 min increments)
- **Payment methods:** Card, PayPal, Link, SEPA Debit

## Deployment
- **Development:** Docker Compose (Laravel Sail)
- **Production:** Hetzner server, Nuremberg, Germany
- **Deployment process:** git push to main → git pull on production (manual)
- **No CI/CD pipeline**
- **No staging environment**
- **No automated testing** (no unit/integration/manual tests)
- **Systemd services:** Horizon (queues), Inertia SSR, scheduler, Soketi

## IGJ History & NL Blocking

**Timeline:**
- October 2025: IGJ (Dutch health inspectorate) visited/inspected based on the claims on the Dutch website
- They aggressively moved to shut down the app, rather than simply requesting claim changes
- Their specific complaint: "The way the software was presented at [date] of October" — referring to the Dutch website claims only
- They did NOT object to the software's functionality, only the marketing claims
- Therapeak changed the claims on the website to wellness-oriented language
- Decision made to block NL entirely from checkout as a business continuity measure
- Reason: IGJ can shut down operations across all EU countries from their jurisdiction — too risky

**Key insight for QMS:** The IGJ issue was about claims (marketing language suggesting medical purpose without CE marking), NOT about the software's safety or functionality. The current wellness-language website is compliant. The CE-marked version will use the original medical terminology (stored in `lang_backup/*_original` directories).

## Business Continuity Strategy
- The wellness version may be licensed to Therapeak Teknoloji Limited Sti (Turkey) as a business continuity measure
- Reason: IGJ can close EU operations; Turkish entity provides a fallback
- Turkey and US markets are more lenient regarding AI health tools
- Even as a medical device, the Turkish licensing route provides a Plan B for non-EU markets
- Therapeak remains cooperative with IGJ and will engage proactively once CE marked
- The CE-marked medical device version will be the primary EU offering

## Languages Supported
20+ locales: Dutch, German, French, Spanish, Italian, Portuguese, Polish, Czech, Japanese, Korean, Norwegian, Swedish, Danish, Finnish, Turkish, English (US/UK/Canada), and more.

---

## Clinical Evidence

### Published Literature (AI Therapy Chatbots)
Sarp compiled a comprehensive literature review for Suzan. Key findings:

**Individual studies:**
- **Therabot (NEJM AI, 2025):** 210 participants, 8 weeks. Depression d=0.85, anxiety d=0.79 (large effects). First RCT for fully generative AI therapy chatbot. Academic (Dartmouth), no commercial interest.
- **Friend chatbot (BMC Psychology, 2025):** 104 participants (women in Ukrainian war zones), 8 weeks. Anxiety d=0.56-0.61.
- **Woebot (JMIR, 2017):** 70 participants, 2 weeks. Depression d=0.44. Note: conflict of interest (co-author is Woebot founder).

**Meta-analyses:**
- **Feng, Tian et al. (JMIR, 2025):** 31 RCTs, 29,637 participants. Depression SMD=-0.43, anxiety SMD=-0.37, stress SMD=-0.41.
- **Linardon et al. (World Psychiatry, 2024):** 176 RCTs. Apps with chatbot tech: depression g=0.53.
- **Zhu et al. (JMIR, 2025):** 14 RCTs, 6,314 participants. Mental health overall ES=0.30.
- **Zhong et al. (J Affective Disorders, 2024):** 18 RCTs, 3,477 participants. Depression g=-0.26, anxiety g=-0.19.

**Context:** SSRI antidepressants score d=0.24-0.31. AI chatbot effects (0.26-0.61 for depression) are comparable to or exceed antidepressants.

### Therapeak's Own Data
- **Sample:** 117 subscribers who joined Aug-Sep 2025, completed ≥14 sessions, ≥6 self-assessments
- **Result:** 45% of users report feeling better (earliest vs most recent mood ratings)
- **Estimated effect size:** d≈0.5-0.7 (rough estimate based on comparison with similar studies)
- **Limitation:** Based on self-assessments, not standardized clinical scales (PHQ-9, GAD-7)
- **Displayed on:** Finalize page with footnote explaining methodology

## Equivalent / Similar Devices

For the Clinical Evaluation Report, these are the most relevant comparators:

| Device | Company | CE Status | Classification | Relevance |
|--------|---------|-----------|---------------|-----------|
| **Limbic Access** | Limbic | CE marked Class IIa under EU MDR | Class IIa (Rule 11) | Best comparator — AI mental health chatbot with Class IIa MDR certification. Used in NHS IAPT services. |
| **Wysa** | Wysa | CE marked Class I under MDD | Class I (MDD, not MDR) | AI CBT chatbot, NHS listed. Lower classification bar — Class I self-certification under old MDD, not MDR with NB. |
| **Woebot** | Woebot Health | No CE marking (FDA pathway) | FDA Breakthrough Device | US-focused. Published RCT data (d=0.44 for depression). No EU regulatory status. |
| **Therabot** | Dartmouth College | No CE marking (research) | N/A | Research project. Strongest published RCT (d=0.85 depression). Not a commercial product. |
| **SilverCloud** | Amwell | CE marked (some programs) | Verify classification | Structured digital CBT programs, not conversational chatbot. |

**Key finding:** Limbic is the strongest regulatory precedent — Class IIa MDR CE marking for an AI mental health chatbot. Study their approach for the CER.

**TODO:** Verify current status of all devices via EUDAMED and recent press releases. Information above is from May 2025 training data.

## Crisis Protocol Status

The crisis protocol code exists in all conversation jobs but is **commented out** in Therapeak's codebase. This is **intentional** — Anthropic's Claude model has built-in safety mechanisms that handle crisis situations (suicidal ideation, self-harm, etc.) at the model level. Claude will:
- Recognize crisis language
- Respond with empathy and concern
- Direct users to emergency services and crisis hotlines
- Refuse to continue a normal therapy conversation during a crisis

**For the QMS:** Document this as a design decision — crisis handling is delegated to the underlying AI model's built-in safety layer (Anthropic Claude), which is continuously updated by Anthropic. This is a valid approach because:
- Anthropic's safety training is more comprehensive than a custom prompt could be
- It doesn't consume response tokens
- It's maintained by Anthropic's safety team
- It covers edge cases that a static prompt might miss

**Consider adding:** A `FLAG_CRISIS` to ChatDebugFlag for monitoring/logging purposes — even if the model handles the response, tracking when crisis situations occur is valuable for post-market surveillance.

## AI Model Change Process (Current)

Current process for switching models (e.g., Sonnet 4.5 → 4.6):
1. Test locally with prompt-testing tool (routes/prompt-testing.php)
2. Deploy to production
3. Monitor 1-2 sessions manually for quality
4. No formal validation protocol

**For QMS:** This needs to be formalized as a predetermined change control plan per MDCG 2019-11.

## Consultant & Audit Details

- **Regulatory consultant:** Suzan Slijpen — guides and advises, reviews documents, ensures right files are created. Has NOT written QMS documents; Claude Code builds them under her guidance.
- **Internal audit (March 31, 2026):** Conducted by a jurist hired by Suzan. Quick QMS audit to check everything is in the right place before NB engagement.
- **Wife's role:** Minimal operational involvement. Occasionally provides therapy-related feedback. Handles Turkish company setup. Not involved in day-to-day Therapeak operations.

## Supplier List (Complete)

| Supplier | Service | Critical? |
|----------|---------|-----------|
| Hetzner | Production hosting (Nuremberg, Germany) | Yes |
| OpenRouter | AI gateway (routes to Anthropic Claude) | Yes |
| Anthropic | AI model provider (via OpenRouter) | Yes |
| OpenAI | Content generation, moderation (in chat-tool) | Yes |
| Fal.ai | AI therapist avatar image generation | No |
| Stripe | Payments, subscriptions | Yes |
| AWS (SES) | Email delivery | Yes |
| AWS (S3) | File storage | No |
| GitHub | Source code repository | Yes |
| Google (Suite) | Business email (Gmail) | No |
| Google (Ads) | Marketing | No |
| Vimexx | Domain registration | No |
| JetBrains | IDE (development tools) | No |
| Google (One) | Video generation for marketing | No |

## Post-Market Feedback (Pre-CE)

Even though the device is currently a wellness tool, real-world user feedback exists:
- **No reports of harm** — no user has reported injury, worsening of condition, or adverse health effects
- **Common complaints:**
  - Users expected a voice function (not offered)
  - Responses were too repetitive (largely addressed by adding reasoning tokens to Claude)
  - AI "repeats what you say" (improved significantly with reasoning in Sonnet 4.5)
- **Refund requests:** Normal rate, related to feature expectations, not safety concerns
- **No adverse events** requiring regulatory reporting

This feedback will be referenced in the PMS Plan and initial PMS Report.

## QMS Timeline

- **December 2025:** Formal discussions began between Sarp and Suzan about building the QMS
- **March 1, 2026:** QMS formally established — documents created, processes activated
- **March 25, 2026:** QMS platform built, documents being created
- **March 31, 2026:** Internal audit by jurist (hired by Suzan)
- **April 7, 2026:** Stage 1 engagement with Scarlet (document review)
- **Ongoing:** ~€3,200/month to Scarlet for surveillance and updates

## Scarlet (Notified Body) Access

- Scarlet will access the QMS platform directly via an **auditor** user account
- The auditor role provides read-only access to all documents, records, and comments
- Auditors can leave comments (visible to all) but cannot edit documents
- Auditors can see `all` visibility comments but NOT `internal` comments (between Sarp and team)
- The goal: Scarlet reviews the QMS and has minimal findings, leading to fastest possible CE marking

## Open Questions / Action Items

### MUST DO before CE marking (Sarp's personal action items):

1. **CRISIS PROTOCOL** — Uncomment and activate the crisis protocol in ALL conversation jobs. Add FLAG_CRISIS to ChatDebugFlag. This is a patient safety issue.
2. **Data Processing Agreement with Hetzner** — Required for GDPR. Contact Hetzner and sign their DPA.
3. **NL blocking reason** — Document why Netherlands is blocked (IGJ regulatory concern?). Needs to be in risk management file.
4. **Backup verification** — Confirm Hetzner backup schedule, test a restore.
5. **AI model validation process** — Formalize the testing process for model changes. Document in a work instruction.

### Should do but QMS can be written to account for it:

6. **Encryption at rest** — Document in risk assessment why chat data is unencrypted (performance/operational reasons) with compensating controls (server security, access controls).
7. **Automated testing** — Not needed immediately, but SOP should describe the testing approach (manual testing + prompt testing tool as validation).
8. **Staging environment** — Not strictly required but recommended. Document that production is the only environment with manual deployment verification.
9. **Clinical evidence gap** — Own data is self-assessment based, not standardized scales. Consider implementing PHQ-9/GAD-7 as optional in-app assessments for future evidence. For now, literature review + own data is sufficient for CER.
