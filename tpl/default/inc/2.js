function readURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			$('.preview').attr('src', e.target.result), $('.preview').css('display', 'block')
		}
		reader.readAsDataURL(input.files[0]);
	}
}
$(document).ready(function () {
	$('.change-avatar').on('click', function () {
		$('.upload-avatar').click().change(function () {
			readURL(this);
		});
	});
	$(".sortable").sortable({
		connectWith: ".connectedSortable",
		receive: function (event, ui) {
			let item = this.id, sender = $(ui.item[0]).find('input[name="member"]').attr('value');
			$.ajax({
				url: './core/ajax.php',
				type: 'POST',
				data: { f: 'updateMember', i: item, m: sender },
				success: function (result) {
					if (!result) {
						location.reload();
						return false;
					}
				}
			});
		}
	}).disableSelection();
	$(".clan-sortable").sortable({
		items: ".clan-order",
		update: function (event, ui) {
			let order = "";
			$(".clan-order").each(function (i) {
				if (order == '')
					order = $(this).attr('id');
				else
					order += "," + $(this).attr('id');
			});
			$.ajax({
				url: './core/ajax.php',
				type: 'POST',
				data: { f: 'updateSort', i: order },
				success: function (result) {
					if (!result) {
						location.reload();
						return false;
					}
				}
			});
		}
	}).disableSelection();

});