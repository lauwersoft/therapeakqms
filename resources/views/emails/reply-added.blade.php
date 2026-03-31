<!DOCTYPE html>
<html>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; color: #374151; line-height: 1.6; max-width: 600px; margin: 0 auto; padding: 20px;">
    <p style="color: #6b7280; font-size: 12px; margin-bottom: 20px;">Therapeak QMS</p>

    <p>Hi {{ $user->name }},</p>

    <p><strong>{{ $replierName }}</strong> replied to a comment on <strong>{{ $docId }} — {{ $docTitle }}</strong>:</p>

    <blockquote style="border-left: 3px solid #3b82f6; padding: 8px 16px; margin: 16px 0; color: #4b5563; background: #f9fafb;">
        {{ Str::limit($replyContent, 300) }}
    </blockquote>

    <p><a href="{{ $docUrl }}" style="display: inline-block; padding: 8px 20px; background: #2563eb; color: #fff; text-decoration: none; border-radius: 6px; font-size: 14px;">View Reply</a></p>

    <p style="color: #9ca3af; font-size: 12px; margin-top: 30px;">You received this because comment notifications are enabled for your account.</p>
</body>
</html>
