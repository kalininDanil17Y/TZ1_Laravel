        <meta name="description" content="">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.css" integrity="sha512-NXUhxhkDgZYOMjaIgd89zF2w51Mub53Ru3zCNp5LTlEzMbNNAjTjDbpURYGS5Mop2cU4b7re1nOIucsVlrx9fA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.js" integrity="sha512-lOrm9FgT1LKOJRUXF3tp6QaMorJftUjowOWiDcG5GFZ/q7ukof19V0HKx/GWzXCdt9zYju3/KhBNdCLzK8b90Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script>
            $(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            });
        </script>
        <script>
            $(function(){
                function outputSyntaxHighlight(id, json) {
                    json = JSON.stringify(
                        json,
                        null,
                        2
                    );

                    json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                    document.getElementById(id).appendChild(document.createElement('pre')).innerHTML = json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)|(\{)|(\})/g, function (match) {

                        var style = 'color: #000e59;';

                        if(match == "{"){
                            return '<span class="scob"><span style="color: #000;">{</span><i style="margin-left:3px; margin-right:3px; color:#025900; cursor: pointer;" class="togglecl-'+id+' fa-solid fa-angle-down"></i><span class="area" style="">';
                        }

                        if(match == "}"){
                            return '</span><span style="color: #000;">}</span></span>';
                        }

                        style = 'color: #ff5370;';

                        if (/^"/.test(match)) {
                            if (/:$/.test(match)) {
                                style = 'color: #000;';
                            } else {
                                style = 'color: #025900; font-weight: 600;';
                            }
                        } else if (/true|false/.test(match)) {
                            style = 'color: #600100; font-weight: 600;';
                        } else if (/null/.test(match)) {
                            style = 'color: red; font-weight: 600;';
                        }


                        return '<span style="' + style + '">' + match + '</span>';
                    });

                    $(".togglecl-" + id).on("click", function(){
                        var area = $(this).parent(".scob").children(".area");
                        var icon = $(this);
                        area.toggle();
                        if (area.is(":hidden")) {
                            icon.removeClass("fa-angle-down");
                            icon.addClass("fa-angle-left");
                        } else {
                            icon.removeClass("fa-angle-left");
                            icon.addClass("fa-angle-down");
                        }

                    });
                }

                $.ajax({
                    url: '{{ route('api.getUsers') }}',
                    method: 'GET',
                    success: function (json){
                        outputSyntaxHighlight('dataUsers', json);
                    }
                });

                $.ajax({
                    url: '{{ route('api.getUsersLogged') }}',
                    method: 'GET',
                    success: function (json){
                        outputSyntaxHighlight('getUsersLogged', json);
                    }
                });

                $.ajax({
                    url: '{{ route('api.getUsersNoTrophy') }}',
                    method: 'GET',
                    success: function (json){
                        outputSyntaxHighlight('getUsersNoTrophy', json);
                    }
                });

                @auth
                $.ajax({
                    url: '{{ route('api.getSumTrophy') }}',
                    method: 'POST',
                    data: {
                        id: {{ Auth()->user()->id }}
                    },
                    success: function (json){
                        outputSyntaxHighlight('getSumTrophy', json);
                    }
                });

                $('#addTrophy').on('click', function (){
                    $.ajax({
                        url: '{{ route('api.addTrophy') }}',
                        method: 'POST',
                        data: {
                            id: {{ Auth()->user()->id }}
                        },
                        success: function (){
                            $.ajax({
                                url: '{{ route('api.getSumTrophy') }}',
                                method: 'POST',
                                data: {
                                    id: {{ Auth()->user()->id }}
                                },
                                success: function (json){
                                    $('#getSumTrophy').html('');
                                    outputSyntaxHighlight('getSumTrophy', json);
                                }
                            });
                        }
                    });
                });
                @endauth

            });
        </script>
