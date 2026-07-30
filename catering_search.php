 <!DOCTYPE html>
<html lang="en">
<head>
    <title>Search Catering</title>

    <style>
        body {
            font-family: sans-serif;
            background: #fff7ed;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
        }

        h2 { color: #076e93; }

        input {
            padding: 12px;
            width: 280px;
            border: 2px solid #088f62;
            border-radius: 5px;
            margin-top: 20px;
        }

        button {
            padding: 12px 25px;
            background: #f97316;
            color: white;
            border: none;
            border-radius: 5px;
            margin-top: 10px;
            cursor: pointer;
        }
    </style>
</head>

<body>

<div class="card">
    <h2>Search Event Menu</h2>

    <form action="search_result.php" method="GET">
        <input type="text" name="event" placeholder="Wedding / Birthday / Corporate" required>
        <br>
        <button type="submit">Search</button>
    </form>

    <br>
    <a href="index.php">← Back</a>
</div>

</body>
</html>