<?php
$tpl = $STYLE->open('acp/store.tpl');
if (isset($_GET['new']) && $_GET['new'] == 'true') {
	if (isset($_POST['submit'])) {
		$title = $secure->clean($_POST['name']);
		$description = $secure->clean($_POST['description']);
		$quantity = $secure->clean($_POST['quantity']);
		$limit = $secure->clean($_POST['limit']);
		$value = $secure->clean($_POST['value']);
		$items = $secure->clean($_POST['items']);
		$db->query("INSERT INTO `sales`	(`items`, `quantity`, `limit`, `seller`, `date`, `value`, `title`, `description`) VALUES ('$items','$quantity','$limit','-1','" . time() . "','$value','$title','$description')");
		$system->message('Sale ready!', 'This sale has been inserted successfully!', './?s=website&module=store', L_CONTINUE);
	}
	$tpl = $STYLE->getcode('new', $tpl);
} else {
	$success = false;
	$remove = (isset($_GET['remove'])) ? $secure->clean($_GET['remove']) : '';
	if (!empty($remove)) {
		$db->query("DELETE FROM `items` WHERE id='$remove'");
		$system->message(L_UPDATED, 'This item has been deleted from the database successfully!', './?s=website&module=store', L_CONTINUE);
	}

	if (isset($_POST['submit'])) {
		$title = $secure->clean($_POST['name']);
		$description = $secure->clean($_POST['description']);
		$quantity = $secure->clean($_POST['quantity']);
		$limit = $secure->clean($_POST['limit']);
		$value = $secure->clean($_POST['value']);
		$items = $secure->clean($_POST['items']);
		if (isset($_POST['id'])) {
			$db->query("UPDATE `sales` SET	`items`='$items',`quantity`='$quantity',`limit`='$limit',`value`='$value',`title`='$title',`description`='$description' WHERE id = '" . $_POST['id'] . "'");
			$system->message(L_UPDATED, 'This sale has been updated successfully!', './?s=website&module=store', L_CONTINUE);
		}
	}
	if (isset($_POST['remove'])) {
		if (isset($_POST['id'])) {
			$db->query("DELETE FROM `sales` WHERE id='" . $_POST['id'] . "'");
			$system->message(L_UPDATED, 'This sale has been deleted from the database successfully!', './?s=website&module=store', L_CONTINUE);
		}
	}


	if (isset($_POST['additem']) && $_POST['additem'] == '1') {
		$type = $secure->clean($_POST['myItem']);
		$value = $secure->clean($_POST['value']);
		$db->query("INSERT INTO `items`(`name`, `value`) VALUES ('$type', '$value')");
		$success = true;
	}


	$deposit = '';
	$items = $db->query("SELECT * FROM `items` ORDER BY `id` DESC");
	$row = 'alternate';
	while ($i = $items->fetch()) {
		if ($row == 'normal')
			$row = 'alternate';
		else
			$row = 'normal';
		if ($i['name'] == 'character') {
			$deposit .= '<tr class="' . $row . '">
						<td> ' . $i['id'] . '</td>
						<td>' . $user->image($i['value'], 'characters', './../', 'item') . ' Item Character ' . $db->fieldFetch('characters', $i['value'], 'name') . '</td>
						<td><a href="./?s=website&module=store&remove=' . $i['id'] . ' class="globaltab"">Remove?</a></td>
					</tr>';
		} elseif ($i['name'] == 'bc') {
			$deposit .= '<tr class="' . $row . '">
						<td> ' . $i['id'] . '</td>
						<td> Blast coin pack of ' . $i['value'] . '</td>
						<td><a href="./?s=website&module=store&remove=' . $i['id'] . ' class="globaltab"">Remove?</a></td>
					</tr>';
		} elseif ($i['name'] == 'xp') {
			$deposit .= '<tr class="' . $row . '">
						<td> ' . $i['id'] . '</td>
						<td> Xp pack of ' . $i['value'] . '</td>
						<td><a href="./?s=website&module=store&remove=' . $i['id'] . ' class="globaltab"">Remove?</a></td>
					</tr>';
		}
	}
	$items = '';
	$item = $STYLE->getcode('item', $tpl);
	$store = $db->query("SELECT * FROM sales WHERE seller = '-1' ORDER BY id DESC");
	if ($store->rowCount() > 0) {
		while ($i = $store->fetch()) {
			if ($row == 'normal')
				$row = 'alternate';
			else
				$row = 'normal';
			$new = $STYLE->tags($item, array(
				"QUANTITY" => $i['quantity'],
				"VALUE" => $i['value'],
				"LIMIT" => $i['limit'],
				"ITEMS" => $i['items'],
				"ID" => $i['id'],
				"DESCRIPTION" => $i['description'],
				"NAME" => $i['title'],
				"STORE" => $items,
				"ROW" => $row
			));
			$items .= $new;
		}
	} else {
		$items = 'No sales currently open..';
	}

	if ($success == false)
		$tpl = str_replace(array($STYLE->getcode('success', $tpl), $STYLE->getcode('new', $tpl)), '', $tpl);
	$tpl = str_replace(array($STYLE->getcode('item', $tpl)), '', $tpl);
	$tpl = $STYLE->tags($tpl, array(
		"DEPOSIT" => $deposit,
		"STORE" => $items
	));
}