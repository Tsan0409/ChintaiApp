from django.http import QueryDict
from django.shortcuts import render

from rest_framework import generics
from rest_framework.views import APIView
from rest_framework.response import Response

from .modules import regression, get_csv

# from .models import Attendance
# from .serializers import AttendanceSerializer


class ExecDeepLearning(APIView):

    def __init__(self, **kwargs):
        super().__init__(**kwargs)
        self.columns = ['賃料', '部屋数', 'プラン', '面積', '距離', '築年数']
        self.str_columns = ['プラン']
        self.csv = 'Takatsuki3.csv'
        self.target_data = None

    def get(self, request, format=None):

        # http://0.0.0.0:8888/api/v1/chintai_app?data=2&data=52&data=40&data=55&data=0&data=1&data=0&data=0&data=0&data=0

        # 配列内容['部屋数', '面積', '距離', '築年数', 'K', 'LDK', 'R', 'SDK', 'SK', 'SLDK']
        self.target_data = [request.GET.getlist('data', None)]

        learning = regression.RegressionAnalysis(self.csv, self.columns, self.str_columns, self.target_data)
        result_data = learning.learning()

        result = {
            'result': result_data,
            'request_params': self.target_data

        }
        return Response(result)

# 機械学習用のデータを取得
class GetDeepLearningData(APIView):

    def __init__(self, **kwargs):

        super().__init__(**kwargs)

    def post(self, request, format=None):
        
        # ポストデータを取得する
        url = request.POST.get('url')
        csv_name = request.POST.get('csv_name')
        
        # データを取得する
        town_data = get_csv.GetDtail(url, csv_name)
        town_data.get_html()
        # total_page = town_data.get_page()
        total_page = 1
        plan_list = town_data.get_town_data(total_page)
        
        # Laravelに返す値を取得する
        
        
        result = {
            'url': f'{url}',
            'csv_name': f'{csv_name}',
            'total_page': f'{total_page}',
            'plan_list': f'{plan_list}'
        }
        return Response(result)







# class AttendanceListAPIView(generics.ListAPIView):
#     queryset = Attendance.objects.all()
#     serializer_class = AttendanceSerializer
#
# class AttendanceSerializer(serializers.ModelSerializer): class Meta:
#     model = Attendance
#     fields = ('name', 'attendance',)
