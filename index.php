<!DOCTYPE html>
<html lang="en">
<head>
    <title>Blood Bank Management System</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        /* MODERN VARIABLES */
        :root {
            --primary: #e63946;
            --secondary: #1d3557;
            --bg-gradient: linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%);
            --glass: rgba(255, 255, 255, 0.95);
            --shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        body { 
            font-family: 'Poppins', sans-serif; 
            background: var(--bg-gradient); 
            overflow-x: hidden; 
            color: #333;
        }
        
        /* GLASS NAVBAR */
        .navbar { 
            background: rgba(255, 255, 255, 0.9) !important; 
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.05); 
            padding: 15px 0; 
        }
        .navbar-brand { color: var(--primary) !important; font-weight: 800; font-size: 1.5rem; letter-spacing: 1px; text-transform: uppercase; }
        .nav-link { color: #555 !important; font-weight: 600; margin-left: 20px; transition: 0.3s; position: relative; }
        .nav-link::after { content: ''; position: absolute; width: 0; height: 2px; bottom: 0; left: 0; background-color: var(--primary); transition: 0.3s; }
        .nav-link:hover { color: var(--primary) !important; }
        .nav-link:hover::after { width: 100%; }
        
        .btn-nav { 
            background: var(--primary); color: white !important; 
            padding: 10px 30px; border-radius: 50px; 
            box-shadow: 0 5px 15px rgba(230, 57, 70, 0.3); transition: 0.3s; 
        }
        .btn-nav:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(230, 57, 70, 0.5); }

        /* HERO SECTION */
        .hero-section {
            position: relative;
            height: 90vh;
            display: flex; align-items: center; justify-content: center;
            text-align: center; color: white;
            background: linear-gradient(135deg, rgba(230, 57, 70, 0.95), rgba(29, 53, 87, 0.9)), url('https://images.unsplash.com/photo-1615461168470-30e24ec14e5b?auto=format&fit=crop&q=80');
            background-size: cover; background-attachment: fixed;
            clip-path: ellipse(150% 100% at 50% 0%);
        }
        
        .hero-content { z-index: 2; max-width: 800px; padding: 20px; }
        .hero-title { font-size: 4rem; font-weight: 800; margin-bottom: 20px; animation: slideDown 1s ease-out; }
        .hero-text { font-size: 1.3rem; margin-bottom: 40px; opacity: 0.9; animation: slideUp 1s ease-out 0.3s backwards; }
        
        /* ANIMATIONS */
        @keyframes slideDown { from { opacity: 0; transform: translateY(-50px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slideUp { from { opacity: 0; transform: translateY(50px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes float { 0% { transform: translateY(0px); } 50% { transform: translateY(-10px); } 100% { transform: translateY(0px); } }

        /* CARDS SECTION */
        .cards-container { margin-top: -80px; position: relative; z-index: 5; padding-bottom: 80px; }
        .feature-card {
            background: white; padding: 40px 30px; border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1); transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-align: center; height: 100%; border: 1px solid rgba(0,0,0,0.05);
        }
        .feature-card:hover { transform: translateY(-15px); border-bottom: 5px solid var(--primary); }
        .card-icon { font-size: 3.5rem; margin-bottom: 20px; display: inline-block; animation: float 3s ease-in-out infinite; }
        
        .btn-outline-custom {
            border: 2px solid var(--primary); color: var(--primary); font-weight: 600; padding: 8px 25px; border-radius: 30px; transition: 0.3s;
        }
        .btn-outline-custom:hover { background: var(--primary); color: white; }

        /* FOOTER */
        .footer { background: var(--secondary); color: white; padding: 30px 0; text-align: center; font-size: 0.9rem; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">🩸 Blood Bank</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon" style="background-color: #ddd; border-radius:3px;"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="patient/login.php">Patient</a></li>
                    <li class="nav-item"><a class="nav-link" href="donor/login.php">Donor</a></li>
                    <li class="nav-item"><a class="nav-link btn-nav" href="admin/login.php">Admin Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title">Give Blood, Give Life</h1>
            <p class="hero-text">Be the reason for someone's heartbeat. A single drop can start a wave of hope. Join our mission today.</p>
            <a href="donor/register.php" class="btn btn-light btn-lg font-weight-bold text-danger px-5 py-3 shadow" style="border-radius: 50px;">Start Donating</a>
        </div>
    </header>

    <section class="container cards-container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="feature-card">
                    <div class="card-icon">🏥</div>
                    <h4>For Patients</h4>
                    <p class="text-muted">Urgent requirement? Register now to request blood from our secure inventory.</p>
                    <a href="patient/login.php" class="btn btn-outline-custom">Request Blood</a>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-card">
                    <div class="card-icon">🩸</div>
                    <h4>For Donors</h4>
                    <p class="text-muted">Be a hero. Register as a donor and manage your appointments and history.</p>
                    <a href="donor/login.php" class="btn btn-outline-custom">Donate Now</a>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-card">
                    <div class="card-icon">🔒</div>
                    <h4>Admin Portal</h4>
                    <p class="text-muted">Secure access for staff to manage stock, approve requests, and monitor data.</p>
                    <a href="admin/login.php" class="btn btn-outline-custom">Admin Login</a>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <p class="mb-0">&copy; 2026 Blood Bank Management System. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="includes/juqery_latest.js"></script>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>