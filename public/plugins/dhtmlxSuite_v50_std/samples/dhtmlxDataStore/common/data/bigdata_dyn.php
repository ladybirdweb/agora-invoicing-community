[
<?php
	for ($i=0; $i<5000; $i++){
?>
	{ id:"x<?php echo $i; ?>", Package:"Package <?php echo $i;?>", Version:"V <?php echo $i;?>", Maintainer:"MT <?php echo $i;?>"},
<?php
	}
?>
	{ id:"x5000", Package:"5000", Version:"5000", Maintainer:"5000"}
]