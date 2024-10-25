<tr>
  <td>Nama Alternatif</td>
  <td>
    <select name="alt" class="form-control">
      <option value="<?php echo htmlspecialchars($_GET['alt'] ?? '', ENT_QUOTES); ?>">
        <?php echo htmlspecialchars($_GET['alt'] ?? '', ENT_QUOTES); ?>
      </option>
      <?php
      // Fetch alternatives
      $stmt3 = $connection->prepare("SELECT * FROM tbl_alternatif");
      $stmt3->execute();
      $result3 = $stmt3->get_result(); // Use get_result() for fetching
      while ($row3 = $result3->fetch_assoc()) {
        echo '<option value="' . htmlspecialchars($row3['id_alternatif'], ENT_QUOTES) . '">' . htmlspecialchars($row3['nama_alternatif'], ENT_QUOTES) . '</option>';
      }
      ?>
    </select>
  </td>
</tr>