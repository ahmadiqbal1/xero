
<script >

	$("document").ready(function(){	
		$("a.share_button").click(function(e) {
	    var width = window.innerWidth * 0.66 ;
	    var height = width * window.innerHeight / window.innerWidth ;
	    window.open(this.href , 'newwindow', 'width=' + width + ', height=' + height + ', top=' + ((window.innerHeight - height) / 2) + ', left=' + ((window.innerWidth - width) / 2));
		e.preventDefault();
	
	});
	
	});

</script>

<?php 	$share_current_url=current_url();     /***This will be current URL ***/ ?>



<!-- Facebook -->
<?php 
$path = 'application/modules/sitedoctor/assets/images/button/facebook.png';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
?>
<a class="share_button" href="http://www.facebook.com/sharer.php?u=<?php echo $share_current_url;  ?>" target="_blank">
<?php echo "<img src='".$base64."' alt='Facebook'>"; ?>
</a>

<!-- Twitter -->
<?php 
$path = 'application/modules/sitedoctor/assets/images/button/twitter.png';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
?>
<a class="share_button" href="https://twitter.com/share?url=<?php echo $share_current_url;  ?>" target="_blank">
<?php echo "<img src='".$base64."' alt='Twitter'>"; ?>
</a>

<!-- Google+ -->
<?php 
$path = 'application/modules/sitedoctor/assets/images/button/google.png';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
?>
<a class="share_button" href="https://plus.google.com/share?url=<?php echo $share_current_url;  ?>" target="_blank">
<?php echo "<img src='".$base64."' alt='Google+'>"; ?>
</a>

<!-- LinkedIn -->
<?php 
$path = 'application/modules/sitedoctor/assets/images/button/linkedin.png';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
?>
<a class="share_button" href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo $share_current_url;  ?>" target="_blank">
<?php echo "<img src='".$base64."' alt='LinkedIn'>"; ?>
</a>

<!-- Pinterest -->
<?php 
$path = 'application/modules/sitedoctor/assets/images/button/pinterest.png';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
?>
<a  href="javascript:void((function()%7Bvar%20e=document.createElement('script');e.setAttribute('type','text/javascript');e.setAttribute('charset','UTF-8');e.setAttribute('src','http://assets.pinterest.com/js/pinmarklet.js?r='+Math.random()*99999999);document.body.appendChild(e)%7D)());">
<?php echo "<img src='".$base64."' alt='Pinterest'>"; ?>
</a>

<!-- Reddit -->
<?php 
$path = 'application/modules/sitedoctor/assets/images/button/reddit.png';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
?>
<a class="share_button" href="http://reddit.com/submit?url=<?php echo $share_current_url;  ?>;title=SEO Analysis" target="_blank">
<?php echo "<img src='".$base64."' alt='Reddit'>"; ?>
</a>

<!-- StumbleUpon-->
<?php 
$path = 'application/modules/sitedoctor/assets/images/button/stumbleupon.png';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
?>
<a href="http://www.stumbleupon.com/submit?url=<?php echo $share_current_url;  ?>;title=SEO Analysis" target="_blank">
<?php echo "<img src='".$base64."' alt='StumbleUpon'>"; ?>
</a>
<!-- Tumblr-->
<?php 
$path = 'application/modules/sitedoctor/assets/images/button/tumblr.png';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
?>
<a class="share_button" href="http://www.tumblr.com/share/link?url=<?php echo $share_current_url;  ?>;title=SEO Analysis" target="_blank">
<?php echo "<img src='".$base64."' alt='Tumblr'>"; ?>
</a>

<!-- Buffer -->
<?php 
$path = 'application/modules/sitedoctor/assets/images/button/buffer.png';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
?>
<a class="share_button" href="https://bufferapp.com/add?url=<?php echo $share_current_url;  ?>" target="_blank">
<?php echo "<img src='".$base64."' alt='Buffer'>"; ?>
</a>

<!-- Digg -->
<?php 
$path = 'application/modules/sitedoctor/assets/images/button/diggit.png';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
?>
<a class="share_button" href="http://www.digg.com/submit?url=<?php echo $share_current_url;  ?>" target="_blank">
<?php echo "<img src='".$base64."' alt='Digg'>"; ?>
</a>




