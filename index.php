<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_connect.php';

$status  = $_GET['status'] ?? '';
$message = htmlspecialchars($_GET['msg'] ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>STUDY WITH CIMON</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="manifest" href="/manifest.json">
  <meta name="theme-color" content="#4CAF50">
  <style>
    /* --- Base Style (Mobile First) --- */
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(180deg, #f5f6fa, #dce6dc);
      margin: 0;
      padding: 20px;
      color: #333;
    }

    h1 {
      text-align: center;
      color: white;
      background: linear-gradient(135deg, #1a7a3a, #0e9e8a);
      border-radius: 8px;
      padding: 12px;
      margin-bottom: 20px;
      font-size: 22px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .logo {
      display: block;
      margin: 0 auto 15px;
      width: 70%;
      max-width: 200px;
      height: auto;
    }

    /* Mobile View - Single Column */
    .container {
      display: flex;
      flex-direction: column;
      gap: 20px;
      align-items: stretch;
    }

    .card {
      background-color: white;
      border-radius: 14px;
      padding: 18px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      transition: transform 0.2s ease;
    }

    .card:hover {
      transform: translateY(-3px);
    }

    h3 {
      margin-top: 0;
      color: #222;
      text-align: center;
    }

    form {
      display: flex;
      flex-direction: column;
    }

    input, select {
      padding: 12px;
      margin: 8px 0 14px 0;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 15px;
    }

    button {
      background: linear-gradient(135deg, #1a7a3a, #0e9e8a);
      color: white;
      border: none;
      border-radius: 8px;
      padding: 12px;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s;
    }

    button:hover {
      background: linear-gradient(135deg, #155f2d, #0b7d6e);
    }

    .bottom-button {
      display: flex;
      justify-content: center;
      margin-top: 30px;
    }

    .dashboard-card {
      display: block;
      width: 100%;
      max-width: 320px;
      background: linear-gradient(135deg, #1a7a3a, #0e9e8a);
      color: white;
      text-align: center;
      border-radius: 16px;
      padding: 30px 20px;
      text-decoration: none;
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
      transition: transform 0.3s ease;
    }

    .dashboard-card:hover {
      transform: translateY(-5px);
    }

    .icon {
      font-size: 45px;
      margin-bottom: 6px;
    }

    /* Flash messages */
    .flash {
      padding: 12px 18px;
      border-radius: 8px;
      margin-bottom: 20px;
      font-size: 15px;
      text-align: center;
      font-weight: 500;
    }

    .flash.success {
      background: #d4edda;
      color: #1a5e2a;
      border: 1px solid #a8d5b5;
    }

    .flash.error {
      background: #fde8e8;
      color: #7b1c1c;
      border: 1px solid #f5b8b8;
    }

    /* --- Desktop View (≥768px) --- */
    @media screen and (min-width: 768px) {
      body {
        padding: 40px 60px;
      }

      h1 {
        font-size: 28px;
        margin-bottom: 40px;
      }

      .logo {
        width: 200px;
        margin-bottom: 25px;
      }

      .container {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 30px;
        align-items: start;
      }

      .card {
        padding: 24px;
      }

      .bottom-button {
        grid-column: span 2;
        margin-top: 40px;
      }

      .dashboard-card {
        width: 360px;
      }
    }
  </style>
</head>
<body>

  <img src="swc.png" alt="swc Logo" class="logo"/>
  <h1>DUES SYSTEM</h1>

  <?php if ($status === 'success' && $message): ?>
    <div class="flash success">✅ <?= $message ?></div>
  <?php elseif ($status === 'error' && $message): ?>
    <div class="flash error">❌ <?= $message ?></div>
  <?php endif; ?>

  <div class="container">
    <div class="card">
      <h3>Add New Member</h3>
      <form action="add_member.php" method="POST">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="text" name="phone" placeholder="Phone Number (e.g. 23354XXXXXXX)" required>
        <button type="submit">Add Member</button>
      </form>
    </div>

    <div class="card">
      <h3>Record a Member Payment</h3>
      <form action="add_payment_process.php" method="POST">
        <label for="fgci">Select Member</label>
        <select name="fgci_id" id="fgci" required>
          <option value="">-- Select Member --</option>
          <?php
          $result = $conn->query("SELECT * FROM dues ORDER BY name ASC");
          while($row = $result->fetch_assoc()) {
            echo "<option value='{$row['id']}'>{$row['name']} ({$row['phone']})</option>";
          }
          ?>
        </select>

        <label for="amount">Enter Amount (GHS)</label>
        <input type="number" step="0.01" name="amount" id="amount" placeholder="Enter Amount" required>

        <button type="submit">Add Payment</button>
      </form>

      <div class="bottom-button">
        <a href="payment_list.php" class="dashboard-card">
          <div class="icon">📋</div>
          <h3>View Payments</h3>
        </a>
      </div>
    </div>
  </div>

  <script>
    if ("serviceWorker" in navigator) {
      navigator.serviceWorker.register("/service-worker.js")
        .then(() => console.log("SW registered"));
    }
  </script>

</body>
</html>
