<?php include('db_connect.php');?>

<div class="container-fluid">
	
	<div class="col-lg-12">
		<div class="row">
			<!-- FORM Panel -->
			<div class="col-md-4">
			<form action="" id="manage-class">
				<div class="card">
					<div class="card-header">
						    Class Form
				  	</div>
					<div class="card-body">
							<input type="hidden" name="id">
							<div id="msg"></div>
							<select name="course_id" id="course_id" class="custom-select select2">
								<option value=""></option>
								<?php 
								$course = $conn->query("SELECT * FROM courses order by course asc");
								while($row=$course->fetch_assoc()):
								?>
								<option value="<?php echo $row['id'] ?>"><?php echo $row['course'] ?></option>
							<?php endwhile; ?>
							</select> </p>
							<div class="form-group">
								<select name="level" id="course_id" class="custom-select select2">
								<option value="HND 2">HND 2</option>
								<option value="HND 1">HND 1</option>
								<option value="ND 2 FT">ND 2 FT</option>
								<option value="ND 2 PT">ND 2 PT</option>
								<option value="ND 1 FT">ND 1 FT</option>
								<option value="ND 1 PT">ND 1 PT</option>
								</select>
							</div></P>
							<div class="form-group">
								<select name="section" id="course_id" class="custom-select select2">
								<option value="FIRST SEMESTER">FIRST SEMESTER</option>
								<option value="SECOND SEMESTER">SECOND SEMESTER</option>
							
								</select>
							</div>
							
					</div>
							
					<div class="card-footer">
						<div class="row">
							<div class="col-md-12">
								<button class="btn btn-sm btn-primary col-sm-3 offset-md-3"> Save</button>
								<button class="btn btn-sm btn-default col-sm-3" type="reset"> Cancel</button>
							</div>
						</div>
					</div>
				</div>
			</form>
			</div>
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-8">
				<div class="card">
					<div class="card-header">
						<b>Class List</b>
					</div>
					<div class="card-body">
						<table class="table table-bordered table-hover">

							<thead>
								<tr>
									<th class="text-center" width="5%">#</th>
									<th class="text-center">Level</th>
									<th class="text-center">Course Code </th>
									<th class="text-center">Semester</th>

									<th class="text-center" width="25%">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$class = $conn->query("SELECT c.*,concat(co.course) as 'class',(c.level) as 'level', c.section as `section` FROM class c inner join courses co on co.id = c.course_id order by c.id asc");
								while($row=$class->fetch_assoc()):
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
									<td class="">
										<p><b><?php echo $row['level'] ?></b></p>
									</td>
									<td class="">
										<p><b><?php echo $row['class'] ?></b></p>
									</td>
									<td class="">
										<p><b><?php echo $row['section'] ?></b></p>
									</td>
									<td class="text-center">
										<button class="btn btn-sm btn-primary edit_class" type="button" data-id="<?php echo $row['id'] ?>"  data-course_id="<?php echo $row['course_id'] ?>"  data-level="<?php echo $row['level'] ?>" data-section="<?php echo $row['section'] ?>" >Edit</button>
										<button class="btn btn-sm btn-danger delete_class" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
									</td>
								</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
	</div>	

</div>
<style>
	
	td{
		vertical-align: middle !important;
	}
	td p {
	    margin: unset;
	}
</style>
<script>
	
	$('#manage-class').on('reset',function(){
		$('#msg').html('')
		$('input:hidden').val('')
		$('.select2').val('').trigger('change')
	})
	$('#manage-class').submit(function(e){
		e.preventDefault()
		$('#msg').html('')
		start_load()
		$.ajax({
			url:'ajax.php?action=save_class',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully saved",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}else if(resp == 2){
				$('#msg').html('<div class="alert alert-danger mx-2">Class already exist.</div>')
				end_load()
				}				
			}
		})
	})
	$('.edit_class').click(function(){
		start_load()
		var cat = $('#manage-class')
		cat.get(0).reset()
		cat.find("[name='id']").val($(this).attr('data-id'))
		cat.find("[name='course_id']").val($(this).attr('data-course_id')).trigger('change')
		cat.find("[name='level']").val($(this).attr('data-level'))
		cat.find("[name='section']").val($(this).attr('data-section'))
		end_load()
	})
	$('.delete_class').click(function(){
		_conf("Are you sure to delete this class?","delete_class",[$(this).attr('data-id')])
	})
	function delete_class($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_class',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
	$('table').dataTable()
</script>