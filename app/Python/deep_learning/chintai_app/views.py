from django.shortcuts import render

from rest_framework import generics
from rest_framework.views import APIView
from rest_framework.response import Response

from .modules import regression

# from .models import Attendance
# from .serializers import AttendanceSerializer


class AttendanceListAPIView(APIView):

    def __init__(self, **kwargs):
        super().__init__(**kwargs)
        self.columns = ['賃料', '部屋数', 'プラン', '面積', '距離', '築年数']
        self.str_columns = ['プラン']
        self.csv = 'Takatsuki3.csv'
        self.target_data = [[2, 52, 40, 55, 0, 1, 0, 0, 0, 0]]

    def get(self, request, format=None):

        larning = regression.RegressionAnalysis(self.csv, self.columns, self.str_columns, self.target_data)
        result = larning.learning()
        print(f'result:{result}')
        result = {
            'result': result
        }
        return Response(result)



# class AttendanceListAPIView(generics.ListAPIView):
#     queryset = Attendance.objects.all()
#     serializer_class = AttendanceSerializer
#
# class AttendanceSerializer(serializers.ModelSerializer): class Meta:
#     model = Attendance
#     fields = ('name', 'attendance',)
