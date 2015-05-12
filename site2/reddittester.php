<!DOCTYPE html>
<html>
<head>
<title>scraper page</title>
<script src="jquery-2.1.3.min.js"></script>
</head>

<body>
	<?php echo 'lol';?>
	<script>
		$.getJSON("https://www.reddit.com/r/Leiden/new.json", function(data) {
			data.reverse();
			$.each(data.data.children, function(i, f) {
				if(String(f.data.stickied) == "false" && String(f.data.title).substring(0,13) != 'Slowchat Week') {
					$.getJSON('items.json', function(local) {
						var test = false;
						$.each(local.items, function(i, l) {
							if(l.id == f.data.id) {
								test = true;
							}
						})
						console.log('toevoegen');
						if(!test) {
							$.ajax({
								data: {title: f.data.title, url: f.data.url, id: f.data.id, thumbnail: f.data.thumbnail, createdtime: f.data.created_utc, source: "reddit"},
								url: '/arrayadder.php',
								method: 'POST',
								success:function(msg) {
									alert(msg);
								}
							});
						}
					})
					
				}
			})
		})
	</script>
</body>

</html>