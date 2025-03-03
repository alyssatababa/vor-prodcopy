  


    <div id="m_templates">
      <script id="similar_list_template" type="text/template">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>Vendor Name</th>
              <th>Contact Person</th>
              <th>Email</th>
            </tr>
          </thead>
          <tbody>
            {{#similar_list}}
              <tr>
                <td>{{VENDOR_NAME}}</td>
                <td>{{CONTACT_PERSON}}</td>
                <td>{{EMAIL}}</td>
              </tr>
            {{/similar_list}}
          </tbody>
        </table>
      </script>
    </div>

    <div id="hidden_elements" class="hidden_el">
      <img id="loading_ring" src="<?=base_url().'assets/img/loading_ring.svg'?>" alt="loading_ring.svg" style="height: 15px;">
    </div>

    <input type="hidden" id="new_vendor" value="<?php echo $this->session->userdata('new_vendor'); ?>">
    <input type="hidden" id="showdpa" value="<?php echo isset($dpa[0]) ? $dpa[0]->CONFIG_VALUE : 0; ?>">
    <input type="hidden" id="vendor_invite_id" value="<?php echo $this->session->userdata('vendor_invite_id'); ?>">
    <input type="hidden" id="base_url" value="<?php echo base_url().'index.php/';?>">
    <input type="hidden" id="asset_url" value="<?php echo base_url().'/assets/';?>">
      <input type="hidden" id="user_type_id" value="<?=$user_type_id?>">
  <input type="hidden" id="position_id" value="<?=$position_id?>">
  <input type="hidden" id="override_position_id" value="0">


  </body>

  <script>


    // IE Compatible object declarations (TESTING)

    // OBJECT DECLARATION 1
    /*var myCar = new Object();
    myCar.make = 'Ford';
    myCar.model = 'Mustang';
    myCar.year = 1969;
    myCar.getModel = function () {
      return this.model;
    }

    console.log(myCar.getModel());*/

    // OBJECT DECLARATION 2
   /* var myCar = {
      make: 'Ford',
      model: 'Mustang ni Jaja',
      year: 1969,
      getModel: function () {
        return this.model;
      }
    }

    console.log(myCar.getModel());*/

    // OBJECT DECLARATION 3
   /* var MyCar = function (model) {
      this.make = 'Ford';
      this.model = model || 'Mustang';
      this.year = 1969;

      this.test_func = function test_func () {
        return 'test';
      }
    };

    MyCar.prototype.getModel = function getModel () {
      return this.model;
    }

    MyCar.prototype.test_func2 = function test_func2 () {
      return this.test_func();
    }

    var jaja = new MyCar();
    console.log(jaja.getModel());
    var pao = new MyCar('Mitsubishi');
    console.log(pao.getModel());*/

    // OBJECT DECLARATION 4 (apply this)
    /*var MyCar = function (model)
    {
      var make = 'Ford';
      var model = model || 'Mustang';
      var year = 1969;

      var test_func = function () {
        return 'test';
      }

      this.getModel = function () {
        return model;
      }

      this.test_func2 = function () {
        return test_func();
      }
    };

    var jaja = new MyCar('Toyota');
    console.log(jaja.getModel());
    var pao = new MyCar('Mitsubishi');
    console.log(pao.getModel());*/
	//$(document).ready(function() {
	//	$.fn.dataTable.moment = function ( format, locale ) {
	//		var types = $.fn.dataTable.ext.type;
	//	 
	//		// Add type detection
	//		types.detect.unshift( function ( d ) {
	//			return moment( d, format, locale, true ).isValid() ?
	//				'moment-'+format :
	//				null;
	//		} );
	//	 
	//		// Add sorting method - use an integer for the sorting
	//		types.order[ 'moment-'+format+'-pre' ] = function ( d ) {
	//			return moment( d, format, locale, true ).unix();
	//		};
	//	};
	//});
  </script>

  <!-- Bootstrap core JavaScript
  ================================================== -->
  <!-- Placed at the end of the document so the pages load faster -->
  <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> -->
  <script src="<?=base_url();?>assets/dist/js/bootstrap.min.js"></script>
  <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
  <script src="<?=base_url();?>assets/js/ie10-viewport-bug-workaround.js"></script>

  <!-- OTHER COMMON IMPORTS -->
  <script src="<?php echo js_path('cache.js') . '?' . filemtime('assets/js/cache.js'); ?>"></script>
  <script src="<?php echo js_path('pagination.js') . '?' . filemtime('assets/js/pagination.js'); ?>"></script>
  <script src="<?php echo js_path('jquery_ajax.js') . '?' . filemtime('assets/js/jquery_ajax.js'); ?>"></script>
  <script src="<?php echo js_path('mustache.min.js'); ?>"></script>
  <script src="<?php echo js_path('jquery.bootpag.min.js'); ?>"></script>
  <script src="<?php echo js_path('jquery.maskedinput.min.js'); ?>"></script>
  <script src="<?php echo js_path('jquery.number.js'); ?>"></script>
  <script src="<?php echo base_url().'assets/js/footer.js?' . filemtime('assets/js/footer.js'); ?>"></script>

  <div id="common_user_scripts">
    <script src="<?php echo js_path('common.js') . '?' . filemtime('assets/js/common.js'); ?>"></script>
    <script src="<?php echo js_path('mail_common.js') . '?' . filemtime('assets/js/mail_common.js'); ?>"></script>
    <script src="<?php echo js_path('rfq.js') . '?' . filemtime('assets/js/rfq.js'); ?>"></script>
  </div>

  <script src="<?php echo base_url().'assets/js/action_logs.js?' . filemtime('assets/js/action_logs.js'); ?>"></script>
 

  <script>

function generateDateTime(date)
{

    var st = lmx;
  //  console.log(date);


    var this_date = date || new Date(st); // get server time
    if(tick == 300){
      tick = 0;

          $.ajax({
            type:'POST',
            data:{},
            url: BASE_URL+'common/common/get_srvDate',
            success: function(result){

              console.log(result + "a");
              return;
           // let n = JSON.parse(result)
           lmx = result;
           console.log(lmx);
    
         

            },error: function(result)
            {
                //alert(result + 'e');  
                return;     
            }
            }).fail(function(result){

                //alert(result + 'f');
                return;
            });    

    }

    tick++;
    var hr = this_date.getHours();
    var min = this_date.getMinutes();
    var sec = this_date.getSeconds();
    ap = (hr < 12) ? "<span>AM</span>" : "<span>PM</span>";
    apval = (hr < 12) ? "AM" : "PM";
    hr = (hr == 0) ? 12 : hr;
    hr = (hr > 12) ? hr - 12 : hr;
    //Add a zero in front of numbers<10
    hr = checkTime(hr);
    min = checkTime(min);
    sec = checkTime(sec);
    var TIME = hr + ":" + min + ":" + sec + " " + ap;
    var TIMEVAL = hr + ":" + min + ":" + sec + " " + apval;

    var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    var days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    var curWeekDay = days[this_date.getDay()];
    var curDay = this_date.getDate();
    var curMonth = months[this_date.getMonth()];
    var curYear = this_date.getFullYear();
    var DATE = curWeekDay+", "+curDay+" "+curMonth+" "+curYear;
    var DATETIME = DATE + ' - ' + TIME;

    var getDay = this_date.getDate();
    var upDay = (getDay < 10) ? "0" + getDay : getDay;
    var getMonth = this_date.getMonth() + 1;
    var upMonth = (getMonth < 10) ? "0" + getMonth : getMonth;
    var DATEVAL = upMonth+"/"+upDay+"/"+this_date.getFullYear();
    var DATETIMEVAL = DATEVAL + ' ' + TIMEVAL;



    $("#sysdate").data('rel', DATETIMEVAL);

    this_date.setSeconds(this_date.getSeconds() + 1);
    lmx = this_date;

    

    //console.log(test);
    if (!date) {
        
        $('.datetime').html(DATETIME);
        var datetime = setTimeout(function(){ generateDateTime() }, 1000);
    }
    else {
        return DATETIME;
    }
}

function checkTime(i) {
    if (i < 10) {
        i = "0" + i;
    }
    return i;
}

	
	$(function(){
		
	  var a = $('nav').html();
	  var b = $('#clone_nav').html(a);

	});

	$(function(){
		$('.video_link_button').on('click', function(e){
			$("#model_video_link").attr('src', $(this).data('video-link'));
			$("#model_video_title").html($(this).data('video-title'));
        });
	});
	
	//Jay fix scrolling menu item  main page
	$.fn.scrollGuard = function() {
      return this
        .on( 'wheel', function ( e ) {
          var $this = $(this);
          if (e.originalEvent.deltaY < 0) {
            /* scrolling up */
            return ($this.scrollTop() > 0);
          } else {
            /* scrolling down */
            return ($this.scrollTop() + $this.innerHeight() < $this[0].scrollHeight);
          }
        })
      ;
    };    
	$(function(){
		$( '.menu_item_scroll' ).scrollGuard();
	});
	
  </script>

   <script src="<?php echo base_url().'assets/js/session.js?' . filemtime('assets/js/session.js');?>"></script>

  <!-- CHANGED TAB DETECTION -->
  <!-- <script>
    document.addEventListener('visibilitychange', function(){
        console.log('changed_tab');
    })
  </script> -->

</html>
