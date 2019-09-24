<?php 

// User Query
$user_id = \Auth::user()->id;
$test = \DB::select( DB::raw("SELECT mt.type FROM user_membership AS um INNER JOIN membership_types AS mt ON mt.id =um.membership_types_id WHERE um.user_id =  $user_id AND mt.status = 'active' AND um.status = 'active'") );

// Normalization
foreach($test AS $key => $value) { $test2[$key] = $value->type; } 


// Condition
if( ! in_array('paid', $test2)) { ?>

	<div id="slide_upgrade" style="background-color: #fff; height: 471px; left: 0; position: absolute; z-index: 999; margin-top: -30px">
	    <div id="slide_contents" style="float: left; display:none">
	        <a href="#" id="slide_close_button">[close]</a>
	    </div>
	    <a href="#" style="position: absolute; right: 0"><img src="{{ url('assets/images/upgrade-tab.jpg') }}" /></a>
	</div>

<?php } else { } ?>
