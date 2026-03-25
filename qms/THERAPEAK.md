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
- **Directors on payroll:** Sarp Derinsu (Directeur), Nisan Derinsu (Directeur)
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
- **NB contract:** Signed. Initial fee €100,000 + €37,000/year (paid monthly). Covers updates/surveillance.
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
- Adults (18+) with mild to moderate mental health issues
- **Minimum age:** 19+ for trial/payment access — survey age dropdown starts at 12, but if age ≤ 18 is selected, user cannot access free trial or pay. Age 18 is also blocked as a buffer against minors (e.g., a 14-year-old claiming to be 18). Effectively, users must report age 19+ to use the platform.
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
- **Content moderation:** GPT-3.5-turbo-0125 (via chat-tool, binary approve/reject)
- **Session quality monitoring:** GPT-4o (role confusion detection, non-response detection)

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
   - Static therapeutic instructions from text files (chat_room_instructions.txt, priority_chat_instructions.txt)
   - 160-200+ embedded static instructions per job (role enforcement, safety, formatting)
   - Dynamic personality description (from PersonalityTypeService based on therapist profile)
   - Chat room context (therapist details, user details, language, session time remaining)
   - User's survey answers
   - Previous session summaries
   - User profile text
6. Message sent to OpenRouter API → response saved as assistant message → broadcast via WebSocket
7. After session: SummarizeTherapySessionJob creates session summary (GPT-4o, max 500 tokens)
8. CreateUserReportJob generates clinical-style report (GPT-4o, max 4000 tokens, uses last 10 sessions + trial survey + previous report)

### Conversation Job Variants
Multiple job classes exist for different models/modes:
- `OpenRouterSonnetFourFiveRunConversationJob` — primary (Claude Sonnet 4.5)
- `OpenRouterSonnetFourSixRunConversationJob` — AB test (Claude Sonnet 4.6)
- `OpenRouterSonnetFourFiveRunCOACHConversationJob` — coaching variant
- `OpenRouterSonnetFourSixRunCOACHConversationJob` — coaching variant (4.6)
- `OpenRouterOpusFourFiveRunConversationJob` — Opus fallback
- `GeminiThreeRunConversationJob` — Gemini variant
- `OpenAiRunConversationJob` — fallback on OpenRouter errors (500, 400, 529, 429)

### Content Moderation System
- **Service:** GPT-3.5-turbo-0125 via chat-tool (`ModerateMessageJob`)
- **Scope:** Moderates user-generated content on the PLATFORM (assistant reviews, survey replies, article replies) — NOT therapy chat messages
- **Therapy conversations are NOT moderated** by this system — they go directly through OpenRouter/Claude with safety handled by the model's built-in safety + prompt instructions
- **Rules:** Binary approve (1) or reject (0)
  - Auto-reject: mentions of AI-generated content, offensive language, violence, drugs, weapons
- **Result delivery:** Webhook notification to psychology-tool with HMAC-SHA256 signed headers

### Session Quality Monitoring (ChatDebugFlag)
Two automated monitoring jobs analyze session quality:
1. **`CheckSessionForSwitchedRolesJob`** — GPT-4o analyzes transcripts for AI responding as patient instead of therapist (FLAG_SWITCHED_ROLES)
2. **`CheckSessionForDidNotRespondJob`** — GPT-4o checks for >30s gaps where user explicitly asks "hello?", "are you there?" (FLAG_DID_NOT_RESPOND)
- No `FLAG_CRISIS` monitoring yet (recommended in THERAPEAK.md but not implemented)

### Two Separate Products, One Codebase

**Product 1 — Wellness tool (current, live):**
- The product currently on the market
- Uses `ai_disclosed_*` locales (therapy mode) — wellness/therapy language
- Only UK uses coaching mode (non-ai_disclosed) due to MHRA
- Operated by Therapeak B.V.
- NOT a medical device, no CE marking required

**Product 2 — Medical device (future, CE-marked):**
- Does not exist yet — will be created once CE marking is obtained
- Will be built on the same codebase but is a SEPARATE product
- Will use medical terminology (translations in `lang_backup`)
- Cannot go on the market until CE marked

**Future strategy (not yet decided):**
- The wellness version may be licensed to Therapeak Teknoloji Limited Sti (Turkey) via a license agreement, with the Turkish company providing the wellness/coaching product
- Therapeak B.V. would then offer the CE-marked medical device in the EU
- Alternatively: keep just the medical device and retain coaching as a backup
- Sarp and Suzan have not finalized this — but the key constraint is: a medical device CANNOT be on the EU market without CE marking

