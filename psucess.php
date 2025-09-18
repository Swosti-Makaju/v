<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Success</title>
    <style>
        body {
            font-family: 'Inter', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .card {
            background: rgba(255, 255, 255, 0.05);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
            backdrop-filter: blur(10px);
            text-align: center;
        }

        .checkmark {
            font-size: 100px;
            color: #6c5ce7;
            display: block;
            margin: 0 auto;
            line-height: 200px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }

        h1 {
            color: #ffffff;
            font-size: 2em;
            margin: 20px 0;
        }

        p {
            font-size: 1.2em;
            margin-bottom: 30px;
        }

        .ba {
            display: flex;
            justify-content: center;
        }

        #back {
            background: linear-gradient(90deg, #6c5ce7, #a29bfe);
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
            border-radius: 8px;
            transition: all 0.3s ease;
            text-shadow: 0 0 3px rgba(0, 0, 0, 0.3);
        }

        #back:hover {
            background: linear-gradient(90deg, #5a4ed1, #8e83ff);
            transform: translateY(-2px);
        }

        #back a {
            color: white;
            text-decoration: none;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="card">
      <div style="border-radius: 200px; height: 200px; width: 200px; background: #F8FAF5; margin: 0 auto;">
        <i class="checkmark">✓</i>
      </div>
      <h1>Success</h1> 
      <p>We received your rental request;<br/> we'll be in touch shortly!</p>
      <div class="ba"><button id="back"><a href="vehiclesdetails.php">Search vehicles</a></button></div>
    </div>
</body>
</html>