<?php

include "config.php";

if(empty($_FILES['new-image']['name']))
{
	$file_name=$_POST['old-image'];
}
else
{
	$errors= array();

	$file_name=$_FILES['fileToUpload']['name'];
	$file_size=$_FILES['fileToUpload']['size'];
	$file_tmp=$_FILES['fileToUpload']['tmp_name'];
	$file_type=$_FILES['fileToUpload']['type'];
	$file_ext=end(explode('.',$file_name));

	$extension=array("jpeg","jpg","png");
	if(in_array($file_ext,$extension) === false)
	{
		$errors[]="This extension file are not allowed..PlZ choose jpef,jpg or png format";
	}

	if($file_size > 2097152)
	{
		$errors[]="File Size must be less than 2 mb.";
	}

	if(empty($errors) == true)	
	{
		move_uploaded_file($file_tmp, "upload/".$file_name);
	}
	else
	{
		print_r($errors);
		die();
	}
}
	

$sql="update post set title='{$_POST["post_title"]}',description='{$_POST["postdesc"]}',category={$_POST["category"]},post_img='{$file_name}' where post_id={$_POST["post_id"]};";

if($_POST['old_category'] != $_POST["category"])
{
	$sql .="update category set post = post-1 where category_id = {$_POST["category"]};";
	$sql .="update category set post = post+1 where category_id = {$_POST["category"]};";
}


$result=mysqli_multi_query($conn,$sql);

if($result)
{
	header("Location:{$hostname}/admin/post.php");
}
else
{
	echo "Query failed...";	
}

?>