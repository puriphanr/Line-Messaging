<div class="col-lg-12">
        <h1 class="page-header">Push</h1>
</div>
<div class="col-lg-12">
	<div class="form-search" style="margin-top:20px">
		<form id="form-search" class="form-horizontal" method="post" action="<?php echo site_url('messages/searchMessage')?>">
			<div class="form-group">
				<label class="col-lg-1">
				Name/Msg.
				</label>
				<div class="col-lg-5">
					<input type="text" class="form-control" name="display_name" required />
				</div>
				<label class="col-lg-1">
				Range
				</label>
				<div class="col-lg-2">
					<input type="text" class="form-control" name="start_range" id="start_range" required />
				</div>
				
				<div class="col-lg-2">
					<input type="text" class="form-control" name="end_range" id="end_range" required />
				</div>
				<div class="col-lg-1">
					<button type="submit" class="btn btn-success">Search</button>
				</div>
			</div>
		</form>
	</div>
</div>
<div class="col-lg-12">

 <table class="table table-bordered" style=" word-wrap:break-word;
              table-layout: fixed;">
    <thead>
      <tr>
		<th width="10%">Time</th>
        <th width="20%">Display Name</th>
		<th width="5%">Image</th>
        <th width="50%">Text</th>
		<th width="5%">Action</th>
      </tr>
    </thead>
    <tbody>
	<?php foreach($data as $key=>$row){ ?>
      <tr>
        <td><?php echo $row['timestamp']?></td>
        <td><?php echo $row['profile_name']?></td>
		<td><img src="<?php echo $row['profile_url']?>" class="img-circle" width="50" /></td>
        <td><?php echo $row['text']?></td>
		<td><a href="#" data-id="<?php echo $row['user_id']?>" data-toggle="modal" data-target="#myModal" class="do-send btn btn-primary btn-xs">Send</a></td>
	  </tr>
	  <?php } ?>
    </tbody>
  </table>
	
	
</div>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
	<form id="sendForm" method="POST" action="<?php echo site_url("messages/push") ?>">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Send Message</h4>
      </div>
      <div class="modal-body">
		<input type="hidden" name="fromID" id="fromID" value="" />
       <textarea name="text" rows="5" class="form-control" required></textarea>
      </div>
      <div class="modal-footer">
	    <button type="submit" class="btn btn-success" >Send</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
	</form>
  </div>
</div>
<link href="<?php echo base_url()?>/assets/js/bootstrap-datetimepicker-master/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script src="<?php echo base_url()?>/assets/js/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.min.js"></script>
<script>
$(function(){
	$("#start_range,#end_range").datetimepicker({
		format: 'yyyy-mm-dd hh:ii'
	});

	$('.do-send').click(function(){
		$('#fromID').val($(this).data('id'));
	
	})
	
	$('#sendForm').on('submit',function(){
		return confirm('ยืนยันการส่งข้อความนี้ ?');
	})
})
</script>