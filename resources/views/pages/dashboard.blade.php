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
                    <div><img src="{{ $value['avatar']}}"  data-id="{{$value['id']}}" alt="{{strtoupper(substr($value['name'], 0, 1))}}" class="avatar"></div>    
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



        $('document').ready(function(){
  
            var userData = <?php echo json_encode($data['usersCollection'],true)?>;
            var userLogsData = <?php echo json_encode($data['statsCollection'],true)?>;
 
            $.fn.renderGraphForUser(userData,userLogsData);

            $('.name_check').change(function() {
                if ($('.name_check').is(":checked"))
                {
                    let nameChk = $(this).attr("name");
                    $('.conver_check,.impr_check,.rev_check').prop('checked',false);
                    $.fn.AjaxCallForUser('name');
                }
            });

            $('.conver_check').change(function() {
                if ($('.conver_check').is(":checked"))
                {
                    let nameChk = $(this).attr("name");
                    $('.name_check,.impr_check,.rev_check').prop('checked',false);
                    $.fn.AjaxCallForUser('conversions');
                }
            });

            $('.impr_check').change(function() {
                if ($('.impr_check').is(":checked"))
                {
                    let nameChk = $(this).attr("name");
                    $('.conver_check,.name_check,.rev_check').prop('checked',false);
                    $.fn.AjaxCallForUser('impressions');
                    
                }
            });

            $('.rev_check').change(function() {
                if ($('.rev_check').is(":checked"))
                {
                    let nameChk = $(this).attr("name");
                    $('.conver_check,.impr_check,.name_check').prop('checked',false);
                    $.fn.AjaxCallForUser('revenue');
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
                            let html= '';
                            $.each(resp['data']['usersCollection'],function(key, value){
                                let date = new Date(resp['data']['statsCollection']['user'+value['id']+'key']['mindate']);
                                let date2 = new Date(resp['data']['statsCollection']['user'+value['id']+'key']['maxdate']);
                                console.log(date);
                                console.log('sa',resp['data']['statsCollection']['user'+value['id']+'key']['mindate']);
                                html += `<div class="customer-card">
                                            <div class="first-row">
                                                <div><img src="" alt="N" class="avatar"></div>
                                                <div style="margin-left:8px;">
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

