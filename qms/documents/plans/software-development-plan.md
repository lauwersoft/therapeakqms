---
id: "PLN-005"
title: "Software Development Plan"
type: "PLN"
version: "1.0"
status: "approved"
effective_date: "2026-03-01"
author: "Sarp Derinsu"
iso_refs:
  - "7.3.2"
mdr_refs:
  - "Annex II"
---

# Software Development Plan

## 1. Purpose

This plan defines the software development lifecycle process for the Therapeak AI therapy platform, a Class IIa medical device software (MDSW) under EU MDR 2017/745. The plan establishes the development environment, tools, methods, testing approach, configuration management, and deployment process, applying IEC 62304:2006+A1:2015 principles proportionate to the software safety classification and the single-developer organization.

## 2. Scope

This plan applies to the development, maintenance, and deployment of the Therapeak medical device software (version 1.0, `DEVICE_MODE=medical`), including:

- The Therapeak web application: user-facing therapy sessions, payments, reports, mood tracking, AI content generation, moderation, therapist profiles
- AI model integration and prompt management
- All SOUP (Software of Unknown Provenance) components

This plan does not cover the wellness product version (`DEVICE_MODE=wellness`), which is a separate product sharing the same codebase.

## 3. Software Safety Classification

### 3.1 Classification: Class B

Per IEC 62304, the Therapeak software is classified as **Class B (non-serious injury possible)**.

**Rationale:**
- The software provides conversational therapeutic guidance for self-management of mild to moderate mental health symptoms.
- Reasonably foreseeable harm from software failure or erroneous output is primarily minor and reversible (e.g., transient distress, unhelpful coping suggestion, temporary exacerbation of symptoms).
- The software does not control life-sustaining equipment, does not provide diagnoses, and does not direct specific clinical interventions.
- Crisis situations are mitigated through disclaimers, contraindications, and Claude's built-in safety mechanisms for crisis detection and resource referral.
- Catastrophic outcomes (e.g., self-harm) could theoretically result from device failure to appropriately handle a crisis, but multiple independent safety barriers exist (AI model safety, prompt instructions, platform disclaimers, contraindication statements). The overall risk architecture supports Class B classification with appropriate risk controls.

### 3.2 Implications for Development Process

Class B requires:
- Software development planning (this document)
- Software requirements analysis
- Software architectural design
- Software detailed design (for high-risk units)
- Software unit verification
- Software integration and integration testing
- Software system testing
- Software release

The rigor of each activity is proportionate to the single-developer context with compensating controls as described in this plan.

## 4. Development Environment

### 4.1 Local Development

| Component | Tool/Technology |
|---|---|
| Operating system | Linux-based development environment |
| Web server | Nginx (local development) |
| Backend runtime | PHP 8.2 |
| Backend framework | Laravel 10 |
| Frontend framework | Vue 3 |
| UI framework | Tailwind CSS + DaisyUI |
| Build tool | Vite 4 |
| Database | MariaDB 10 (containerized) |
| Cache/Queue | Redis (containerized), Laravel Horizon |
| WebSocket | Soketi (self-hosted, Pusher-compatible) |
| IDE | PHPStorm (JetBrains) |

### 4.2 Production Environment

| Component | Detail |
|---|---|
| Hosting | Hetzner VPS, Nuremberg, Germany |
| Server management | Self-managed by Sarp Derinsu |
| SSL | Let's Encrypt (auto-renewed) |
| Process management | Systemd services (Horizon, scheduler, Soketi) |
| Monitoring | Laravel Telescope (request monitoring, error tracking) |

## 5. Configuration Management

### 5.1 Version Control

| Aspect | Detail |
|---|---|
| System | Git |
| Repository hosting | GitHub |
| Branch strategy | Single branch (main), direct commits |
| Branching for features | Used for larger features when warranted; not required for routine changes |
| Access control | Sarp Derinsu (sole developer, full access) |

