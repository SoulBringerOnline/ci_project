<?php

namespace Libs\View;

class Json extends View {

    public function format($body) {
	    $callback = $GLOBALS["jsonp_callback"];
	    if(!empty($callback)) {
		    return $callback."(".json_encode($body).")";
	    }
        return json_encode($body);
    }
}