**Product differentiation:** A config value `settings.device_mode` (`wellness` or `medical`) defines which product the deployment serves. When CE marking is obtained, flip to `medical`. Medical terminology translations and other medical-specific changes can be added over time separately.

**For QMS documents:** The QMS describes the INTENDED medical device (Product 2, `DEVICE_MODE=medical`). The current wellness tool (Product 1, `DEVICE_MODE=wellness`) provides pre-market experience and equivalent device data, but is not the medical device itself.

**Per-country mode logic (from `AiDisclosureMiddleware`):**
- Almost all countries use `ai_disclosed` (therapy mode) — this is the default
- Only UK uses non-ai_disclosed (coaching mode) — because MHRA classifies "AI therapy" language as medical device
- `COACHING_ACTIVE_LANGUAGE_CODES`: `['uk', 'it', 'de']` exist in config but only UK is actively used
- The middleware also handles per-country Stripe pricing tiers

**Historical context:** The `ai_disclosed` flag originally meant "disclose that this is AI" vs "don't disclose." It evolved to mean "therapy mode" vs "coaching mode." The naming in the codebase reflects the original purpose.

**For CE marking:** The medical device will be a separate product built on the same codebase, with medical terminology from `lang_backup`.

### Software Version
- No formal version numbering exists currently
- For QMS/technical documentation: will assign version 1.0 to the CE-marked release
- Version tracking to be implemented as part of the QMS software lifecycle process

### Key Safety Features (in AI prompts)
- Crisis handling: delegated to Anthropic Claude's built-in safety (see Crisis Protocol section)
- **Role enforcement:** "You are the THERAPIST" repeated 10+ times per job, with "deletion threat" language to reinforce
- **Relationship protection:** Never encourage leaving relationships, always try to heal first, never demonize people, never label as toxic/narcissistic
- **Behavioral guardrails:** No role-playing, no games, no off-platform contact, no referrals to other therapists
- **Content restrictions:** No weather mentions, no meta-commentary, no AI model details
- **Formatting restrictions:** No lists, no bold/italic, conversational text only, keep responses very short
- **Monitoring language:** "Every session is being monitored by psychologists" (enforcement mechanism)
- Session time awareness (wraps up near end of session)
- Max 400 output tokens with 500 reasoning tokens per response
- Reports explicitly state "this is not a medical document" / "not a diagnosis"
- Reports instructed to never advise about medication
- PDF export available for session reports (users can share with their therapist/doctor)

### Session Monitoring
- Sarp personally checks 1-2 sessions per day/week for harmful patterns
- No automated monitoring system for session quality
- No formal protocol for what to do if harmful output is found (needs to be formalized in QMS)

### Usability Evidence
- No formal usability testing conducted
- Usability is improved iteratively based on user feedback (contact messages, complaints)
- **Accessibility example:** A German user with visual impairment used voice control to navigate the app. Reported that the "send" button was not accessible for screen readers/voice control. Sarp added the appropriate label — user confirmed it resolved the issue.
- **FAQ popup on contact page:** Common questions (e.g., how to cancel subscription) are answered automatically via popup before the user needs to send a message. Significantly reduced contact volume.
- **Contact volume:** ~1-2 messages per day (down from higher volume before FAQ popup)
- **Support language:** Sarp responds in the user's language using AI translation

### Prompt Design Approach
- Prompts designed by Sarp based on: user feedback, creative thinking about effectiveness, LLM-based research conversations, and consultation with his wife
- **Wife's clinical input:** Sarp's wife studied Psychological Counseling and Guidance in Turkey — consulted on prompt design and therapeutic approach
- Iterative improvement based on user feedback and session quality observation
- Focus on: empathy, practical coping suggestions, avoiding harmful statements

### Therapist Switching
- Users can switch to a different AI therapist
- No memory transfer between therapists (conversation history stays with original therapist)
- New therapist starts fresh with trial survey data only

