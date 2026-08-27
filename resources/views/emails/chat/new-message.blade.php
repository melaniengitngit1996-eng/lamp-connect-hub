<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New message</title>
</head>

<body style="margin: 0; padding: 0; background-color: #f5f5f5; font-family: Arial, Helvetica, sans-serif;">

    <table
        width="100%"
        cellpadding="0"
        cellspacing="0"
        style="background-color: #f5f5f5; padding: 40px 20px;"
    >
        <tr>
            <td align="center">

                <table
                    width="100%"
                    cellpadding="0"
                    cellspacing="0"
                    style="max-width: 560px; background: #ffffff; border-radius: 10px; overflow: hidden;"
                >

                    <!-- Header -->
                    <tr>
                        <td style="padding: 24px; border-bottom: 1px solid #e5e7eb;">
                            <div style="font-size: 20px; font-weight: 700;">
                                LAMP Church Connect
                            </div>

                            <div style="margin-top: 4px; font-size: 13px; color: #6b7280;">
                                New chat message
                            </div>
                        </td>
                    </tr>


                    <!-- Content -->
                    <tr>
                        <td style="padding: 28px 24px;">

                            <div style="font-size: 16px; font-weight: 600;">
                                {{ $sender->name }} sent you a message
                            </div>

                            <div style="margin-top: 6px; font-size: 13px; color: #6b7280;">
                                {{ $conversationName }}
                            </div>


                            <!-- Message -->
                            <div
                                style="
                                    margin-top: 20px;
                                    padding: 16px;
                                    background-color: #f3f4f6;
                                    border-radius: 8px;
                                    font-size: 14px;
                                    line-height: 1.6;
                                    color: #111827;
                                "
                            >
                                {{ $message->message }}
                            </div>


                            <!-- Button -->
                            <div style="margin-top: 24px;">
                                <a
                                    href="{{ url('/chat') }}"
                                    style="
                                        display: inline-block;
                                        padding: 10px 16px;
                                        background-color: #111827;
                                        color: #ffffff;
                                        text-decoration: none;
                                        border-radius: 6px;
                                        font-size: 14px;
                                        font-weight: 600;
                                    "
                                >
                                    View Message
                                </a>
                            </div>

                        </td>
                    </tr>


                    <!-- Footer -->
                    <tr>
                        <td
                            style="
                                padding: 20px 24px;
                                border-top: 1px solid #e5e7eb;
                                font-size: 12px;
                                line-height: 1.5;
                                color: #9ca3af;
                            "
                        >
                            You are receiving this email because chat email
                            notifications are enabled for your account.
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>