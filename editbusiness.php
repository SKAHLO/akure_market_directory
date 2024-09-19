<?php
$listing = getListing($id, $pdo);
?>
<script>
	let bname = "<?php echo $listing['name']?>"
	let category = "<?php echo $listing['category']?>"
	let address = "<?php echo $listing['full_address']?>"
	let tel_1 = "<?php echo $listing['tel_1']?>"
	let tel_2 = "<?php echo $listing['tel_2']?>"
	let longitude = "<?php echo $listing['longitude']?>"
	let latitude = "<?php echo $listing['latitude']?>"
	let description = "<?php echo $listing['description']?>"
	let id = "<?php echo $listing['id']?>"
	let openhours =  "<?php echo $listing['open_hours']?>"
	let products =  "<?php echo $listing['products']?>"

	document.getElementById('business_name').value = bname;
	document.getElementById('category').value = category;
	document.getElementById('address').value = address;
	document.getElementById('tel_1').value = tel_1;
	document.getElementById('tel_2').value = tel_2;
	document.getElementById('longitude').value = longitude;
	document.getElementById('latitude').value = latitude;
	document.getElementById('description').value = description;
	document.getElementById('id').value = id;
	document.getElementById('products').value = products;
	document.getElementById('openhours').value = openhours;
	document.getElementById('addbusiness').action = '/updatebusiness';
	document.getElementById('image').parentNode.style.display = 'none';

</script>