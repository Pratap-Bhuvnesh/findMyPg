<!DOCTYPE html>

<html>
<head>
    <meta charset="UTF-8">
    <title>Activate Your PGLocator Account</title>
</head>
<body style="margin:0;padding:0;background:#f5f7fb;font-family:Arial,Helvetica,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#f5f7fb;padding:30px 15px;">
    <tr>
        <td align="center">

```
        <table width="650" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.08);">

            <!-- Header -->
            <tr>
                <td align="center" style="background:linear-gradient(135deg,#2563eb,#f97316);padding:40px 20px;">                        
                    <img
                        src="{{ asset('logo.png') }}"
                        alt="PGLocator"
                        style="height:70px;margin-bottom:15px;"
                    >

                    <h1 style="margin:0;color:#ffffff;font-size:32px;font-weight:700;">
                        Welcome to PGLocator
                    </h1>

                    <p style="margin-top:10px;color:#e8f0ff;font-size:16px;">
                        Find your perfect student accommodation
                    </p>

                </td>
            </tr>

            <!-- Content -->
            <tr>
                <td style="padding:50px 40px;">

                    <h2 style="color:#111827;margin-bottom:20px;">
                        Hello {{ $user->name }},
                    </h2>

                    <p style="font-size:16px;line-height:28px;color:#4b5563;">
                        Thank you for joining <strong>PGLocator</strong>.
                        Your account has been created successfully.
                    </p>

                    <p style="font-size:16px;line-height:28px;color:#4b5563;">
                        To start exploring verified PG accommodations,
                        please activate your account by clicking the button below.
                    </p>

                    <div style="text-align:center;margin:40px 0;">
                        <a href="{{ $url }}"
                           style="
                            display:inline-block;
                            background:#2563eb;
                            color:#ffffff;
                            text-decoration:none;
                            padding:16px 36px;
                            border-radius:10px;
                            font-size:16px;
                            font-weight:700;
                           ">
                            Activate Account
                        </a>
                    </div>

                    <p style="font-size:14px;color:#6b7280;">
                        If the button doesn't work, copy and paste the following link into your browser:
                    </p>

                    <p style="word-break:break-all;color:#2563eb;font-size:14px;">
                        {{ $url }}
                    </p>

                </td>
            </tr>

            <!-- Features -->
            <tr>
                <td style="padding:0 40px 40px;">

                    <table width="100%">
                        <tr>

                            <td align="center" style="padding:15px;">
                                <div style="font-size:32px;">🏠</div>
                                <p style="font-weight:600;color:#111827;">
                                    Verified PGs
                                </p>
                            </td>

                            <td align="center" style="padding:15px;">
                                <div style="font-size:32px;">⭐</div>
                                <p style="font-weight:600;color:#111827;">
                                    Genuine Reviews
                                </p>
                            </td>

                            <td align="center" style="padding:15px;">
                                <div style="font-size:32px;">🎓</div>
                                <p style="font-weight:600;color:#111827;">
                                    Near Universities
                                </p>
                            </td>

                        </tr>
                    </table>

                </td>
            </tr>

            <!-- Footer -->
            <tr>
                <td style="background:#111827;padding:30px;text-align:center;">

                    <h3 style="color:#ffffff;margin-top:0;">
                        PGLocator
                    </h3>

                    <p style="color:#9ca3af;font-size:14px;line-height:24px;">
                        Helping students find safe and affordable accommodation.
                    </p>

                    <p style="color:#6b7280;font-size:12px;margin-top:20px;">
                        If you did not create this account, you can safely ignore this email.
                    </p>

                    <p style="color:#6b7280;font-size:12px;">
                        © {{ date('Y') }} PGLocator. All Rights Reserved.
                    </p>

                </td>
            </tr>

        </table>

    </td>
</tr>
```

</table>

</body>
</html>
