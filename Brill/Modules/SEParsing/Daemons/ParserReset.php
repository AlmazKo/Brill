<?php
/* 
 * ���������� �������� ����������
 */

require_once 'Queries.php';
$sql = Queries::getSql('RESET_PARSER');
DB::query($sql);
echo '����� ��������';