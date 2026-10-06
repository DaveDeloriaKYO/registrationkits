<?php require_once __DIR__ . '/auth.php'; ?>
  <!doctype html>
  <html lang="en">

  <head>
    
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NID</title>
    <link rel="shortcut icon" type="image/png" href="assets/images/logos/psalogo.png" />
    <link rel="stylesheet" href="assets/css/styles.min.css" />
  </head>

   <?php include 'bootstrap.php'?>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Link to SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.24/dist/sweetalert2.min.css">

    <!-- Optional: Bootstrap for modal styling (or use your own CSS) -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <!-- FontAwesome for the icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  </head>

  <body>
   
      <!--  Sidebar End -->
      <!--  Main wrapper -->
      <div class="container">
        <!--  Header Start -->
       
        <!--  Header End -->
          

        <div class="container-fluid">
        <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f0f4f9;
      text-align: center;
    }

    .header {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 20px;
      margin-top: 20px;
      margin-bottom: 40px;
    }

    .header img {
      width: 80px;
      height: 80px;
    }

    .header h1 {
      font-size: 28px;
      color: #333;
      text-transform: uppercase;
      font-weight: bold;
      letter-spacing: 1px;
    }

    .buttons-container {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 20px;
      margin-top: 50px;
      flex-wrap: wrap; /* Allows buttons to wrap on smaller screens */
    }

    .button {
      position: relative;
      width: 240px;
      height: 120px;
      background: linear-gradient(135deg, #4682B4, #5a9bd9);
      color: white;
      font-size: 16px;
      font-weight: bold;
      text-align: center;
      text-decoration: none;
      border-radius: 15px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
      transition: transform 0.3s ease, box-shadow 0.3s ease, background 0.3s ease;
      overflow: hidden;
      cursor: pointer;
    }

    .button i {
      font-size: 40px;
      margin-bottom: 10px;
    }

    .button:hover {
      transform: translateY(-10px); /* Moves button upward */
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.25); /* Adds a stronger shadow */
      background: linear-gradient(135deg, #315f86, #4682B4); /* Darkens background on hover */
    }

    .button::before {
      content: "";
      position: absolute;
      top: 0;
      left: -100%;
      width: 200%;
      height: 100%;
      background: rgba(255, 255, 255, 0.3);
      transform: skewX(-45deg);
      transition: left 0.5s ease;
    }

    .button:hover::before {
      left: 100%; /* Moves the light overlay effect */
    }

    @media (max-width: 768px) {
      .buttons-container {
        flex-direction: column; /* Stacks buttons vertically on smaller screens */
      }

      .button {
        width: 90%; /* Adjust button width for smaller screens */
      }
    }
  </style>
</head>
<body>
  <div style="display:flex; justify-content:flex-end; gap:16px; padding:10px 20px; font-size:14px; color:#555;">
    <span>Logged in as <strong><?= htmlspecialchars($_SESSION['username'] ?? '') ?></strong></span>
    <a href="change_password.php">Change Password</a>
    <a href="logout.php">Logout</a>
  </div>
  <div class="header">
  <img src="assets/images/logos/psalogo.png" alt="PSA Logo">
    <h1>NATIONAL ID DATABASE MANAGEMENT SYSTEM</h1>
    <img src="assets/images/logos/NIDlogo.png" alt="PSA Logo">
  </div>

  <div class="buttons-container">
  <a href="" class="button" id="">
        <i class="fas fa-dashboard"></i> DASHBOARD
    </a>

  <a href="personnel_information.php" class="button">
        <i class="fas fa-users"></i> NID Field Personnel
    </a>
    <a href="registration_kits.php" class="button">
      <i class="fas fa-laptop"></i>
      Registration Kit
    </a>

    <a href="nid_inventory.php" class="button">
      <i class="fas fa-gear"></i>
      NID Inventory
    </a>
    
    
    <a href="ismd_active_regkits.php" class="button">
      <i class="fas fa-book"></i>
      ISMD Recorded Active Registration Kits
    </a>
    <a href="monitoring_daily_packets.php" class="button">
      <i class="fas fa-chart-bar"></i>
      Daily Monitoring of Data Packets 2025
    </a>
    <a href="registration_kit_monitoring.php" class="button">
      <i class="fas fa-chart-bar"></i>
      Registration Kit Monitoring 2025
    </a>
    <a href="uploadedpackets.php" class="button">
      <i class="fas fa-upload"></i>
      Uploaded Packets 2025
    </a>

    <a href="borrowers.php" class="button">
      <i class="fas fa-desktop"></i>
      Borrowed Laptops
    </a>

    <a href="rts.php" class="button">
      <i class="fas fa-envelope"></i>
      NID Return to Sender
    </a>
  </div>

      </div>

      
    </div>

    <?php include 'script.php'; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.24/dist/sweetalert2.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>

    <script src="assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/sidebarmenu.js"></script>
    <script src="assets/js/app.min.js"></script>
    <script src="assets/libs/apexcharts/dist/apexcharts.min.js"></script>
    <script src="assets/libs/simplebar/dist/simplebar.js"></script>
    <script src="assets/js/dashboard.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="assets/libs/jquery/dist/jquery.min.js"></script>



</body>


  </html>