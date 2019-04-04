<!DOCTYPE html>
  <html>
    <head>
		<!--Import Google Icon Font-->
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<!--Import materialize.css-->
		<!-- Compiled and minified CSS -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
		
		<!--Let browser know website is optimized for mobile-->
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    </head>

    <body>
		<div class="container">
			<div class="row">
				<div class="col s12">
					<h1>CV Rank</h1>
				
					<h5>Input key Words!</h5>
					<div class="input-field">
						<div id="enterWords" id="chips" class="chips input-field" style="user-select: text;">	</div>
					</div>
					
					<h5>Choose a CV!</h5>
					<form id="form" method="POST" action="search.php" enctype="multipart/form-data">
						<input name="word" id="words" type="hidden" value="no" >
						<div class="file-field input-field">
						  <div class="btn">
							<span>File</span>
							<input id="file" name="file" type="file" accept=".doc,.docx, .xslx, .pptx">
						  </div>
						  <div class="file-path-wrapper">
							<input name="cv" id="cv" class="file-path validate" type="text">
						  </div>
						</div>

						<button type="submit" id="send" class="waves-effect waves-light btn-large">Check It!</button>

					 </form> 
					 
					 <div class="row">
						<div class "col s12">
							<h5>Rank!</h5>
						</div>
						<div class="col s12 l12 m12 xl12 center-align">
							<div id="result_stars"></div>
						</div>
						<div class="col l12 s12 m12 xl12 center-align">
							<div id="result_values"></div>
						</div>
					 </div>
				</div>
	
			</div>
		</div>

		<!-- Compiled and minified JavaScript -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
		
		<script  src="https://code.jquery.com/jquery-3.3.1.min.js"  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="  crossorigin="anonymous"></script>
		
		
		<script>

		(function() {
			var App = App || {};
			
			App = {
				init: function(){
					this.cacheDom();
					this.bindEvents();
				},
				
				cacheDom: function(){
					this.$form = $('#form');
					this.$formButton = $('#send');
					this.$formWords = $('#words');

					this.$formChips = document.querySelector('.chips');
					this.$instanceChips = M.Chips.init(this.$formChips);
					
					this.url = this.$form.attr('action');
					this.method = this.$form.attr('method');
				},
				
				
				bindEvents: function(){
					this.$formButton.on('click', this.searchForWords.bind(this));
				},
				
				
				chipsToArray: function(){
					let rtnChipsToArray = [];			
					var data = this.$instanceChips.chipsData;		
					for (let i = 0; i < data.length; i++){
						rtnChipsToArray.push(data[i]['tag']);
					}
					return rtnChipsToArray;
				},
				
				
				searchForWords: function(event){
					
					event.preventDefault();
					
					let chipsArray = [];
					chipsArray = this.chipsToArray();
					this.$formWords.val(chipsArray);
					this.ajaxPost(this.method, this.url, this.$form[0]);
				},
				
				
				ajaxPost: function(method, url, form) {
					$.ajax({
						type: method,
						url: url,
						data: new FormData(form),
						contentType: false,
						cache: false,
						processData:false,
						success: this.renderTemplate
					});	
				},
				
				renderTemplate: function(data){
					let html = '';
					let calcInteger = Math.trunc(data);
					let calcDecimal = Math.trunc(((data*2) % 2)* 10);
					let calcTotal = 0;
					let precision = (data*2) * 10;
					
					for(let i=0; i < calcInteger; i++){
						html += '<i class="large material-icons">star</i>';
						calcTotal++;
					};
					
					if (calcDecimal > 0){
						html += '<i class="large material-icons">star_half</i>';
						calcTotal++;
					};
					
					for(let i=0; i < (5-calcTotal); i++){
						html += '<i class="large material-icons">star_border</i>';
					};
						
					$('#result_stars').html(html);
					
					html = '<h3 class="precision">'+precision+'% of accuracy.</h3>';
					
					$('#result_values').html(html);
				}
				
			};
			
			// Start APP
			App.init();
			
		})();
		</script>
    </body>
  </html>