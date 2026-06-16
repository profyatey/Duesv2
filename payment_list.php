<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_connect.php';

$result = $conn->query("
    SELECT p.id, d.name, d.phone, p.amount, p.paid_at
    FROM payments p
    JOIN dues d ON p.fgci_id = d.id
    ORDER BY p.paid_at DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payment List – STUDY WITH CIMON</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
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

    .back-btn {
      display: inline-block;
      margin-bottom: 20px;
      padding: 10px 18px;
      background: linear-gradient(135deg, #1a7a3a, #0e9e8a);
      color: white;
      border-radius: 8px;
      text-decoration: none;
      font-size: 14px;
    }

    .back-btn:hover {
      background: linear-gradient(135deg, #155f2d, #0b7d6e);
    }

    .summary {
      background: white;
      border-radius: 12px;
      padding: 16px 20px;
      margin-bottom: 20px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.08);
      display: flex;
      gap: 30px;
      flex-wrap: wrap;
    }

    .summary div {
      font-size: 15px;
    }

    .summary span {
      font-weight: bold;
      color: #1a7a3a;
    }

    /* Table */
    .table-wrap {
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    }

    thead {
      background: linear-gradient(135deg, #1a7a3a, #0e9e8a);
      color: white;
    }

    th, td {
      padding: 12px 16px;
      text-align: left;
      font-size: 14px;
    }

    tbody tr:nth-child(even) {
      background: #f4faf7;
    }

    tbody tr:hover {
      background: #e2f5ed;
    }

    .amount {
      font-weight: bold;
      color: #1a7a3a;
    }

    .empty {
      text-align: center;
      padding: 40px;
      color: #888;
    }

    @media screen and (min-width: 768px) {
      body { padding: 40px 60px; }
      h1   { font-size: 28px; margin-bottom: 30px; }
    }
  </style>
</head>
<body>

  <img src="swc.png" alt="SWC Logo" class="logo"/>
  <h1>PAYMENT LIST</h1>

  <a href="index.php" class="back-btn">← Back to Dashboard</a>

  <?php
  $total_payments = 0;
  $total_amount   = 0;
  $rows = [];

  if ($result && $result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          $rows[] = $row;
          $total_payments++;
          $total_amount += $row['amount'];
      }
  }
  ?>

  <div class="summary">
    <div>Total Payments: <span><?= $total_payments ?></span></div>
    <div>Total Collected: <span>GHS <?= number_format($total_amount, 2) ?></span></div>
  </div>

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Member Name</th>
          <th>Phone</th>
          <th>Amount (GHS)</th>
          <th>Date & Time</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($rows)): ?>
          <tr><td colspan="5" class="empty">No payments recorded yet.</td></tr>
        <?php else: ?>
          <?php foreach ($rows as $i => $row): ?>
            <tr>
              <td><?= $i + 1 ?></td>
              <td><?= htmlspecialchars($row['name']) ?></td>
              <td><?= htmlspecialchars($row['phone']) ?></td>
              <td class="amount"><?= number_format($row['amount'], 2) ?></td>
              <td><?= date('d M Y, h:i A', strtotime($row['paid_at'])) ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

</body>
</html>
<?php $conn->close(); ?>
