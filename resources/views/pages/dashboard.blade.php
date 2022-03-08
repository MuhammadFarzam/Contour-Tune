@push('header-css')
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
@endpush
@push('content-css')
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
@endpush
@push('footer-css')
    <link href="{{ asset('css/footer.css') }}" rel="stylesheet">
@endpush

@extends('layouts.default')
@section('content')
    <div class="article">
        <div class="grid-container">
        @foreach($data['usersCollection'] as $key => $value)
            <div class="customer-card">
                <div class="first-row">
                    @if(!empty($value['avatar']))
                    <div><img src="{{ $value['avatar']}}"  data-id="{{$value['id']}}" alt="{{strtoupper(substr($value['name'], 0, 1))}}" class="avatar"></div>    
                    @else
                    <div class="alternate-image">{{strtoupper(substr($value['name'], 0, 1))}}</div>
                    @endIf                    
                    <div style="margin-left:8px;">
                        <div class="nam">{{ $value['name'] }}</div>
                        <div class="prof">{{ $value['occupation'] }}</div>
                    </div>
                </div>
                
                <div class="second-row">
                    <div class="card-graph">
                        <div id="graph_container_{{$value['id']}}" class="graph">Graph</div>
                        <div class="conver">Conversion <?php echo Carbon\Carbon::parse($data['statsCollection']['user'.$value['id'].'key']['mindate'])->format('n/j') .' - '. Carbon\Carbon::parse($data['statsCollection']['user'.$value['id'].'key']['maxdate'])->format('n/j'); ?></div>
                    </div>
                    <div class="user-detail">
                        <div class="font-weight-text">{{ $data['statsCollection']['user'.$value['id'].'key']['impressions'] }}</div>
                        <div class="font-weight-heading">impressions</div>
                        <div class="font-weight-text">{{ $data['statsCollection']['user'.$value['id'].'key']['conversions'] }}</div>
                        <div class="font-weight-heading">conversions</div>
                        <div class="font-weight-text">${{ $data['statsCollection']['user'.$value['id'].'key']['revenue'] }}</div>
                        <div class="font-weight-heading">revenue</div>
                    </div>
                </div> 
            </div>
        @endforeach
        </div>
    </div>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    @push('jquery')
        <script type="text/javascript" src="{{asset('js/jquery.min.js')}}"></script>
    @endpush
    <script type="text/javascript">
        (function($) {
            $.fn.renderGraphForUser = function(userData, userLogsData){
                $.each(userData,(function(key,user){
                    var unordered = userLogsData['user'+user.id+'key'].time;
                    var ordered = Object.keys(unordered).sort().reduce(
                            (obj, key) => { 
                                obj[key] = unordered[key]; 
                                return obj;
                            }, 
                            {}
                    );
                    var TimeXAxis = Object.keys(ordered);
                    var TimeValueYAxis = Object.values(ordered);

                    Highcharts.chart(`graph_container_${user.id}`, {
                        chart: {
                            type: 'line',
                            width: 220,
                            height: 130,
                        },
                        title:{
                                text:null
                        },
                        credits: {
                            enabled: false
                        },
                        xAxis: {
                            categories: TimeXAxis,
                            visible: false
                        },
                        yAxis: {
                            visible: false
                        },
                        plotOptions: {
                            series: {
                                marker: {
                                    fillColor: '#FFFFFF',
                                    lineWidth: 2,
                                    lineColor: null // inherit from series
                                }
                            }
                        },
                        series: [{
                            showInLegend: false,
                            data: TimeValueYAxis
                        }]
                    });

                }));
            }
        }(jQuery));


        document.onreadystatechange = function () {
            if (document.readyState == "complete") {
                $('#cover-spin').hide();
            }
        }
        console.log(document.readyState);
        $('document').ready(function(){
            $('#cover-spin').show(0);
            var userData = <?php echo json_encode($data['usersCollection'],true)?>;
            var userLogsData = <?php echo json_encode($data['statsCollection'],true)?>;
 
            $.fn.renderGraphForUser(userData,userLogsData);
            //$('#cover-spin').hide();
            $('.name_check_des,.name_check_mob').change(function() {
                if ($(this).is(":checked"))
                {
                    let nameChk = $(this).attr("name");
                    $('.name_check_des,.name_check_mob').prop('checked',true);
                    $('.conver_check_des,.impr_check_des,.rev_check_des,.conver_check_mob,.impr_check_mob,.rev_check_mob').prop('checked',false);
                    $.fn.AjaxCallForUser('name');
                }else{
                    $('.name_check_des,.name_check_mob').prop('checked',false);
                }
            });

            $('.conver_check_des,.conver_check_mob').change(function() {
                if ($(this).is(":checked"))
                {
                    let nameChk = $(this).attr("name");
                    $('.conver_check_des,.conver_check_mob').prop('checked',true);
                    $('.name_check_des,.impr_check_des,.rev_check_des,.name_check_mob,.impr_check_mob,.rev_check_mob').prop('checked',false);
                    $.fn.AjaxCallForUser('conversions');
                }else{
                    $('.conver_check_des,.conver_check_mob').prop('checked',false);
                }
            });

            $('.impr_check_des,.impr_check_mob').change(function() {
                if ($(this).is(":checked"))
                {
                    let nameChk = $(this).attr("name");
                    $('.impr_check_des,.impr_check_mob').prop('checked',true);
                    $('.conver_check_des,.name_check_des,.rev_check_des,.conver_check_mob,.name_check_mob,.rev_check_mob').prop('checked',false);
                    $.fn.AjaxCallForUser('impressions');
                    
                }else{
                    $('.impr_check_des,.impr_check_mob').prop('checked',false);
                }
            });

            $('.rev_check_des,.rev_check_mob').change(function() {
                if ($(this).is(":checked"))
                {
                    let nameChk = $(this).attr("name");
                    $('.rev_check_des,.rev_check_mob').prop('checked',true);
                    $('.conver_check_des,.impr_check_des,.name_check_des,.conver_check_mob,.impr_check_mob,.name_check_mob').prop('checked',false);
                    $.fn.AjaxCallForUser('revenue');
                }else{
                    $('.rev_check_des,.rev_check_mob').prop('checked',false);
                }
            });

            (function($) {
                $.fn.AjaxCallForUser  =  function(identifier){
                    $('#cover-spin').show(0);
                    $.ajax({
                        type: "GET",
                        url: '/filter-user', // This is what I have updated
                        dataType: 'json',
                        data: { filterKey: identifier },

                        success: function(resp){
                            $('#cover-spin').hide();
                            var html= '';
                            if(!$.isEmptyObject(resp['data']['statsCollection']) && !$.isEmptyObject(resp['data']['usersCollection'])){  
                                $.each(resp['data']['usersCollection'],function(key, value){
                                    let date = new Date(resp['data']['statsCollection']['user'+value['id']+'key']['mindate']);
                                    let date2 = new Date(resp['data']['statsCollection']['user'+value['id']+'key']['maxdate']);
                                    console.log(date);
                                    console.log('sa',resp['data']['statsCollection']['user'+value['id']+'key']['mindate']);
                                    html += `<div class="customer-card">
                                                <div class="first-row">`;
                                                if(value['avatar'] != ""){
                                                html += `<div><img src=${value['avatar']} alt="${value['name'].substring(0, 1).toUpperCase()}" class="avatar"></div>`
                                                }else{
                                                html += `<div class="alternate-image">${value['name'].substring(0, 1).toUpperCase()}</div>`
                                                }    
                                                html += `<div style="margin-left:8px;">
                                                        <div class="nam">${value['name']}</div>
                                                        <div class="prof">${value['occupation']}</div>
                                                    </div>
                                                </div>
                                                
                                                <div class="second-row">
                                                    <div class="card-graph">
                                                        <div id="graph_container_${value['id']}" class="graph">Graph</div>
                                                        <div class="conver">Conversion  ${(date.getMonth()+1)+'/'+date.getDate()} - ${(date2.getMonth()+1)+'/'+date2.getDate()} </div>
                                                    </div>
                                                    <div class="user-detail">
                                                        <div class="font-weight-text">${resp['data']['statsCollection']['user'+value['id']+'key']['impressions']}</div>
                                                        <div class="font-weight-heading">impressions</div>
                                                        <div class="font-weight-text">${resp['data']['statsCollection']['user'+value['id']+'key']['conversions']}</div>
                                                        <div class="font-weight-heading">conversions</div>
                                                        <div class="font-weight-text">$${resp['data']['statsCollection']['user'+value['id']+'key']['revenue'].toFixed(2)}</div>
                                                        <div class="font-weight-heading">revenue</div>
                                                    </div>
                                                </div> 
                                            </div>`;
                                });
                            }else{
                                html = `<div style="text-align:center;margin:38vh auto;">No Records Found<div>`;
                            }
                            
                            $('.grid-container').html(html);
                            $('#cover-spin').hide();
                            $.fn.renderGraphForUser(resp['data']['usersCollection'],resp['data']['statsCollection']);
                        },
                        error: function(err){
                            $('#cover-spin').hide();
                        }
                    });
                }
            }(jQuery));
        });
    </script>
@endsection

