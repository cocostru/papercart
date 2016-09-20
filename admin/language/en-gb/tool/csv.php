<?php
/**
 * @total-module	CSV Import/Export
 * @author-name 	◘ Dotbox Creative
 * @copyright		Copyright (C) 2014 ◘ Dotbox Creative www.dotboxcreative.com
 * @license			GNU/GPL, see http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt
 */
// Heading
$_['heading_title']    = 'CSV Import/Export';
$_['text_module']      = 'Modules';
// Text
$_['text_success']     = 'Success: You have successfully imported your csv file!';
$_['text_success_erase']     = 'Success: You have successfully cleared %s table!';
$_['text_semi_colon']     = '&nbsp;&nbsp;&nbsp;  ;  ';
$_['text_comma']     = '&nbsp;&nbsp;&nbsp;  ,  ';

// Tabs
$_['tab_csv']            = 'CSV Import/Export';
$_['tab_truncate']       = 'Erase Tables';
// Entry
$_['entry_export']       = 'Export:';
$_['entry_import']       = 'Import:';
$_['entry_export_info']  = 'Choose which data should be exported. The structure is pulled from database.';
$_['entry_import_info']  = 'Choose file to import data. Use the same data structure for the heading as export. Best to use exported data -> modify them and import them back. ';

$_['entry_erase']       = 'Select:';
$_['entry_erase_info']       = 'Choose which sql table should be cleared in database. Table will be empty after procedure.';

$_['entry_separator']    = 'Separator:';
$_['entry_separator_help']     = 'Enter the separator for the spreadsheet';
$_['entry_from_record']    = 'From:';
$_['entry_from_record_help']     = 'Enter the number of row FROM which the export starts. </br></br>0 is from beginning';
$_['entry_number_of_record']    = 'Number of Records:';
$_['entry_number_of_record_help']     = 'Enter the number of HOW MANY rows are exported.</br></br>0 is all or till the end';

$_['entry_backup_reminder']       = 'Just in case, <b>do not</b> forget to back-up your database.';
// Buttons
$_['button_export']     = 'Export';
$_['button_import']     = 'Import';
$_['button_export_info']     = 'Export CSV data';
$_['button_import_info']     = 'Import CSV data';

$_['button_erase']          = 'Erase';
$_['button_erase_info']     = 'Erase/Truncate all data from selected table';
$_['info_erase']     = 'Be very careful as deleting table data will delete all the data from that table which could cause to breaking the store. It is always advised to double-check you procedure. Always backup your database before processing. ';

// Error
$_['error_permission'] = 'Warning: You do not have permission to modify CSV Import/Export!';
$_['error_empty']      = 'Warning: The file you uploaded was empty!';
$_['error_permission_erase']      = 'Warning: You do not have permission to clear tables in database';

?>