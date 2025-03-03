$('#btn_submit_approve').click(function(){

var url = "rfqb/rfq_rfb_award/to_failed_bid";
sub_approve(this.getAttribute('data-rfq'),url);

})

function sub_approve(data,url)
{


		$.ajax({
			type:'POST',
			data:{data:  data},
			url: url,
			success: function(result){

			alert(result);

			return;	

			},error: function(result)
			{
				alert(result + 'e');	
				return;		
			}
			}).fail(function(result){

				alert(result + 'f');
				return;
			});								



}