
<!-- Non-intrussive Alerts -->
<div class="info message">
		 <h3>Informaci&oacute;n</h3>
		 <p>This is just an info notification message.</p>
</div>

<div class="error message">
		 <h3>Error!</h3>
		 <p>This is just an error notification message.</p>
</div>

<div class="warning message">
		 <h3>Advertencia!</h3>
		 <p>This is just a warning notification message.</p>
</div>

<div class="success message">
		 <h3>Listo!</h3>
		 <p>This is just a success notification message.</p>
</div>

<style>
.message{
	background-size: 40px 40px;
	background-image: linear-gradient(135deg, rgba(255, 255, 255, .05) 25%, transparent 25%,
						transparent 50%, rgba(255, 255, 255, .05) 50%, rgba(255, 255, 255, .05) 75%,
						transparent 75%, transparent);										
	 box-shadow: inset 0 -1px 0 rgba(255,255,255,.4);
	 width: 100%;
	 border: 1px solid;
	 color: #fff;
	 padding: 15px;
	 position: fixed;
	 _position: absolute;
	 text-shadow: 0 1px 0 rgba(0,0,0,.5);
	 animation: animate-bg 5s linear infinite;
}

.info{
	 background-color: #4ea5cd;
	 border-color: #3b8eb5;
}

.error{
	 background-color: #de4343;
	 border-color: #c43d3d;
}
		 
.warning{
	 background-color: #eaaf51;
	 border-color: #d99a36;
}

.success{
	 background-color: #61b832;
	 border-color: #55a12c;
}

.message h3{
	 margin: 0 0 5px 0;													 
}

.message p{
	margin: 0;													 
}

@keyframes animate-bg {
    from {
        background-position: 0 0;
    }
    to {
       background-position: -80px 0;
    }
}
</style>
<script>
var myMessages = ['info','warning','error','success'];
function hideAllMessages()
{
	 var messagesHeights = new Array(); // this array will store height for each
 
	 for (i=0; i<myMessages.length; i++)
	 {
			  messagesHeights[i] = $('.' + myMessages[i]).outerHeight(); // fill array
			  $('.' + myMessages[i]).css('top', -messagesHeights[i]); //move element outside viewport	  
	 }
}
function showMessage(type)
{
	hideAllMessages();	  
	$('.'+type).animate({top:"0"}, 500);
}

function showMessageWithMessage(type,message)
{			  
	$('.'+type+' > p').html(message);
	showMessage(type);
}
function showMessageWithTitleAndMessage(type,title,message)
{		
	$('.'+type+' > h3').html(title);
	showMessageWithMessage(type,message)
}
$(document).ready(function(){
		 // Initially, hide them all
		 hideAllMessages();

		 // When message is clicked, hide it
		 $('.message').click(function(){			  
				  $(this).animate({top: -$(this).outerHeight()}, 500);
		  });		 
		 
}); 
</script>