### Platform Details
- **Web app only** — no native mobile app (iOS/Android). Responsive web app.
- **Browser support:** Modern evergreen browsers (Chrome, Firefox, Edge, Safari). Special iOS 15+ Safari handling for chat interface.
- **Session minutes:** 30 min/day default, 45 min/day max accumulation, 10 min free trial
- **Pricing:** Per-country (e.g. Germany €79/4 weeks, Spain/Portugal €59/4 weeks)
- **Languages:** 20+ locales. AI quality varies — best in English, good in major European languages.

### AI Therapist Personalization
Each AI therapist is generated with 17 randomized personality traits:
- Personality type (empathetic, analytical, direct, humorous, etc.)
- Communication style (formal, casual, friendly, professional)
- Emotional tone (optimistic, calming, compassionate, motivational)
- Response length, metaphor usage, humor style, empathy level
- Problem-solving approach, feedback style, session pace
- Questioning style (open-ended, Socratic, reflective, scaling)
- And more (emoji usage, punctuation style, etc.)

Plus: generated name, bio, backstory, avatar image (via Fal.ai), therapeutic topics.

### Trial Survey (Onboarding Questionnaire)
Two-part survey defined in `QuizService.php`:

**Part 1 — Initial Quiz (17 questions):**
1. Gender (select)
2. Age (dropdown, 12-100 — but under-19 blocked from trial/payment)
3. Relationship status (select)
4. Previous therapy experience (yes/no)
5. What led you to consider therapy (multi-select, 15+ options)
6-13. **PHQ-9-style screening** (8 items, standard response scale: Not at all / Several days / More than half the days / Nearly every day):
   - Little interest or pleasure in doing things
   - Feeling down or hopeless
   - Sleep problems
   - Fatigue
   - Appetite changes
   - Self-esteem issues
   - Concentration problems
   - "The feeling that nothing I do is good enough" (replaces original PHQ-9 suicidal ideation item)
14. Functional impairment question
15. Anxiety/panic/phobia screening (yes/no)
16. Therapist expectations (multi-select)
17. Therapist preferences (multi-select)

**Part 2 — Finalize Questions:**
1. Therapist experience areas (multi-select)
2. Additional focus areas (multi-select)
3. "What brings you here?" (free-text)

**Key note:** The therapy mode (`ai_disclosed` locales) has replaced the original PHQ-9 suicidal ideation question (item 9) with "The feeling that nothing I do is good enough." The suicidal ideation text remains in the base English locale (non-medical/wellness version) but is NOT used in the therapy mode that will be CE marked.

**Same questionnaire structure for both therapy and coaching modes** — text differs only in AI disclosure language (e.g., "AI therapist" vs "therapist").

