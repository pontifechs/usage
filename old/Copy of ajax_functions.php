<?php
require "db.php";
include 'common.php';


switch ($_GET['function']) {
    case 'deleteAddress':
    	if (mysql_query("delete from log_email_address where logEmailAddressID = " . $_GET['addressID'])){
    		echo "Address has been deleted";
    	}else{
    		echo "Error processing your request - please contact support!";
    	}
        break;

    case 'addAddress':
    	if (mysql_query("insert into log_email_address (emailAddress) values ('" . $_GET['emailAddress'] . "');")){
    		echo "Address has been added";
    	}else{
    		echo "Error processing your request - please contact support!";
    	}
        break;

    case 'getAddressTable':
		$result = mysql_query("select * from log_email_address order by logEmailAddressID;");
		$addressTable='<table border=0>';
		$i=1;

		while ($row = mysql_fetch_assoc($result)) {
			$addressTable.="<tr><td>Address " . $i . ": " . $row['emailAddress'] . "</td>";
			$addressTable.="<td><a href='javascript:deleteAddress(" . $row['logEmailAddressID'] . ")';>delete this address</a></td></tr>";
			$i++;

		}

		$addressTable.='</table>';
		mysql_free_result($result);

		echo $addressTable;
        break;

    case 'updateOutlier':
    	$outlierID = $_GET['outlierID'];
    	$overageCount = $_GET['overageCount'];
    	$overagePercent = $_GET['overagePercent'];
    	$displayColor = $_GET['color'];

		if (mysql_query("update outlier set overageCount = '" . $overageCount . "', overagePercent='" . $overagePercent . "', color='" . $displayColor . "' where outlierID='" . $outlierID . "';")){
    		echo "Outlier has been updated";
    	}else{
    		echo "Error processing your request - please verify data looks correct and contact support!";
    	}

        break;

    case 'getOutlierData':
		$result = mysql_query("select * from outlier order by outlierID;");

		$outlierData='';
		while ($row = mysql_fetch_assoc($result)) {
			$outlierData.="Level " . $row['outlierLevel'] . ": " . $row['overageCount'] . " over plus " .  $row['overagePercent'] . "% over - displayed " . $row['color'] . "<br />";
		}


		echo $outlierData;
        break;

    case 'addInterface':
    	$notes = str_replace("'","''",$_GET['interfaceNotes']);
    	if (mysql_query("insert into platform_interface (platformID, startYear, endYear, counterCompliantInd, notCounterCompliantInd, HTMLMultiplicationInd, interfaceNotes)
    		values ('" . $_GET['platformID'] . "', '" . $_GET['startYear'] . "', '" . $_GET['endYear'] . "', '" . $_GET['counterCompliantInd'] . "', '" . $_GET['notCounterCompliantInd'] . "', '" . $_GET['HTMLMultiplicationInd'] . "', '" . $notes . "');")){

    		echo "New Interface Notes have been added";
    	}else{
    		echo "Error processing your request - please contact support!";
    	}
        break;
    case 'updateInterface':
    	$notes = str_replace("'","''",$_GET['interfaceNotes']);
    	if (mysql_query("update platform_interface set startYear='" . $_GET['startYear'] . "',
    		endYear='" . $_GET['endYear'] . "',
    		counterCompliantInd='" . $_GET['counterCompliantInd'] . "',
    		notCounterCompliantInd='" . $_GET['notCounterCompliantInd'] . "',
    		HTMLMultiplicationInd='" . $_GET['HTMLMultiplicationInd'] . "',
    		interfaceNotes='" . $notes . "' where platformInterfaceID = '" . $_GET['platformInterfaceID']. "';")){

    		echo "Interface Notes have been updated";
    	}else{
    		echo "Error processing your request - please contact support!";
    	}
        break;

    case 'getAddInterfaceTable':
		$table = "
			<h3>Add New Interface Record</h3>
			<table border=\"1\">
			<tr>
			<th>Start Year</th>
			<th>End Year</th>
			<th>Counter<br />Compliant?</th>
			<th>Not Counter<br />Compliant?</th>
			<th>Multiplies<br />HTML?</th>
			<th>Interface Notes</th>
			<th>&nbsp;</th>
			</tr>
			<tr>
			<td><input name='startYear' id='startYear' type='text' size='10' value='' /></td>
			<td><input name='endYear' id='endYear' type='text' size='10' value='' /></td>
			<td align='center'><input type='checkbox' name='counterCompliantInd' id='counterCompliantInd' /></td>
			<td align='center'><input type='checkbox' name='notCounterCompliantInd' id='notCounterCompliantInd' /></td>
			<td align='center'><input type='checkbox' name='HTMLMultiplicationInd' id='HTMLMultiplicationInd' /></td>
			<td><textarea name='interfaceNotes' id='interfaceNotes' cols='35' rows='2'></textarea></td>
			<td><a href='javascript:addInterface();'>add</a></td>
			</tr>
		   </table>
		   <a href=\"javascript:toggleLayer('div_interface_add','none');javascript:toggleLayer('div_interface_add_prompt','block');\">Click to hide</a>";

		echo $table;
        break;

    case 'deleteInterface':
    	if (mysql_query("delete from platform_interface where platformInterfaceID = " . $_GET['interfaceID'])){
    		echo "Interface Notes have been deleted";
    	}else{
    		echo "Error processing your request - please contact support!";
    	}
        break;


    case 'getInterfaceTable':
		$platformID = $_GET['platformID'];

		$table = "
		<table border=\"1\">
		<tr>
		<th>Start Year</th>
		<th>End Year</th>
		<th>Counter<br />Compliant?</th>
		<th>Not Counter<br />Compliant?</th>
		<th>Multiplies<br />HTML?</th>
		<th>Interface Notes</th>
		<th>&nbsp;</th>
		</tr>";

		$result = mysql_query("select * from platform_interface where platformID='" . $_GET['platformID'] . "';");

		while ($row = mysql_fetch_assoc($result)) {
			if ($row['counterCompliantInd'] == "1") $counterCompliantInd = 'checked'; else $counterCompliantInd = '';
			if ($row['notCounterCompliantInd'] == "1") $notCounterCompliantInd = 'checked'; else $notCounterCompliantInd = '';
			if ($row['HTMLMultiplicationInd'] == "1") $HTMLMultiplicationInd = 'checked'; else $HTMLMultiplicationInd = '';

			$table.= "<tr>";
			$table.= "<td><input name='startYear_" . $row['platformInterfaceID'] . "' id='startYear_" . $row['platformInterfaceID'] . "' type='text' size='10' value='" . $row['startYear'] . "' /></td>";
			$table.= "<td><input name='endYear_" . $row['platformInterfaceID'] . "' id='endYear_" . $row['platformInterfaceID'] . "' type='text' size='10' value='" . $row['endYear'] . "' /></td>";
			$table.= "<td align='center'><input type='checkbox' name='counterCompliantInd_" . $row['platformInterfaceID'] . "' id='counterCompliantInd_" . $row['platformInterfaceID'] . "' $counterCompliantInd /></td>";
			$table.= "<td align='center'><input type='checkbox' name='notCounterCompliantInd_" . $row['platformInterfaceID'] . "' id='notCounterCompliantInd_" . $row['platformInterfaceID'] . "' $notCounterCompliantInd /></td>";
			$table.= "<td align='center'><input type='checkbox' name='HTMLMultiplicationInd_" . $row['platformInterfaceID'] . "' id='HTMLMultiplicationInd_" . $row['platformInterfaceID'] . "' $HTMLMultiplicationInd /></td>";
			$table.= "<td><textarea name='interfaceNotes_" . $row['platformInterfaceID'] . "' id='interfaceNotes_" . $row['platformInterfaceID'] . "' cols='35' rows='2'>" . $row['interfaceNotes'] . "</textarea></td>";
			$table.= "<td><a href='javascript:updateInterface(" . $row['platformInterfaceID'] . ");'>update</a><br /><a href='javascript:deleteInterface(" . $row['platformInterfaceID'] . ");'>delete</a></td>";
			$table.= "</tr>";

		}
		mysql_free_result($result);
		$table .= "</table>";

		echo $table;
        break;




    case 'addLogin':
    	$platformID = $_GET['platformID'];
		$publisherPlatformID = $_GET['publisherPlatformID'];
    	$notes = str_replace ("'","''",$_GET['notes']);

    	if (mysql_query("insert into interface_login (platformID, publisherPlatformID, loginID, password, url, notes)
    		values ('" . $_GET['platformID'] . "', '" . $_GET['publisherPlatformID'] . "', '" . $_GET['loginID'] . "', '" . $_GET['password'] . "', '" . $_GET['url'] . "', '" . $notes . "');")){

    		echo "New Login Notes have been added";
    	}else{
    		echo "Error processing your request - please contact support!";
    	}
        break;
    case 'updateLogin':
    	$notes = str_replace ("'","''",$_GET['notes']);
    	if (mysql_query("update interface_login set loginID='" . $_GET['loginID'] . "',
    		password='" . $_GET['password'] . "',
    		url='" . $_GET['url'] . "',
    		notes='" . $notes . "'
    		where interfaceLoginID = '" . $_GET['interfaceLoginID']. "';")){

    		echo "Login Notes have been updated";
    	}else{
    		echo "Error processing your request - please contact support!";
    	}
        break;

    case 'getAddLoginTable':
		?>

		<h3>Add New Login Record</h3>
		<table border="1">
	    <tr>
	    <th>Interface Login</th>
	    <th>Password</th>
	    <th>URL</th>
	    <th>Login Notes</th>
	    <th>&nbsp;</th>
	    </tr>
		<tr>
		<td><input name='loginID' id='loginID' type='text' size='10' value='' /></td>
		<td><input name='password' id='password' type='text' size='10' value='' /></td>
		<td><input name='url' id='url' type='text' size='40' value='' /></td>
		<td><textarea name='loginNotes' id='loginNotes' cols='35' rows='2'></textarea></td>
		<td><a href='javascript:addLogin();'>add</a></td>
		</tr>
	    </table>
   		<a href="javascript:toggleLayer('div_login_add','none');javascript:toggleLayer('div_login_add_prompt','block');">Click to hide</a>
   <?php
        break;

    case 'deleteLogin':
    	if (mysql_query("delete from interface_login where interfaceLoginID = " . $_GET['interfaceLoginID'])){
    		echo "Login Notes have been deleted";
    	}else{
    		echo "Error processing your request - please contact support!";
    	}
        break;


    case 'getLoginTable':
		$platformID = $_GET['platformID'];
		$publisherPlatformID = $_GET['publisherPlatformID'];

		if ($platformID) {
			$result = mysql_query("select * from interface_login where platformID='" . $platformID . "';");
		}else{
			$result = mysql_query("select * from interface_login where publisherPlatformID='" . $publisherPlatformID . "';");
		}

		$currentRows = mysql_num_rows($result);

		if ($currentRows > 0){
		?>

		<table border="1">
		<tr>
		<th>Interface Login</th>
		<th>Password</th>
		<th>URL</th>
		<th>Login Notes</th>
		<th>&nbsp;</th>
		</tr>

		<?php

		while ($row = mysql_fetch_assoc($result)) {
			echo "<tr>";
			echo "<td><input name='loginID_" . $row['interfaceLoginID'] . "' id='loginID_" . $row['interfaceLoginID'] . "' type='text' size='10' value='" . $row['loginID'] . "' /></td>";
			echo "<td><input name='password_" . $row['interfaceLoginID'] . "' id='password_" . $row['interfaceLoginID'] . "' type='text' size='10' value='" . $row['password'] . "' /></td>";
			echo "<td><input name='url_" . $row['interfaceLoginID'] . "' id='url_" . $row['interfaceLoginID'] . "' type='text' size='40' value='" . $row['url'] . "' /></td>";
			echo "<td><textarea name='loginNotes_" . $row['interfaceLoginID'] . "' id='loginNotes_" . $row['interfaceLoginID'] . "' cols='35' rows='2'>" . $row['notes'] . "</textarea></td>";
			echo "<td><a href='javascript:updateLogin(" . $row['interfaceLoginID'] . ");'>update</a><br /><a href='javascript:deleteLogin(" . $row['interfaceLoginID'] . ");'>delete</a></td>";
			echo "</tr>";

		}
		mysql_free_result($result);

		?>
		</table>

		<?php
		}else{
			echo "None Found";
		}


        break;




    case 'addNotes':
    	if (mysql_query("insert into publisher_notes (publisherPlatformID, startYear, endYear, notes)
    		values ('" . $_GET['publisherPlatformID'] . "', '" . $_GET['startYear'] . "', '" . $_GET['endYear'] . "', '" . $_GET['notes'] . "');")){

    		echo "New Notes have been added";
    	}else{
    		echo "Error processing your request - please contact support!";
    	}
        break;
    case 'updateNotes':
    	if (mysql_query("update publisher_notes set startYear='" . $_GET['startYear'] . "',
    		endYear='" . $_GET['endYear'] . "',
    		notes='" . $_GET['notes'] . "' where publisherNotesID = '" . $_GET['publisherNotesID']. "';")){

    		echo "Notes have been updated";
    	}else{
    		echo "Error processing your request - please contact support!";
    	}
        break;


    case 'deleteNotes':
    	if (mysql_query("delete from publisher_notes where publisherNotesID = " . $_GET['notesID'])){
    		echo "Notes have been deleted";
    	}else{
    		echo "Error processing your request - please contact support!";
    	}
        break;


    case 'getAddNotesTable':
		$table = "
			<h3>Add New Notes</h3>
			<table border=\"1\">
			<tr>
			<th>Start Year</th>
			<th>End Year</th>
			<th>Notes</th>
			<th>&nbsp;</th>
			</tr>
			<tr>
			<td><input name='startYear' id='startYear' type='text' size='10' value='' /></td>
			<td><input name='endYear' id='endYear' type='text' size='10' value='' /></td>
			<td><textarea name='notes' id='notes' cols='35' rows='2'></textarea></td>
			<td><a href='javascript:addNotes();'>add</a></td>
			</tr>
		   </table>
		   <a href=\"javascript:toggleLayer('div_notes_add','none');javascript:toggleLayer('div_notes_add_prompt','block');\">Click to hide</a>";

		echo $table;
        break;


    case 'getNotesTable':
		$publisherPlatformID = $_GET['publisherPlatformID'];

		$table = "
		<table border=\"1\">
		<tr>
		<th>Start Year</th>
		<th>End Year</th>
		<th>Notes</th>
		<th>&nbsp;</th>
		</tr>";

		$result = mysql_query("select * from publisher_notes where publisherPlatformID='" . $_GET['publisherPlatformID'] . "';");

		while ($row = mysql_fetch_assoc($result)) {
			$table.= "<tr>";
			$table.= "<td><input name='startYear_" . $row['publisherNotesID'] . "' id='startYear_" . $row['publisherNotesID'] . "' type='text' size='10' value='" . $row['startYear'] . "' /></td>";
			$table.= "<td><input name='endYear_" . $row['publisherNotesID'] . "' id='endYear_" . $row['publisherNotesID'] . "' type='text' size='10' value='" . $row['endYear'] . "' /></td>";
			$table.= "<td><textarea name='notes_" . $row['publisherNotesID'] . "' id='notes_" . $row['publisherNotesID'] . "' cols='35' rows='2'>" . $row['notes'] . "</textarea></td>";
			$table.= "<td><a href='javascript:updateNotes(" . $row['publisherNotesID'] . ");'>update</a><br /><a href='javascript:deleteNotes(" . $row['publisherNotesID'] . ");'>delete</a></td>";
			$table.= "</tr>";

		}
		mysql_free_result($result);
		$table .= "</table>";

		echo $table;
        break;



    case 'deleteMonth':

    	if ($_GET['publisherPlatformID']){
			if (mysql_query("delete from title_stats_monthly where publisherPlatformID = '" . $_GET['publisherPlatformID'] . "' and year = '"  . $_GET['year'] . "' and month = '" . $_GET['month'] . "' and archiveInd = '" . $_GET['archiveInd'] . "';")){
				echo "Month for publisher has been deleted";
			}else{
				echo "Error processing your request - please contact support!";
			}
		}else{
			if (mysql_query("delete from title_stats_monthly where publisherPlatformID in (select publisherPlatformID from publisher_platform where platformID = '" . $_GET['platformID'] . "') and year = '"  . $_GET['year'] . "' and month = '" . $_GET['month'] . "' and archiveInd = '" . $_GET['archiveInd'] . "';")){
				echo "Month for entire platform has been deleted";
			}else{
				echo "Error processing your request - please contact support!";
			}

		}
        break;



    case 'updateOverride':
    	if (mysql_query("update title_stats_monthly set overrideUsageCount = '" . $_GET['overrideUsageCount'] . "' where titleStatsMonthlyID = '" . $_GET['titleStatsMonthlyID'] . "';")){
    		echo "Override Usage Count has been updated";
    	}else{
    		echo "Error processing your request - please contact support!";
    	}
        break;


    case 'ignoreOutlier':
    	if (mysql_query("update title_stats_monthly set ignoreOutlierInd = '1' where titleStatsMonthlyID = '" . $_GET['titleStatsMonthlyID'] . "';")){
    		echo "Outlier flag has been removed";
    	}else{
    		echo "Error processing your request - please contact support!";
    	}
        break;


    case 'removeOutlier':
    	if (mysql_query("update title_stats_monthly set outlierID = '0' where titleStatsMonthlyID = '" . $_GET['titleStatsMonthlyID'] . "';")){
    		echo "Outlier flag has been removed";
    	}else{
    		echo "Error processing your request - please contact support!";
    	}
        break;

    case 'updateYTDOverride':
    	if (mysql_query("update title_stats_ytd set " . $_GET['overrideColumn'] . " = '" . $_GET['overrideCount'] . "' where titleStatsYTDID = '" . $_GET['titleStatsYTDID'] . "';")){
    		echo "Override Count has been updated";
    	}else{
    		echo "Error processing your request - please contact support!";
    	}
        break;



    case 'getOutliersTable':
		$publisherPlatformID = $_GET['publisherPlatformID'];
		$platformID = $_GET['platformID'];
		$archiveInd = $_GET['archiveInd'];
		$year = $_GET['year'];
		$month = $_GET['month'];

		if ($publisherPlatformID) {
			$query = "select titleStatsMonthlyID, title, archiveInd, usageCount, overrideUsageCount, color
				from title_stats_monthly tsm, title t, outlier o
				where tsm.titleID = t.titleID
				and o.outlierID = tsm.outlierID
				and publisherPlatformID='" . $publisherPlatformID . "'
				and archiveInd='" . $archiveInd . "'
				and year='" . $year . "'
				and month='" . $month . "' and ignoreOutlierInd = 0;";
		}else{
			$query = "select titleStatsMonthlyID, title, archiveInd, usageCount, overrideUsageCount, color
				from title_stats_monthly tsm, title t, outlier o, publisher_platform pp
				where tsm.titleID = t.titleID
				and o.outlierID = tsm.outlierID
				and pp.publisherPlatformID = tsm.publisherPlatformID
				and platformID='" . $platformID . "'
				and archiveInd='" . $archiveInd . "'
				and year='" . $year . "'
				and month='" . $month . "' and ignoreOutlierInd = 0
				order by 1,2,3;";
		}

		$result = mysql_query($query);


		$totalRows = mysql_num_rows($result);


		echo "<table border='0' style='width:400px'>";

		if ($totalRows == 0){
			echo "<tr><td>None currently</td></tr>";
		}else{
			while ($row = mysql_fetch_assoc($result)) {
				echo "<tr>";
				echo "<td style='width:150px;'>" . $row['title']. "</td>";
				echo "<td style='width:50px;text-align:right;background-color:" . $row['color'] . "'>" . $row['usageCount'] . "</td>";
				echo "<td style='width:100px;'><input type='text' name = 'overrideUsageCount_" . $row['titleStatsMonthlyID'] . "' id = 'overrideUsageCount_" . $row['titleStatsMonthlyID'] . "' value='" . $row['overrideUsageCount'] . "' style='width:50px'></td>";
				echo "<td style='width:50px;'><a href=\"javascript:updateOverride('" . $row['titleStatsMonthlyID'] . "');\">update override</a></td>";
				echo "<td style='width:50px;'><a href=\"javascript:ignoreOutlier('" . $row['titleStatsMonthlyID'] . "');\">ignore outlier</a></td>";
				echo "</tr>";
			}
		}

		echo "</table>";



		mysql_free_result($outlier_result);


        break;




    case 'getOverridesTable':

		$publisherPlatformID  = $_GET['publisherPlatformID'];
		$platformID  = $_GET['platformID'];
		$archiveInd  = $_GET['archiveInd'];
		$year  = $_GET['year'];

		if ($publisherPlatformID) {
			$query = "select distinct titleStatsYTDID, title, totalCount, HTMLCount, PDFCount, overrideTotalCount, overrideHTMLCount, overridePDFCount
			from title_stats_ytd tsy, title_stats_monthly tsm, title t
			where tsy.titleID = t.titleID
			and tsm.publisherPlatformID = tsy.publisherPlatformID
			and tsm.titleID = tsy.titleID
			and tsm.year = tsy.year
			and tsm.archiveInd = tsy.archiveInd
			and tsm.outlierID > 0
			and tsy.publisherPlatformID='" . $publisherPlatformID . "'
			and tsy.archiveInd='" . $archiveInd . "'
			and tsy.year='" . $year . "' and ignoreOutlierInd = 0;";
		}else{
			$query = "select distinct titleStatsYTDID, title, totalCount, HTMLCount, PDFCount, overrideTotalCount, overrideHTMLCount, overridePDFCount
			from title_stats_ytd tsy, title_stats_monthly tsm, title t, publisher_platform pp
			where tsy.titleID = t.titleID
			and tsm.publisherPlatformID = tsy.publisherPlatformID
			and tsm.titleID = tsy.titleID
			and tsm.year = tsy.year
			and tsm.archiveInd = tsy.archiveInd
			and tsm.outlierID > 0
			and pp.publisherPlatformID = tsm.publisherPlatformID
			and pp.platformID='" . $platformID . "'
			and tsy.archiveInd='" . $archiveInd . "'
			and tsy.year='" . $year . "' and ignoreOutlierInd = 0;";
		}


		$result = mysql_query($query);

		?>

		<table border='0' style='width:400px'>

		<?php

		while ($ytd_row = mysql_fetch_assoc($result)) {
		?>
			<tr>
			<td width="149"><?php echo $ytd_row['title']; ?></td>
			<td width="40">Total<td>
			<td width="40" ><?php echo $ytd_row['totalCount']; ?></td>
			<td width="40"><input name="overrideTotalCount_<?php echo $ytd_row['titleStatsYTDID']; ?>" id="overrideTotalCount_<?php echo $ytd_row['titleStatsYTDID']; ?>" type="text"value="<?php echo $ytd_row['overrideTotalCount']; ?>" size="6" maxlength="6"/></td>
			<td width="40"><a href="javascript:updateYTDOverride('<?php echo $ytd_row['titleStatsYTDID']; ?>', 'overrideTotalCount')">update</a></td>
			</tr>
			<tr>
			<td width="149">&nbsp;</td>
			<td width="40">PDF<td>
			<td width="40"><?php echo $ytd_row['PDFCount']; ?></td>
			<td width="40"><input name="overridePDFCount_<?php echo $ytd_row['titleStatsYTDID']; ?>" id="overridePDFCount_<?php echo $ytd_row['titleStatsYTDID']; ?>" type="text"value="<?php echo $ytd_row['overridePDFCount']; ?>" size="6" maxlength="6"/></td>
			<td width="40"><a href="javascript:updateYTDOverride('<?php echo $ytd_row['titleStatsYTDID']; ?>', 'overridePDFCount')">update</a></td>
			</tr>
			<tr>
			<td width="149">&nbsp;</td>
			<td width="40">HTML<td>
			<td width="40"><?php echo $ytd_row['HTMLCount']; ?></td>
			<td width="40"><input name="overrideHTMLCount_<?php echo $ytd_row['titleStatsYTDID']; ?>" id="overrideHTMLCount_<?php echo $ytd_row['titleStatsYTDID']; ?>" type="text"value="<?php echo $ytd_row['overrideHTMLCount']; ?>" size="6" maxlength="6"/></td>
			<td width="40"><a href="javascript:updateYTDOverride('<?php echo $ytd_row['titleStatsYTDID']; ?>', 'overrideHTMLCount')">update</a></td>
			</tr>
		<?php

		}
		mysql_free_result($ytd_result);

		?>

		</table>

		<?php


        break;


	case 'getStatsTable':
		?>
		<table border="0" style="width:700px">
		<?php

		$publisherPlatformID = $_GET['publisherPlatformID'];
		$platformID = $_GET['platformID'];

		if ($publisherPlatformID){
			$result = mysql_query("select distinct year, month, archiveInd from title_stats_monthly where publisherPlatformID='" . $publisherPlatformID . "' order by year, archiveInd, month;");
		}else{
			$result = mysql_query("select distinct year, month, archiveInd from title_stats_monthly tsm, publisher_platform pp where pp.publisherPlatformID = tsm.publisherPlatformID and pp.platformID = '" . $platformID . "' order by year, archiveInd, month;");
		}

		$totalRows = mysql_num_rows($result);
		$currentRow=1;

		while ($row = mysql_fetch_assoc($result)) {
			if ($row['archiveInd'] == "1") {$archive = 'Archive';}else{$archive='';}
			echo "<tr>";
			echo "<td style='width:80px'><b>" . numberToMonth($row['month']) . " " . $row['year'] . "</b><br />" . $archive . "</td>";
			echo "<td style='width:200px'><a href=\"javascript:deleteMonth('" . $row['month'] . "','" . $row['year'] . "','" . $row['archiveInd'] . "')\">delete entire month</a></td>";

			//monthly ouliers
			if ($publisherPlatformID){
				$query = "select titleStatsMonthlyID, title, archiveInd, usageCount, overrideUsageCount, color
				from title_stats_monthly tsm, title t, outlier o
				where tsm.titleID = t.titleID and o.outlierID = tsm.outlierID and publisherPlatformID='" . $publisherPlatformID . "'
				and archiveInd='" . $row['archiveInd'] . "'	and year='" . $row['year'] . "'	and month='" . $row['month'] . "';";
			}else{
				$query = "select titleStatsMonthlyID, title, archiveInd, usageCount, overrideUsageCount, color
				from title_stats_monthly tsm, title t, outlier o, publisher_platform pp
				where tsm.publisherPlatformID = pp.publisherPlatformID and tsm.titleID = t.titleID and o.outlierID = tsm.outlierID and platformID='" . $platformID . "'
				and archiveInd='" . $row['archiveInd'] . "'	and year='" . $row['year'] . "'	and month='" . $row['month'] . "';";

			}
			$outlier_result = mysql_query($query);

			if (mysql_num_rows($outlier_result) != 0) {
				echo "<td style='width:200px'><a href=\"javascript:popUp('outliers.php?publisherPlatformID=" . $publisherPlatformID . "&platformID=" . $platformID . "&archiveInd=" . $row['archiveInd'] . "&month=" . $row['month'] . "&year=" . $row['year'] . "');\">view outliers for this month</a></td>";
			}else{
				echo "<td style='width:200px'>&nbsp;</td>";
			}

			echo "</tr>";


			//Print YTD - only prints those titles for which there were outliers
			if (($row['month'] == "12") || ($totalRows == $currentRow)){
				if ($publisherPlatformID){
					$ytd_result = mysql_query("select distinct titleStatsYTDID, title, totalCount, HTMLCount, PDFCount, overrideTotalCount, overrideHTMLCount, overridePDFCount
					from title_stats_ytd tsy, title_stats_monthly tsm, title t
					where tsy.titleID = t.titleID
					and tsm.publisherPlatformID = tsy.publisherPlatformID
					and tsm.titleID = tsy.titleID
					and tsm.year = tsy.year
					and tsm.archiveInd = tsy.archiveInd
					and tsm.outlierID > 0
					and tsy.publisherPlatformID='" . $publisherPlatformID . "'
					and tsy.archiveInd='" . $row['archiveInd'] . "'
					and tsy.year='" . $row['year'] . "' and ignoreOutlierInd = 0;");
				}else{
					$ytd_result = mysql_query("select distinct titleStatsYTDID, title, totalCount, HTMLCount, PDFCount, overrideTotalCount, overrideHTMLCount, overridePDFCount
					from title_stats_ytd tsy, title_stats_monthly tsm, title t, publisher_platform pp
					where tsy.titleID = t.titleID
					and tsm.publisherPlatformID = tsy.publisherPlatformID
					and pp.publisherPlatformID = tsy.publisherPlatformID
					and tsm.titleID = tsy.titleID
					and tsm.year = tsy.year
					and tsm.archiveInd = tsy.archiveInd
					and tsm.outlierID > 0
					and pp.platformID='" . $platformID . "'
					and tsy.archiveInd='" . $row['archiveInd'] . "'
					and tsy.year='" . $row['year'] . "' and ignoreOutlierInd = 0;");
				}
				if (mysql_num_rows($ytd_result) > 0){
					?>
					<tr>
					<td class="ytd"><b>YTD <?php echo $row['year']; ?></b></font></td>
					<td>
					<a href="javascript:popUp('ytd_override.php?publisherPlatformID=<?php echo $publisherPlatformID . "&platformID=" . $platformID . "&archiveInd=" . $row['archiveInd'] . "&year=" . $row['year']; ?>');">update overrides for this year</a>
					</td>
					<td><a target='_blank' href='spreadsheet.php?publisherPlatformID=<?php echo $publisherPlatformID; ?>&platformID=<?php echo $platformID; ?>&year=<?php echo $row['year']; ?>&archiveInd=<?php echo $row['archiveInd']; ?>'>view spreadsheet</a></td>
					</tr>
					<?php
				}else{
					?>
					<tr>
					<td class="ytd"><b>YTD <?php echo $row['year']; ?></b><br /><?php echo $archive; ?></font></td>
					<td>(no outliers found for this year)</td>
					<td><a target='_blank' href='spreadsheet.php?publisherPlatformID=<?php echo $publisherPlatformID; ?>&platformID=<?php echo $platformID; ?>&year=<?php echo $row['year']; ?>&archiveInd=<?php echo $row['archiveInd']; ?>'>view spreadsheet</a></td>
					</tr>
					<tr>
					<td>&nbsp;</td>
					</tr>
					<?php
				}

			}


			$currentRow++;

		}
		mysql_free_result($result);
			?>
			</table>
			<?php

	break;

    case 'addISSN':
    	$ISSN = trim(str_replace ('-','',$_GET['issn']));

		# do SFX ID lookup if Default ISSN Reason is passed in
		if (($_GET['issnReason'] == '0')){
			$result = mysql_query("select SFXID from title where titleID = '" . $_GET['titleID'] . "';");

			while ($row = mysql_fetch_assoc($result)) {
				$SFXID = $row['SFXID'];
			}
			mysql_free_result($result);

			//only update if there isnt an sfx id already
			if ($SFXID == ''){
				$result = mysql_query("select sfx_id from ejl2.sfx_data_new where issn = '" . $ISSN . "';");

				while ($row = mysql_fetch_assoc($result)) {
					$sfx_id = $row['sfx_id'];
				}
				mysql_free_result($result);

				if ($sfx_id){
					if (mysql_query("update title set SFXID= '" . $sfx_id . "' where titleID = '" . $_GET['titleID'] . "';")){
						echo "SFX ID has been updated\n";
					}
				}
			}
		}

    	if (mysql_query("insert into title_issn (titleID, ISSN, ISSNType, ISSNChangeReasonID) values ('" . $_GET['titleID'] . "', '" . $ISSN . "', '" . $_GET['issnType'] . "', '" . $_GET['issnReason'] . "');")){
    		echo "ISSN has been added";
    	}else{
    		echo "Error processing your request - please contact support!";
    	}
        break;

    case 'removeISSN':
    	if (mysql_query("delete from title_issn where titleISSNID = '" . $_GET['titleISSNID'] . "';")){
    		echo "ISSN has been removed";
    	}else{
    		echo "Error processing your request - please contact support!";
    	}
        break;



    case 'getISSNReasonDropDown':
    	$titleID = $_GET['titleID'];
    	$i = $_GET['i'];

		echo "<select name='issn_reason_" . $titleID . "_" . $i . "' id='issn_reason_" . $titleID . "_" . $i . "'>";

		$result = mysql_query("select * from issn_change_reason;");

		while ($row = mysql_fetch_assoc($result)) {
			echo "<option value='" . $row['ISSNChangeReasonID'] . "'>" . $row['reason'] . "</option>";
		}

		echo "</select>";

        break;


	case 'getTitlesTable':

		$publisherPlatformID = $_GET['publisherPlatformID'];
		$platformID = $_GET['platformID'];
		$titleSearch = $_GET['titleSearch'];

		if ($publisherPlatformID) {
			$result = mysql_query("select distinct t.titleID titleID, title, sfxid from title t, title_stats_monthly tsm where tsm.titleID = t.titleID and publisherPlatformID = '" . $publisherPlatformID . "' order by title;");
		}else if ($platformID){
			$result = mysql_query("select distinct t.titleID titleID, title, sfxid from title t, title_stats_monthly tsm, publisher_platform pp where pp.publisherPlatformID = tsm.publisherPlatformID and tsm.titleID = t.titleID and pp.platformID = '" . $platformID . "' order by title;");
		}else{
			$result = mysql_query("select distinct titleID, title, sfxid from title t where (title like '" . $titleSearch . "%' or title like '% " . $titleSearch . " %') order by title;");
		}

		if (mysql_num_rows($result) == '0'){
			if ($publisherPlatformID) {
				echo "No titles found for this publisher / platform combination";
			}else if ($platformID){
				echo "No titles found for this platform";
			}else{
				echo "No titles found for search term.<br /><a href='titles.php'>return to titles page</a>";
			}
		}else{

		?>

		<h3>Associated Titles and ISSNs</h3>

		<div id="div_titles">
		<table border="0" style="width:650px">
		<tr>
		<th>&nbsp;</th>
		<th>ISSN Type</th>
		<th>ISSN Change Reason</th>
		<th>ISSN</th>
		<th>&nbsp;</th>
		</tr>


		<?php

			while ($row = mysql_fetch_assoc($result)) {

				echo "<tr>";
				if ($row['sfxid']) {
					echo "<td style='width:250px'><b>" . $row['title'] . "</b><br /><a href=\"javascript:popUp('relatedTitles.php?titleID=" . $row['titleID'] . "');\">view related titles</a>&nbsp;&nbsp;<a href='http://findtext.library.nd.edu:8889/ndu_local?url_ver=Z39.88-2004&ctx_ver=Z39.88-2004&ctx_enc=info:ofi/enc:UTF-8&rfr_id=info:sid/ND_ejl_loc&url_ctx_fmt=info:ofi/fmt:kev:mtx:ctx&svc_val_fmt=info:ofi/fmt:kev:mtx:sch_svc&sfx.ignore_date_threshold=1&rft.object_id=" . $row['sfxid']  . "' target='_blank'>view in findtext</a></td>";
				}else{
					echo "<td style='width:250px'><b>" . $row['title'] . "</b><br /><a href=\"javascript:popUp('relatedTitles.php?titleID=" . $row['titleID'] . "');\">view related titles</a></td>";
				}


				$issn_result = mysql_query("select titleissnid, issn, issntype, reason from title_issn ti left join issn_change_reason icr on (icr.ISSNChangeReasonID = ti.ISSNChangeReasonID) where ti.titleID = '" . $row['titleID'] . "' order by issnType desc;");

				while ($issn_row = mysql_fetch_assoc($issn_result)) {
					$displayISSN = substr($issn_row['issn'],0,4) . "-" . substr($issn_row['issn'],4,4);

					echo "<td>" . $issn_row['issntype'] . "</td>";
					if ($issn_row['reason']){
						echo "<td>" . $issn_row['reason'] . "</td>";
					}else{
						echo "<td>N/A</td>";
					}
					echo "<td>" . $displayISSN . "</td>";
					echo "<td style='width:150px'><a href='javascript:removeISSN(" . $issn_row['titleissnid'] . ");'>remove this issn</a></td>";
					echo "</tr>";

					echo "<tr>";
					echo "<td>&nbsp;</td>";
				}

				echo "<td colspan='2'><a href=\"javascript:showAddISSN('" .  $row['titleID'] . "');\">add issn</a></td><td colspan='2'>&nbsp;</td></tr>";
				echo "<tr>";
				echo "<td>&nbsp;</td>";
				echo "<td>";
				echo "<div id='div_add_issn_type_" .  $row['titleID'] . "'>";
				echo "</div>";
				echo "</td>";
				echo "<td>";
				echo "<div id='div_add_issn_reason_" .  $row['titleID'] . "'>";
				echo "</div>";
				echo "</td>";
				echo "<td>";
				echo "<div id='div_add_issn_" .  $row['titleID'] . "'>";
				echo "</div>";
				echo "</td>";
				echo "<td>";
				echo "<div id='div_add_issn_prompt_" .  $row['titleID'] . "'>";
				echo "</div>";
				echo "</td>";
				echo "</tr>";


			}
		}
		mysql_free_result($result);
	?>
		</table>
		<?php

	break;



    case 'getPlatformEdit':
    	$platformID = $_GET['platformID'];

		$result = mysql_query("select distinct platform.platformID, platform.name platform, platform.reportDisplayName reportPlatform, platform.reportDropDownInd from publisher_platform pp, platform where pp.platformID = platform.platformID and platform.platformID = '" . $platformID . "';");


		while ($row = mysql_fetch_assoc($result)) {
			if ($row['reportDropDownInd'] == '1') { $reportDropDownInd = 'checked';}else{$reportDropDownInd = '';}

			echo "<input type='checkbox' id='chk_platform_" . $row['platformID']  . "' onclick='javascript:updatePlatformDropDown(" . $row['platformID']  . ");' $reportDropDownInd>";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;<span class='platformText'>" . $row['platform'] . "</span>";


			echo "&nbsp;&nbsp;<input type='textbox' id='txt_" . $platformID . "' value='" . $row['reportPlatform'] . "'>";
			echo "&nbsp;&nbsp;<a href='javascript:updateDisplayPlatform(" . $platformID . ");'>update</a><br />";


		}


        break;


    case 'getPlatformDisplay':
    	$platformID = $_GET['platformID'];

		$result = mysql_query("select distinct platform.platformID, platform.name platform, platform.reportDisplayName reportPlatform, platform.reportDropDownInd from publisher_platform pp, platform where pp.platformID = platform.platformID and platform.platformID = '" . $platformID . "';");

		while ($row = mysql_fetch_assoc($result)) {
			if ($row['reportDropDownInd'] == '1') { $reportDropDownInd = 'checked';}else{$reportDropDownInd = '';}

			echo "<input type='checkbox' id='chk_platform_" . $row['platformID']  . "' onclick='javascript:updatePlatformDropDown(" . $row['platformID']  . ");' $reportDropDownInd>";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;<span class='platformText'>" . $row['platform'] . "</span>";

			if ($row['reportPlatform'])  echo "&nbsp;&nbsp;(<i>" . $row['reportPlatform'] . "</i>)";
			echo "&nbsp;&nbsp;<a href='javascript:showDisplayPlatform(" . $row['platformID'] . ")'>edit report display name</a><br />";


		}



        break;


    case 'updatePlatformDisplay':
    	$platformID = $_GET['platformID'];
    	$reportPlatform = $_GET['reportPlatform'];


    	if (mysql_query("update platform set reportDisplayName  = '" . $reportPlatform . "' where platformID = '" . $platformID . "';")){
    		echo "Platform display name has been updated";
    	}else{
    		echo "Error processing your request - please contact support!" . "here4";
    	}
        break;



    case 'updatePlatformDropDown':
    	$platformID = $_GET['platformID'];
    	$dropDownInd = $_GET['dropDownInd'];


    	if (mysql_query("update platform set reportDropDownInd  = '" . $dropDownInd . "' where platformID = '" . $platformID . "';")){
    		echo "Default display list has been updated";
    	}else{
    		echo "Error processing your request - please contact support!" . "here3";
    	}
        break;


    case 'getPublisherEdit':
    	$publisherPlatformID = $_GET['publisherPlatformID'];

		$result = mysql_query("select distinct publisher.publisherPlatformID, publisher.name publisher, pp.reportDisplayName reportPublisher, pp.reportDropDownInd from publisher_platform pp, publisher where pp.publisherPlatformID = publisher.publisherPlatformID and publisher.publisherPlatformID = '" . $publisherPlatformID . "';");


		while ($row = mysql_fetch_assoc($result)) {
			if ($row['reportDropDownInd'] == '1') { $reportDropDownInd = 'checked';}else{$reportDropDownInd = '';}

			echo "<table><tr valign='top'><td><input type='checkbox' id='chk_publisher_" . $row['publisherPlatformID']  . "' onclick='javascript:updatePublisherDropDown(" . $row['publisherPlatformID']  . ");' $reportDropDownInd></td>";
			echo "<td><span class='publisherText'>" . $row['publisher'] . "</span>";


			echo "&nbsp;&nbsp;<input type='textbox' id='txt_" . $publisherPlatformID . "' value='" . $row['reportPublisher'] . "'>";
			echo "&nbsp;&nbsp;<a href='javascript:updateDisplayPublisher(" . $publisherPlatformID . ");'>update</a></td></tr></table>";


		}


        break;


    case 'getPublisherDisplay':
    	$publisherPlatformID = $_GET['publisherPlatformID'];

		$result = mysql_query("select distinct publisher.publisherPlatformID, publisher.name publisher, pp.reportDisplayName reportPublisher, pp.reportDropDownInd from publisher_platform pp, publisher where pp.publisherPlatformID = publisher.publisherPlatformID and publisher.publisherPlatformID = '" . $publisherPlatformID . "';");

		while ($row = mysql_fetch_assoc($result)) {
			if ($row['reportDropDownInd'] == '1') { $reportDropDownInd = 'checked';}else{$reportDropDownInd = '';}

			echo "<table><tr valign='top'><td><input type='checkbox' id='chk_publisher_" . $row['publisherPlatformID']  . "' onclick='javascript:updatePublisherDropDown(" . $row['publisherPlatformID']  . ");' $reportDropDownInd></td>";


			echo "<td>" . $row['publisher'];
			if ($row['reportPublisher'])  echo "&nbsp;&nbsp;(<i>" . $row['reportPublisher'] . "</i>)";
			echo "&nbsp;&nbsp;<a href='javascript:showDisplayPublisher(" . $row['publisherPlatformID'] . ")'>edit report display name</a></td></tr></table>";


		}



        break;


    case 'updatePublisherDisplay':
    	$publisherPlatformID = $_GET['publisherPlatformID'];
    	$reportPublisher = $_GET['reportPublisher'];


    	if (mysql_query("update publisher_platform set reportDisplayName  = '" . $reportPublisher . "' where publisherPlatformID = '" . $publisherPlatformID . "';")){
    		echo "Publisher display name has been updated";
    	}else{
    		echo "Error processing your request - please contact support!" . "here2";
    	}
        break;


    case 'updatePublisherDropDown':
    	$publisherPlatformID = $_GET['publisherPlatformID'];
    	$dropDownInd = $_GET['dropDownInd'];


    	if (mysql_query("update publisher_platform set reportDropDownInd  = '" . $dropDownInd . "' where publisherPlatformID = '" . $publisherPlatformID . "';")){
    		echo "Default display list has been updated";
    	}else{
    		echo "Error processing your request - please contact support!" . "update publisher_platform set reportDropDownInd  = '" . $dropDownInd . "' where publisherPlatformID = '" . $publisherPlatformID . "';";
    	}
        break;




	default:
       echo "Function " . $_GET['function'] . " not set up!";
       break;


}



?>