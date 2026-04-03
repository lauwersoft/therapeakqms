<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $record['id'] ?? '' }} — {{ $record['title'] ?? 'Record' }}</title>
    <style>
        @page {
            size: A4;
            margin: 18mm 16mm 12mm 16mm;

            @top-left { content: ""; }
            @top-center { content: ""; }
            @top-right { content: ""; }

            @bottom-left {
                content: "{{ $record['id'] ?? '' }} \2014  {{ str_replace('"', '', $record['title'] ?? 'Record') }}";
                font-size: 7.5px;
                color: #9ca3af;
                font-family: Arial, sans-serif;
                vertical-align: top;
                border-top: 0.5pt solid #d1d5db;
                padding-top: 7px;
            }
            @bottom-center {
                content: "Page " counter(page);
                font-size: 7.5px;
                color: #9ca3af;
                font-family: Arial, sans-serif;
                border-top: 0.5pt solid #d1d5db;
                vertical-align: top;
                padding-top: 7px;
            }
            @bottom-right {
                content: "Therapeak B.V. \2014  Confidential";
                font-size: 7.5px;
                color: #9ca3af;
                font-family: Arial, sans-serif;
                vertical-align: top;
                border-top: 0.5pt solid #d1d5db;
                padding-top: 7px;
                text-align: right;
            }
        }

        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.6;
            color: #1f2937;
            margin: 0;
            padding: 0;
        }

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
        }
        .doc-badges { margin-bottom: 8px; }
        .doc-badge {
            display: inline-block;
            font-size: 9px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 3px;
            margin-right: 4px;
        }
        .badge-id { background: #fdf2f8; color: #9d174d; font-family: 'Courier New', monospace; }
        .badge-form { background: #eff6ff; color: #1d4ed8; font-family: 'Courier New', monospace; }
        .badge-submitted { background: #dbeafe; color: #1e40af; }

        .meta-table {
            width: 100%;
            border: none;
            margin: 0;
            font-size: 9.5px;
        }
        .meta-table td {
            border: none;
            padding: 2px 0;
            color: #6b7280;
            background: none;
            vertical-align: top;
            width: 50%;
        }
        .meta-table .label { font-weight: 700; color: #374151; }

        .footer-line {
            font-size: 8px;
            color: #9ca3af;
            margin-top: 10px;
            padding-top: 6px;
            border-top: 1px solid #e5e7eb;
        }

        .field {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 14px;
            margin-bottom: 10px;
            page-break-inside: avoid;
        }
        .field-label {
            font-size: 10px;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 4px;
        }
        .field-value {
            font-size: 11px;
            color: #1f2937;
            white-space: pre-line;
        }
        .field-value-empty {
            color: #d1d5db;
        }
        .field-value-yes {
            color: #059669;
            font-weight: 600;
        }
        .field-value-no {
            color: #9ca3af;
        }
    </style>
</head>
<body>
    <div class="doc-header">
        <div class="doc-header-title">{{ $record['title'] ?? 'Record' }}</div>

        <div class="doc-badges">
            @if($record['id'] ?? null)
                <span class="doc-badge badge-id">{{ $record['id'] }}</span>
            @endif
            @if($record['form_id'] ?? null)
                <span class="doc-badge badge-form">{{ $record['form_id'] }}</span>
            @endif
            <span class="doc-badge badge-submitted">SUBMITTED</span>
        </div>

        <table class="meta-table">
            <tr>
                <td>
                    <span class="label">Author:</span> {{ $record['author'] ?? '—' }}
                </td>
                <td>
                    <span class="label">Submitted:</span> {{ $record['submitted_at'] ?? '—' }}
                </td>
            </tr>
            @if($record['form_title'] ?? null)
                <tr>
                    <td colspan="2">
                        <span class="label">Form:</span> {{ $record['form_title'] }}
                    </td>
                </tr>
            @endif
        </table>

        <div class="footer-line">Therapeak B.V. — {{ $record['id'] ?? '' }} — Printed {{ date('Y-m-d') }}</div>
    </div>

    @foreach(($record['data'] ?? []) as $label => $value)
        <div class="field">
            <div class="field-label">{{ $label }}</div>
            <div class="field-value {{ empty($value) ? 'field-value-empty' : '' }} {{ $value === 'Yes' ? 'field-value-yes' : '' }} {{ $value === 'No' ? 'field-value-no' : '' }}">
                @if(empty($value))
                    —
                @else
                    {{ $value }}
                @endif
            </div>
        </div>
    @endforeach
</body>
</html>
