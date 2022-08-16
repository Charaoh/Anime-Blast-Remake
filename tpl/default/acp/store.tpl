<div class="transparent">Store Management</div>
<!-- BEGIN success -->
<div class="transparent">Successfully added!</div>
<div class="content">
	This item as been successfully added!
</div>
<!-- END success -->
<div class="content">
	<table width="100%" border="0" cellspacing="0" cellpadding="4">
		<tr>
			<td width="5%">
				<p class="navfont">ID</p>
			</td>
			<td width="80%">
				<p class="navfont">Item Description</p>
			</td>
			<td>
				<p class="navfont">Options</p>
			</td>
		</tr>
		{DEPOSIT}
	</table>
</div>

<div class="transparent">Create new item</div>
<div class="content">
	<form action="" method="post">
		<label>
			<div class="navfont">Select item type</div>
		</label>
		<select name="myItem" class="globaltab">
			<option value="character">Character</option>
			<option value="bc">Blast Coins</option>
			<option value="xp">Experience</option>
		</select>
		<br class="clearfix" />
		<label>
			<div class="navfont">Item id (If character set the id here, otherwise the value)</div>
		</label>
		<input type="text" class="globaltab" name="value" value="" placeholder="Item value" />
		<div style="justify-content:center"><input type="submit" name="submit" value="Add Item"
				class="globaltab" /><input type="hidden" name="additem" value="1" /></div>
	</form>
</div>

<div class="transparent">Current Sales |<span class="mx-1"><a href="./?s=website&module=store&new=true" />New
		sale</a></div>
</div>
<div class="content">
	{STORE}
	<!-- BEGIN item -->

	<form action="" method="post" class="{ROW}" style="
    padding: 5px;
    text-align: center;margin-bottom:5px;">
		<input type="text" class="globaltab" name="name" value="{NAME}" placeholder="Sale Title" /> - <input type="text"
			class="globaltab" name="description" value="{DESCRIPTION}" placeholder="Sale Description" />
		<br class="clearfix" />
		<label>
			<p class="navfont">The items assigned to this sale (if various glued with commas)</p>
		</label>
		<input type="text" class="globaltab" name="items" value="{ITEMS}" placeholder="Sale Items" />
		<br class="clearfix" />
		<label>
			<p class="navfont">The quantity of this sale</p>
		</label>
		<input type="text" class="globaltab" name="quantity" value="{QUANTITY}" placeholder="Sale Quantity" />
		<br class="clearfix" />
		<label>
			<p class="navfont">The account limit for this item</p>
		</label>
		<input type="text" class="globaltab" name="limit" value="{LIMIT}" placeholder="Limit for Sale" />
		<br class="clearfix" />
		<label>
			<p class="navfont">The value of this </p>
		</label>
		<input type="text" class="globaltab" name="value" value="{VALUE}" placeholder="Sale Value" />
		<div style="justify-content:center"><input type="submit" name="submit" value="Update" class="globaltab" /><input
				type="submit" name="remove" value="Remove" class="globaltab" /><input type="hidden" name="id"
				value="{ID}" class="globaltab" /></div>
	</form>
	<!-- END item -->
</div>

<!-- BEGIN new -->
<div class="transparent">New sale</div>
<div class="content">
	<form action="" method="post">
		<label class="transparent"> Name - Description:</label> <input type="text" class="globaltab" name="name"
			value="" placeholder="Sale Title" /> - <input type="text" class="globaltab" name="description" value=""
			placeholder="Sale Description" />
		<br class="clearfix" />
		<label>
			<p class="navfont">The items assigned to this sale (if various glued with commas)</p>
		</label>
		<input type="text" class="globaltab" name="items" value="" placeholder="Sale Items" />
		<br class="clearfix" />
		<label>
			<p class="navfont">The quantity of this sale</p>
		</label>
		<input type="text" class="globaltab" name="quantity" value="" placeholder="Sale Quantity" />
		<br class="clearfix" />
		<label>
			<p class="navfont">The account limit for this item</p>
		</label>
		<input type="text" class="globaltab" name="limit" value="" placeholder="Limit for Sale" />
		<br class="clearfix" />
		<label>
			<p class="navfont">The value of this </p>
		</label>
		<input type="text" class="globaltab" name="value" value="" placeholder="Sale Value" />
		<div style="justify-content:center"><input type="submit" name="submit" value="Sell this!" class="globaltab" />
		</div>
	</form>
</div>
<!-- END new -->