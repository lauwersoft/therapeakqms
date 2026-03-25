---
id: "DWG-002"
title: "Software Architecture Diagram"
type: "DWG"
version: "1.0"
status: "approved"
effective_date: "2026-03-01"
author: "Sarp Derinsu"
iso_refs:
  - "7.3.3"
mdr_refs:
  - "Annex II"
---

# Software Architecture Diagram

## 1. Purpose

This document provides the software architecture diagram for the Therapeak medical device (software version 1.0). It illustrates the system components, external interfaces, data flows, and the boundary between health data and non-health data processing.

## 2. System Architecture Overview

```mermaid
graph TB
    subgraph "User Environment"
        USER["User Browser<br/>(Chrome, Firefox, Edge, Safari)"]
    end

    subgraph "Therapeak Infrastructure (Hetzner, Nuremberg, Germany)"
        subgraph "Therapeak Application"
            WEBAPP["Therapeak Web App<br/>Laravel 10 / Vue 3 / Inertia.js"]
            HORIZON["Laravel Horizon<br/>(Queue Processing)"]
            SOKETI["Soketi<br/>(WebSocket Server)"]
        end

        MARIADB[("MariaDB 10<br/>(User Data, Messages,<br/>Sessions, Reports)")]
        REDIS[("Redis<br/>(Queues, Cache,<br/>WebSocket Events)")]
    end

    subgraph "AI Providers"
        OPENROUTER["OpenRouter API<br/>(Multi-Provider Gateway)"]
        CLAUDE["Anthropic Claude<br/>(Therapy Conversations)"]
        OPENAI["OpenAI API<br/>(Summaries, Reports,<br/>Moderation, Monitoring)"]
        FALAI["Fal.ai<br/>(Avatar Image Generation)"]
    end

    subgraph "Payment & Email"
        STRIPE["Stripe API<br/>(Payments, Subscriptions)"]
        AWSSES["AWS SES<br/>(Transactional Email,<br/>eu-north-1 Stockholm)"]
    end

    subgraph "Storage"
        S3["AWS S3<br/>(Avatar Images)"]
    end

    USER <-->|"HTTPS/WSS<br/>(SSL/TLS)"| WEBAPP
    USER <-->|"WebSocket"| SOKETI

    WEBAPP <--> MARIADB
    WEBAPP <--> REDIS
    HORIZON <--> REDIS
    SOKETI <--> REDIS


    WEBAPP -->|"Conversation<br/>Prompts"| OPENROUTER
    OPENROUTER -->|"Multi-Provider<br/>Routing"| CLAUDE
    CLAUDE -->|"AI Responses"| OPENROUTER
    OPENROUTER -->|"AI Responses"| WEBAPP

    WEBAPP --> OPENAI
    WEBAPP --> FALAI

    WEBAPP --> STRIPE
    WEBAPP --> AWSSES
    WEBAPP --> S3

    classDef healthData fill:#ff9999,stroke:#cc0000,color:#000
    classDef nonHealthData fill:#99ccff,stroke:#0066cc,color:#000
    classDef infrastructure fill:#d4edda,stroke:#28a745,color:#000

    class MARIADB,OPENROUTER,CLAUDE,OPENAI,AWSSES healthData
    class STRIPE,FALAI,S3 nonHealthData
    class WEBAPP,HORIZON,SOKETI,REDIS infrastructure
```

**Legend:**
- Red nodes: Components that process or store **health data** (therapy conversations, session summaries, reports, mood data, screening results)
- Blue nodes: Components that process **non-health data only** (payments, avatar images, file storage)
- Green nodes: Infrastructure components (application logic, queues, real-time messaging)

## 3. Data Flow: Therapy Session

The following diagram illustrates the complete data flow for a single therapy session message exchange.

```mermaid
sequenceDiagram
    participant U as User Browser
    participant W as Therapeak Web App
    participant R as Redis / Horizon
    participant DB as MariaDB
    participant OR as OpenRouter
    participant C as Anthropic Claude
    participant SK as Soketi (WebSocket)

    U->>W: Send therapy message (HTTPS)
    W->>DB: Save user message
    W->>R: Dispatch ConversationJob to queue

    R->>R: Horizon picks up job

    Note over R,DB: ConversationJob constructs system prompt

    R->>DB: Fetch: therapist profile, survey answers,<br/>session summaries, user profile, chat history
    DB-->>R: Context data returned

    Note over R: Build system prompt:<br/>- Static therapeutic instructions (160-200+)<br/>- Dynamic personality traits (17 traits)<br/>- Chat room context<br/>- User survey answers<br/>- Previous session summaries<br/>- User profile text

    R->>OR: Send prompt + conversation history
    OR->>C: Route to Claude (Vertex/Bedrock/API)
    C-->>OR: AI therapy response
    OR-->>R: Response returned

    alt API Error (500, 400, 529, 429)
        R->>OR: Retry (up to 3 attempts, 1s interval)
        alt All retries fail
            R->>OR: Fallback to Claude Opus 4.5
            alt Opus fallback fails
                Note over R,OR: OpenRouter model fallback:<br/>Sonnet 4 → Sonnet 3.7 → Opus 4
            end
        end
    end

    R->>DB: Save AI response as assistant message
    R->>SK: Broadcast message event
    SK->>U: Deliver AI response (WebSocket)

    Note over U: User sees AI therapist response in real time
```

