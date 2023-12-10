from django.urls import path
from django.views.generic import TemplateView

from . import views
app_name = 'chintai_app'

urlpatterns = [
    path('', views.ExecDeepLearning.as_view(), name="attendance"),
    path('get_deepl_data/', views.GetDeepLearningData.as_view(), name="get_deeplearning_data"),
]
