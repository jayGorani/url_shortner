<!doctype html>

<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>You're invited to {{ config('app.name') }}</title>

    </head>

    <body>

        <h1>You're invited to {{ config('app.name') }}</h1>
        <p>Hello {{ $invitation->name }},</p>

        <p>You have been invited to join <strong>{{ $invitation->company_name ?: $invitation->company?->name }}</strong>.</p>

        <p>Your assigned role is <strong>{{ ucfirst($invitation->role) }}</strong>.</p>

        <p><a href="{{ $acceptUrl }}">Accept invitation and create your account</a></p>

        <p>This invitation expires in seven days and can only be used once.</p>

        <p>If you were not expecting this invitation, you can safely ignore this email.</p>

        <p>— {{ config('app.name') }} Team</p>

    </body>

</html>