## 4. Data Flow: Post-Session Processing

```mermaid
sequenceDiagram
    participant W as Therapeak Web App
    participant R as Redis / Horizon
    participant DB as MariaDB
    participant OA as OpenAI (GPT-4o)
    participant SES as AWS SES

    Note over W: Session ends (timer expires or user ends)

    W->>R: Dispatch SummarizeTherapySessionJob

    R->>DB: Fetch session transcript
    DB-->>R: Transcript returned
    R->>OA: Generate session summary (max 500 tokens)
    OA-->>R: Summary returned
    R->>DB: Save session summary

    R->>SES: Send session summary email to user
    Note over SES: Email contains FULL summary text<br/>(health data transmitted via email)

    W->>R: Dispatch CreateUserReportJob

    R->>DB: Fetch: last 10 sessions, trial survey,<br/>previous report
    DB-->>R: Data returned
    R->>OA: Generate user report (max 4000 tokens)
    OA-->>R: Report returned
    R->>DB: Save user report

    W->>R: Dispatch CheckSessionForSwitchedRolesJob
    R->>DB: Fetch session transcript
    R->>OA: Analyze for role confusion
    OA-->>R: Analysis result
    R->>DB: Save ChatDebugFlag (if flagged)

    W->>R: Dispatch CheckSessionForDidNotRespondJob
    R->>DB: Fetch session transcript
    R->>OA: Analyze for non-response gaps
    OA-->>R: Analysis result
    R->>DB: Save ChatDebugFlag (if flagged)
```

## 5. Health Data vs Non-Health Data Flow

The following table clarifies which external interfaces handle health data and which do not.

| Interface | Health Data | Data Description |
|---|---|---|
| **OpenRouter / Anthropic Claude** | Yes | Therapy conversation prompts and responses containing user messages, system instructions with survey data and session context |
| **OpenAI (GPT-4o)** | Yes | Session transcripts for summary generation, report generation, and session quality monitoring |
| **OpenAI (GPT-3.5-turbo)** | No | Platform content moderation only (reviews, survey replies, article replies) — NOT therapy messages |
| **AWS SES** | Yes | Session summary emails contain full therapy summary text in the email body |
| **MariaDB** | Yes | All user data, therapy messages, session summaries, reports, mood ratings, survey responses |
| **Redis** | Transient | Queue payloads containing therapy messages (transient, not persisted long-term) |
| **Stripe** | No | Payment and subscription data only |
| **Fal.ai** | No | Avatar image generation prompts and images only |
| **AWS S3** | No | Avatar images only |

### 5.1 Health Data Boundary

```mermaid
graph LR
    subgraph "Health Data Zone"
        direction TB
        DB["MariaDB<br/>(Permanent Storage)"]
        OR["OpenRouter → Claude<br/>(Processing)"]
        OA["OpenAI GPT-4o<br/>(Processing)"]
        SES["AWS SES<br/>(Email Delivery)"]
    end

    subgraph "Non-Health Data Zone"
        direction TB
        ST["Stripe<br/>(Payments)"]
        FA["Fal.ai<br/>(Images)"]
        S3["AWS S3<br/>(Images)"]
    end

    subgraph "Application"
        direction TB
        APP["Therapeak Web App"]
    end

    APP --> DB
    APP --> OR
    APP --> OA
    APP --> SES
    APP --> ST
    APP --> FA
    APP --> S3

    classDef health fill:#ff9999,stroke:#cc0000,color:#000
    classDef nonHealth fill:#99ccff,stroke:#0066cc,color:#000
    classDef boundary fill:#ffffcc,stroke:#cccc00,color:#000

    class DB,OR,OA,SES health
    class ST,FA,S3 nonHealth
    class APP boundary
```

## 6. Infrastructure Components

| Component | Role | Technology |
|---|---|---|
| **Therapeak Web App** | Main application serving the user interface and orchestrating all backend operations | PHP 8.2 / Laravel 10 / Vue 3 / Inertia.js |
| **MariaDB 10** | Primary data store for all user data, therapy messages, sessions, reports, and surveys | Relational database |
| **Redis** | Queue broker (for Horizon job processing), cache layer, and WebSocket event transport | In-memory data store |
| **Laravel Horizon** | Queue worker that processes all asynchronous jobs including conversation jobs, summary generation, report generation, and monitoring jobs | Laravel queue dashboard/manager |
| **Soketi** | Self-hosted WebSocket server enabling real-time delivery of AI therapy responses to the user browser | Pusher-compatible WebSocket server |
| **Vite 4** | Frontend build tool | JavaScript bundler |

## 7. Revision History

| Version | Date | Author | Description |
|---|---|---|---|
| 1.0 | 2026-03-01 | Sarp Derinsu | Initial release |
