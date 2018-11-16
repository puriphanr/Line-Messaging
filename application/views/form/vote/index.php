<div class="col-lg-12">
        <h1 class="page-header">Vote</h1>
</div>
<div class="col-lg-12">

	 <table class="table table-bordered" style=" word-wrap:break-word;
              table-layout: fixed;">
    <thead>
      <tr>
		<th width="10%">Time</th>
        <th width="40%">Title</th>
		<th width="5%">Prefix</th>
        <th width="10%">Start</th>
		<th width="10%">Finish</th>
		<th width="10%">Publish</th>
		<th width="10%">Manual</th>
		<th width="10%">Action</th>
      </tr>
    </thead>
    <tbody>
	<?php foreach($data as $key=>$row){ ?>
      <tr>
        <td><?php echo $row['create_time']?></td>
        <td><?php echo $row['vote_title']?></td>
		<td><?php echo $row['question_prefix']?></td>
        <td><?php echo $row['start_time']?></td>
		<td><?php echo $row['finish_time']?></td>
		<td><?php echo $row['status'] == 1 ? 'YES' : 'NO' ?></td>
		<td><?php echo $row['manual'] == 1 ? 'YES' : 'NO' ?></td>
		<td>
			<a href="<?php echo site_url('form/updateVote/1/'.$row['vote_id'])?>" class="btn btn-warning btn-xs"><i class="fa fa-edit"></i></a>
			<a href="<?php echo site_url('form/removeVote/'.$row['vote_id'])?>" class="btn btn-danger btn-xs"><i class="fa fa-remove"></i></a>
		</td>
	  </tr>
	  <?php } ?>
    </tbody>
  </table>
  
</div>