### 5.2 Software Versioning

The medical device software shall use semantic versioning (MAJOR.MINOR.PATCH):

- **MAJOR:** Changes that affect the intended purpose, safety classification, or require re-certification.
- **MINOR:** New features, significant functional changes, AI model changes.
- **PATCH:** Bug fixes, minor UI changes, prompt refinements that do not change clinical behavior.

The initial CE-marked release shall be designated version 1.0.0. Version numbers shall be tracked in the application configuration and recorded in the software release record.

### 5.3 Prompt Version Management

AI therapy prompts are a critical component of device behavior. Prompt changes are managed as follows:

- System prompts are stored as text files in the codebase (chat_room_instructions.txt, priority_chat_instructions.txt) and as inline instructions in conversation job classes.
- All prompt changes are tracked through git commit history.
- Prompt changes that could affect clinical behavior (e.g., changes to therapeutic approach, safety instructions, role enforcement) are treated as MINOR version changes and require risk assessment per [[SOP-002]].
- Prompt changes that are purely formatting or non-clinical refinements are treated as PATCH changes.

## 6. No CI/CD and No Staging: Compensating Controls

Therapeak does not use a CI/CD pipeline or a staging environment. This is appropriate for a single-developer organization and is compensated by the following controls:

| Risk | Compensating Control |
|---|---|
| Untested code reaches production | All changes are tested locally in local development environment before deployment. Manual verification covers functional and visual correctness. |
| Regression not detected | Post-deployment live verification via Telescope monitors for errors and unexpected behavior. Sarp actively monitors after every deployment. |
| No automated test safety net | Prompt testing tool validates AI output quality for prompt changes. Session quality monitoring (ChatDebugFlags) provides ongoing automated detection of AI quality issues. |
| No staged rollout | Deployment is a simple git pull; rollback is a git revert + git pull. Single-server architecture means instant rollback capability. |
| No code review | Single developer context. All changes are made by the sole authorized developer. Design decisions and significant changes are documented in commit messages and discussed with regulatory consultant when relevant to QMS. |

## 7. Testing Approach

### 7.1 Testing Methods

| Method | Description | When Applied |
|---|---|---|
| Manual local testing | Functional testing in local development environment, covering user flows, edge cases, and visual verification | Before every deployment |
| Prompt testing tool | Dedicated tool for testing AI prompt changes, evaluating response quality, safety compliance, and therapeutic appropriateness | When prompts are modified |
| Live verification (Telescope) | Post-deployment monitoring of requests, errors, and system behavior in production | After every deployment |
| Session quality monitoring | Automated ChatDebugFlag checks (role confusion, non-response) on production sessions | Continuous (every session) |
| Manual session review | Sarp reviews 1-2 live therapy sessions for harmful patterns or quality degradation | Daily to weekly |

### 7.2 Test Categories per IEC 62304

| IEC 62304 Activity | Therapeak Approach |
|---|---|
| Unit verification | Manual testing of individual functions/components locally. Critical AI-related functions (prompt construction, safety instructions) verified through prompt testing tool. |
| Integration testing | Manual end-to-end testing in local development environment covering all application modules. |
| System testing | Full user-flow testing locally: onboarding survey, therapist matching, therapy session, session summary, mood tracking, report generation. |
| Acceptance testing | Live verification post-deployment confirms system operates correctly in production environment. |

### 7.3 AI Model Testing

Given that Therapeak relies on third-party AI models (Anthropic Claude, accessed via OpenRouter gateway) whose behavior may change with upstream updates, the following AI-specific testing is performed:

