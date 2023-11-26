import pandas as pd
from sklearn.model_selection import train_test_split

from deep_learning.chintai_app.modules.deep_learning import DeepLearning


class RegressionAnalysis(DeepLearning):

    def __init__(self, csv, column_name, str_column):

        # 全てのカラム（一番最初に入れる値は正解データ）
        self.column_names = column_name
        self.str_column = str_column
        self.main_column = '賃料'
        self.add_column = []
        self.train_val = None
        self.csv = csv

        super().__init__(self.main_column, csv=csv)

    # テストデータの検証用関数
    def research(self):

        # 駅名が正しく入力されていない場合削除
        # not_eki = self.df['最寄駅'].str.contains('駅')
        # a = not_eki[not_eki == False].index
        # for i in a:
        #     print(i)
        #
        # # 駅名が存在していないデータを削除
        # self.df = self.df.drop(a, axis=0)
        # self.df = self.df.dropna(subset=['最寄駅'], how='all')

        print('----------------------- 調査開始 -----------------------')

        # 文字列を変数に変更
        self.change_df(self.column_names)
        for i in self.column_names:
            if i in self.str_column:
                self.string_to_value(i)
                self.add_column.append([i for i in self.df.columns.values if i != self.column_names or i not in self.add_column])
                print(self.add_column)

        # テストデータと教師データに分割する
        self.train_val, self.test = train_test_split(self.df, test_size=0.2, random_state=0)

        # 欠損値を埋める
        print('----------------------- 欠損値 -------------------------\n', self.train_val.isnull().sum())

        print('fill null')
        self.fill_null_mean()

        print('----------------------- 外れ値 -------------------------')

        # 外れ値を取り除く
        print('remove outlier')

        self.train_val = self.remove_outlier_manual(self.train_val, '賃料', '> 100')
        self.train_val = self.remove_outlier_manual(self.train_val, '部屋数', '> 4')
        self.train_val = self.remove_outlier_manual(self.train_val, '面積', '> 100')
        self.train_val = self.remove_outlier_manual(self.train_val, '築年数', '> 60')

        # グラフで外れ値を確認
        self.check_outlier()

        print('----------------------- カラムごとの重み ----------------')
        for i in self.column_names:
            if not i in ['最寄駅', 'プラン']:
                print(f'\n {i}の重み \n')
                sam = self.train_val.groupby(i).mean()
                for n in ['賃料', '部屋数', '面積', '距離']:
                    if i != n:
                        print(f'\n {i}の、{n}の重み\n {sam[n]}')

        print('----------------------- 相関係数 -----------------------')

        self.check_corr()

        print('----------------------- 調査終了 -----------------------')




all_columns = ['最寄駅', '距離', '築年数', '賃料', '面積', 'プラン', '部屋数', '管理費', '敷金', '礼金']
need_columns = ['賃料', '部屋数', 'プラン', '面積', '距離', '築年数']
str_columns = ['プラン']

research = RegressionAnalysis('Takatsuki3.csv', need_columns, str_columns)
research.research()