### Session Reports (UserReport)
Generated by GPT-4o after multiple sessions. Contains 8 sections:
1. Presenting problem
2. Background information
3. Assessment findings
4. Diagnosis (clinical-style, with disclaimer it's not a medical document)
5. Treatment plan
6. Progress notes
7. Recommendations (how to work with AI therapists)
8. Summary and prognosis

Reports use up to 10 most recent sessions + trial survey data + previous report for comparison.

### Session Summaries
Generated by GPT-4o after each session (max 500 tokens). Concise memory aid for the therapist containing specific details. Used as context in subsequent sessions.

### Mood Tracking
Two types:
1. **User self-reported** — via UI, limited to once per 12 hours. Scale: Sad/Neutral/Fine/Good/Great (mapped to 1-10)
2. **AI session-based** — GPT-4o rates user's mood after each session (1-10 scale)

Users can toggle mood tracking on/off. UI shows graphs of self-reported, AI-reported, and combined ratings.

### Account Deletion
**User-initiated soft delete** (ProfileBladeController):
- Email gets `deleted_` prefix (e.g., `deleted_user@example.com`)
- Social login IDs (Google, Facebook, Microsoft, TikTok) set to NULL
- Notifications disabled, approval revoked
- `deleted_at` timestamp set (Laravel SoftDeletes)
- **All therapy data remains untouched** (messages, reports, summaries, mood ratings)

**Admin soft delete** (AdminUserController):
- Email gets `deleted_` prefix
- Social IDs get `deleted_` prefix (preserved, not nulled — inconsistency with user-initiated)
- Admin can also restore soft-deleted users

**`UserService::delete()` method** (more thorough but NOT exposed via routes):
- Deletes: all messages, therapy sessions, chat rooms, article replies, survey replies, reviews, minute transactions
- Does NOT delete: user reports, session summaries, mood ratings (left orphaned)
- Only callable programmatically

**Console commands:**
- `soft-remove-user {userId}` — soft delete by ID
- `disable-suspicious-users` — marks suspicious users with `deleted_FAKE_` prefix

**Data retention policy (to be implemented):**
- **User self-deletes account:** soft delete immediately, permanent data wipe after 180 days (automated via scheduled Laravel command)
- **Explicit GDPR erasure request:** permanent data wipe within 30 days (manual trigger of same wipe process)
- **Active accounts:** data retained while active
- Implementation: `app:purge-deleted-users` artisan command on daily scheduler, finding soft-deleted users older than 180 days and permanently deleting all related data

**Current GDPR gaps (to be addressed before CE marking):**
- No automated permanent deletion yet (needs the scheduled command)
- No GDPR data export endpoint (privacy policy claims this right exists)
- Orphaned data after soft deletion (reports, summaries, mood ratings remain until permanent wipe)
- No audit trail of deletion actions

**User blocking:**
- Hardcoded in `config/banned.php` with specific user IDs
- Separate `no_checkout_user_ids` list (can browse but not pay)
- IP/country/VPN blocking via `BlockCountry` middleware
- No database-backed user suspension system

### Disclaimers Shown to Users
- **Homepage:** "In emergencies, this site is not a substitute for immediate help. If you are in a crisis, call the national crisis line, dial emergency services, or visit the nearest emergency room."
- **AI therapist profiles:** "All AI therapists on this platform are fictional profiles with AI-generated avatars. No real people are depicted."
- **Reports:** "This is not a medical document" + "Never talk or advise about medication"
- **Severe depression test results:** "If you are having thoughts of self-harm, please contact a crisis helpline immediately."
- **Terms of Service:** Standard liability limitation, governed by Netherlands law

### Onboarding Flow
1. Landing page → "Go To Questionnaire" CTA
2. Quiz (20 questions including depression screening)
3. Registration (email/password or social login)
4. Email verification (4-digit code)
5. Finalize page (what to expect, pricing, trial info)
6. AI therapist generated/matched based on survey
7. Chat begins (10 min free trial)

### OpenRouter Fallback Strategy
Multi-layer redundancy:
1. Primary: Claude Sonnet 4.5 via OpenRouter (3 retry attempts with 1s sleep)
2. If all retries fail: falls back to Claude Opus 4.5
3. OpenRouter itself has model fallback array: Sonnet 4 → Sonnet 3.7 → Opus 4
4. OpenRouter routes through Vertex AI + Amazon Bedrock + Anthropic API
5. **Planned:** Direct Anthropic API as additional backup if OpenRouter is completely down

**Why OpenRouter over direct Anthropic:** Anthropic's direct API is inconsistent and frequently down. OpenRouter provides multi-provider routing (Vertex AI, Bedrock, Anthropic) so if one provider is down, traffic routes through another. This dramatically improves availability.

### Availability Target
- **Target: 99.9% uptime** — achievable due to multi-provider AI fallback (OpenRouter → multiple providers)
- Self-caused outages: none to date

### Production Outages
- Outages have only been from external services, not self-caused
- **OpenRouter outages:** Most common, but usually resolved quickly due to multi-provider routing
- **Fal.ai outage:** Happened once, affected avatar generation only (not therapy sessions)
- **No self-caused outages** of the main therapy service
- Therapeak is NOT dependent on any single AI provider (Vertex, Bedrock, or Anthropic directly)

### Data Storage Locations
- **MySQL/MariaDB** (Hetzner): All user data, messages, sessions, reports, surveys, mood ratings
- **Redis** (Hetzner): Queue processing, WebSocket events, cache
- **AWS S3**: Avatar images (via Spatie Media Library)
- **OpenRouter/Anthropic**: Conversation data passes through for AI processing. Data sharing was turned OFF (March 25, 2026)
- **OpenRouter data retention:** With sharing OFF, OpenRouter still retains request metadata (tokens, model, timestamps) for billing/abuse. May retain prompt/completion text briefly for abuse monitoring. Policy at openrouter.ai/privacy.
- **Anthropic API data retention:** API inputs/outputs are NOT used for training by default. Retained for up to 30 days for trust & safety monitoring, then deleted. Policy at anthropic.com/policies/privacy. (Verify these are still current as of 2026.)
- **Stripe**: Payment/subscription data
- **Email provider**: AWS SES (eu-north-1, Stockholm) via SMTP
  - **Session summary emails contain FULL therapy summary text** in the email body (not just a notification link)
  - This means health/therapy data is transmitted via unencrypted email
  - 9 email types total: session report, user report, checkup, welcome, verification, therapist message, review requests (x2), password reset

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

### User Support & Complaint Handling
- **Contact channels:** info@therapeak.com + in-app contact form
- **Support handled by:** Sarp personally
- **Response time:** Typically 5-10 minutes. On busy days up to 8 hours. Never more than 24 hours.
- **Complaint tracking:** Complaints requiring fixes are labelled "Needs-fix" in email. Picked up when Sarp starts a new development cycle.
- **Refund process:** Done via Stripe dashboard directly, Stripe sends automatic confirmation email to user.
- **Refund policy:** Very lenient — almost always refunds when asked, sometimes proactively offers refunds to unhappy users. Only refuses refunds for more than 1 billing term.
- **Chargebacks:** Handled cooperatively — typically accepts/refunds. One user was fully banned from the platform for chargeback abuse (only user ever fully banned).
- **No backup support person** — if Sarp is unavailable, emails wait (but he works even when sick)
- **Emergency backup for vigilance:** Wife (director) designated as backup for serious incident reporting if Sarp is unreachable. Needs basic training on what constitutes a reportable incident and how to escalate.

### Monitoring
- **Telescope** (Laravel Telescope) is monitored constantly by Sarp — requests page shows live user activity, incoming requests, errors
- Sarp monitors even outside work hours (evenings, weekends)
- This serves as the primary system health monitoring tool

### User Feedback & Reviews
- **Trustpilot:** Active presence, reviews collected as post-market user feedback
- **Review request system:** Automated emails requesting reviews from users (see app for details)
- Negative reviews are actively addressed — Sarp contacts users personally to resolve issues

### User Disagreement with AI Advice
- Users are told the AI output is informational, not a diagnosis or treatment plan
- If a user disagrees with AI guidance, they should: (1) disregard the suggestion — all guidance is optional, (2) discuss concerns with their healthcare professional, (3) switch therapist for a different AI perspective, (4) contact support if they believe the AI said something harmful
- The IFU will include clear language that users are never obligated to follow AI suggestions

### GDPR / Data Protection
- **DPO:** Sarp Derinsu (designated as DPO for the company)
- **DPIA:** Not yet done — will be created as part of QMS
- **Privacy policy:** Exists in 20+ languages, covers GDPR rights, data collection, international transfers
- **Data location:** Netherlands (Hetzner server in Nuremberg, Germany)
- **Data Processing Agreement:** SIGNED with Hetzner (March 25, 2026) via customer portal. Covers: personal master data, communication data, health data (Art. 9 GDPR). Data subjects: customers and interested parties.
- **OpenRouter data sharing:** Was enabled (1% discount) — NOW TURNED OFF (as of March 25, 2026)
- **Account deletion:** Soft delete with option for full data wipe on GDPR request
- **Product liability insurance:** None currently — to be obtained post-certification

### Infrastructure Security
- **SSL:** Let's Encrypt (auto-renewed)
- **SSH access:** Sarp only, no other users have server access
- **Server config:** Default Hetzner VPS configuration, no known special firewall setup
- **Data breaches:** None known
- **2FA status:**
  - GitHub: ✓
  - Hetzner: ✓
  - Stripe: ✓
  - AWS: ✓
  - OpenRouter: ✗
  - Therapeak admin panel: ✗ (no 2FA on the web app)
- **API keys/secrets:** Stored in `.env` files only (no password manager)
- **Anti-abuse measures:**
  - Temp email blocklist (maintained manually, expanded when new temp domains are found)
  - IP/country/VPN blocking via middleware
  - One user fully banned for chargeback abuse (only full ban ever issued)

### Supplier History
- **AI models:** Tried alternatives to Claude but quality was significantly lower. Claude (via OpenRouter) remains the primary choice due to response quality.
- **No supplier switches** for critical infrastructure (Hetzner, Stripe, AWS have been used since the beginning)

### Marketing & Analytics
- **Tracking:** Google Ads (gtag), Meta pixel, TikTok pixel — active on all pages
- **Google Ads** is critical for user acquisition and very sensitive to changes (campaign learning resets)
- Conversion tracking fires on purchase events

### Consent & Legal
- **Privacy policy:** Created by Suzan, exists in 20+ languages
- **Terms of Service:** Old, not recently reviewed by Sarp
- **Signup consent:** Users agree to privacy policy/ToS during registration — no specific separate consent for AI-based health data processing
- **For CE marking:** May need explicit consent for health data processing (GDPR Art. 9), and ToS needs review/update

### Error Handling (User-Facing)
- **AI failure:** User sees "typing" indicator indefinitely — no explicit error message shown
- **Retry logic:** Endless retry across multiple providers (OpenRouter → fallback models). System keeps trying until a response is generated.
- **`FLAG_DID_NOT_RESPOND`** monitoring catches cases where users notice delays ("Hello?", "Are you there?")
- **Message loss:** Not a practical concern — retry logic and queue persistence prevent message loss
- **Session minutes:** If AI fails to respond, the session timer continues running (user loses minutes)

### Authentication
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
- **Production:** Hetzner VPS, Nuremberg, Germany (self-managed, Sarp does everything)
- **Deployment process:** git push to main → git pull on production → check it live
- **Local development environment:** Docker Compose (Laravel Sail) — used for development and testing before deploying
- **No CI/CD pipeline**
- **No staging environment** (but local environment serves as testing ground)
- **No automated testing** (manual testing locally + live verification)
- **No git branches** — direct commits to main (will use branches for bigger features, but solo developer so not needed)
- **Deploy frequency:** Variable — couple times a day during active feature work, otherwise when needed for changes
- **Systemd services:** Horizon (queues), scheduler, Soketi
- **Known issue:** Horizon sometimes stops processing jobs after ~2 months online. Fixed by periodic systemd restart.
- **Other production commands:** Occasionally runs artisan commands for user management (blocking users, admin tasks with hardcoded user IDs)

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

- **Regulatory consultant:** Suzan Slijpen, via Pander Consultancy (consultancy agreement in place)
  - Guides and advises, reviews documents, ensures right files are created
  - Has NOT written QMS documents; Claude Code builds them under her guidance
  - Contract: Pander Consultancy (KvK: 67870279), invoices per engagement, governing law Netherlands
  - Scope: Advice on regulatory requirements, QMS build guidance, audit preparation
  - **Training provided to Sarp:** General overview of what a QMS is and what's needed. Sarp is learning as the QMS is being built — no formal training course completed.

### Sarp Derinsu — Background & Competency
- **Education:** VWO (N&G + N&T profiles), AI at University of Amsterdam (1 year), Dentistry at Radboud Nijmegen (propedeuse obtained), International Business & Communication (1 year)
- **Self-taught software developer** — built the entire Therapeak platform solo
- **Informal study:** Extensive self-directed study in psychology and neuropsychology (age 14-18), ongoing strong interest in psychology
- **Roles in QMS:** Management Representative, Quality Manager, Developer, DPO, Person Responsible for Regulatory Compliance (PRRC per MDR Art. 15)
- **Internal audit (March 31, 2026):** Conducted by a jurist hired by Suzan. Quick QMS audit to check everything is in the right place before NB engagement.
- **Nisan Derinsu (wife):** Studied Psychological Counseling and Guidance (Turkey). Consulted on AI prompt design and therapeutic approach. Handles Turkish company setup. Not involved in day-to-day operations but provides clinical perspective when asked. Designated as emergency vigilance backup.
- **Communication with Suzan:** Email, video calls, WhatsApp. Regular contact during QMS build.

## Supplier List (Complete)

| Supplier | Service | Critical? |
|----------|---------|-----------|
| Hetzner | Production hosting (Nuremberg, Germany) | Yes |
| OpenRouter | AI gateway (routes to Anthropic Claude) | Yes |
| Anthropic | AI model provider (via OpenRouter) | Yes |
| OpenAI | Content generation, moderation (in chat-tool) | Yes |
| Fal.ai | AI therapist avatar image generation | No |
| Stripe | Payments, subscriptions | Yes |
| AWS (SES) | Email delivery (eu-north-1, incl. full session summaries in emails) | Yes |
| AWS (S3) | File storage | No |
| GitHub | Source code repository | Yes |
| Google (Suite) | Business email (Gmail) | No |
| Google (Ads) | Marketing | No |
| Vimexx | Domain registration | No |
| JetBrains | IDE (development tools) | No |
| Google (One) | Video generation for marketing | No |

## Post-Market Feedback (Pre-CE, Wellness Version)

**IMPORTANT:** The current product is a wellness tool, NOT a medical device. The medical device (CE-marked version) has 0 users, 0 sessions, and 0 revenue. All data below is from the wellness version and can only be referenced as "pre-market experience" or "equivalent device experience" in QMS documents, not as medical device post-market data.

### Wellness Version Usage Data
- **Active subscribers:** A few hundred (wellness product)
- **User retention:** 65-75% month 1, 35-45% month 2, ~25% month 3, ~20% month 4, then slowly levels off
- **No reports of harm** — no user has reported injury, worsening of condition, or adverse health effects

### Known Issues (Wellness Version)
- **AI role confusion:** Occasionally the AI doesn't distinguish between patient and therapist role, acts as the patient — users feel mocked. This is a technical issue, not intentional.
- **Common complaints:**
  - Users expected a voice function (not offered)
  - Responses were too repetitive (largely addressed by adding reasoning tokens to Claude)
  - AI "repeats what you say" (improved significantly with reasoning in Sonnet 4.5)
- **Refund requests:** Normal rate, related to feature expectations, not safety concerns
- **No adverse events** requiring regulatory reporting

This feedback will be referenced in the PMS Plan and initial PMS Report as pre-market experience.

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

## Risk Assessment Context

### Foreseeable Hazards (from Sarp's input)
- **Worst realistic case:** AI validates user's self-harming behavior, toxic behavior, or destructive patterns — because some users specifically come to the platform with these issues. (Unknown if this also occurs in normal therapy sessions.)
- **AI role confusion:** AI occasionally acts as the patient instead of therapist, making users feel mocked. Technical issue, not intentional.
- **User dependency:** Some users use the platform heavily. Unknown whether this constitutes unhealthy dependency. To be monitored via PMS.
- **No known incidents:** Nothing has actually gone wrong so far. No user harm reported.

### Marketing / Google Ads Constraint
- **Google Ads is extensively used** and very sensitive to website changes
- Any large changes to the website or campaigns can cause campaigns to restart learning (losing optimization)
- This means website changes for CE marking (medical terminology, IFU, disclaimers) must be planned carefully to minimize campaign disruption
- This is a business constraint, not a QMS issue — but affects the rollout plan

## Supplier Relationship Status
- **No formal contracts/ToS** with critical suppliers beyond standard terms of service
- Therapeak is a small company using services from large providers (Hetzner, Stripe, AWS, OpenRouter)
- Supplier relationships are governed by each provider's standard terms of service
- Hetzner: Self-managed VPS (Sarp manages everything). No managed/premium support tier.
- For QMS: supplier control will be based on standard ToS review + performance monitoring

## Open Questions / Action Items

### MUST DO before CE marking (Sarp's personal action items):

1. **CRISIS PROTOCOL** — Uncomment and activate the crisis protocol in ALL 6 conversation jobs. Add FLAG_CRISIS to ChatDebugFlag. This is a patient safety issue.
2. **Data Processing Agreement with Hetzner** — Required for GDPR. Contact Hetzner and sign their DPA.
5. **Backup verification** — Confirm Hetzner backup schedule, test a restore.
6. **AI model validation process** — Formalize the testing process for model changes. Document in a work instruction.
7. **GDPR data erasure** — Implement actual data deletion (not just soft delete) for GDPR Article 17 requests. Current process only adds `deleted_` prefix.
8. **Session summary emails** — Full therapy content sent via unencrypted email. Consider: link-only emails instead of content in body, or at minimum document this as a known data protection risk with user consent.

### Should do but QMS can be written to account for it:

6. **Encryption at rest** — Document in risk assessment why chat data is unencrypted (performance/operational reasons) with compensating controls (server security, access controls).
7. **Automated testing** — Not needed immediately, but SOP should describe the testing approach (manual testing + prompt testing tool as validation).
8. **Staging environment** — Not strictly required but recommended. Document that production is the only environment with manual deployment verification.
9. **Clinical evidence gap** — Own data is self-assessment based, not standardized scales. Consider implementing PHQ-9/GAD-7 as optional in-app assessments for future evidence. For now, literature review + own data is sufficient for CER.
10. **Privacy policy update** — CE-marked version needs updated privacy policy with health data processing language. Plan needed.
11. **Product liability insurance** — To be obtained post-certification. Current status: none.