| Test | Description | Frequency |
|---|---|---|
| Prompt output quality | Verify therapeutic responses are appropriate, safe, and within intended scope using prompt testing tool | After prompt changes, after model updates |
| Role enforcement | Verify AI maintains therapist role consistently | Continuous (FLAG_SWITCHED_ROLES monitoring) |
| Safety instruction compliance | Verify AI follows crisis handling, contraindication, and scope limitation instructions | After prompt changes |
| Model fallback | Verify fallback chain (Sonnet 4.5 -> Opus 4.5 -> Sonnet 4 -> Sonnet 3.7 -> Opus 4) functions correctly | After architecture changes |
| Cross-language quality | Spot-check therapeutic quality in major supported languages | Periodically |

## 8. AI Model Management

### 8.1 Model Selection Criteria

AI models used for therapy conversations shall meet the following criteria:

- Demonstrated capability for empathetic, therapeutically appropriate conversational responses
- Reliable adherence to system prompt instructions (role enforcement, safety boundaries, content restrictions)
- Support for multi-language output consistent with the device's supported locales
- Availability through multi-provider routing (OpenRouter gateway routes to Vertex AI, Bedrock, Anthropic API) for reliability
- No training on user data (verified via provider data policies)

### 8.2 Model Change Process

Changes to the primary AI model (e.g., upgrading from Claude Sonnet 4.5 to a newer version) shall follow this process:

1. Evaluate new model against selection criteria (Section 8.1)
2. Test with prompt testing tool for output quality and safety compliance
3. Conduct A/B test with subset of users if feasible (existing A/B infrastructure supports this)
4. Assess risk impact per [[SOP-002]] -- model changes are treated as MINOR version changes
5. Update conversation job classes, deploy, and verify live
6. Monitor ChatDebugFlag rates and user feedback post-deployment
7. Document model change in software change record

### 8.3 Current Model Configuration

| Function | Model | Provider | Accessed via |
|---|---|---|---|
| Primary therapy chat | Claude Sonnet 4.5 (with reasoning tokens) | Anthropic | OpenRouter gateway |
| A/B test variant | Claude Sonnet 4.6 | Anthropic | OpenRouter gateway |
| Fallback chain | Claude Opus 4.5, Claude Sonnet 4, Claude 3.7 Sonnet, Claude Opus 4 | Anthropic | OpenRouter gateway |
| Session summaries | GPT-4o | OpenAI | Direct API |
| User reports | GPT-4o | OpenAI | Direct API |
| Session quality monitoring | GPT-4o | OpenAI |
| Image generation | Flux Pro | Fal.ai |

## 9. Deployment Process

### 9.1 Standard Deployment

1. **Develop and test locally:** Implement change in local development environment. Test manually covering affected functionality.
2. **Commit to main:** Git commit with descriptive message to the main branch.
3. **Push to GitHub:** `git push` to remote repository.
4. **Deploy to production:** `git pull` on the production server.
5. **Verify live:** Check Telescope for errors, verify affected functionality in production, monitor for 15-30 minutes.
6. **Rollback if needed:** `git revert` the commit + `git pull` on production.

### 9.2 Deployment Verification Checklist

After each deployment, the following shall be verified:

- [ ] No new errors in Telescope request log
- [ ] No new exceptions in Telescope exception log
- [ ] Therapy sessions functioning (test message if significant change)
- [ ] Key user flows accessible (login, chat, mood tracking)
- [ ] Queue processing active (Horizon dashboard)

### 9.3 Change Classification for Deployment

| Change Type | Pre-Deployment Requirements | Post-Deployment Monitoring |
|---|---|---|
| PATCH (bug fix, minor UI) | Local testing of fix | Standard Telescope monitoring |
| MINOR (new feature, prompt change, model change) | Local testing + prompt testing (if AI-related) + risk assessment | Extended monitoring (24-48 hours), ChatDebugFlag review |
| MAJOR (intended purpose change, safety classification impact) | Full risk assessment + regulatory review + testing | Extended monitoring, NB notification if required |

## 10. SOUP (Software of Unknown Provenance)

### 10.1 SOUP List

The following SOUP components are used in the Therapeak medical device:

