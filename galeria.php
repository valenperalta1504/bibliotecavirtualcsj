<?php
//Condición para redirigir si se abre desde la url
if (!defined('ACCESO_PERMITIDO')) {
    header("Location: home.php");
}
else {		//Array con imágenes
			$images = array("banner1.jpg", "banner2.jpg", "banner3.jpg", "banner4.jpg");
			
			$currentImage = isset($_GET['image']) ? $_GET['image'] : 0;
			if ($currentImage < 0 || $currentImage >= count($images)) {
				$currentImage = 0;
			}
            if ($currentImage > 0) {
				echo '<a href="?image=' . ($currentImage - 1) . '"><button class="btn"><</button></a>';
			}
            else {
                echo '<a><button class="btn"><</button></a>';
            }
			echo '<img src="' . $images[$currentImage] . '" alt="Imagen">';

			echo '<div>';
			
			if ($currentImage < count($images) - 1) {
				echo '<a href="?image=' . ($currentImage + 1) . '"><button class="btn">></button></a>';
			}
            else{
                echo '<a><button class="btn">></button></a>';
            }
			echo '</div>';
        }
			?>
