<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $meta['id'] ?? '' }} — {{ $meta['title'] ?? 'Document' }}</title>
    <style>
        @page {
            size: A4;
            margin: 20mm 15mm 25mm 15mm;

            @bottom-center {
                content: "{{ $meta['id'] ?? '' }} — {{ $meta['title'] ?? 'Document' }} — v{{ $meta['version'] ?? '1.0' }}";
                font-size: 8px;
                color: #999;
            }
            @bottom-right {
                content: "Page " counter(page) " of " counter(pages);
                font-size: 8px;
                color: #999;
            }
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', Arial, Helvetica, sans-serif;
            font-size: 11px;
            line-height: 1.6;
            color: #1a1a1a;
            background: white;
        }

        /* Document header */
        .doc-header {
            border-bottom: 2px solid #2563eb;
            padding-bottom: 12px;
            margin-bottom: 24px;
        }
        .doc-header h1 {
            font-size: 20px;
            font-weight: 700;
            color: #111;
            margin: 0 0 8px 0;
        }
        .doc-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4px 24px;
            font-size: 10px;
            color: #555;
        }
        .doc-meta .label {
            font-weight: 600;
            color: #333;
        }
        .doc-id {
            display: inline-block;
            background: #eff6ff;
            color: #2563eb;
            font-family: monospace;
            font-weight: 700;
            font-size: 12px;
            padding: 2px 8px;
            border-radius: 4px;
            margin-right: 8px;
        }
        .doc-status {
            display: inline-block;
            font-size: 10px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 4px;
        }
        .status-approved { background: #dcfce7; color: #166534; }
        .status-draft { background: #f3f4f6; color: #6b7280; }
        .status-in_review { background: #fef3c7; color: #92400e; }

        /* Manufacturer info */
        .manufacturer {
            font-size: 9px;
            color: #888;
            margin-top: 8px;
            border-top: 1px solid #e5e7eb;
            padding-top: 6px;
        }

        /* Content styles */
        h1 { font-size: 18px; font-weight: 700; margin: 28px 0 12px; color: #111; border-bottom: 1px solid #e5e7eb; padding-bottom: 8px; }
        h2 { font-size: 15px; font-weight: 700; margin: 24px 0 10px; color: #222; }
        h3 { font-size: 13px; font-weight: 600; margin: 20px 0 8px; color: #333; }
        h4 { font-size: 12px; font-weight: 600; margin: 16px 0 6px; color: #444; }

        p { margin: 8px 0; }
        ul, ol { margin: 8px 0 8px 24px; }
        li { margin: 3px 0; }

        strong { font-weight: 600; color: #111; }
        em { font-style: italic; }

        a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0;
            font-size: 10px;
            page-break-inside: auto;
        }
        thead { display: table-header-group; }
        tr { page-break-inside: avoid; }
        th {
            background: #f8fafc;
            font-weight: 600;
            color: #374151;
            text-align: left;
            padding: 6px 8px;
            border: 1px solid #d1d5db;
        }
        td {
            padding: 5px 8px;
            border: 1px solid #e5e7eb;
            vertical-align: top;
        }
        tr:nth-child(even) td { background: #fafbfc; }

        /* Code blocks */
        code {
            font-family: 'Courier New', monospace;
            font-size: 10px;
            background: #f3f4f6;
            padding: 1px 4px;
            border-radius: 3px;
        }
        pre {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 12px;
            margin: 12px 0;
            overflow-x: auto;
            font-size: 10px;
            page-break-inside: avoid;
        }
        pre code { background: none; padding: 0; }

        /* Mermaid diagrams */
        .mermaid {
            margin: 16px 0;
            text-align: center;
            page-break-inside: avoid;
        }
        .mermaid svg {
            max-width: 100%;
            height: auto;
        }

        /* Blockquotes */
        blockquote {
            border-left: 3px solid #2563eb;
            padding: 8px 16px;
            margin: 12px 0;
            background: #f8fafc;
            color: #555;
        }

        /* Horizontal rules */
        hr {
            border: none;
            border-top: 1px solid #e5e7eb;
            margin: 20px 0;
        }

        /* Print helpers */
        .page-break { page-break-before: always; }
        .no-break { page-break-inside: avoid; }
    </style>
</head>
<body>
    {{-- Document header --}}
    <div class="doc-header">
        <h1>
            @if($meta['id'])
                <span class="doc-id">{{ $meta['id'] }}</span>
            @endif
            {{ $meta['title'] ?? 'Untitled Document' }}
        </h1>

        <div class="doc-meta">
            <div>
                <span class="label">Status:</span>
                <span class="doc-status status-{{ $meta['status'] ?? 'draft' }}">{{ ucfirst(str_replace('_', ' ', $meta['status'] ?? 'draft')) }}</span>
            </div>
            <div>
                <span class="label">Version:</span> {{ $meta['version'] ?? '—' }}
            </div>
            <div>
                <span class="label">Author:</span> {{ $meta['author'] ?? '—' }}
            </div>
            <div>
                <span class="label">Effective date:</span> {{ $meta['effective_date'] ?? '—' }}
            </div>
            @if(!empty($meta['iso_refs']))
                <div>
                    <span class="label">ISO 13485:</span> {{ implode(', ', array_map(fn($r) => 'Clause ' . $r, $meta['iso_refs'])) }}
                </div>
            @endif
            @if(!empty($meta['mdr_refs']))
                <div>
                    <span class="label">EU MDR:</span> {{ implode(', ', $meta['mdr_refs']) }}
                </div>
            @endif
        </div>

        <div class="manufacturer">
            Therapeak B.V. — Confidential — Printed {{ date('Y-m-d') }}
        </div>
    </div>

    {{-- Document content --}}
    <div class="content">
        {!! $content !!}
    </div>
</body>
</html>
