<?php
class ModelToolCSV extends Model {
	public function getTables() {
		$table_data = array();

		$query = $this->db->query("SHOW TABLES FROM `" . DB_DATABASE . "`");

		foreach ($query->rows as $result) {
			$table_data[] = $result['Tables_in_' . DB_DATABASE];
		}

		return $table_data;
	}

	public function getTableNames() {
		$table_data = array();

		$query = $this->db->query("SHOW TABLES FROM `" . DB_DATABASE . "`");

		foreach ($query->rows as $result) {
			$table_data[] = str_replace(DB_PREFIX,"",$result['Tables_in_' . DB_DATABASE]);
		}

		return $table_data;
	}

	public function csvExport($table,$from_record,$number_of_records,$separator) {
		if ($number_of_records == 0 || $number_of_records == ""){$number_of_records = "99999999999999";};
		if ($from_record == ""){$from_record = "0";};

		$limit = " limit " . $from_record . ", " . $number_of_records;

		$output 	= '';
		$query 		= "SELECT * FROM `" . $table . "`" . $limit;
	    $result 	= $this->db->query($query);
	    $columns 	= array_keys($result->row);

		$csv_terminated = "\n";
	    $csv_separator = $separator;
	    $csv_enclosed = '"';
	    $csv_escaped = "\\";
		$csv_escaped = '"';

	 	$output .= '"' . $table . '.' . stripslashes(implode('"'.$csv_separator.'"' . $table . '.',$columns)) . "\"\n";

	    foreach ($result->rows as $row) {
			$schema_insert = '';
			$fields_cnt = count($row);
			foreach ($row as $k => $v) {
		        if ($row[$k] == '0' || $row[$k] != '') {
		            if ($csv_enclosed == '') {
		                $schema_insert .= $row[$k];
		            } else {
		            	$row[$k] = str_replace(array("\r","\n","\t"), "", $row[$k]);
		            	$row[$k] = html_entity_decode($row[$k], ENT_COMPAT, "utf-8");
		                $schema_insert .= $csv_enclosed . str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $row[$k]) . $csv_enclosed;
		            }
		        } else {
		            $schema_insert .= '';
		        }

				if ($k < $fields_cnt - 1) {
		            $schema_insert .= $csv_separator;
		        }
		    }

		    $output .= $schema_insert;
		    $output .= $csv_terminated;

	    }
		//$BOM = "\357\273\277 ";
		//$BOM = " \xef\xbb\xbf ";
		//$BOM = "\xEF\xBB\xBF"; // UTF-8 BOM
		$BOM = chr(239) . chr(187) . chr(191);
		$BOM2 = chr(0xEF).chr(0xBB).chr(0xBF);//BOM



		//$output =  mb_convert_encoding($output, 'UTF-16LE', 'UTF-8');
		//$output =  $BOM . $output  ;
		//$encoded_csv = mb_convert_encoding($csv, 'UTF-16LE', 'UTF-8');

