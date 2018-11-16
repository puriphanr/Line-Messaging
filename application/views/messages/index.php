 <div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Messages</h1>
    </div>
</div>
               
<div class="row">
	<div class="col-lg-12">
		<form>
			<div id="msg-row"></div>
			<div class="submit-group">
				<button type="button" class="btn btn-primary"><i class="fa fa-refresh"></i> Refresh</button>
				<button type="submit" class="btn btn-success"><i class="fa fa-paper-plane"></i> Send</button>
			</div>
		</form>
	</div>
</div>
<script>
$(function(){
		$.ajax({
			url : '<?php echo site_url('messages/reloadMessage')?>',
			type : 'POST',
			dataType : 'JSON',
			beforeSend : function(){
				$('#modal-loader').modal('show');
			},
			success : function(callback){
				$('#modal-loader').modal('hide');
				if(callback[0] == true){
					$html = '<div class="list-img">';
					$.each(callback[1],function(key,row){
						$html += '<div class="col-lg-3">'
						$.each(row,function(skey,srow){
							if(srow.contentType == 8){
								var message = '<img src="'+srow.text+'" />';
							}
							else{
								var message = srow.text;
							}
							$html += '<div class="col-lg-12">';
							$html += '<div class="profile"><img src="'+srow.pictureUrl+'"/> </div> ';
							$html += '<div class="description"><div class="display_name">'+srow.displayName+' <div class="pull-right"><div><i class="fa fa-check fa-lg"></i> </div></div></div><div class="text">'+message+'</div> </div>';
							$html += '</div>';
						})
						$html += '</div>';
					})
					$html += '</div>';
					$('#msg-row').html($html);
				}
				else{
					$('#errText').text('ไม่สามารถดึงข้อมูลได้ กรุณาลองใหม่อีกครั้ง !');
					$('#modal-error').modal('show');
				}
			}
		})
})
</script>