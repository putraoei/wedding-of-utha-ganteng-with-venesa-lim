<?php
$conn = new mysqli("localhost", "root", "", "undangan_db");
$result = $conn->query("SELECT * FROM rsvp ORDER BY created_at DESC");
?>

<h2>Data RSVP</h2>
<table border="1" cellpadding="8">
  <tr>
    <th>ID</th>
    <th>Nama</th>
    <th>Email</th>
    <th>WA</th>
    <th>Status</th>
    <th>Pesan</th>
    <th>Tanggal</th>
  </tr>
<?php while($row = $result->fetch_assoc()): ?>
  <tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['nama'] ?></td>
    <td><?= $row['email'] ?></td>
    <td><?= $row['wa'] ?></td>
    <td><?= $row['status'] ?></td>
    <td><?= $row['pesan'] ?></td>
    <td><?= $row['created_at'] ?></td>
  </tr>
<?php endwhile; ?>
</table>