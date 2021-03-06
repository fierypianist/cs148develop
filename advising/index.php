<?php

//##############################################################################
//
// main home page for the site 
// 
//##############################################################################
include "top.php";

// Begin output
print '<article>';
print '<h2>Welcome</h2>';
$columns = 12;
		
	print '<table>';
	$query = file_get_contents("sql/q01.sql");
	//$testquery = $thisDatabaseReader->testquery($query, "", 0, 2, 0,0 , false, false);
	//print $query;
	print '<br>';
	$info2 = $thisDatabaseReader->select($query, "", 0, 2, 0,0 , false, false);
	$fields = array_keys($info2[0]);
	$labels = array_filter($fields, "is_string");
	$numRecords = count($info2);
    $highlight = 0; // used to highlight alternate rows
	print '<br>';
	
	print '<strong>Records: ' . $numRecords . '</strong>';
	foreach ($labels as $label) {
		$camelCase = preg_split('/(?=[A-Z])_/', substr($label, 3));
			$message = '';
		foreach ($camelCase as $one) {
			$message .= $one . " ";
		}
		print '<th>' . $message . '</th>';
	}
    foreach ($info2 as $rec) {
        $highlight++;
        if ($highlight % 2 != 0) {
            $style = ' odd ';
        } else {
            $style = ' even ';
        }
        print '<tr class="' . $style . '">';
        for ($i = 0; $i < $columns; $i++) {
            print '<td>' . $rec[$i] . '</td>';
        }
        print '</tr>';
    }

    // all done
    print '</table>';
print '</article>';
?>
<?php 

$totalCredits = 0;
$totalCreditsSemester = 0;
$semester = " ";
$year = " ";
foreach($info2 as $row){
	$year = $row["pmkYear"];
	if($row["pmkTerm"] != $semester or $semester == " "){ //if the semester is new or we havent started yet
		if($semester != " "){ 	// if the semester is not new
			print "</ol>";
			print "<br>";
			print "<p><strong>Credits: " . $totalCreditsSemester . "</strong></p>";
			$totalCreditsSemester = 0;
			print "</section>";
		}
		print "<section class='termSection" . $row["pmkTerm"] . "'>";
		print "<h3 class='termAndYear'>". $row["pmkTerm"] . " " . $row["pmkYear"] . "</h3><br>";
		print "<ol>";
	}
	print "<li>";
	print $row["fldDepartment"] . " " . $row["fldCourseNumber"];
	print "</li>";
	if(!empty($row["pmkTerm"])){ 
		$semester = $row["pmkTerm"];
	}
	$totalCreditsSemester = $totalCreditsSemester + $row["fldCredits"];
	$totalCredits = $totalCredits + $row["fldCredits"];
}
print "</ol>";
print "<br>";
print "<p><strong>Credits: " . $totalCreditsSemester . "</strong></p>";
print "</section>";
print "<br>";
print "<section class='finalSec'>";
print "<p><strong>Total Credits: " . $totalCredits . "</strong></p>";
print "</section>";
?>

<?php
print "<br>";
include "footer.php";
?>