	    return $output;
	}

	public function csvImport($file) {

		ini_set('memory_limit','512M');
		ini_set('post_max_size','16M');
		ini_set('upload_max_filesize','16M');


		ini_set('max_execution_time', 999999);

	    $handle = fopen($file,'r');
	    if(!$handle) die('Cannot open uploaded file.');

		//// first chars checker
		// Read 100 characters starting from the 0st character
		$char_check = file_get_contents($file,NULL,NULL,0,100);

		if (strpos($char_check,',') !== false) {
			$separator = ",";
		} else {
			$separator = ";";
		}
	  	////

		$columns = array();
		$data = fgetcsv($handle, 0, $separator);
		if (!$data[0]) {
			$data = fgetcsv($handle, 0, $separator);
		}

		foreach ($data as $d) {
			if (strpos($d, '.') !== false) {
				$tmp = explode('.', $d);

				$table = $tmp[0];

				$badstring = $tmp[1];


				//$data_sql = $tmp[0]  . "   " .  $tmp[1] . "\r\n";
				//file_put_contents('testdb.txt', $data_sql, FILE_APPEND);

				$goodstring = str_replace('"', '', $badstring);

				$columns[] =  "`" . $goodstring . "`";
			} else {

				//$d = utf8_encode($d);
			//	$d = iconv("ISO-8859-1", "UTF-8", $d);

				$columns[] = "`" . $d . "`";
			}

		}

		// gett the file prefix
		$current_tables = $this->getTableNames(); // run the query and assign the result to $result



		foreach ($current_tables as $value) {

			$find_string = strpos($table, $value);
			$file_prefix = '';
			if ($find_string != false) {
				$file_prefix = substr($table,0,$find_string);

				if ($value == "product_to_store") {
					break;
				}
			}

			//$data_sql =  "   " . $value  . "   " .  $find_string . "\r\n";
			//file_put_contents('testdb.txt', $data_sql, FILE_APPEND);
		}

		//$data_sql =  "   " . $file_prefix  . "   " .  $table . "\r\n";
		//file_put_contents('testdb.txt', $data_sql, FILE_APPEND);

		// correct the table prefix
		$table = str_replace($file_prefix, DB_PREFIX, $table);

		if (!$table) {
			exit('Could not retrieve table.  You are probably importing wrong file type.');
		}

	    $row_count = 0;
        $sql_query_array = array();

	    while (($data = fgetcsv($handle, 0, $separator)) !== FALSE) {

			if (count($data) > count($columns)) {
				$data = array_slice($data, 0, count($columns));
			}
	        $row_count++;
	        $pattern = '/\A\d{1,2}\/\d{1,2}\/\d{4}/';
	        $pattern2 = '/\A\d{1,2}\-\d{1,2}\-\d{4}/';
	        foreach($data as $key=>$value) {
	        	$matches = '';
	        	$test = preg_match_all($pattern, $value, $matches);
	        	$test2 = preg_match_all($pattern2, $value, $matches);
	        	if ($test || $test2) {
					$value = date("Y-m-d", strtotime($value));
					$data[$key] = "DATE('" . $this->db->escape($value) . "')";
	        	} else {

	        		//$escaped_value = utf8_encode($this->db->escape($value));
	        		$escaped_value = $this->db->escape($value);
	            	$data[$key] = "'" . $escaped_value . "'";


	            	//$data_sql = $escaped_value  . "\r\n";
		    	//file_put_contents('testdb.txt', $data_sql, FILE_APPEND);
				}
	        }

            if (count($data) < count($columns)) {
                while (count($data) < count($columns)){
                    $key++;
                    $data[$key] = "''";
                }
            }


            //REPLACE INTO
            //$sql_query_array[] = "REPLACE INTO " . $table . " (". implode(',',$columns) .") VALUES (" . htmlentities(implode(",",$data)) . " )";

            //INSERT INTO ... ON DUPLICATE KEY UPDATE
            $dum_text = "INSERT INTO " . $table . " (". implode(',',$columns) .") VALUES (" . htmlentities(implode(",",$data)) . " ) ON DUPLICATE KEY UPDATE ";

            $data_counter = 0;
            $array_size = count($columns);
            foreach ($columns as $key =>$value) {
            	$dum_text .=  $value . "=" . $data[$data_counter];
            	if ($data_counter < $array_size-1) {
            		$dum_text .= ", ";
            	}
            	$data_counter++;
            }
            $dum_text .= ";";

            $sql_query_array[] = $dum_text;

	    }

	    fclose($handle);

        if(count($sql_query_array)) {
			//$this->db->query("TRUNCATE TABLE " . $table);
            foreach($sql_query_array as $sql_query) {
                $this->db->query($sql_query);
            }
			$this->cache->delete('product');
		}
	    return $row_count;
	}

	public function csvErase($table) {
		$query = $this->db->query("TRUNCATE TABLE `" . $table . "`");

		$output =  $table;
	    return $output;
	}

	function validDate($date){
		$date = strtr($date,'/','-');
		$datearr = explode('-', $date);
		if(count($datearr) == 3){
			list($d, $m, $y) = $datearr;
			if(checkdate($m, $d, $y) && strtotime("$y-$m-$d") && preg_match('#\b\d{2}[/-]\d{2}[/-]\d{4}\b#', "$d-$m-$y")) {
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
		return FALSE;
	}
}
?>
