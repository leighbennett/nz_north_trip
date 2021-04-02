<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>NZ North Trip</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://cdn.jsdelivr.net/npm/vue"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://d3js.org/d3.v4.min.js"></script> 
        <style>
            body {
                background-color:#38885b;
                font-family: Matiz, sans-serif;
                text-align: center;
                font-size: calc(14px + (19 - 14) * ((100vw - 300px) / (1600 - 300)));
                color: #343131;
            }
            #app {
                display: flex;
                flex-direction: column;
                margin: 0% 10% 0%; 
                width: 80%;  
            }
            h1 {
                margin: 0px; 
                color: #e01919;
            }
            /* ITINERARY */
            .days {
                display: flex;        
                width: 100%; 
            }
            .days div {
                line-height: 1vw;
                white-space: nowrap;
                width: 10%;
                padding: 1%;
                margin: 0.1%;
                font-weight: bold;
                border: 1px solid #333333;
                border-radius: 5px;
                background-color: #e8b823;
            }
            .days div:hover {
                color: #e01919;
                background-color: #e8e7e3;
            }
            .active {
                color: #e01919;
                background-color: #e8e7e3 !important;
            }
            .days div:first-child:hover{
                color: #343131;
                background-color: #e8b823;
            }
            .api{
                display: flex; 
                flex-direction: row;
                width: 100%;  
            } 
            #spotify { width:20%; }
            #map {
                height: 25vw;
                width: 80%;
            } 
            /* BUDGET */
            table { 
                width: 100%;
                border-collapse: collapse; 
            }
            th, td {
                padding: 8px;
                text-align: left;
                border-bottom: 1px solid #343131;
            }
            .cost { text-align: right; }
            @media only screen and (max-width: 768px) {
                #app {
                    margin: 0%; 
                    width: 100%;  
                }
                .api { flex-direction: column; } 
                #spotify { width:100%; height:80px; }
                #map {
                    height: 50vh;
                    width: 100%;
                } 
                .days div { padding: 3%; }
            }
        </style>
    </head>
    <body>
        <div id="app">
            <!--<h1>My North NZ Travel Itinerary</h1>-->
            <div id="itinerary">
                <div class="api">
                    <iframe id="spotify" src="https://open.spotify.com/embed/playlist/3zzIY2w0BAFtZbjwVhT8FH" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>
                    <div id="map"></div>
                </div>
                <div class="days">
                    <div>Days :</div>
                    <div class="day" v-if = "days.day!=null" v-for="(days,idx) in itinerary" v-on:click="showDay(idx,this)">
                        {{days.day}}
                    </div>
                </div>
            </div> 
            <div class="tab" id="budget">
                <table>
                    <tr><th>Item</th><th class="cost">Cost</th></tr>    
                    <tr v-for="unit in budget">
                        <td>{{ unit.item }}</td> 
                        <td class="cost">${{ parseFloat(unit.cost).toFixed(2) }}</td>
                    </tr>
                    <tr><td>Budget :</td><td class="cost">$3000.00</td></tr>    
                    <tr><td>Total  :</td><td class="cost"> ${{ total}} </td></tr>    
                </table>
            </div>  
        </div>
    </body>
    <script>
        var map; 
        var load_info_window = true;
        var app = new Vue({
            el: '#app',
            data: {   
                itinerary : [
                {
                    day : '1',
                    coordinates: [-32.833253, 162.639070],
                    zoom: 4,
                    events : [
                        {
                            place:'Gold Coast Airport',                  
                            lat:-28.165862, log:153.508977, zoom:10,
                            date:'24.01.20', arrival:'7am', departure:'8am',
                            image:''        
                        },
                        {
                            place:'Auckland Airport',   
                            lat:-37.006198, log:174.791007, zoom:10,
                            date:'24.01.20', arrival:'2pm', departure:'2.30pm',
                            image:''
                        },
                        {
                            place:'Wicked Campers',   
                            lat:-36.907177, log:174.807415, zoom:10,
                            date:'24.01.20', arrival:'3pm', departure:'3.30pm',
                            image:''
                        },
                        {
                            place:'Tauranga Domain (One Love 2020)', 
                            lat:-37.679481, log:176.165531, zoom:10,
                            date:'24.01.20', arrival:'6pm', departure:'',
                            image:''
                        }
                    ]
                },
                {
                    day : '2 & 3',
                    coordinates: [-37.679481, 176.165531],
                    zoom: 10,
                    events : [
                        {
                            place:'Tauranga Domain (One Love 2020)',
                            lat:-37.679481, log:176.165531, zoom:10,
                            date:'26.01.20', arrival:'', departure:'',
                            image:''        
                        }
                    ]
                },
                {
                    day : '4',
                    coordinates: [-37.848796, 176.195255],
                    zoom: 9,
                    events : [
                        {
                            place:'Tauranga Domain (One Love 2020)',
                            lat:-37.679481, log:176.165531, zoom:10, 
                            date:'27.01.20', arrival:'', departure:'10am',
                            image:''
                        },
                        {
                            place:'Hobbiton Movie Set', 
                            lat:-37.871946, log:175.682856, zoom:10,
                            date:'27.01.20', arrival:'11.30am', departure:'1pm',
                            image:''   
                        },
                        {
                            place:'Rotorua',    
                            lat:-38.145282, log:176.251725, zoom:10,
                            date:'27.01.20', arrival:'2pm', departure:'',
                            image:''
                        }
                    ]
                },
                {
                    day : '5',
                    coordinates: [-38.072433, 176.262071],
                    zoom: 9, 
                    events : [
                        {
                            place:'Wai-O-Tapu Thermal Wonderland',
                            lat:-38.358222, log:176.369006, zoom:10,
                            date:'28.01.20', arrival:'10am', departure:'12pm',
                            image:''
                        }
                    ]
                },
                {
                    day : '6',
                    coordinates: [-38.225570, 175.538227],
                    zoom: 9,
                    events : [
                        {
                            place:'Rotorua',  
                            lat:-38.145282, log:176.251725, zoom:10,
                            date:'29.01.20', arrival:'', departure:'10am',
                            image:''
                        },
                        {
                            place:'Waitomo',    
                            lat:-38.245041, log:175.053496, zoom:10,
                            date:'29.01.20', arrival:'', departure:'',
                            image:''       
                        }
                    ]
                },
                {
                    day : '7',
                    coordinates: [-38.276712, 175.124713],    
                    zoom: 10,
                    events : [
                        {
                            place:'Waitomo Caves', 
                            lat:-38.245041, log:175.053496, zoom:10,
                            date:'30.01.20', arrival:'', departure:'',
                            image:''
                        }
                    ]
                },
                {
                    day : '8',
                    coordinates: [-37.475185, 174.946764],
                    zoom: 7,
                    events : [
                        {
                            place:'Waitomo ',      
                            lat:-38.245041, log:175.053496, zoom:10,
                            date:'31.01.20', arrival:'', departure:'',
                            image:''        
                        },
                        {
                            place:'Auckland',
                            lat:-37.006198, log:174.791007, zoom:10,
                            date:'31.01.20', arrival:'', departure:'',
                            image:''        
                        }
                    ]
                },
                {
                    day : '9',
                    coordinates: [-32.833253, 162.639070],
                    zoom: 4,
                    events : [
                        {
                            place:'Wicked Campers', 
                            lat:-36.907177, log:174.807415, zoom:10,
                            date:'01.02.20', arrival:'10am', departure:'11am',
                            image:''   
                        },
                        {
                            place:'Auckland Airport',   
                            lat:-37.006198, log:174.791007, zoom:10,
                            date:'01.02.20', arrival:'3.20pm', departure:'4.20pm',
                            image:''    
                        },
                        {
                            place:'Gold Coast Airport', 
                            lat:-28.165862, log:153.508977, zoom:10,
                            date:'01.02.20', arrival:'4.50pm', departure:'',
                            image:''
                        }
                    ]
                }
                ],
                budget : [
                    {item:'Flights',                          cost:313.72},
                    {item:'9 day camper hire + camping pass', cost:986.95},
                    {item:'one love tickets + 2 day camping', cost:229.61},
                    {item:'Hobbiton™ Movie Set Tour (2hrs)',  cost:80.62},
                    {item:'Wai-O-Tapu Thermal Wonderland ',   cost:31.19},
                    {item:'Waitomo Caves Tour (5hrs)',        cost:249.55},
                    {item:'Spending Money',                   cost:1000.00}
                ]
            },
            methods: {
                showDay: function(idx) {
                    var days = document.getElementsByClassName("day");
                    for (i = 0; i < days.length; i++) {
                        days[i].className = days[i].className.replace(" active", "");
                    }
                    days[idx].className += " active";
                    features = [];
                    var clickDay = this.itinerary[idx].events;
                    for (d = 0; d < clickDay.length; d++) {
                        features.push({
                            title: clickDay[d].place,    
                            position: [clickDay[d].lat, clickDay[d].log],
                            date: clickDay[d].date,
                            arrival: clickDay[d].arrival,
                            departure: clickDay[d].departure
                        });
                    }
                    drawMap(
                        this.itinerary[idx].coordinates,
                        this.itinerary[idx].zoom,
                        features
                    );
                }
            },
            computed:{
                total: function(){
                    var num = this.budget.reduce(function(amount,item){
                        return amount + (item.cost); 
                    },0);
                    return parseFloat(num).toFixed(2);
                } 
            }    
        })

        function drawMap(coordinates,zoom,features) {
            if ([coordinates,zoom,features].includes(undefined) || [coordinates,zoom,features].includes(null)){ 
                app.showDay(0);
                return;
            }
            map = new google.maps.Map(document.getElementById('map'), {
            zoom: zoom,
            center: new google.maps.LatLng(coordinates[0],coordinates[1]),
            styles:[
                    {elementType: 'geometry', stylers: [{color: '#f28602'}]},
                    {elementType: 'geometry',featureType: 'water', stylers: [{ color: '#38885b'}]},
                    {elementType: 'labels.text.stroke', stylers: [{color: '#333333'}]},
                    {elementType: 'labels.text.fill', stylers: [{color: '#ffffff'}]},
                    {elementType: 'geometry.stroke',featureType: 'administrative',stylers: [{color: '#c9b2a6'}]},
                    {elementType: 'labels',featureType: 'road',stylers: [{ visibility: 'off'}]},
                    {elementType: 'geometry', featureType: 'road',stylers: [{color: '#ffffff'}]},
                    {elementType: 'geometry',featureType: 'road.arterial',stylers: [{color: '#ffffff'}]},
                    {elementType: 'geometry',featureType: 'road.highway',stylers: [{color: '#ffffff'}]},
                    {elementType: 'geometry.stroke',featureType: 'road.highway',stylers: [{color: '#ffffff'}]},
                    {elementType: 'geometry',featureType: 'road.highway.controlled_access',stylers: [{color: '#ffffff'}]},
                    {elementType: 'geometry.stroke',featureType: 'road.highway.controlled_access',stylers: [{color: '#ffffff'}]} 
                ]
            });
            if(load_info_window){
                const originalMapCenter = new google.maps.LatLng(-35.18856870250346, 161.49448269151006);
                const infowindow = new google.maps.InfoWindow({
                    content: "Click the markers for more information <br> Click on the days to change the itinerary.",
                    position: originalMapCenter,
                });
                infowindow.open(map);
                load_info_window = false;
            }    
            //Create Markers for each feature
            for(var m = 0; m < features.length; m++) {
                var marker_title = features[m].title;
                var marker_label = (m+1).toString();    
                var marker_arrival = features[m].arrival;
                var marker_departure = features[m].departure;
                var marker_date = features[m].date;
                var latLng = new google.maps.LatLng(features[m].position[0], features[m].position[1]); 
                var marker_content =
                    '<div>'+ 
                        '<h3 style=\'margin:0%;\'>'+ marker_title +'</h3>'+
                        '<b>'+ marker_date +'</b></br>'+
                        '<b>Arrival :</b>'+ marker_arrival +' '+
                        '<b>Departure :</b>'+ marker_departure +'</br>'+
                    '</div>';
                var markers = [];
                // Sets the map on all markers in the array. 
                var marker = new google.maps.Marker({
                    position: latLng,
                    title: marker_title,
                    clickable: true,
                    map: map,
                    label: marker_label,
                    animation: google.maps.Animation.DROP
                });
                markers.push(marker);   
                var infowindow = new google.maps.InfoWindow();
                google.maps.event.addListener(marker,'click',(function(marker,marker_content,infowindow) { 
                    return function() {
                        infowindow.setContent(marker_content);
                        infowindow.open(map,marker);
                    };
                })(marker,marker_content,infowindow));            
            }
        }
    </script>
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCndgugFHCSgxdUbk3rDPOYwJxNMktYBZw&callback=drawMap">
    </script>
</html>   

