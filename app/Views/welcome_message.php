<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Button Links</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f9;
            margin: 0;
        }

        .button-container {
            display: flex;
            gap: 20px;
        }

        .button {
            padding: 15px 30px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .button.signage {
            background-color: #28a745;
        }

        .button.signage:hover {
            background-color: #218838;
        }

        .button.display {
            background-color: #dc3545;
        }

        .button.display:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

    <div class="button-container">
        <a href="http://172.16.15.25/farmasi/public/antrian" target="_blank" class="button signage">Signage</a>
        <a href="http://172.16.15.25/farmasi/public/display" target="_blank" class="button display">Display</a>
    </div>

</body>
</html>