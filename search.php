<!DOCTYPE html>

<html>

    <head>
        <title>楊東翰</title>
	    <meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
    </head>
	<body>
		<div class="container" id="searchApp">
			<br />
			<br />
			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="row">
						<div class="col-md-9">
							<h3 class="panel-title">Sample Data</h3>
						</div>
						<div class="col-md-3" align="right">
							<input type="text" class="form-control input-sm" placeholder="Search Data" v-model="query" @keyup="fetchData()" />
						</div>
					</div>
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-bordered table-striped">
							<tr>
								<th>First Name</th>
								<th>Last Name</th>
							</tr>
							<tr v-for="row in allData">
								<td>{{ row.first_name }}</td>
								<td>{{ row.last_name }}</td>
							</tr>
							<tr v-if="nodata">
								<td colspan="2" align="center">No Data Found</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div id="app">
    		<v-app>
      			<v-main>
    				<v-container>Hello world</v-container>
      			</v-main>
    		</v-app>
  		</div>
	</body>
</html>

<script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

<script>

var application = new Vue({
	el:'#searchApp',
	vuetify: new Vuetify(),
	data:{
		allData:'',
		query:'',
		nodata:false
	},
	methods: {
		fetchData:function(){
			axios.post('action.php', {
				query:this.query
			}).then(function(response){
				if(response.data.length > 0)
				{
					application.allData = response.data;
					application.nodata = false;
				}
				else
				{
					application.allData = '';
					application.nodata = true;
				}
			});
		}
	},
	created:function(){
		this.fetchData();
	}
});

</script>
