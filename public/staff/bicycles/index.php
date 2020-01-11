<?php require_once('../../../private/initialize.php'); ?>


<?php
require_login();

//use pagination instead 
$current_page = $_GET['page'] ?? 1; 
$per_page = 2 ; 
$total_count = bicycle::total_count();
$url = url_for('/staff/bicycles/index.php');
$pagination = new Pagination($current_page, $per_page, $total_count,$url);
$bicycles = bicycle::find_all_pagination($per_page, $pagination->offset());
  
?>
<?php $page_title = 'Bicycles'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">
  <div class="bicycles listing">
    <h1>Bicycles</h1>

    <div class="actions">
      <a class="action" href="<?php echo url_for('/staff/bicycles/new.php'); ?>">Add Bicycle</a>
    </div>

  	<table class="list">
      <tr>
        <th>ID</th>
        <th>Brand</th>
        <th>Model</th>
        <th>Year</th>
        <th>Category</th>
        <th>Gender</th>
        <th>Color</th>
        <th>Price</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
      </tr>

      <?php foreach($bicycles as $bicycle) { ?>
        <tr>
          <td><?php echo h($bicycle->id); ?></td>
          <td><?php echo h($bicycle->brand); ?></td>
          <td><?php echo h($bicycle->model); ?></td>
          <td><?php echo h($bicycle->year); ?></td>
          <td><?php echo h($bicycle->category); ?></td>
          <td><?php echo h($bicycle->gender); ?></td>
          <td><?php echo h($bicycle->color); ?></td>
          <td><?php echo h($bicycle->price); ?></td>
          <td><a class="action" href="<?php echo url_for('/staff/bicycles/show.php?id=' . h(u($bicycle->id))); ?>">View</a></td>
          <td><a class="action" href="<?php echo url_for('/staff/bicycles/edit.php?id=' . h(u($bicycle->id))); ?>">Edit</a></td>
          <td><a class="action" href="<?php echo url_for('/staff/bicycles/delete.php?id=' . h(u($bicycle->id))); ?>">Delete</a></td>
    	  </tr>
      <?php } ?>
  	</table>
<?php
  echo $pagination->pagination_interface();
?>
  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
