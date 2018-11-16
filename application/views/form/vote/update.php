<div class="col-lg-12">
        <h1 class="page-header">Vote</h1>
</div>
<div class="col-lg-12">
	<form action="<?php echo site_url('form/updateVote/2/'.$this->uri->segment(4))?>" method="POST">
			 <div class="form-group col-lg-12">
				<label for="email">Title :</label>
				<input type="text" class="form-control" id="vote_title" name="vote_title" value="<?php echo $this->uri->segment(4) ? $vote['vote_title'] : NULL ?>" />
			</div>
		  <div class="form-group">
			<div class="col-lg-4">
				<label for="email">Prefix :</label>
				<input type="text" class="form-control" id="question_prefix" name="question_prefix" value="<?php echo $this->uri->segment(4) ? $vote['question_prefix'] : NULL ?>" />
			</div>
			
			<div class="col-lg-4">
				<label for="email">Start :</label>
				<input type="text" class="form-control" id="start_time" name="start_time" value="<?php echo $this->uri->segment(4) ? $vote['start_time'] : NULL ?>" />
			</div>
			
			<div class="col-lg-4">
				<label for="email">Finish :</label>
				<input type="text" class="form-control" id="finish_time" name="finish_time" value="<?php echo $this->uri->segment(4) ? $vote['finish_time'] : NULL ?>" />
			</div>
			
			 <div class="form-group col-lg-12">
				<label for="email">Answers :</label>
				<textarea class="form-control" id="question_answers" name="question_answers" rows="5"><?php echo $this->uri->segment(4) ? $vote['question_answers'] : NULL ?></textarea>
			</div>
			 <div class="form-group col-lg-12">
				<div class="col-lg-4">
					<label for="email">Publish :</label>
					<input type="checkbox" name="status" id="status" value="1" <?php echo $this->uri->segment(4) && $vote['status'] == 1 ? 'checked="checked"' : NULL ?> /> Publish
				</div>
				
				<div class="col-lg-4">
					<label for="email">Manual :</label>
						<input type="checkbox" name="manual" id="manual" value="1" <?php echo $this->uri->segment(4) && $vote['manual'] == 1 ? 'checked="checked"' : NULL ?> /> Manual
				</div>
			</div>
		  </div>
		  
		<button type="submit" class="btn btn-default">Submit</button>
	</form>
</div>