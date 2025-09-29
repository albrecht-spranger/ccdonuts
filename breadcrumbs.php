<!-- パンくずリスト -->
<div class="breadcrumbContainer">
	<p class="breadcrumb">
		<?php
		if (empty($breadcrumbs)) {
			echo 'undefined';
		} else {
			foreach ($breadcrumbs as $i => $bc) {
				if ($i !== 0) {
					echo '＞';
				}
				$label = htmlspecialchars($bc['label'] ?? '', ENT_QUOTES, 'UTF-8');
				$url   = $bc['url']   ?? null;
				if ($url) {
					$href = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
					echo '<a href="' . $href . '">' . $label . '</a>';
				} else {
					echo $label;
				}
			}
		}
		?>
	</p>
</div>