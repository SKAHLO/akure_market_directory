<?php

$categories = getCategories($pdo);

$spanhtml = "";

foreach ($categories as $cat) {
	$clss = '';
	if(isset($listing) && $listing['category']==$cat['id']){$clss = "active";};

	$spanhtml .= '<span class=\'input_option '. $clss.'\' onclick=\'cat.value = '. $cat['id'] .'; reset();this.classList.add(active);\'>'. $cat['name'] .'</span>';
}

?>

<script>
	let active = 'active';
	let s = document.getElementById('catinput');
	function reset() {
		for (let sc of s.childNodes) {
		    sc.classList.remove('active');
		}
	}
	document.getElementById('catinput').innerHTML = "<?php echo $spanhtml; ?>";
</script>