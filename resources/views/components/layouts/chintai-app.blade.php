<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}"></script> --}}

    {{-- jquery --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <title>賃貸料金調査アプリ</title>
</head>
<body>
    <main>
        {{ $slot }}
    </main>
</body> 
<footer>
    <a href="{{ route('home') }}">ホーム画面 </a>
</footer>
<script>
    const prefecture = document.getElementById('prefecture_id');

    // jqueryのドキュメント読み込み
    $(function(){

        // セレクトボックスの値が変更されると発火
        $('#prefecture_id').on('change',function(){

            var prefecture_id = $(this).val();
            var api_url = "{{ route('api.get_cities') }}";
            
            // リクエスト実行
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url:  api_url,
                type: 'GET',
                data: {'prefecture_id' : prefecture_id},
                datatype: 'json',
            })
            // リクエスト成功時の処理
            .done(function(data) {
                
                // 現存のセレクトボックスを削除
                $('#city_id option').remove();

                // セレクトボックスを再生成
                $.each(data, function(key, value) {
                    $('#city_id').append($('<option>').text(value.name).attr('value', value.id));
                });

                var city_id = data[0].id;
                var room_plan_api_url = "{{ route('api.get_room_plans') }}";          

                // リクエスト実行
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url:  room_plan_api_url,
                    type: 'GET',
                    data: {'city_id' : city_id},
                    datatype: 'json',
                })

                // リクエスト成功時の処理
                .done(function(data) {

                    // 現存のセレクトボックスを削除
                    $('#room_plan option').remove();

                    // セレクトボックスを再生成
                    $.each(data, function(key, value) {

                        // 間取りのパラメータが1の時のみ実行
                        if (value == 0) {
                            return true;
                        };

                        $('#room_plan').append($('<option>').text(key).attr('value', value.id));
                    });
                });
            });
        });

        // セレクトボックスの値が変更されると発火
        $('#city_id').on('change',function(){
            
            var city_id = $(this).val();
            var api_url = "{{ route('api.get_room_plans') }}";          

            // リクエスト実行
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url:  api_url,
                type: 'GET',
                data: {'city_id' : city_id},
                datatype: 'json',
            })
            // リクエスト成功時の処理
            .done(function(data) {

                // 現存のセレクトボックスを削除
                $('#room_plan option').remove();
                
                // セレクトボックスを再生成
                $.each(data, function(key, value) {

                    // 間取りのパラメータが1の時のみ実行
                    if (value == 0) {
                        return true;
                    };

                    $('#room_plan').append($('<option>').text(key).attr('value', value.id));
                });
            });

        });
    });
</script>
</html>