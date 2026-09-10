<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcement</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f8fafc; margin: 0; padding: 24px; color: #0f172a;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; padding: 32px; box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);">
        <h2 style="margin-top: 0; color: #0f172a;">{{ $announcement->title }}</h2>

        <p style="margin: 0 0 16px; color: #475569; font-size: 14px; text-transform: uppercase; letter-spacing: 0.08em;">
            {{ ucfirst($announcement->type ?? 'news') }} · {{ ucfirst($announcement->urgency ?? 'medium') }}
        </p>

        <p style="line-height: 1.7; color: #334155; font-size: 16px;">
            {!! nl2br(e($announcement->body)) !!}
        </p>

        <p style="margin-top: 24px;">
            <a href="{{ route('announcements.index') }}" style="display: inline-block; background: #7c3aed; color: #ffffff; text-decoration: none; padding: 12px 20px; border-radius: 8px; font-weight: 600;">
                View Announcement
            </a>
        </p>
    </div>
</body>
</html>
