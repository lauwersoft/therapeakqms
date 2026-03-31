<!DOCTYPE html>
<html>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; color: #374151; line-height: 1.6; max-width: 600px; margin: 0 auto; padding: 20px;">
    <p style="color: #6b7280; font-size: 12px; margin-bottom: 20px;">Therapeak QMS</p>

    <p>Hi {{ $user->name }},</p>

    <p><strong>{{ $publisherName }}</strong> published {{ count($changedFiles) }} {{ count($changedFiles) === 1 ? 'document' : 'documents' }}:</p>

    <table style="width: 100%; border-collapse: collapse; margin: 16px 0; font-size: 14px;">
        @foreach($changedFiles as $file)
            <tr style="border-bottom: 1px solid #f3f4f6;">
                <td style="padding: 6px 0;">
                    @if($file['status'] === 'added')
                        <span style="color: #16a34a; font-size: 12px;">created</span>
                    @elseif($file['status'] === 'modified')
                        <span style="color: #2563eb; font-size: 12px;">updated</span>
                    @elseif($file['status'] === 'deleted')
                        <span style="color: #dc2626; font-size: 12px;">removed</span>
                    @else
                        <span style="color: #6b7280; font-size: 12px;">{{ $file['status'] }}</span>
                    @endif
                </td>
                <td style="padding: 6px 8px; font-family: monospace; font-size: 13px; color: #6b7280;">
                    {{ $file['doc_id'] ?? '' }}
                </td>
                <td style="padding: 6px 0; color: #374151;">
                    {{ $file['doc_title'] ?? $file['path'] }}
                </td>
            </tr>
        @endforeach
    </table>

    <p><a href="{{ $qmsUrl }}" style="display: inline-block; padding: 8px 20px; background: #2563eb; color: #fff; text-decoration: none; border-radius: 6px; font-size: 14px;">Open QMS</a></p>

    <p style="color: #9ca3af; font-size: 12px; margin-top: 30px;">You received this because publication notifications are enabled for your account.</p>
</body>
</html>
