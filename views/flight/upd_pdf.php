<?php

use \yii\helpers\ArrayHelper;



$I29 = $model->we;

if(is_numeric($I29) == false){
	$I29 = 0;
}

$I28 = round($I29 * 20 / 120, 2);
$I27 = round($I29 - $I28, 2);

?><?xml version="1.0" encoding="windows-1251"?>
<���� ������="ON_NSCHFDOPPR_2be69560103c7c34888b398471cc3628768_2BM-7726458040-772601001-201911010726579234624_20240502_3bdb7f04-ed90-414c-aa2e-9a3961b7762c" ��������="5.01" ��������="Diadoc 1.0">
	<����������� ������="2BM-7726458040-772601001-201911010726579234624" �����="2be69560103c7c34888b398471cc3628768">
		<��������� �������="�� &quot;�� &quot;��� ������&quot;" �����="6663003127" �����="2BM"/>
	</�����������>
	<�������� ���="1115131" �������="���" ��������="�������� �� �������� ������� (���������� �����), �������� ������������� ���� (�������� �� �������� �����)" ����������="�������� �� �������� ������� (���������� �����), �������� ������������� ���� (�������� �� �������� �����)" ���������="02.05.2024" ���������="19.34.33" ���������������="��� &quot;���&quot;, ���/���: 7726458040/130001001">
		<�������� ��������="1492" �������="30.04.2024" ������="643">
			<������>
				<����>
					<������ �������="��� &quot;���&quot;" �����="<?= ArrayHelper::getValue($model, 'organization.inn') ?>" ���="ArrayHelper::getValue($model, 'organization.kpp')"/>
				</����>
				<�����>
					<������ ������="643" ��������="<?= ArrayHelper::getValue($model, 'organization.official_address') ?>"/>
				</�����>
				<�������� ����������="<?= ArrayHelper::getValue($model, 'organization.nomer_rascheta') ?>">
					<������ ��������="<?= ArrayHelper::getValue($model, 'organization.bank_name') ?>" ���="<?= ArrayHelper::getValue($model, 'organization.bic') ?>" �������="<?= ArrayHelper::getValue($model, 'organization.kr') ?>"/>
				</��������>
			</������>
			<�������>
				<����>
					<������ �������="<?= ArrayHelper::getValue($model, 'zakazchik.name') ?>" �����="<?= ArrayHelper::getValue($model, 'zakazchik.inn') ?>" ���="<?= ArrayHelper::getValue($model, 'zakazchik.kpp') ?>"/>
				</����>
				<�����>
					<������ ������="643" ��������="<?= ArrayHelper::getValue($model, 'zakazchik.official_address') ?>"/>
				</�����>
				<������� �������="info@daks-group.ru"/>
				<�������� ����������="<?= ArrayHelper::getValue($model, 'zakazchik.nomer_rascheta') ?>">
					<������ ��������="<?= ArrayHelper::getValue($model, 'zakazchik.bank_name') ?>" ���="<?= ArrayHelper::getValue($model, 'zakazchik.bic') ?>" �������="<?= ArrayHelper::getValue($model, 'zakazchik.kr') ?>"/>
				</��������>
			</�������>
			<��������1 �������="���������� �����"/>
			<������������ �����������="���������� (���, ���������, ���)" ����������="1492" �����������="30.04.2024"/>
		</��������>
		<����������>
			<������� ������="1" �������="�������� �������������� ������������ �� ������ 2024 �." ����_���="796" ������="1.00" �������="270.00" �����������="270.00" �����="��� ���" ����������="270.00">
				<�����>
					<��������>��� ������</��������>
				</�����>
				<������>
					<������><?php

				if(ArrayHelper::getValue($model, 'organization.nds') && $model->fio != '���� ��������'){
					if($model->fio != '�������������' || true){
						echo '20%';
					} else {
						echo "0%";
					}
				} else {
					echo '��� ���';
				}

			?></������>
				</������>
				<���������� ��������="3" ������="00-00000042" ���������="��"/>
			</�������>
			<������� ������="2" �������="�������� �������������� ������������ �� ������ 2024 �." ����_���="796" ������="51.00" �������="350.00" �����������="17850.00" �����="��� ���" ����������="17850.00">
				<�����>
					<��������>��� ������</��������>
				</�����>
				<������>
					<������><?php

				if(ArrayHelper::getValue($model, 'organization.nds') && $model->fio != '���� ��������'){
					if($model->fio != '�������������' || true){
						echo '20%';						
					} else {
						echo "0%";
					}
				} else {
					echo '��� ���';
				}

			?></������>
				</������>
				<���������� ��������="3" ������="00-00000042" ���������="��"/>
			</�������>
			<������� ������="3" �������="�������� �������������� ������������ �� ������ 2024 �." ����_���="796" ������="4.00" �������="390.00" �����������="1560.00" �����="��� ���" ����������="1560.00">
				<�����>
					<��������>��� ������</��������>
				</�����>
				<������>
					<������><?php

				if(ArrayHelper::getValue($model, 'organization.nds') && $model->fio != '���� ��������'){
					if($model->fio != '�������������' || true){
						echo number_format($I28, 2, ",", "");
					} else {
						echo "�";
					}
				} else {
					echo '-';
				}

			?></������>
				</������>
				<���������� ��������="3" ������="00-00000042" ���������="��"/>
			</�������>
			<������� ������="4" �������="�������� �������������� ������������ �� ������ 2024 �." ����_���="796" ������="2.00" �������="400.00" �����������="800.00" �����="��� ���" ����������="800.00">
				<�����>
					<��������>��� ������</��������>
				</�����>
				<������>
					<������><?php

				if(ArrayHelper::getValue($model, 'organization.nds') && $model->fio != '���� ��������'){
					if($model->fio != '�������������' || true){
						echo number_format($I28, 2, ",", "");
					} else {
						echo "�";
					}
				} else {
					echo '-';
				}

			?></������>
				</������>
				<���������� ��������="3" ������="00-00000042" ���������="��"/>
			</�������>
			<�������� ����������������="20480.00" ���������������="20480.00">
				<�����������>
					<������><?php

				if(ArrayHelper::getValue($model, 'organization.nds') && $model->fio != '���� ��������'){
					if($model->fio != '�������������' || true){
						echo number_format($I28, 2, ",", "");
					} else {
						echo "�";
					}
				} else {
					echo '-';
				}

			?></������>
				</�����������>
				<����������>58</����������>
			</��������>
		</����������>
		<���������>
			<����� �������="������ ��������, ������ �����, ������ �������">
				<������ �������="�������" ������="�27-1/12��-2021" �������="27.12.2021"/>
			</�����>
		</���������>
		<��������� �������="2" ������="1" �������="����������� �����������">
			<�� �����="7726458040" �������="��� &quot;���&quot;" �����="����������� ��������">
				<��� �������="�����������" ���="������" ��������="������������"/>
			</��>
		</���������>
	</��������>
</����>