| SOUP Component | Version | Function | Risk Assessment |
|---|---|---|---|
| Laravel | 10.x | Backend framework (routing, ORM, auth, queues) | Core framework; failure could affect all device functions |
| Vue 3 | 3.x | Frontend framework (SPA rendering) | UI failure could prevent user interaction |
| Tailwind CSS | -- | Utility CSS framework | Visual styling only |
| Laravel Cashier | -- | Stripe subscription management | Billing/subscription issues (non-clinical) |
| OpenRouter SDK | -- | API gateway client (routes to Anthropic) | Core dependency for therapy chat |
| Stripe SDK | -- | Payment processing | Billing (non-clinical) |
| Laravel Horizon | -- | Queue dashboard and management | Queue processing visibility |
| Laravel Telescope | -- | Debug/monitoring dashboard | Monitoring capability |
| Spatie Media Library | -- | File/image management (S3) | Avatar storage (non-clinical) |
| Laravel Sanctum | -- | SPA authentication | Authentication security |
| Laravel Passport | -- | Service-to-service OAuth | Inter-service authentication |
| Soketi | -- | WebSocket server | Real-time message delivery |
| MariaDB | 10.x | Database | Core data storage |
| Redis | -- | Cache and queue backend | Performance and queue processing |
| PHP | 8.2 | Server runtime | Core runtime environment |
| Vite | 4.x | Frontend build tool | Build-time only |

### 10.2 SOUP Management

- SOUP versions are tracked in `composer.json` (PHP) and `package.json` (JavaScript).
- SOUP updates are evaluated for impact on device safety and performance before being applied.
- Security advisories for SOUP components are monitored through GitHub Dependabot alerts.
- Critical security patches are applied promptly; feature updates are evaluated and tested before adoption.

## 11. Software Problem Resolution

Software problems (bugs, defects, anomalies) identified during development or post-market use are managed as follows:

1. **Identification:** Via testing, Telescope monitoring, user complaints, or session quality flags.
2. **Assessment:** Evaluate safety impact. Classify as: critical (safety-related, immediate fix), major (significant functional issue), minor (cosmetic or low-impact).
3. **Resolution:** Implement fix, test locally, deploy per Section 9.
4. **Verification:** Confirm fix resolves the problem without introducing new issues.
5. **Documentation:** Record in git commit history. Safety-related problems additionally documented in risk management file [[RA-001]].

Critical safety-related software problems shall be assessed for reportability as serious incidents per [[SOP-009]].

## 12. Responsibilities

All software development activities are performed by Sarp Derinsu as the sole developer, quality manager, and release authority for Therapeak B.V.

| Role | Person | Responsibilities |
|---|---|---|
| Developer | Sarp Derinsu | Design, implementation, testing, deployment |
| Quality Manager | Sarp Derinsu | Process compliance, release approval, change assessment |
| Release Authority | Sarp Derinsu | Authorization of production deployments |

## 13. Timeline

| Milestone | Target Date |
|---|---|
| Software development plan approved | 2026-03-01 |
| Software requirements specification ([[SPE-001]]) complete | Before NB Stage 1 (April 2026) |
| Software architecture documentation complete | Before NB Stage 1 (April 2026) |
| Version numbering system implemented | Before CE marking |
| Medical device version 1.0.0 release | Upon CE marking |
| Ongoing maintenance and updates | Post-market, continuous |

## 14. References

- [[SOP-011]] Software Development and Maintenance Procedure
- [[SOP-002]] Risk Management Procedure
- [[SOP-009]] Vigilance and Post-Market Surveillance Procedure
- [[SPE-001]] Software Requirements Specification
- [[RA-001]] Risk Management File
- IEC 62304:2006+A1:2015 Medical device software -- Software life cycle processes
- IEC 82304-1:2016 Health software -- General requirements for product safety
- EU MDR 2017/745 Annex II (Technical Documentation)
- MDCG 2019-11 Guidance on qualification and classification of software in MDR and IVDR
