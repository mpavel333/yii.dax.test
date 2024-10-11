<?php


$result = json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

?>


<div class="form-group">
	<label>Ответ от сервера</label>
	<textarea disabled="" class="form-control" rows="10"><?= $result ?></textarea>
</div>