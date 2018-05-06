<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <title>User Notification</title>
    </head>
    <body>
        <h1>Thank you for creating a quote, {{ $name }}!</h1>
        <p>
            Please, register here: <a href="{{ route('mail_callback', ['author_name' => $name]) }}">Register</a>
        </p>
    </body>
</html>