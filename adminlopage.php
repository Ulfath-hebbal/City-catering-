
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Access | City Catering System</title>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cloudflare.com">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family: 'Segoe UI', Arial, sans-serif; }

        body { 
            background: #f0f4f8; 
            min-height: 100vh; 
            display: flex; 
            flex-direction: column; 
        }

        /* Header with System Colors */
        header { 
            background: linear-gradient(to right, #088f62, #076e93);
            color: white; 
            text-align: center; 
            padding: 20px 0; 
            font-family: Forte, sans-serif; 
        }
        .logo { width: 70px; height: 70px; border-radius: 50%; border: 2px solid #38bdf8; margin-bottom: 10px; }

        /* Navbar */
        .navbar { background: linear-gradient(to right, #011811bf, #022e216f); display: flex; justify-content: center; }
        .navbar ul { list-style: none; display: flex; }
        .navbar ul li a { display: block; padding: 15px 25px; color: #cbd5e1; text-decoration: none; font-weight: bold; transition: 0.3s; }
        .navbar ul li a:hover { color: white; background: rgba(255,255,255,0.05); }

        /* Admin Login Container */
        .admin-container { flex: 1; display: flex; justify-content: center; align-items: center; padding: 20px; }
        
        .admin-card { 
            background: white; 
            width: 100%; 
            max-width: 400px; 
            padding: 40px; 
            border-radius: 12px; 
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border-top: 6px solid #076e93; /* Admin Blue Accent */
        }

        .admin-card h2 { color: #0f172a; text-align: center; margin-bottom: 8px; font-size: 24px; letter-spacing: 1px; }
        .admin-card p { color: #64748b; text-align: center; margin-bottom: 30px; font-size: 13px; font-weight: 500; }

        /* Input Styling */
        .input-group { position: relative; margin-bottom: 20px; }
        .input-group i { position: absolute; left: 15px; top: 14px; color: #076e93; font-size: 18px; }
        .input-group input { 
            width: 100%; 
            padding: 12px 12px 12px 45px; 
            border: 2px solid #e2e8f0; 
            border-radius: 8px; 
            outline: none; 
            transition: 0.3s; 
            font-size: 16px;
            background: #f8fafc;
        }
        .input-group input:focus { 
            border-color: #076e93; 
            background: white;
            box-shadow: 0 0 0 4px rgba(7, 110, 147, 0.1); 
        }

        /* Admin Login Button */
        .btn-admin { 
            background: linear-gradient(135deg, #076e93 0%, #054f6a 100%);
            color: white; 
            width: 100%; 
            padding: 14px; 
            border: none; 
            border-radius: 8px; 
            font-weight: bold; 
            font-size: 16px;
            cursor: pointer; 
            transition: 0.3s; 
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }
        .btn-admin:hover { 
            transform: translateY(-1px); 
            box-shadow: 0 10px 15px -3px rgba(7, 110, 147, 0.3);
        }

        /* Clear Button */
        .btn-reset { 
            background: white; 
            color: #64748b; 
            width: 100%; 
            padding: 10px; 
            border: 1px solid #e2e8f0; 
            border-radius: 8px; 
            font-size: 14px;
            cursor: pointer; 
            transition: 0.2s;
        }
        .btn-reset:hover { background: #f1f5f9; color: #0f172a; }

        /* Security Warning */
        .security-note { 
            margin-top: 25px; 
            text-align: center; 
            font-size: 11px; 
            color: #94a3b8; 
            text-transform: uppercase;
            letter-spacing: 1px;
        }
    </style>
    <script>
    function logchek()
    {
        n=document.login.uname.value;
        p=document.login.pass.value;
        if(n=="")
    {
        alert("plse enter name");
        document.login.uname.focus();
        return false;
    
    }
       else if(p=="")
    {
        alert("plse enter pass");
        document.login.pass.focus();
        return false;
    
    }
     else if(n=="akasha" && p=="ask")
    {
        alert("valid account");
        
    }
       else
    {
        alert("invalid user name and password");
        document.login.uname.value="";
        document.login.pass.value="";
        document.login.uname.focus();
        return false;
    
    }
    }
</script>
</head>
<body>

<header>
    <img src="hg.jpeg" class="logo" alt="Logo">
    <h1>City Catering Management</h1>
</header>

<nav class="navbar">
    <ul>
        <li><a href="home.php"><i class="fa-solid fa-house"></i> Home</a></li>
       
    </ul>
</nav>

<div class="admin-container">
    <div class="admin-card">
        <h2><i class="fa-solid fa-user-lock"></i> SYSTEM ACCESS</h2>
        <p>Authorized Personnel Only</p>

        <form method="post" name="login" action="adminf.html">
            <div class="input-group">
                <i class="fa-solid fa-user-shield"></i>
                <input type="text" name="uname" placeholder="Admin ID" required autocomplete="off">
            </div>

            <div class="input-group">
                <i class="fa-solid fa-key"></i>
                <input type="password" name="pass" placeholder="Secret Password" required>
            </div>
           <button type="submit" value="login" onclick="return logchek();"  class="btn-admin">System Login</button>
               
           </button>
            
            <button type="reset" class="btn-reset">Clear Credentials</button>
            
            <div class="security-note">
                <i class="fa-solid fa-shield-check"></i> Encrypted Access Point
            </div>
        </form>
    </div>
</div>

</body>
</html>
