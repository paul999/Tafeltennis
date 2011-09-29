$(document).ready(function(){

			function selectBan()
			{
				$("#ban").html("");
				$.ajax({
					url: 'ajax.php',
					data: {mode: 'ban'},
					type: 'POST',
					beforeSend: function() { $('#wait5').show(); },
					complete: function() { $('#wait5').hide(); },
					success: function(xml){parseBan(xml); /*setTimeout(function() {selectBan();} , 5000);*/}
				});				
			}
			
			function parseServers(xml)
			{
				  $(xml).find("row").each(function()
				  {
					$("#servers").append("<li>" + $(this).find("srv").text() + "(" + $(this).find("ip").text() + ")</li>");
					
					// Select services for that server
					
					//
					var id = $(this).find("id").text();
					$("#servers2").append("<li id='serv" + id + "'>" + $(this).find("srv").text() + "<ul id=\"srv" + id + "\"><li id='waitS" + id + "'><img src='ajax-loader.gif'   /></li></ul></il>");
					$("#servers3").append("<li id='acc" + id + "'>" + $(this).find("srv").text() + "<ul id=\"ac" + id + "\"><li id='waitA" + id + "'><img src='ajax-loader.gif'   /></li></ul></il>");
					
				$.ajax({
					url: 'ajax.php',
					data: {mode: 'servicesServer', id: id },
					type: 'POST',
					beforeSend: function() { $('#waitS' + id).show(); },
					success: function(xml) { 
							var id = $("oid", xml).text();
							$('#waitS' + id).hide();
							$('#waitA' + id).hide();
							
							$("#srv" + id).append("<li><div id='srv_" + id + "_success' class='success'>Service opgeslagen</div><form>Service: <input type='text' id='srv_service_" + id  + "' /><br /><input type='submit' id='srv_sm_" + id + "' value='submit' /></form></li>");
							$('#srv_sm_' + id).click(function(e){
								e.preventDefault();
								srv_submit(id);
							});								
							
							$(xml).find("row").each(function()
							{
								var id2 = $(this).find("id").text();
								$("#srv" + id).append("<li>" + $(this).find("service").text() + "</li>");
								$("#ac" + id).append("<li>" + $(this).find("service").text() + "</li><ul id='ac_" + id2 + "'><li id='wait_A" + id2 + "'><img src='ajax-loader.gif'   /></li></ul>");
								
								$.ajax({
									url: 'ajax.php',
									data: {mode: 'access', id: id2 },
									type: 'POST',
									beforeSend: function() { $('#wait_A' + id).show(); },
									success: function(xml) { 
										var id = $("oid", xml).text();
										$('#wait_A' + id).hide();
							
										$(xml).find("row").each(function()
										{
											$("#ac_" + id).append("<li>" + $(this).find("ip").text() + "</li>");						
										});
										
										$("#ac_" + id).append("<li><div id='ac_" + id + "_success' class='success'>Access opgeslagen</div><form>IP: <input type='text' id='ac_ip_" + id  + "' /><br /><input type='submit' id='ac_sm_" + id + "' value='submit' /></form></li>");
										$('#ac_sm_' + id).click(function(e){
											e.preventDefault();
											ac_submit(id);
										});
									}
								});								
							});
						}
					});
				});	
					
				$('#wait3').hide();
				$('#wait4').hide(); 		 		
			}
			
			function ac_submit(id)
			{
					$('#ac_sm_' + id).attr({'disabled' : 'true', 'value' : 'Sending...' });	
					
					var ip = $("#ac_ip_" + id).attr("value");
					
					$.ajax({
						url: 'ajax.php',
						data: {mode: 'addaccess', 'ip': ip, 'server': id},
						type: 'POST',
						beforeSend: function() { $('#wait5').show(); },
						complete: function() { $('#wait5').hide(); },
						success: function()
						{
							
							
							$('#ac_sm_' + id).attr({'disabled' : false, 'value' : 'submit' });
							$('#ac_' + id + '_success').fadeIn(500);
							
							setTimeout(";$('#ac_" + id + "_success').fadeOut(500);", 1000);
							setTimeout(selectServers, 1000);
						}
					});		
			}
			
			function srv_submit(id)
			{
					$('#srv_sm_' + id).attr({'disabled' : 'true', 'value' : 'Sending...' });	
					
					var ip = $("#ac_srv_" + id).attr("value");
					
					$.ajax({
						url: 'ajax.php',
						data: {mode: 'serverservice', 'service': ip, 'server': id},
						type: 'POST',
						beforeSend: function() { $('#wait5').show(); },
						complete: function() { $('#wait5').hide(); },
						success: function()
						{
							
							
							$('#srv_sm_' + id).attr({'disabled' : false, 'value' : 'submit' });
							$('#srv_' + id + '_success').fadeIn(500);
							
							setTimeout(";$('#srv_" + id + "_success').fadeOut(500);", 1000);
							setTimeout(selectServers, 1000);
						}
					});		
			}			
			
			function parseServices(xml)
			{
				  $(xml).find("row").each(function()
				  {
					$("#services").append("<li>" + $(this).find("service").text() + "(" + $(this).find("port").text() + ")</li>");
				  });	
			}
			function parseBan(xml)
			{
				  $(xml).find("row").each(function()
				  {

					$("#ban").append("<li>" + $(this).find("ip").text() + "</li>");
				  });			
			}
			
			$('#send_message_ban').click(function(e){
					//stop the form from being submitted
					e.preventDefault();
		
					$('#send_message_ban').attr({'disabled' : 'true', 'value' : 'Sending...' });	
					
					var ip = $("#addBanIp").attr("value");
					
					$.ajax({
						url: 'ajax.php',
						data: {mode: 'addban', 'ip': ip},
						type: 'POST',
						beforeSend: function() { $('#wait5').show(); },
						complete: function() { $('#wait5').hide(); },
						success: function()
						{
							selectBan();
							
							$('#send_message_ban').attr({'disabled' : false, 'value' : 'Add ban' });
							$('#ban_success').fadeIn(500);
							
							setTimeout(";$('#ban_success').fadeOut(500);", 1000);
						}
					});					
					
			});
			
			$('#send_message_server').click(function(e){
					//stop the form from being submitted
					e.preventDefault();
		
					$('#send_message_server').attr({'disabled' : 'true', 'value' : 'Sending...' });	
					
					var ip = $("#addServerIp").attr("value");
					var server = $("#addServer").attr("value");
					
					$.ajax({
						url: 'ajax.php',
						data: {mode: 'addserver', 'ip': ip, 'server': server},
						type: 'POST',
						beforeSend: function() { $('#wait5').show(); },
						complete: function() { $('#wait5').hide(); },
						success: function()
						{
							selectServers();
							
							$('#send_message_server').attr({'disabled' : false, 'value' : 'Add server' });
							$('#server_success').fadeIn(500);
							
							setTimeout(";$('#server_success').fadeOut(500);", 1000);
						}
					});					
					
			});		
			
			$('#send_message_service').click(function(e){
					//stop the form from being submitted
					e.preventDefault();
		
					$('#send_message_service').attr({'disabled' : 'true', 'value' : 'Sending...' });	
					
					var ip = $("#addServicePort").attr("value");
					var server = $("#addService").attr("value");
					
					$.ajax({
						url: 'ajax.php',
						data: {mode: 'addservice', 'port': ip, 'service': server},
						type: 'POST',
						beforeSend: function() { $('#wait5').show(); },
						complete: function() { $('#wait5').hide(); },
						success: function()
						{
							selectServices();
							selectServers();
							
							$('#send_message_service').attr({'disabled' : false, 'value' : 'Add service' });
							$('#service_success').fadeIn(500);
							
							setTimeout(";$('#service_success').fadeOut(500);", 1000);
						}
					});					
					
			});			
			
});