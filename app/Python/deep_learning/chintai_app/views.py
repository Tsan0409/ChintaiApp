from django.http import QueryDict
from django.shortcuts import render

from rest_framework import generics
from rest_framework.views import APIView
from rest_framework.response import Response

from .modules import regression, get_csv

# from .models import Attendance
# from .serializers import AttendanceSerializer

# 機械学習の実行
class ExecDeepLearning(APIView):

    def __init__(self, **kwargs):
        super().__init__(**kwargs)
        self.columns = ['賃料', '部屋数', 'プラン', '面積', '距離', '築年数']
        self.str_columns = ['プラン']
        self.target_data = None

    def post(self, request, format=None):

        # http://0.0.0.0:8888/api/v1/chintai_app?data=2&data=52&data=40&data=55&data=0&data=1&data=0&data=0&data=0&data=
        # 配列内容['部屋数', '面積', '距離', '築年数', 'K', 'LDK', 'R', 'SDK', 'SK', 'SLDK']
        file_name = f"scraping_data/{request.POST.get('file_name')}"
        data = request.POST.getlist('data')
        plan = request.POST.get('plan')

        learning = regression.RegressionAnalysis(file_name, self.columns, self.str_columns, data, plan)
        result_data = learning.learning()

        return Response(result_data[0][0])

# スクレイピングを実行して機械学習用のデータを取得（csvファイルを保存する）
class GetDeepLearningData(APIView):

    def __init__(self, **kwargs):
        super().__init__(**kwargs)

    def post(self, request, format=None):
        
        # ポストデータを取得する
        url = request.POST.get('url')
        csv_name = f"scraping_data/{request.POST.get('csv_name')}"
        
        # データを取得する
        town_data = get_csv.CreateCityCsvFile(csv_name)
        plan_list = town_data.get_town_data(url)
        
        # Laravelに返す値を取得する
        
        result = {
            'url': f'{url}',
            'csv_name': f'{csv_name}',
            'plan_list': f'{plan_list}'
        }
        return Response(result)