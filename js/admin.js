var $jq = jQuery.noConflict();
$jq(document).ready(function() {
	$jq(".note").fadeIn("slow").animate({opacity: 1.0}, 3000).fadeOut("slow", function() {
		$jq(this).remove();
	});

	$jq("#showArrows").hover( function () { $jq('#arrowType').fadeIn(); }, function () { $jq('#arrowType').fadeOut("slow"); } );
	
	$jq('#main_color, #secondary_color').ColorPicker({
		onShow: function (colpkr) { 
			$jq(colpkr).fadeIn(500); 
			return false; 
		}, 
		onHide: function (colpkr) {
			$jq(colpkr).fadeOut(500); 
			return false; 
		},
		onSubmit: function(hsb, hex, rgb, el) {
			$jq(el).val(hex);
			$jq(el).ColorPickerHide();
		},
		onBeforeShow: function () {
			$jq(this).ColorPickerSetColor(this.value);
		}
	})
	.bind('keyup', function(){
		$jq(this).ColorPickerSetColor(this.value);
	});
});