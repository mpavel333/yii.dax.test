<html
	xmlns:o='urn:schemas-microsoft-com:office:office'
	xmlns:x='urn:schemas-microsoft-com:office:excel'
	xmlns='http://www.w3.org/TR/REC-html40'>
	<head>
		<meta http-equiv='Content-Type' content='text/html;charset=utf-8'/>
		<!--[if gte mso 9]>
		<xml>
			<x:ExcelWorkbook>
				<x:ExcelWorksheets>
					<x:ExcelWorksheet>
						<x:Name>Заказы</x:Name>
						<x:WorksheetOptions>
							<x:DisplayGridlines/>
						</x:WorksheetOptions>
					</x:ExcelWorksheet>
				</x:ExcelWorksheets>
			</x:ExcelWorkbook>
		</xml>
		<![endif]-->
	</head>
	<body>
		<table class='kv-grid-table table table-bordered table-striped table-condensed'>
			<thead>
				<tr>
					<?php foreach($data[0] as $label): ?>
						<th><?= $label ?></th>
					<?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<?php $counter = 1; foreach ($data as $datum): ?>
				<?php if($counter == 1) { $counter++; continue; } ?>
				<tr>
					<?php foreach($datum as $key => $value): ?>
						<td><?php 

							echo $value;

						?></td>
					<?php endforeach; ?>
				</tr>
				<?php $counter++; ?>
				<?php endforeach; ?>
			</tbody>
		</table>
	</body>
</html>