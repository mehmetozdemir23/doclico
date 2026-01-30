<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #000;
            padding: 1.5cm 2cm;
        }

        .header {
            margin-bottom: 1cm;
        }

        .sender {
            margin-bottom: 1cm;
        }

        .recipient {
            margin-bottom: 0;
        }

        .date-place {
            text-align: right;
            margin-bottom: 1cm;
        }

        .subject {
            font-weight: bold;
            margin-bottom: 0.8cm;
        }

        .greeting {
            margin-bottom: 0.5cm;
        }

        .content {
            text-align: justify;
            margin-bottom: 0.5cm;
        }

        .signature {
            margin-top: 1.5cm;
            text-align: right;
        }
    </style>
</head>
<body>
    @yield('content')
</body>
</html>
