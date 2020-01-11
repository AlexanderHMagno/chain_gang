<?php require_once('../private/initialize.php'); ?>

<?php $page_title = 'Inventory'; ?>
<?php include(SHARED_PATH . '/public_header.php'); ?>

<div id="main">

  <div id="page">
    <div class="intro">
      <img class="inset" src="<?php echo url_for('/images/AdobeStock_55807979_thumb.jpeg') ?>" />
      <h2>Our Inventory of Used Bicycles</h2>
      <p>Choose the bike you love.</p>
      <p>We will deliver it to your door and let you try it before you buy it.</p>
    </div>

    <table id="inventory">
      <tr>
        <th>Brand</th>
        <th>Model</th>
        <th>Year</th>
        <th>Category</th>
        <th>Gender</th>
        <th>Color</th>
        <th>Weight</th>
        <th>Condition</th>
        <th>Price</th>
        <th>Link</th>
      </tr>

<?php
  
    $current_page = $_GET['page'] ?? 1; 
    $per_page = 2 ; 
    $total_count = bicycle::total_count();
    $url = url_for('/bicycles.php');
    $pagination = new Pagination($current_page, $per_page, $total_count,$url);
    $bike_array = bicycle::find_all_pagination($per_page, $pagination->offset());
?>
      <?php foreach($bike_array as $bike) { ?>
      <tr>
        <td><?php echo h($bike->brand); ?></td>
        <td><?php echo h($bike->model); ?></td>
        <td><?php echo h($bike->year); ?></td>
        <td><?php echo h($bike->category); ?></td>
        <td><?php echo h($bike->gender); ?></td>
        <td><?php echo h($bike->color); ?></td>
        <td><?php echo h($bike->weight_kg()) . ' / ' . h($bike->weight_lbs()); ?></td>
        <td><?php echo h($bike->condition()); ?></td>
        <td><?php echo h(money_format('$%i', $bike->price)); ?></td>
        <td><a href="detail.php?id=<?php echo $bike->id?>"> View </a></td>
      </tr>
      <?php } ?>

    </table>

   <?php echo $pagination->pagination_interface() ?>
  </div>

</div>

<?php include(SHARED_PATH . '/public_footer.php'); ?>
