<?php
add_action("admin_menu", "fs_img_upload12");

function fs_img_upload12() {
   add_menu_page("Social icons", "Social icons", 10, "social_icons", "social_icons");
}

function social_icons(){
	global $wpdb;
	$get_uploader_path = wp_upload_dir();
	$basedir = $get_uploader_path['basedir'];
//echo "<pre>"; print_r($get_uploader_path);
   			if($_POST['btnsub_msg12'])
			{
				$wpdb->query("INSERT INTO `wp_socialshare` ( `id` , `s_title` , `s_link` , `order` )
VALUES ( NULL , '".$_POST['btn_link']."' , '".$_POST['btn_ic_link']."' , NULL
);");
			}




		if(isset($_GET['del_id']))
		{
			$wpdb->query("delete from `wp_socialshare` where id = ".$_GET['del_id']." ;");
		}
			$all_data = $wpdb->get_results("SELECT * FROM wp_socialshare");
   ?>
   <script type="text/javascript">
   		function removeit(id)
		{
			r = confirm('Are you sure ?');
			if(r)
			{
				window.location='?page=social_icons&del_id='+id;
			}
		}
   </script>
   <h2>Social Icons</h2>
    <form action="" method="post" enctype="multipart/form-data">
    		<table class="widefat">
            	<tr>
                	<td colspan="">Add Link</td><td><input type="text" name="btn_link" required="required" /></td>
                </tr>

                <tr>
                	<td colspan="">Add Icon Link</td><td><input type="text" name="btn_ic_link" required="required" /></td>
                </tr>

                <tr>
                       <td colspan="2"><input type="submit" class="button button-primary button-large" name="btnsub_msg12" id="btnsub444" value="Add" /></td>
                </tr>
            </table>

     <div><hr /></div>

            <table class="widefat">

            	<tr>
                		<td>Link </td> <td >Icon </td> <td > </td>
                </tr>

              <?php
              		foreach($all_data as $all_data1){
			  ?>
              <tr>
                		<td><?php echo $all_data1->s_title; ?> </td>
                        <td><?php echo $all_data1->s_link; ?></td><td><a onclick="return removeit('<?php echo $all_data1->id; ?>')" style="cursor:pointer;">Remove</a></td>
                </tr>
              <?php } ?>

            </table>

    </form>
   <div style="clear:both;"><br /></div>
   <?php
}
