var lolapi = angular.module('lolapi', ['tc.chartjs', 'mgcrea.bootstrap.affix']);
lolapi.controller('mainController', ['$scope', '$http', '$timeout', function($a, $http , $timeout) {
  $a.server = "lan";
  $a.modo = "normal_5x5";
  $a.items_state = 'before';
  $a.items = null;
  $a.selected = null;
  $a.itemsbefore = [];
  $a.itemsafter = [];
  $a.itemSeleccionado = "";

  $a.linealcharts = {};

  $a.cambiarServer = function(server){
    $a.server = server;
    $a.peticion();
  }

  $a.cambiarModo = function(modo){
    $a.modo = modo;
    $a.peticion();
  }

  $a.peticion = function(callback){
    $http.get('http://ocando.vnz.la/api/v1/'+$a.server+'/5.11/'+$a.modo+'/items').
    then(function(response) {

      $http.get('http://ocando.vnz.la/api/v1/'+$a.server+'/5.14/'+$a.modo+'/items').
      then(function(response2) {
          $a.itemsbefore = response.data;
          $a.itemsafter = response2.data;
          $a.cambiarItems();
          if(angular.isFunction(callback))
            callback();
      });

    });


  }

  $a.getItemById = function(itemId){
    for (var i = $a.items.length - 1; i >= 0; i--) {
      if( itemId == $a.items[i].itemId)
        return $a.items[i];
    };
    return undefined;
  };

  $a.cambiarItems = function(items){
    $a.items_state = items || $a.items_state;
    $a.items = ($a.items_state == 'before')? $a.itemsbefore : $a.itemsafter;
    $a.cambiarItem();
  };

  $a.cambiarItem = function(itemId){

    $a.itemSeleccionado = itemId || $a.itemSeleccionado;
    if(!$a.itemSeleccionado) return;

    $a.selected = $a.getItemById($a.itemSeleccionado);

    $timeout(function(){

        $a.winrate[0].value = $a.selected.win_rate;
        $a.winrate[1].value = 100-$a.winrate[0].value;


        for(var x in $a.champs){
          $a.champs[x].value = $a.selected.champions[x].champion_rate;
          $a.champs[x].label = $a.selected.champions[x].name;
        }


        $a.setLinealCharts($a.selected);

    },50);



  }



  $a.setLinealCharts = function(item){
    console.log(item);
    $a.linealcharts.creeps = {
      labels: ['Zero To Ten', 'Ten To Twenty', 'Twenty To Thirty', 'Thirty To End'],
      datasets: [
        {
          label: 'Creeps',
          fillColor: 'rgba(220,100,100,0.2)',
          strokeColor: 'rgba(220,100,100,1)',
          pointColor: 'rgba(220,100,100,1)',
          pointStrokeColor: '#fff',
          pointHighlightFill: '#fff',
          pointHighlightStroke: 'rgba(220,100,100,1)',
          data: [
              item.avg_creeps_zeroToTen,
              item.avg_creeps_tenToTwenty,
              item.avg_creeps_twentyToThirty,
              item.avg_creeps_thirtyToEnd
          ]
        },
        {
          label: 'Global Creeps',
          fillColor: 'rgba(151,87,40,0.2)',
          strokeColor: 'rgba(151,87,40,1)',
          pointColor: 'rgba(151,87,40,1)',
          pointStrokeColor: '#fff',
          pointHighlightFill: '#fff',
          pointHighlightStroke: 'rgba(151,187,205,1)',
          data: [
              item.avg_creeps_zeroToTen - item.avg_csDiff_zeroToTen ,
              item.avg_creeps_tenToTwenty - item.avg_csDiff_tenToTwenty,
              item.avg_creeps_twentyToThirty - item.avg_csDiff_twentyToThirty,
              item.avg_creeps_thirtyToEnd - item.avg_csDiff_thirtyToEnd
          ]
        }
      ]
    };


    $a.linealcharts.xp = {
      labels: ['Zero To Ten', 'Ten To Twenty', 'Twenty To Thirty', 'Thirty To End'],
      datasets: [
        {
          label: 'Exp',
          fillColor: 'rgba(220,60,150,0.2)',
          strokeColor: 'rgba(220,60,150,1)',
          pointColor: 'rgba(220,60,150,1)',
          pointStrokeColor: '#fff',
          pointHighlightFill: '#fff',
          pointHighlightStroke: 'rgba(220,60,150,1)',
          data: [
              item.avg_xp_zeroToTen,
              item.avg_xp_tenToTwenty,
              item.avg_xp_twentyToThirty,
              item.avg_xp_thirtyToEnd
          ]
        },
        {
          label: 'Global Exp',
          fillColor: 'rgba(151,187,205,0.2)',
          strokeColor: 'rgba(151,187,205,1)',
          pointColor: 'rgba(151,187,205,1)',
          pointStrokeColor: '#fff',
          pointHighlightFill: '#fff',
          pointHighlightStroke: 'rgba(151,187,205,1)',
          data: [
              item.avg_xp_zeroToTen - item.avg_xpDiff_zeroToTen ,
              item.avg_xp_tenToTwenty - item.avg_xpDiff_tenToTwenty,
              item.avg_xp_twentyToThirty - item.avg_xpDiff_twentyToThirty,
              item.avg_xp_thirtyToEnd - item.avg_xpDiff_thirtyToEnd
          ]
        }
      ]
    };


    $a.linealcharts.damageTaken = {
      labels: ['Zero To Ten', 'Ten To Twenty', 'Twenty To Thirty', 'Thirty To End'],
      datasets: [
        {
          label: 'Damage Taken',
          fillColor: 'rgba(220,60,50,0.2)',
          strokeColor: 'rgba(220,60,50,1)',
          pointColor: 'rgba(220,60,50,1)',
          pointStrokeColor: '#fff',
          pointHighlightFill: '#fff',
          pointHighlightStroke: 'rgba(220,60,150,1)',
          data: [
              item.avg_damageTaken_zeroToTen,
              item.avg_damageTaken_tenToTwenty,
              item.avg_damageTaken_twentyToThirty,
              item.avg_damageTaken_thirtyToEnd
          ]
        },
        {
          label: 'Global Damage Taken',
          fillColor: 'rgba(250,187,187,0.2)',
          strokeColor: 'rgba(250,187,187,1)',
          pointColor: 'rgba(250,187,187,1)',
          pointStrokeColor: '#fff',
          pointHighlightFill: '#fff',
          pointHighlightStroke: 'rgba(151,187,205,1)',
          data: [
              item.avg_damageTaken_zeroToTen - item.avg_damageTakenDiff_zeroToTen ,
              item.avg_damageTaken_tenToTwenty - item.avg_damageTakenDiff_tenToTwenty,
              item.avg_damageTaken_twentyToThirty - item.avg_damageTakenDiff_twentyToThirty,
              item.avg_damageTaken_thirtyToEnd - item.avg_damageTakenDiff_thirtyToEnd
          ]
        }
      ]
    };


    $a.linealcharts.gold = {
      labels: ['Zero To Ten', 'Ten To Twenty', 'Twenty To Thirty', 'Thirty To End'],
      datasets: [
        {
          label: 'Gold',
          fillColor: 'rgba(150,128,0,0.2)',
          strokeColor: 'rgba(150,128,0,1)',
          pointColor: 'rgba(150,128,0,1)',
          pointStrokeColor: '#fff',
          pointHighlightFill: '#fff',
          pointHighlightStroke: 'rgba(150,128,0,1)',
          data: [
              item.avg_gold_zeroToTen,
              item.avg_gold_tenToTwenty,
              item.avg_gold_twentyToThirty,
              item.avg_gold_thirtyToEnd
          ]
        }
      ]
    };
  };
  


$a.champs = [
{
  value: 40,
  color:'#F7464A',
  highlight: '#FF5A5E',
  label: 'Cassiopeia'
},
{
  value: 10,
  color: '#46BFBD',
  highlight: '#5AD3D1',
  label: 'Leblanc'
},
{
  value: 10,
  color: '#FDB45C',
  highlight: '#FFC870',
  label: 'Annie'
}
];
$a.winrate = [
{
  value: 79,
  color:'#F7464A',
  highlight: '#FF5A5E',
  label: 'Win'
},
{
  value: 21,
  color: '#4D5360',
  highlight: '#616774',
  label: 'Lose'
}
];

$a.options =  {

      // Sets the chart to be responsive
      responsive: true,

      //Boolean - Show a backdrop to the scale label
      scaleShowLabelBackdrop : true,

      //String - The colour of the label backdrop
      scaleBackdropColor : 'rgba(255,255,255,0.75)',

      // Boolean - Whether the scale should begin at zero
      scaleBeginAtZero : true,

      //Number - The backdrop padding above & below the label in pixels
      scaleBackdropPaddingY : 2,

      //Number - The backdrop padding to the side of the label in pixels
      scaleBackdropPaddingX : 2,

      //Boolean - Show line for each value in the scale
      scaleShowLine : true,

      //Boolean - Stroke a line around each segment in the chart
      segmentShowStroke : false,

      //String - The colour of the stroke on each segement.
      segmentStrokeColor : 'white',

      //Number - The width of the stroke value in pixels
      segmentStrokeWidth : 1,

      //Number - Amount of animation steps
      animationSteps : 100,

      //String - Animation easing effect.
      animationEasing : 'easeOutBounce',

      //Boolean - Whether to animate the rotation of the chart
      animateRotate : true,

      //Boolean - Whether to animate scaling the chart from the centre
      animateScale : false,

      //String - A legend template
      legendTemplate : '<ul class="tc-chart-js-legend"><% for (var i=0; i<segments.length; i++){%><li><span style="background-color:<%=segments[i].fillColor%>"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>'
    };


    $a.linealcharts_options =  {


      // Sets the chart to be responsive
      responsive: true,

      ///Boolean - Whether grid lines are shown across the chart
      scaleShowGridLines : true,

      //String - Colour of the grid lines
      scaleGridLineColor : "rgba(255,255,255,0.75)",

      //Number - Width of the grid lines
      scaleGridLineWidth : 1,

      //Boolean - Whether the line is curved between points
      bezierCurve : true,

      //Number - Tension of the bezier curve between points
      bezierCurveTension : 0.4,

      //Boolean - Whether to show a dot for each point
      pointDot : true,

      //Number - Radius of each point dot in pixels
      pointDotRadius : 4,

      //Number - Pixel width of point dot stroke
      pointDotStrokeWidth : 2,

      //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
      pointHitDetectionRadius : 20,

      //Boolean - Whether to show a stroke for datasets
      datasetStroke : true,

      //Number - Pixel width of dataset stroke
      datasetStrokeWidth : 2,

      //Boolean - Whether to fill the dataset with a colour
      datasetFill : true,

      // Function - on animation progress
      onAnimationProgress: function(){},

      // Function - on animation complete
      onAnimationComplete: function(){},

      //String - A legend template
      legendTemplate : '<ul class="tc-chart-js-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].strokeColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>'
    };

    $a.peticion();
  }]);
