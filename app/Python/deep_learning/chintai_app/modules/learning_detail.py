import pandas as pd

from .deep_learning import DeepLearning
from matplotlib import pyplot as plt

from sklearn.model_selection import train_test_split
from sklearn.linear_model import LinearRegression
from sklearn.covariance import MinCovDet


class LearningOutlier(DeepLearning):

    def __init__(self, df, main_column, column_name, str_column, add_column):

        # 全てのカラム
        self.__column_names = column_name
        self.__str_column = str_column
        self.__add_column = add_column
        self.__main_column = main_column
        self.__all_column = []
        self.__merged_column = None
        self.__train_val = None
        self.df = df

        super().__init__(main_column, df=self.df)

    # マハラノビス距離
    def maharanobisu(self):

        # カラムを結合する
        self.__merged_column = self.marge_column(self.__column_names, self.__add_column, self.__str_column)

        # self.check_by_box(non_df)
        # データフレームを作成する
        # x = self.df
        # x = df.loc[:, self.__merged_column[1]:self.__merged_column[-2]]
        print(f'\n全てのカラム:{self.__merged_column}\n')
        x = self.df.loc[:, '賃料':'距離']
        print(x)

        # マハラノビス距離を計算する準備をする
        mcd = MinCovDet(random_state=0, support_fraction=0.7)
        mcd.fit(x)

        # マハラノビス距離を求める
        distance = mcd.mahalanobis(x)
        print('\n1', distance)

        # 外れ値用のシリーズに変換
        distance = pd.Series(distance)
        # distance.plot(kind='box')
        # plt.show()

        print('判定後の外れ値を表示する\n')
        # outlier = self.remove_outlier_auto(df, distance)

        # 四分位範囲を用いた外れ値の判定
        tmp = distance.describe()
        igr = tmp['75%'] - tmp['25%']
        jougen = 1.5 * (igr) + tmp['75%']
        kagen = tmp['25%'] - 1.5 * (igr)

        # シリーズ内を合致する条件で検索する
        outlier = distance[(distance > jougen) | (distance < kagen)]
        print()

        distance.drop(outlier.index)
        print('df', x.sort_index())
        print('dropped', distance)

        self.df = self.df.drop(outlier.index, axis=0)


        # tmp = distance.describe()
        # x.drop(outlier.index, axis=0)
        return self.df

    def remove_outlier_auto(self, df, distance):
        describe = distance.describe()
        igr = describe['75%'] - describe['25%']
        jougen = 1.5 * (igr) + describe['75%']
        kagen = describe['25%'] - 1.5 * (igr)
        outlier = distance[(distance > jougen) | (distance < kagen)]

        print('\ndf\n',df[46:].sort_index())
        print('\noutlier\n', outlier.index)
        # self.df.drop(outlier.index, axis=0)
        return outlier

    def check_by_box(self, df):

        for i in self.__merged_column:
            print(i)
            if i != self.__main_column:
                # データフレームを作成する
                x = df.loc[:, self.__main_column:i]

                # マハラノビス距離を計算する準備をする
                mcd = MinCovDet(random_state=0, support_fraction=0.7)
                mcd.fit(x)

                # マハラノビス距離を求める
                distance = mcd.mahalanobis(x)

                # シリーズに変換
                distance = pd.Series(distance)

                # 箱ひげ図を表示
                distance.plot(kind='box')
                # plt.show()
            else:
                continue
        return


class LearningNull(DeepLearning):

    def __init__(self, df, null_column, column_name, str_column, add_column, main_column):

        # 全てのカラム
        self.__column_names = column_name
        self.__str_column = str_column
        self.__add_column = add_column

        self.__all_column = []
        self.__null_column = null_column
        self.__main_column = main_column
        self.__train_val = None
        self.df = df

        super().__init__(null_column, df=self.df)

    def fill_null(self):

        # 欠損値を含む行を削除
        non_df = self.df.dropna()
        print(f'non_df\n{non_df}')
        # めも
        # 距離を予測するモデルを作成しているときに、距離のモデルが元データに入ってしまっている
        # xに距離が入ってしまっている
        # スライサーでやると距離はマンなkにあるので弾けていないことが原因

        # カラムを結合する
        merged_column = self.marge_column(self.__column_names, self.__add_column, self.__str_column)
        merged_column.remove(self.main_column)

        # データフレームを作成する
        x = non_df.loc[:, merged_column[0]:merged_column[-2]]
        t = non_df[[self.__null_column]]

        # NULL埋め対象もカラムに入っているため除去
        x = x.drop(self.__null_column, axis=1) if self.__main_column != self.__null_column else x

        # 学習モデルを作成する
        model = LinearRegression()
        model.fit(x, t)

        # 欠損値のある行を抜き出す
        condition = self.df[self.main_column].isnull()

        # 欠損値だけのデータフレーム
        non_data = self.df.loc[condition]

        # 欠損値のある行が存在しているかを確認
        if non_data.empty:
            print('欠損値存在せず')
            return 0

        # 欠損値予測用のデータフレームを作成
        x = non_data.loc[:, merged_column[0]:merged_column[-2]]
        x = x.drop(self.main_column, axis=1) if self.__null_column != self.__main_column else x

        # 欠損値をモデルで予測し挿入
        pred = model.predict(x)

        print('c')
        self.df.loc[condition, self.main_column] = pred

        print('欠損値あり')
        return self.df
