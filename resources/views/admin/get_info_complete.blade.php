<x-layouts.chintai-app>
    <h1>機械学習用のデータの保存が完了</h1>
    <h2>市町村</h2>
    <div>
        {{ $city->name }}
    </div>
    <h2>ファイル名</h2>
    <div>
        {{ $city_csv_file->file_name }}
    </div>
    <div>
        <a href="{{ route('info.get') }}">戻る</a>
    </div>
</x-layouts.chintai-app>