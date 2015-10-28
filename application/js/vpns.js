function humanFileSize(bytes, si) {
    var thresh = si ? 1000 : 1024;
    if(Math.abs(bytes) < thresh) {
        return bytes.toFixed(1) + ' B';
    }
    var units = si
        ? ['kB','MB','GB','TB','PB','EB','ZB','YB']
        : ['KiB','MiB','GiB','TiB','PiB','EiB','ZiB','YiB'];
    var u = -1;
    do {
        bytes /= thresh;
        ++u;
    }
    while(Math.abs(bytes) >= thresh && u < units.length - 1);
    return bytes.toFixed(1)+' '+units[u];
}

function updateLogs() {
	$.get("do_retrieveRates.php", null, function(data) {
		if (data.ok) {
			var stats = {};
			var total = {uploadRate: 0, downloadRate: 0};

			$(".panel").removeClass("panel-success");

			for(var index = 0; index < data.logs.length; ++index) {
				var log = data.logs[index];

				if (log["vlo_server_id"]) {
					var vpnId = "vpn-" + log["vlo_vpn_id"] + "-" + log["vlo_server_id"];
					var serverId = "server-" + log["vlo_server_id"];
					var allId = "all";

					var panel = $("#" + vpnId);

					if (log["vlo_last_log"] == 1) {
						if (panel.length) {
							panel.parent(".panel").addClass("panel-success");

							panel.find(".log-since").text(moment(new Date(log["vlo_since_date"])).locale("fr").format("llll"));
							panel.find(".log-update").text(moment(new Date(log["vlo_log_date"])).locale("fr").format("llll"));

							panel.find(".log-upload-rate").text(humanFileSize(log["vlo_upload_rate"] * 1., false) + "/s");
							panel.find(".log-upload").text(humanFileSize(log["vlo_upload"] * 1., false));
							panel.find(".log-download-rate").text(humanFileSize(log["vlo_download_rate"] * 1., false) + "/s");
							panel.find(".log-download").text(humanFileSize(log["vlo_download"] * 1., false));
						}

						total["uploadRate"] += log["vlo_upload_rate"] * 1.;
						total["downloadRate"] += log["vlo_download_rate"] * 1.;
					}

					if (!stats[vpnId]) {
						stats[vpnId] = {uploads: [], downloads: []};
					}

					if (!stats[serverId]) {
						stats[serverId] = {uploads: [], downloads: []};
					}

					if (!stats[allId]) {
						stats[allId] = {uploads: [], downloads: []};
					}

					// Upload

					var stat = {
						x: new Date(log["vlo_log_date"]),
						y: log["vlo_upload_rate"] * 1.,
						x_string : log["vlo_log_date"]
					};

					stats[vpnId]["uploads"][stats[vpnId]["uploads"].length] = stat;

					found = false;
					for(var jndex = 0; jndex < stats[serverId]["uploads"].length; ++jndex) {
						if (stats[serverId]["uploads"][jndex].x_string == stat.x_string) {
							stats[serverId]["uploads"][jndex].y += stat.y;
							found = true;
							break;
						}
					}
					if (!found) {
						stats[serverId]["uploads"][stats[serverId]["uploads"].length] = stat;
					}

					found = false;
					for(var jndex = 0; jndex < stats[allId]["uploads"].length; ++jndex) {
						if (stats[allId]["uploads"][jndex].x_string == stat.x_string) {
							stats[allId]["uploads"][jndex].y += stat.y;
							found = true;
							break;
						}
					}
					if (!found) {
						stats[allId]["uploads"][stats[allId]["uploads"].length] = stat;
					}

					// Download

					var stat = {
						x: new Date(log["vlo_log_date"]),
						y: log["vlo_download_rate"] * 1.,
						x_string : log["vlo_log_date"]
					};

					stats[vpnId]["downloads"][stats[vpnId]["downloads"].length] = stat;

					found = false;
					for(var jndex = 0; jndex < stats[serverId]["downloads"].length; ++jndex) {
						if (stats[serverId]["downloads"][jndex].x_string == stat.x_string) {
							stats[serverId]["downloads"][jndex].y += stat.y;
							found = true;
							break;
						}
					}
					if (!found) {
						stats[serverId]["downloads"][stats[serverId]["downloads"].length] = stat;
					}

					found = false;
					for(var jndex = 0; jndex < stats[allId]["downloads"].length; ++jndex) {
						if (stats[allId]["downloads"][jndex].x_string == stat.x_string) {
							stats[allId]["downloads"][jndex].y += stat.y;
							found = true;
							break;
						}
					}
					if (!found) {
						stats[allId]["downloads"][stats[allId]["downloads"].length] = stat;
					}
				}
			}

			var chartWidth = $(".breadcrumb").width() - 55;

			for(var panelId in stats) {
				var logs = stats[panelId];

				var chartContainer = $("#" + panelId + "-chart");

				if (!chartContainer.length) continue;

				chartContainer.show();

				var chart = new CanvasJS.Chart(panelId + "-chart",
		    	    {
					  height: 300,
					  width: chartWidth,
		    	      title:{
		    	/*        text: "Tweets et Validation dans le temps"*/
		    	      },
		    	      toolTip: {
		    	        shared: true,
		    	        content: function(e){
		    	          var body;
		    	          var head;
		    	          var date = e.entries[0].dataPoint.x;
//		    			  var printedDate = (date.getMonth() < 9 ? '0' : '') + (date.getMonth() + 1) + '/' + (date.getDate() < 10 ? '0' : '') + date.getDate() + '/' + date.getFullYear();
		    			  var printedDate = (date.getHours() < 10 ? '0' : '') + date.getHours() + ':' + (date.getMinutes() < 10 ? '0' : '') + date.getMinutes();

		    	          head = "<span style = 'color:DodgerBlue; '><strong>@ "+ printedDate  + "</strong></span><br/>";

		    	          body = "<span style= 'color:"+e.entries[0].dataSeries.color + "'> " + e.entries[0].dataSeries.name + "</span>: <strong>"+  humanFileSize(e.entries[0].dataPoint.y, false) + "/s</strong>";
		    	          body +="<br/>";
		    	          body +="<span style= 'color:"+e.entries[1].dataSeries.color + "'> " + e.entries[1].dataSeries.name + "</span>: <strong>"+  humanFileSize(e.entries[1].dataPoint.y, false) + "/s</strong>";
//		    	          body +="<br/>";
//		    	          body +="<span style= 'color:"+e.entries[2].dataSeries.color + "'> " + e.entries[2].dataSeries.name + "</span>: <strong>"+  e.entries[2].dataPoint.y + "</strong>";

		    	          return (head.concat(body));
		    	        }
		    	      },
		    	      axisY:{
		    	        title: "Vitesse",
		    	        includeZero: true,
		    	        lineColor: "#369EAD",
		    	        labelFormatter: function(e){
		    				return  humanFileSize(e.value, false) + "/s";
		    			}
		    	      },
		    	/*
		    	      axisY2:{
		    	          title: "<?php echo lang("mypage_score_chart_axisY", false); ?>",
		    	          includeZero: false,
		    	          lineColor: "#C24642"
		    	        },
		    	*/
		    	      axisX: {
		    	          title: "Date",
		    	          valueFormatString: "HH:mm"
		    	        },
		    	      data: [
			    	      {
			    	        type: "spline",
			    	        showInLegend: true,
			    	        name: "Vitesse de téléversement",
			    	        dataPoints: logs["uploads"]
			    	      },
			    	      {
			    	        type: "spline",
			    	        showInLegend: true,
			    	        name: "Vitesse de téléchargement",
			    	        dataPoints: logs["downloads"]
			    	      }
		    	      ]
		    	    });

		    		chart.render();
			}

			$("li#total-rates").show();
			$("li#total-rates span#total-download-rate").text(humanFileSize(total["downloadRate"], false) + "/s");
			$("li#total-rates span#total-upload-rate").text(humanFileSize(total["uploadRate"], false) + "/s");

			for(var index = 0; index < data.servers.length; ++index) {
				var log = data.servers[index];
				if (log["vlo_last_log"] == 1) {
					var serverId = log["vlo_server_id"];
					var serverTr = $("#server-" + serverId);
					if (serverTr.length > 0) {
						var bandwidth = serverTr.data("bandwidth");
						var consumption = log["vlo_upload_rate"] * 1. + log["vlo_download_rate"] * 1.;
						var occupation = consumption * 100 / bandwidth;

						serverTr.find(".users").text(log["vlo_number_of_users"]);
//						serverTr.find(".used-capacity").text(occupation.toFixed(1) + "%");

						var progressBarClass = "progress-bar-success";
						if (occupation < 60) {
							progressBarClass = "progress-bar-success";
						}
						else if (occupation < 80) {
							progressBarClass = "progress-bar-warning";
						}
						else {
							progressBarClass = "progress-bar-danger";
						}

						var progressBar = serverTr.find(".used-capacity .progress-bar");
						progressBar.css({width: occupation.toFixed(1) + "%"});
						progressBar.attr("aria-valuenow", occupation.toFixed(1));
						progressBar.find("span").text(occupation.toFixed(1) + "%");
						progressBar.removeClass("progress-bar-success").removeClass("progress-bar-warning").removeClass("progress-bar-danger").addClass(progressBarClass);
					}
				}
			}
		}
	}, "json");
}

$(function() {
	var logTimer = $.timer(updateLogs);
	logTimer.set({ time : 60000, autostart : true });

	updateLogs();
});