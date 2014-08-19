
	
		
		<div>
			<form id="book" name="book" method="post" action="">
				
				<?php foreach($data as $data) { ?>
					<label> <?php echo $data; ?>
						<input type="text" name="<?php echo $data; ?>" id="<?php echo $data; ?>"/>
					</label>			
				<?php }?>
			
					<label>
						<input type="submit" name="submitBook" id="submit" value="Submit"/>
					</label>
			
			</form>
		</div>	
		
