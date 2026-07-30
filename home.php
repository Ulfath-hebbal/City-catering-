 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>City Catering Management | Premium Events</title>
    
    <!-- Google Fonts for a Premium Look -->
    <link href="https://googleapis.com" rel="stylesheet">
    
    <style>
        :root {
            --primary: #059669;
            --secondary: #0ea5e9;
            --accent: #f59e0b;
            --dark: #0f172a;
            --light: #f8fafc;
            --white: #ffffff;
            --gradient: linear-gradient(135deg, #059669 0%, #0ea5e9 100%);
            --glass: rgba(255, 255, 255, 0.85);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--light);
            color: #334155;
            font-family: 'Montserrat', sans-serif;
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* --- HEADER & LOGO --- */
        header {
            background: var(--gradient);
            padding: 50px 20px 10px 10px;
            text-align: center;
            position: relative;
            clip-path: ellipse(150% 100% at 50% 0%);
        }

        header h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2rem, 5vw, 3.8rem);
            color: white;
            margin-top: 25px;
            letter-spacing: -1px;
            text-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .logo {
            width: 120px;
            height: 120px;
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            border: 5px solid rgba(255, 255, 255, 0.3);
            background: white;
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            object-fit: cover;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }

        .logo:hover {
            border-radius: 50%;
            transform: rotate(360deg) scale(1.1);
        }

        /* --- MODERN NAVIGATION --- */
        .navbar {
            background: var(--glass);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            position: sticky;
            top: 0;
            z-index: 1000;
            height: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }

        .navbar ul {
            list-style: none;
            display: flex;
            gap: 15px;
        }

        .navbar ul li a {
            padding: 12px 28px;
            color: var(--dark);
            text-decoration: none;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .navbar ul li a:hover {
            background: var(--gradient);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3);
        }

        /* --- HERO SECTION --- */
        .hero {
            height: 50vh;
            background: linear-gradient(rgba(255,255,255,0.8), rgba(255,255,255,0.7)), 
                        url('https://unsplash.com');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 20px;
        }

        .hero h2 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.5rem, 8vw, 4.5rem);
            margin-bottom: 15px;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: fadeInUp 0.8s ease;
        }

        .hero p {
            font-size: 1.3rem;
            max-width: 700px;
            font-weight: 400;
            color: #475569;
            margin-bottom: 30px;
            animation: fadeInUp 1s ease;
        }

        /* --- INFO CARDS --- */
        .container {
            max-width: 1200px;
            margin: -80px auto 100px;
            padding: 0 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
        }

        .card {
            background: var(--white);
            padding: 60px 40px;
            border-radius: 30px;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0,0,0,0.05);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid rgba(255,255,255,1);
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 6px;
            background: var(--gradient);
        }

        .card:hover {
            transform: translateY(-15px);
            box-shadow: 0 30px 60px rgba(0,0,0,0.1);
        }

        .card h3 {
            font-family: 'Playfair Display', serif;
            color: var(--primary);
            margin-bottom: 20px;
            font-size: 1.8rem;
        }

        .card p {
            color: #64748b;
            font-size: 1.05rem;
        }

        /* --- FOOTER --- */
        footer {
            background: var(--dark);
            color: #e2e8f0;
            text-align: center;
            padding: 100px 20px 60px;
            clip-path: polygon(0 15%, 100% 0, 100% 100%, 0 100%);
        }

        footer p {
            font-size: 1.1rem;
            margin-bottom: 25px;
            opacity: 0.8;
        }

        footer a {
            color: var(--secondary);
            text-decoration: none;
            font-size: 0.9rem;
            transition: 0.3s;
            text-transform: uppercase;
            letter-spacing: 2px;
            padding: 10px 20px;
            border: 1px solid rgba(14, 165, 233, 0.3);
            border-radius: 8px;
        }

        footer a:hover {
            background: var(--secondary);
            color: white;
            border-color: var(--secondary);
        }

        /* --- ANIMATIONS --- */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* --- RESPONSIVE DESIGN --- */
        @media (max-width: 768px) {
            .navbar { height: auto; padding: 20px; }
            .navbar ul { flex-direction: column; width: 100%; align-items: center; gap: 5px; }
            .hero { height: auto; padding: 80px 20px; }
            .container { margin-top: 40px; }
            header h1 { font-size: 2.2rem; }
        }
    </style>
</head>
<body>

<header>
    <img src="hg.jpeg" class="logo" alt="City Catering Logo">
    <h1>City Catering Management</h1>
</header>

<nav class="navbar">
    <ul>
        <li><a href="home.php">Home</a></li>
        <li><a href="publicmain.php">Public</a></li>
        <li><a href="ownlog.php">Caterer</a></li>
        <li><a href="adminlopage.php">admin</a></li>
    </ul>
</nav>

<section class="hero">
    
    <h2>Delicious Moments, Delivered.</h2>
    <p>The most trusted platform for high-end wedding catering, corporate galas, and private celebrations.</p>
</section>

<div class="container">
    <div class="card">
        <h3>Premium Venues</h3>
        <p>Curated list of the most elegant locations in the city, perfectly suited for luxury occasions.</p>
    </div>
    <div class="card">
        <h3>Master Chefs</h3>
        <p>Our network features award-winning culinary experts ready to craft your custom menu.</p>
    </div>
    <div class="card">
        <h3>Seamless Booking</h3>
        <p>Intuitive management for both customers and business owners to track every detail.</p>
    </div>
</div>

<footer>
    <p>&copy; 2024 City Catering Management System. All Rights Reserved.</p>
    <a href="adminlopage.php">Admin Portal Access</a>
</footer>

</body>
</html>
