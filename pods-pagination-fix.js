/**
 * Theme functions file.
 *
 * Contains handlers for navigation and widget area.
 */

( function( $ ) {

	function in_array(needle, haystack) {
    	for(var i in haystack) {
        	if(haystack[i] == needle) return true;
    	}
    	return false;
	}

	function get_url_vars(url) {
    	var vars = {};
    	var parts = url.replace(/[?&]+([^=&]+)=([^&]*)/gi,    
    	function(m,key,value) {
      		vars[key] = value;
    	});
    	return vars;
  	}

	$('div.tablenav-pages a').click(function() {
	
		$.href = function (url) {
		  // This function is anonymous, is executed immediately and 
		  // the return value is assigned to QueryString!
		  var query_string = {};
		  var query = url.substring(url.indexOf("?") + 1);

		  var vars = query.split("&");
		  for (var i=0;i<vars.length;i++) {
			var pair = vars[i].split("=");
				// If first entry with this name
			if (typeof query_string[pair[0]] === "undefined") {
			  query_string[pair[0]] = decodeURIComponent(pair[1]);
				// If second entry with this name
			} else if (typeof query_string[pair[0]] === "string") {
			  var arr = [ query_string[pair[0]],decodeURIComponent(pair[1]) ];
			  query_string[pair[0]] = arr;
				// If third or later entry with this name
			} else {
			  query_string[pair[0]].push(decodeURIComponent(pair[1]));
			}
		  }
		  return query_string;
		};
	
		var from_url = $.href(window.location.search);
		var referrer_pg = (typeof from_url.pg === "undefined" ? '1' : from_url.pg);
		
		
		// looks like /wp-admin/admin.php?page=pods-manage-app&orderby=name&orderby_dir=ASC&pg=2
		var action = $(this).attr('href');		
    	var form = $('#posts-filter').attr('action', action).attr('method','post');
    	if ( ! $('input#referrer_pg').length ) {
    		$('<input>').attr({
    			type: 'hidden',
    			id: 'referrer_pg',
    			name: 'referrer_pg'
			}).appendTo(form);
    	}
    	$('input#referrer_pg').val(referrer_pg);

		var to_url = $.href($(this).attr('href'));
    	$('input.current-page').val(to_url.pg);
    	
    	$(form).submit();
    	return false;
	});
	

	$( document ).ready( function() {
		$('#posts-filter').attr('method','post');
		$('input[type="checkbox"]').each( function(index) {
			if ( in_array($(this).val(), $('#bulk_ids').val().split(",")) ) {
				$( this).prop( "checked", true );
			}
		});
	} );

} )( jQuery );
