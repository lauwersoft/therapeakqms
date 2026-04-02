<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $meta['id'] ?? '' }} — {{ $meta['title'] ?? 'Document' }}</title>
    <style>
        @page {
            size: A4;
            margin: 18mm 16mm 28mm 16mm;
        }

        @font-face {
            font-family: 'Ubuntu';
            src: url('https://fonts.gstatic.com/s/ubuntu/v20/4iCv6KVjbNBYlgo-sgEzQ.woff2') format('woff2');
            font-weight: 400;
            font-style: normal;
        }
        @font-face {
            font-family: 'Ubuntu';
            src: url('https://fonts.gstatic.com/s/ubuntu/v20/4iCv6KVjbNBYlgoOsgEzQ.woff2') format('woff2');
            font-weight: 700;
            font-style: normal;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Ubuntu', 'DejaVu Sans', Arial, Helvetica, sans-serif;
            font-size: 10.5px;
            line-height: 1.65;
            color: #1f2937;
            background: white;
            padding: 0;
            margin: 0;
        }

        /* ── Document header ── */
        .doc-header {
            border-bottom: 2px solid #2563eb;
            padding-bottom: 14px;
            margin-bottom: 28px;
        }
        .doc-header-title {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
            margin: 0 0 10px 0;
            line-height: 1.3;
        }
        .doc-id-badge {
            display: inline-block;
            background: #eff6ff;
            color: #1d4ed8;
            font-family: 'Courier New', monospace;
            font-weight: 700;
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 3px;
            margin-right: 6px;
        }
        .doc-meta-table {
            width: 100%;
            border: none;
            margin: 0;
            font-size: 9.5px;
        }
        .doc-meta-table td {
            border: none;
            padding: 2px 0;
            color: #6b7280;
            background: none;
            vertical-align: top;
            width: 50%;
        }
        .doc-meta-table .label {
            font-weight: 700;
            color: #374151;
        }
        .doc-status-badge {
            display: inline-block;
            font-size: 9px;
            font-weight: 700;
            padding: 1px 7px;
            border-radius: 3px;
            letter-spacing: 0.3px;
        }
        .status-approved { background: #dcfce7; color: #166534; }
        .status-draft { background: #f3f4f6; color: #6b7280; }
        .status-in_review { background: #fef3c7; color: #92400e; }
        .doc-footer-line {
            font-size: 8px;
            color: #9ca3af;
            margin-top: 10px;
            padding-top: 6px;
            border-top: 1px solid #e5e7eb;
        }

        /* ── Headings ── */
        h1 {
            font-size: 17px;
            font-weight: 700;
            color: #111827;
            margin: 30px 0 10px;
            padding-bottom: 6px;
            border-bottom: 1px solid #e5e7eb;
            page-break-after: avoid;
        }
        h2 {
            font-size: 14px;
            font-weight: 700;
            color: #1f2937;
            margin: 24px 0 8px;
            page-break-after: avoid;
        }
        h3 {
            font-size: 12px;
            font-weight: 700;
            color: #374151;
            margin: 18px 0 6px;
            page-break-after: avoid;
        }
        h4 {
            font-size: 11px;
            font-weight: 700;
            color: #4b5563;
            margin: 14px 0 4px;
            page-break-after: avoid;
        }

        /* ── Body text ── */
        p {
            margin: 6px 0;
            orphans: 3;
            widows: 3;
            text-align: left;
        }
        ul, ol {
            margin: 6px 0 6px 20px;
            padding: 0;
            text-align: left;
        }
        li {
            margin: 2px 0;
            text-align: left;
        }
        .content {
            text-align: left;
        }
        strong { font-weight: 700; color: #111827; }
        em { font-style: italic; }
        a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
        }

        /* ── Tables ── */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
            font-size: 8.5px;
            line-height: 1.4;
        }
        th {
            background: #f1f5f9;
            font-weight: 700;
            color: #334155;
            text-align: left;
            padding: 3px 4px;
            border: 1px solid #cbd5e1;
            font-size: 8px;
        }
        td {
            padding: 3px 4px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
            color: #334155;
        }

        /* ── Code ── */
        code {
            font-family: 'Courier New', monospace;
            font-size: 9.5px;
            background: #f1f5f9;
            padding: 1px 3px;
            border-radius: 2px;
            color: #be185d;
        }
        pre {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 10px 12px;
            margin: 10px 0;
            font-size: 9px;
            line-height: 1.5;
            page-break-inside: avoid;
            overflow: hidden;
        }
        pre code {
            background: none;
            padding: 0;
            color: #334155;
        }

        /* ── Blockquotes ── */
        blockquote {
            border-left: 3px solid #3b82f6;
            padding: 6px 14px;
            margin: 10px 0;
            background: #f8fafc;
            color: #475569;
            font-style: italic;
        }

        /* ── Horizontal rules ── */
        hr {
            border: none;
            border-top: 1px solid #e2e8f0;
            margin: 18px 0;
        }

        /* ── Page break helpers ── */
        .page-break { page-break-before: always; }
        .no-break { page-break-inside: avoid; }

        /* ── Page footer (repeats on every page) ── */
        .page-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            font-size: 7.5px;
            color: #9ca3af;
            padding: 6px 0;
            border-top: 1px solid #e5e7eb;
        }
        .page-footer .doc-ref {
            float: left;
        }
        .page-footer .confidential {
            float: right;
        }


    </style>
</head>
<body>
    {{-- Footer (repeats on every page via position:fixed) --}}
    <div class="page-footer">
        <span class="doc-ref">{{ $meta['id'] ?? '' }} — {{ $meta['title'] ?? 'Document' }} — v{{ $meta['version'] ?? '1.0' }}</span>
        <span class="confidential">Therapeak B.V. — Confidential</span>
    </div>

    {{-- Document header (first page) --}}
    <div class="doc-header">
        <div class="doc-header-title">
            @if($meta['id'])
                <span class="doc-id-badge">{{ $meta['id'] }}</span>
            @endif
            {{ $meta['title'] ?? 'Untitled Document' }}
        </div>

        <table class="doc-meta-table">
            <tr>
                <td>
                    <span class="label">Status:</span>
                    <span class="doc-status-badge status-{{ $meta['status'] ?? 'draft' }}">{{ strtoupper(str_replace('_', ' ', $meta['status'] ?? 'draft')) }}</span>
                </td>
                <td>
                    <span class="label">Version:</span> {{ $meta['version'] ?? '—' }}
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Author:</span> {{ $meta['author'] ?? '—' }}
                </td>
                <td>
                    <span class="label">Effective date:</span> {{ $meta['effective_date'] ?? '—' }}
                </td>
            </tr>
            @if(!empty($meta['iso_refs']) || !empty($meta['mdr_refs']))
                <tr>
                    @if(!empty($meta['iso_refs']))
                        <td>
                            <span class="label">ISO 13485:</span> {{ implode(', ', array_map(fn($r) => 'Clause ' . $r, $meta['iso_refs'])) }}
                        </td>
                    @else
                        <td></td>
                    @endif
                    @if(!empty($meta['mdr_refs']))
                        <td>
                            <span class="label">EU MDR:</span> {{ implode(', ', $meta['mdr_refs']) }}
                        </td>
                    @else
                        <td></td>
                    @endif
                </tr>
            @endif
        </table>

        <div class="doc-footer-line">
            Therapeak B.V. — {{ $meta['id'] ?? '' }} — Printed {{ date('Y-m-d') }}
        </div>
    </div>

    {{-- Document content --}}
    <div class="content">
        {!! $content !!}
    </div>
</body>
</html>
