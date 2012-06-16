
         function TimeLine( _canvasId, _width, _height ) {
        	this.paper = null;
        	this.arrayOfHeadlinesAndDates = [];
        	this.canvasId = _canvasId;
        	this.width = _width;
        	this.height = _height;
        	this.pixelsPerDay = 15;
        	this.pixelsHeightDay = 3;
        	this.pixelsHeightWeek = 6;
        	this.pixelsHeightMonth = 9;
        	this.yAxesPosition = this.height / 2;
        	this.xStep = this.width / 2; // pixels left/right when clicking 
	        this.paper = null;
	        this.sortedHeadlines = [];
	        this.xMax = 0;
	        this.currentXPosition = 0;
	        this.today = new Date();
	        this.shallHaveEditIcon = false;
	        
	        TimeLine.prototype.setSize = function( _width, _height ) {
	        	this.width = _widht;
	        	this.height = _height;	
	        }
	       
	       	TimeLine.prototype.setHeadlines = function ( _arrayOfHeadlinesAndDates ) {
				this.sortedHeadlines = _arrayOfHeadlinesAndDates.slice(0);

				// add TODAY to the list of Events				
				this.sortedHeadlines[ this.sortedHeadlines.length ] = new Array( this.today.getFullYear()+'-'+(this.today.getMonth()+1)+'-'+this.today.getDate(), 'Today' );
	
				this.sortedHeadlines.sort( function(a,b) { 
	   					if( a[0] == b[0] ) return 0; 
	   					if( a[0] < b[0] ) return 1; 
	   					return -1; 
	   				}	
	   			);
				
				//for( var i = 0; i < this.sortedHeadlines.length; i++ ) {
				//	$('body').append('<br>sortedHeadlines['+i+'] = '+this.sortedHeadlines[i] );
				//}

	       	}
	       	
	       	
	       	TimeLine.prototype.calculateLimits = function () {
	       		// find limits start and end
				this.startDate = Number.MAX_VALUE;
				this.endDate = Number.MIN_VALUE;
				
				for( var i = 0; i < this.sortedHeadlines.length; i++ ) {
					var d = this.sortedHeadlines[i][0].split('-');
					var tmpDate = new Date(0);
					tmpDate.setFullYear( d[0] );
					tmpDate.setMonth( d[1]-1 );
					tmpDate.setDate( d[2] );
					var t  = tmpDate.getTime();
					if( t > this.endDate ) this.endDate = t;
					if( t < this.startDate ) this.startDate = t;
				}
				
				// allways start on a Monday
				var tmpDate = new Date( this.startDate );
				if( tmpDate.getDay() == 0 ) {
					this.startDate = this.startDate - (1000*3600*24*6); // minus 6 days
				} else {
					this.startDate = this.startDate - (1000*3600*24*(tmpDate.getDay()-1)); // minus n days
				}
				this.endDate = this.endDate + (1000*3600*24*7); // plus 7 days
					
				this.totalDays = (this.endDate - this.startDate) / (1000*3600*24);
	       	}
	       	
	       	TimeLine.prototype.drawNotes = function() {
	       		// Position the notes at the dates
				var textPositionOffsets = [ 0.5 * this.height / 2 , -0.5 * this.height / 2, 0.75 * this.height / 2, -0.75 * this.height / 2 ];
				var textOffsetIndex = 0;
				path="";
				var over = true;
				for( var i = 0; i < this.sortedHeadlines.length; i++ ) {
					var d = this.sortedHeadlines[i][0].split('-');
					var tmpDate = new Date(0);
					tmpDate.setFullYear( d[0] );
					tmpDate.setMonth( d[1]-1 );
					tmpDate.setDate( d[2] );
					var t  = tmpDate.getTime();
					var days = (t - this.startDate) / (1000*3600*24);
					x = days * this.pixelsPerDay;
					var noteString = this.sortedHeadlines[i][1];
					if( textOffsetIndex >= textPositionOffsets.length ) textOffsetIndex = 0;
					var textOffset = textPositionOffsets[ textOffsetIndex ];
					var yTextPosition = this.yAxesPosition + textOffset;
					var xTextPosition = x+((textOffset>0)?-textOffset:textOffset);
					textOffsetIndex++;
				    this.timeline.push( this.paper.text( xTextPosition, yTextPosition, noteString ) );
				    path = path.concat( "M", x, " ", this.yAxesPosition, "L", xTextPosition, " ", yTextPosition );
				}
				var notePaths = this.paper.path( path );
				notePaths.attr({stroke: '#f00', 'stroke-width': 1});  
				this.timeline.push( notePaths );	
	       	}
	       	
	       	TimeLine.prototype.drawLine = function() {
	       		
				var monthNames = [ 'Jan', 'Feb', 'Mar' ,'Apr', 'May' , 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' ];

				// Draw timeline
				this.timeline = this.paper.set();
				var x1 = 0;
				this.xMax = this.totalDays * this.pixelsPerDay;
				var x = x1;
				var currentDay = this.startDate;
				var path = "M"+x1+" "+this.yAxesPosition+"L"+this.xMax+" "+this.yAxesPosition;
				while( x <= this.xMax ) {    
			    	// always set a marker per day
			    	path = path.concat( "M", x, " ", this.yAxesPosition-this.pixelsHeightDay, "L", x, " ", this.yAxesPosition+this.pixelsHeightDay );
			    	var tmpDate = new Date( currentDay );
			    	// set a DATE number every Monday
			    	if( tmpDate.getDay() == 1 ) {
			    		path = path.concat( "M", x, " ", this.yAxesPosition-this.pixelsHeightWeek, "L", x, " ", this.yAxesPosition+this.pixelsHeightWeek );
			    		var dayString = ''+tmpDate.getDate();
			    		this.timeline.push( this.paper.text( x, this.yAxesPosition+this.pixelsHeightWeek*2, dayString ) );
			    	}
			    	// set a MONTH name every 1;st in the month
			    	if( tmpDate.getDate() == 1 ) {
			    		path = path.concat( "M", x, " ", this.yAxesPosition-this.pixelsHeightMonth, "L", x, " ", this.yAxesPosition+this.pixelsHeightMonth );
			    		var monthString = ''+monthNames[ tmpDate.getMonth() ];
			    		this.timeline.push( this.paper.text( x, this.yAxesPosition-this.pixelsHeightMonth*2, monthString ) );
			    		if( tmpDate.getMonth() == 0 ) {
			    			var yearString = ''+tmpDate.getFullYear();
				    		this.timeline.push( this.paper.text( x, this.yAxesPosition-this.pixelsHeightMonth*3, yearString ) );
				    	}
			    	}
			    	x = x + this.pixelsPerDay;
			    	currentDay = currentDay + (1000*3600*24);
			    }
			    var timelineLine = this.paper.path( path );
				timelineLine.attr({stroke: '#f00', 'stroke-width': 1});  
				this.timeline.push( timelineLine );
	       	}
	        
	        TimeLine.prototype.drawLeftRightArrow = function() {
	        	// Left and Right arrows
				var leftArrowPath = "M16,30.534c8.027,0,14.534-6.507,14.534-14.534c0-8.027-6.507-14.534-14.534-14.534C7.973,1.466,1.466,7.973,1.466,16C1.466,24.027,7.973,30.534,16,30.534zM18.335,6.276l3.536,3.538l-6.187,6.187l6.187,6.187l-3.536,3.537l-9.723-9.724L18.335,6.276z";
				this.leftArrow = this.paper.path( leftArrowPath );
			    this.leftArrow.attr({ fill: "#ccc", stroke: "none" } );
			    var rightArrowPath = "M16,1.466C7.973,1.466,1.466,7.973,1.466,16c0,8.027,6.507,14.534,14.534,14.534c8.027,0,14.534-6.507,14.534-14.534C30.534,7.973,24.027,1.466,16,1.466zM13.665,25.725l-3.536-3.539l6.187-6.187l-6.187-6.187l3.536-3.536l9.724,9.723L13.665,25.725z";
				var rightArrow = this.paper.path( rightArrowPath );
			    rightArrow.attr({ fill: "#ccc", stroke: "none" } );
			    rightArrow.transform( "t"+(this.width-32)+",0" );
			    var self = this;
				// Left and Right buttons
			    this.leftArrow.click(function () {
			    	if( this.currentXPosition >=  this.xStep ) return;
			    	this.currentXPosition = this.currentXPosition + this.xStep;
			    	var t = "t"+this.currentXPosition+",0";
	            	this.timeline.animate( { transform: t}, this.xStep, ">" );
	            }.bind(this));
	            rightArrow.click(function () {
	            	if( this.currentXPosition <= (2*this.xStep)-this.xMax ) return; 
			    	this.currentXPosition = this.currentXPosition - this.xStep;
			    	var t = "t"+this.currentXPosition+",0";
	            	this.timeline.animate( { transform: t}, this.xStep, ">" );
	            }.bind(this));     
	        }
	        
	        TimeLine.prototype.animateToToday = function() {
	        	// Animate to TODAY
				var initialXMove = this.pixelsPerDay * ( (this.today.getTime()-this.startDate) / (1000*3600*24) );
				this.currentXPosition = this.currentXPosition - (initialXMove - this.width/2);
				if( this.currentXPosition >=  this.xStep ) this.currentXPosition = this.xStep;
				if( this.currentXPosition <= (2*this.xStep)-this.xMax ) this.currentXPosition = (2*this.xStep)-this.xMax;
				var t = "t"+this.currentXPosition+",0";
	            this.timeline.animate( { transform: t}, this.xStep, ">" );
				
			}
	        
	        TimeLine.prototype.create = function() {
	        	this.dom = document.getElementById( this.canvasId );
	        	if( this.dom == null ) {
	        		alert( "Oops! Seems like the DOM for "+this.canvasId+" does not exists!" );
	        		return;
	        	}
			    this.paper = new Raphael(this.dom, this.width, this.height);  
			    this.calculateLimits();
			    this.drawLine();
			    this.drawNotes();
			    this.drawLeftRightArrow();
			    if( this.shallHaveEditIcon == true ) this.drawEditIcon();
			    this.animateToToday();
			}
			TimeLine.prototype.drawEditIcon = function() {
				var editIconPath = "M16,1.466C7.973,1.466,1.466,7.973,1.466,16c0,8.027,6.507,14.534,14.534,14.534c8.027,0,14.534-6.507,14.534-14.534C30.534,7.973,24.027,1.466,16,1.466zM24.386,14.968c-1.451,1.669-3.706,2.221-5.685,1.586l-7.188,8.266c-0.766,0.88-2.099,0.97-2.979,0.205s-0.973-2.099-0.208-2.979l7.198-8.275c-0.893-1.865-0.657-4.164,0.787-5.824c1.367-1.575,3.453-2.151,5.348-1.674l-2.754,3.212l0.901,2.621l2.722,0.529l2.761-3.22C26.037,11.229,25.762,13.387,24.386,14.968z";
				var editIcon = this.paper.path( editIconPath );
		    	editIcon.attr({ fill: "#ccc", stroke: "none" } );
		    	editIcon.transform( "t"+(this.width-32)+","+(this.height-32) );
				editIcon.click(function () {
            		window.location = this.editHeadlinesUrl;
            	}.bind(this)); 
			}
			
			TimeLine.prototype.setEditIconURL = function( _editHeadlinesUrl ) {
				this.editHeadlinesUrl = _editHeadlinesUrl;
				this.shallHaveEditIcon = true;
			}
		}
		