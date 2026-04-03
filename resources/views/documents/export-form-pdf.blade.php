<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $meta['id'] ?? '' }} — {{ $meta['title'] ?? 'Form' }}</title>
    <style>
        @page {
            size: A4;
            margin: 18mm 16mm 12mm 16mm;

            @top-left { content: ""; }
            @top-center { content: ""; }
            @top-right { content: ""; }

            @bottom-left {
                content: "{{ $meta['id'] ?? '' }} \2014  {{ str_replace('"', '', $meta['title'] ?? 'Form') }} \2014  v{{ $meta['version'] ?? '1.0' }}";
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
        .doc-id-badge {
            display: inline-block;
            background: #eff6ff;
            color: #1d4ed8;
            font-family: monospace;
            font-weight: 700;
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 3px;
            margin-right: 6px;
        }
        .meta-line {
            font-size: 9.5px;
            color: #6b7280;
            margin: 2px 0;
        }
        .meta-line .label { font-weight: 700; color: #374151; }
        .footer-line {
            font-size: 8px;
            color: #9ca3af;
            margin-top: 10px;
            padding-top: 6px;
            border-top: 1px solid #e5e7eb;
        }

        .form-field {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px 14px;
            margin-bottom: 12px;
            page-break-inside: avoid;
        }
        .field-label {
            font-size: 12px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 4px;
        }
        .field-required {
            color: #ef4444;
            margin-left: 2px;
        }
        .field-type {
            font-size: 9px;
            color: #9ca3af;
            margin-bottom: 6px;
        }
        .field-input {
            border: 1px solid #d1d5db;
            border-radius: 4px;
            padding: 6px 8px;
            min-height: 28px;
            background: #fafbfc;
            font-size: 10px;
            color: #9ca3af;
        }
        .field-textarea {
            min-height: 60px;
        }
        .field-options {
            font-size: 10px;
            color: #6b7280;
        }
        .field-option {
            display: inline-block;
            margin-right: 12px;
            margin-bottom: 4px;
        }
        .field-option::before {
            content: "\25CB";
            margin-right: 4px;
            color: #d1d5db;
        }
        .field-checkbox::before {
            content: "\2610";
            margin-right: 4px;
            color: #d1d5db;
        }

        h2 {
            font-size: 14px;
            font-weight: 700;
            color: #1f2937;
            margin: 24px 0 12px;
            page-break-after: avoid;
        }
    </style>
</head>
<body>
    <div class="doc-header">
        <div class="doc-header-title">
            @if($meta['id'])
                <span class="doc-id-badge">{{ $meta['id'] }}</span>
            @endif
            {{ $meta['title'] ?? 'Untitled Form' }}
        </div>
        <div class="meta-line"><span class="label">Type:</span> Form Template</div>
        <div class="meta-line"><span class="label">Version:</span> {{ $meta['version'] ?? '1.0' }}</div>
        <div class="meta-line"><span class="label">Status:</span> {{ ucfirst($meta['status'] ?? 'draft') }}</div>
        @if($meta['author'])
            <div class="meta-line"><span class="label">Author:</span> {{ $meta['author'] }}</div>
        @endif
        <div class="footer-line">Therapeak B.V. — {{ $meta['id'] ?? '' }} — Printed {{ date('Y-m-d') }}</div>
    </div>

    <h2>Form Fields</h2>

    @foreach($schema['fields'] ?? [] as $field)
        <div class="form-field">
            <div class="field-label">
                {{ $field['label'] ?? 'Untitled Field' }}
                @if($field['required'] ?? false)
                    <span class="field-required">*</span>
                @endif
            </div>
            <div class="field-type">{{ ucfirst($field['type'] ?? 'text') }}</div>

            @if(($field['type'] ?? 'text') === 'select' && !empty($field['options']))
                <div class="field-options">
                    @foreach($field['options'] as $option)
                        <span class="field-option">{{ $option }}</span>
                    @endforeach
                </div>
            @elseif(($field['type'] ?? 'text') === 'checkbox')
                <div class="field-options">
                    @if(!empty($field['options']))
                        @foreach($field['options'] as $option)
                            <span class="field-checkbox">{{ $option }}</span>
                        @endforeach
                    @else
                        <span class="field-checkbox">{{ $field['label'] ?? '' }}</span>
                    @endif
                </div>
            @elseif(($field['type'] ?? 'text') === 'textarea')
                <div class="field-input field-textarea"></div>
            @else
                <div class="field-input"></div>
            @endif
        </div>
    @endforeach
</body>
</html>
