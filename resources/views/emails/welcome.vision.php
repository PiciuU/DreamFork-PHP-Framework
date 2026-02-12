<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to DreamFork</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f3f4f6; margin: 0; padding: 0; line-height: 1.6; color: #374151; }
        .wrapper { width: 100%; table-layout: fixed; background-color: #f3f4f6; padding: 40px 0px 40px 0px; }
        .content { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); }
        .header { background-color: #4f46e5; padding: 30px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; font-weight: 700; }
        .body { padding: 30px; }
        .btn { display: inline-block; background-color: #4f46e5; color: #ffffff !important; text-decoration: none; padding: 12px 24px; border-radius: 6px; font-weight: 600; margin-top: 20px; }
        .footer { text-align: center; padding: 20px; color: #9ca3af; font-size: 12px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="content">
            <div class="header">
                <h1>DreamFork Framework</h1>
            </div>

            <div class="body">
                <h2>Hello, {{ $name }}!</h2>

                <p>Congratulations! You have successfully configured the mail system in your new application.</p>

                <p>This is a default example email to demonstrate how <strong>Mailables</strong> and <strong>Views</strong> work together in DreamFork.</p>

                <p>You can find this class in <code>app/Mail/WelcomeMail.php</code> and modify this template in <code>resources/views/emails/welcome.vision.php</code>.</p>

                <div style="text-align: center;">
                    <a href="{{ env('APP_URL') }}" class="btn" style="color: #ffffff !important; text-decoration: none;">Visit Your App</a>
                </div>
            </div>

            <div class="footer">
                &copy; {{ date('Y') }} DreamFork Framework. All rights reserved.<br>
                Sent via generic SMTP driver.
            </div>
        </div>
    </div>
</body>
</html>