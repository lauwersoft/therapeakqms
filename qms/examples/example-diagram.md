---
id: "DWG-001"
title: "CAPA Process Flow"
type: "DWG"
version: "0.1"
status: "draft"
author: "Sarp Derinsu"
iso_refs:
  - "8.5.2"
  - "8.5.3"
---

# CAPA Process Flow

This diagram illustrates the corrective and preventive action process as defined in [[SOP-003]].

```mermaid
flowchart TD
    A[Nonconformity Identified] --> B[Open CAPA Record<br/>Assign CAPA Number]
    B --> C{Severity?}
    C -->|Critical| D[Immediate Containment<br/>Notify within 24h]
    C -->|High| E[Investigate within 5 days]
    C -->|Medium| F[Investigate within 15 days]
    C -->|Low| G[Investigate within 30 days]
    D --> H[Root Cause Analysis]
    E --> H
    F --> H
    G --> H
    H --> I[Define Corrective Action]
    I --> J[Define Preventive Action]
    J --> K[Assess Impact on<br/>Safety & Performance]
    K --> L{Approved by<br/>Quality Manager?}
    L -->|No| I
    L -->|Yes| M[Implement Actions]
    M --> N[Update Documents<br/>& Training]
    N --> O[Verify Effectiveness<br/>min. 30 days]
    O --> P{Effective?}
    P -->|No| H
    P -->|Yes| Q[Close CAPA]
    Q --> R[Update Risk<br/>Management File]

    style A fill:#fee2e2,stroke:#991b1b
    style Q fill:#dcfce7,stroke:#166534
    style D fill:#fef3c7,stroke:#92400e
```

## Process Steps

### 1. Identification
Nonconformity identified from any source: complaint, audit, deviation, post-market surveillance, or management review.

### 2. Classification
Severity classified as Critical, High, Medium, or Low based on patient safety impact and regulatory implications.

### 3. Root Cause Analysis
Investigation using appropriate methods (5 Why, Fishbone, Fault Tree Analysis).

### 4. Action Planning
Corrective actions address the immediate issue. Preventive actions prevent recurrence. Impact on safety and performance assessed before approval.

### 5. Implementation
Actions implemented per timeline. Documents updated, training provided.

### 6. Effectiveness Verification
Data collected over minimum 30 days. If not effective, return to root cause analysis.

### 7. Closure
Quality Manager reviews and closes. Risk management file updated.

## Related Documents
- [[SOP-003]] CAPA Procedure
- [[FM-001]] CAPA Form
- [[RA-001]] Risk Management File
